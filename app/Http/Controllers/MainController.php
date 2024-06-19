<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Address;
use App\Models\Party;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
   
 public function index()
 {
    $states = State::all();
    $districts = District::all();
    $parties = Party::with('address')->get();
    $candidates = Candidate::with('address')->get();

    // Fetch the cities where candidates are living
    $cities = Address::whereIn('id', $candidates->pluck('address_key'))->pluck('city')->unique();

    $cityDetails = [];

    foreach ($cities as $city) {
        $totalCityVotes = Candidate::whereHas('address', function ($query) use ($city) {
            $query->where('city', $city);
        })->sum('votes');

        $leadingCandidate = Candidate::whereHas('address', function ($query) use ($city) {
            $query->where('city', $city);
        })->orderByDesc('votes')->first();

         if($leadingCandidate->votes!=0){
            $leadingCandidatePercentage = round(($leadingCandidate->votes / $totalCityVotes) * 100, 2);
         }else {
            $leadingCandidatePercentage= 0;
         }

        $cityDetails[] = [
            'city' => $city,
            'totalVotes' => $totalCityVotes,
            'leadingCandidate' => $leadingCandidate,
            'leadingCandidatePercentage' => $leadingCandidatePercentage,
        ];
    }

    return view('index', compact('states', 'districts', 'parties', 'candidates', 'cityDetails'));
 }


    public function getResults(Request $request){
        
        try {
        $city = $request->district;
      
        $leading = Candidate::whereHas('address', function ($query) use ($city) {
            $query->where('city', $city);
        })->orderByDesc('votes')->first(); 

        if($leading){

            $totalCityVotes = Candidate::whereHas('address', function ($query) use ($city) {
                $query->where('city', $city);
            })->sum('votes');
    
            if($leading->votes!=0){
                $leadingCandidatePercentage = round(($leading->votes / $totalCityVotes) * 100, 2);
             }else {
                $leadingCandidatePercentage= 0;
             }
             
            return redirect()->back()
                ->with('leading', $leading)
                ->with('leadingCandidatePercentage', $leadingCandidatePercentage)
                ->with('totalCityVotes',$totalCityVotes);
        }
        return redirect()->back()->with('success', 'No election in this city');
        } catch (\Exception $e) {
        return back()->withError($e->getMessage())->withInput();
        }
    }
}