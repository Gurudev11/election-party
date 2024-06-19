<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Candidate;
use App\Models\District;
use App\Models\Party;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CandidateController extends Controller
{

    public function candidates($id = null)
    {
        $states = State::all();
        $parties = Party::with('address')->get();

        $existingCandidates = Candidate::join('address', 'candidates.address_key', '=', 'address.id')
            ->select('candidates.party', 'address.city')
            ->get()
            ->toArray();

        // $existingCandidates = Candidate::with('address:city')
        //     ->get()
        //     ->map(function ($candidate) {
        //         return [
        //             'party' => $candidate->party,
        //             'city' => $candidate->address->city,
        //         ];
        //     })
        //     ->toArray();

        $candidates = Candidate::with('address')->get();
        $candidateToEdit = $id ? Candidate::find($id) : null;
        $districts = District::all();
        return view('candidate', compact('states', 'parties', 'existingCandidates', 'candidates', 'candidateToEdit', 'districts'));
    }

    public function saveCandidate(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'party' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'number' => 'required|integer|min:6000000000|max:9999999999',
            'candidate_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $existingCandidate = Candidate::join('address', 'candidates.address_key', '=', 'address.id')
            ->where('candidates.party', $request->party)
            ->where('address.city', $request->city)
            ->first();

        if ($existingCandidate) {
            return back()->withErrors(['city' => 'A candidate from this city already exists for the selected party.'])->withInput();
        }

        try {
            $address = Address::create([
                'address1' => $request->address1,
                'address2' => $request->address2,
                'city' => $request->city,
                'state' => $request->state,
            ]);

            $dob = Carbon::parse($request->dob);
            $age = $dob->age;

            $candidateData = [
                'name' => $request->name,
                'party' => $request->party,
                'dob' => $request->dob,
                'age' => $age,
                'number' => $request->number,
                'address_key' => $address->id,

            ];

            $candidateData['candidate_photo'] = $request->file('candidate_photo')->store('photo', 'public');

            Candidate::create($candidateData);

            return redirect()->back()->with('success', 'Candidate enrolled successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

    }

    public function candidatesDetails()
    {

        $candidates = Candidate::with('address')->paginate(10);
        return view('candidatedetails', compact('candidates'));
    }

    public function deleteCandidates(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if ($ids) {
                Candidate::whereIn('id', $ids)->each(function ($candidate) {
                    $candidate->delete();
                    $candidate->address->delete();
                });
                return redirect()->back()->with('success', 'Selected candidates deleted successfully');
            }
            return redirect()->back()->with('error', 'Please select one to delete');

        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function updateCandidate(Request $request, $id)
    {

        $candidate = Candidate::find($id);
        $request->validate([
            'name' => 'required|string|max:255' . $candidate->id,
            'party' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'number' => 'required|integer|min:6000000000|max:9999999999',
            'candidate_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $existingCandidate = Candidate::join('address', 'candidates.address_key', '=', 'address.id')
            ->where('candidates.party', $request->party)
            ->where('address.city', $request->city)
            ->first();

        if ($existingCandidate) {
            return back()->withErrors(['city' => 'A candidate from this city already exists for the selected party.'])->withInput();
        }

        try {
            $candidate->update($request->only('name', 'party', 'dob', 'number'));

            $dob = Carbon::parse($request->dob);
            $age = $dob->age;
            $candidate->age = $age;

            $candidate->address->update($request->only('address1', 'address2', 'city', 'state'));

            $candidate['candidate_photo'] = $request->file('candidate_photo')->store('photo', 'public');

            return redirect()->route('candidates.details')
                ->with('success', 'Candidate details updated successfully.');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }

    }

}
