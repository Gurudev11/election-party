<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\MainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/index', function () {
//     return view('index');
// });


Route::get('/index', [MainController::class, 'index'])->name('index');
Route::post('/index', [MainController::class, 'getResults'])->name('results.get');

Route::get('/parties', [PartyController::class, 'parties'])->name('form.parties');
Route::get('/candidates', [CandidateController::class, 'candidates'])->name('form.candidates');
Route::get('/votes', [ResultController::class, 'votes'])->name('form.votes');

Route::post('/parties', [PartyController::class, 'saveParty'])->name('parties.save');
Route::post('/candidates', [CandidateController::class, 'saveCandidate'])->name('candidates.save');
Route::post('/votes', [ResultController::class, 'checkResults'])->name('results.check');

Route::get('/parties/details', [PartyController::class, 'partiesDetails'])->name('parties.details');
Route::get('/candidates/details', [CandidateController::class, 'candidatesDetails'])->name('candidates.details');
Route::post('/candidates/details', [CandidateController::class, 'deleteCandidates'])->name('candidates.delete');
Route::post('/parties/details', [PartyController::class, 'deleteParties'])->name('parties.delete');

Route::get('/parties/{id}',[PartyController::class, 'parties'])-> name('parties.edit');
Route::get('/candidates/{id}',[CandidateController::class, 'candidates'])-> name('candidates.edit');

Route::put('party/update/{id}', [PartyController::class, 'updateParty'])->name('party.update');
Route::put('candidate/update/{id}', [CandidateController::class, 'updateCandidate'])->name('candidate.update');

