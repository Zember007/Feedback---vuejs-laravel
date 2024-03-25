<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TariffsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
    Route::post('/survey', [SurveyController::class, 'create'])->name('survey.create');
    Route::get('/survey/{id}', [SurveyController::class, 'show'])->name('survey.show');
    Route::post('/add_question', [SurveyController::class, 'add_question'])->name('survey.add_question');
    Route::get('/users', [UserController::class, 'users'])->name('users');
});

Route::get('/survey_answers/{id}', [SurveyController::class, 'survey_answers'])->name('survey.survey_answers');
Route::post('/answers', [SurveyController::class, 'answers'])->name('survey.answers');

Route::get('/tariffs', [TariffsController::class, 'index'])->name('tariffs');


require __DIR__.'/auth.php';
