<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\{AuthController, UserController};
use App\Models\User;

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


Route::prefix('v1')->group(function () {

    /// Declare the heartbeat route for the API
    Route::any('/', function () {
        return response()->json(['message' => 'Welcome to Elite Homes API'], 200);
    });
    Route::group(['prefix' => 'api/events/{eventId}'], function () {
        // Ticket routes
        Route::get('/tickets', [TicketController::class, 'index']);
        Route::post('/tickets', [TicketController::class, 'store']);
        Route::get('/tickets/{ticketId}', [TicketController::class, 'show']);
        Route::put('/tickets/{ticketId}', [TicketController::class, 'update']);
        Route::delete('/tickets/{ticketId}', [TicketController::class, 'destroy']);
    
        // Booking routes
        Route::get('/bookings', [BookingController::class, 'index']);
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/{booking}', [BookingController::class, 'show']);
        Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
    });



    // Declare unauthenticated routes
    Route::group(['middleware' => 'guest'], function () {

        // Place your unauthenticated routes here
        Route::post('register', [AuthController::class, 'register'])->name('register');

        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::post('users/{id}', [UserController::class, 'show'])->name('show');
    });


    //Declare Authenticated routes
    Route::group(['middleware' => 'auth:api'], static function () {


        Route::prefix('users')->middleware(['role:user'])->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('index');
            Route::put('/{id}', [UserController::class, 'update'])->name(' update');
        });
    });
});
