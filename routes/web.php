<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\User\QuestionController;
use App\Http\Controllers\ElectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(IndexController::class)->group(function () {
    Route::get('/', 'index')->name('index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

    Route::prefix('user')->as('user.')->controller(QuestionController::class)->group(function () {
        Route::get('/elections', 'elections')->name('elections');
        Route::get('/elections/{id}/vote', 'vote_form')->name('vote_form');
        Route::post('/elections/store/vote', 'store')->name('vote.store');
    });

    Route::middleware(['admin'])->group(function () {
        Route::controller(ElectionController::class)->group(function () {
            Route::get('/election', 'index')->name('election');
            Route::get('/election/create', 'create')->name('election.create');
            Route::post('/election/create-process', 'store')->name('election.store');
            Route::get('/election/{id}/edit', 'edit')->name('election.edit');
            Route::post('/election/update', 'update')->name('election.update');
            Route::post('/election/delete', 'destroy')->name('election.destroy');
            Route::get('/election/{id}/detail','detail')->name('election.detail');
            Route::get('/election/{id}/view-results','view_results')->name('election.view_results');


            Route::get('/election/{id}/add-question', 'addQuestion')->name('election.addQuestion');
            Route::post('/election/store-question', 'questionStore')->name('election.questionStore');
            Route::get('/election/{election_id}/edit-question/{question_id}', 'editQuestion')->name('election.editQuestion');
            Route::post('/election/update-question', 'updateQuestion')->name('election.updateQuestion');
            Route::post('/election/delete-question', 'questionDestroy')->name('election.questionDestroy');
        });
    });
});

require __DIR__.'/auth.php';
