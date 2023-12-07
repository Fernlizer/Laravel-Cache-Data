<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::patch('/agent', [AgentsController::class, 'update'])->name('agent.update');
Route::get('/agent', [AgentsController::class, 'all'])->name('agent.get');
