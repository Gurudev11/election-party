<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Address;
use App\Models\Party;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartyController extends Controller
{
    public function parties($id = null)
    {        
        $states = State::all(); 
        $existingPartyNames = Party::pluck('name')->toArray();

        $parties = Party::with('address')->get();
        $candidates = Candidate::with('address')->get();

        $partyToEdit = $id ? Party::find($id) : null;
        $districts = District::all();
        return view('party', compact('states','existingPartyNames','parties', 'candidates','partyToEdit','districts'));
    }
   

    public function saveParty(Request $request){

        $request->validate([
            'name' => 'required|string|max:255|unique:parties,name',
            'registration_date' => 'nullable|date',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'number' => 'required|integer|min:6000000000|max:9999999999',
            'party_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],[
            'name.unique' => 'The party name already exists. Please use a different name.'
        ]);

        try {
        $address = Address::create([
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'state' => $request->state           
        ]);
      
        $partyData = [
            'name' => $request->name,
            'registration_date' => $request->registration_date,
            'number' => $request->number,
            'address_key' => $address->id
           
        ];
     
            $partyData['party_logo'] = $request->file('party_logo')->store('logos', 'public');
       
        Party::create($partyData);
        return redirect()->back()->with('success', 'Party created successfully.');
    } catch (\Exception $e) {
        return back()->withError($e->getMessage())->withInput();
    }
    // return "Trisha";
    }
    
    public function partiesDetails()
    {
        $parties = Party::with('address')->paginate(10); 
        return view('partydetails', compact('parties'));
    }
    

    public function deleteParties(Request $request)
    {
        try {
            
            $ids = $request->input('ids');
    
            if ($ids) {
                
                $parties = Party::whereIn('id', $ids)->get();
    
                $partyNames = $parties->pluck('name')->toArray();
    
                $candidates = Candidate::whereIn('party', $partyNames)->get();
    
                $candidates->each(function($candidate) {
            
                    if ($candidate->address) {
                        $candidate->address->delete();
                    }
                   
                    $candidate->delete();
                });
    
                $parties->each(function($party) {
                  
                    if ($party->address) {
                        $party->address->delete();
                    }
                    
                    $party->delete();
                });
                return redirect()->back()->with('success', 'Selected parties and their respective candidates deleted successfully');
            }
            return redirect()->back()->with('error', 'Please select one to delete');
            
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
    

    public function updateParty(Request $request, $id) {
        $party = Party::find($id);
    
        $request->validate([
            'name' => 'required|string|max:255|unique:parties,name,' . $party->id,
            'registration_date' => 'nullable|date',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'number' => 'required|integer|min:6000000000|max:9999999999',
            'party_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],[
            'name.unique' => 'The party name already exists. Please use a different name.'
        ]);
    
        try {
            $party->update($request->only('name', 'registration_date', 'number'));
            $party->address->update($request->only('address1', 'address2', 'city', 'state'));

                $party->party_logo = $request->file('party_logo')->store('logos', 'public');

            return redirect()->route('parties.details')
                ->with('success', 'Party details updated successfully.');

        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
    
  
}

