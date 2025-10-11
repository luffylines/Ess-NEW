<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HolidayController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Holiday API Routes
Route::prefix('holidays')->group(function () {
    // Check if a specific date is a holiday
    Route::get('/check', [HolidayController::class, 'checkHoliday']);
    
    // Get remaining working days in current month
    Route::get('/remaining-working-days', [HolidayController::class, 'remainingWorkingDays']);
    
    // Calculate overtime pay with holiday considerations
    Route::post('/calculate-overtime', [HolidayController::class, 'calculateOvertimePay']);
    
    // Get holidays in a date range
    Route::get('/range', [HolidayController::class, 'getHolidays']);
    
    // Get upcoming holidays
    Route::get('/upcoming', [HolidayController::class, 'upcomingHolidays']);
});