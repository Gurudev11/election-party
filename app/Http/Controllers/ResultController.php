<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Candidate;
use App\Models\Party;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function votes()
    {
        $parties = Party::all();
        $states = State::all();
        $districts = District::all();
        $candidates = Candidate::with('address')->get();
        $address = Address::all();
        return view('votes', compact('parties','states','districts','candidates','address'));
    }

    public function checkResults(Request $request)
    {
        $request->validate([
            'party' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',          
            'candidate' => 'required|string|max:255',
            'votes' => 'required|integer|min:0',
        ]);
    
        try {
            $partyName = $request->party;
            $state = $request->state;
            $city = $request->city;
            $candidateName = $request->candidate;
            $votes = $request->votes;    
    
            
            $candidate = Candidate::whereHas('address', function ($query) use ($state, $city) {
                $query->where('state', $state)->where('city', $city);
            })->where('party', $partyName)->where('name', $candidateName)->first();
    
           
            $candidate->votes += $votes;
            $candidate->save();
            
            // Calculate the total votes in the city
            $totalCityVotes = Candidate::whereHas('address', function ($query) use ($city) {
                $query->where('city', $city);
            })->sum('votes');
    
            $leading = Candidate::whereHas('address', function ($query) use ($city) {
                $query->where('city', $city);
            })->orderByDesc('votes')->first();

            if($leading->votes!=0){
                $leadingCandidatePercentage = round(($leading->votes / $totalCityVotes) * 100, 2);
             }else {
                $leadingCandidatePercentage= 0;
             }
                        
           
            return redirect()->back()->with('success', 'Votes counted successfully.')
                ->with('leading', $leading)
                ->with('leadingCandidatePercentage', $leadingCandidatePercentage);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
    
}
