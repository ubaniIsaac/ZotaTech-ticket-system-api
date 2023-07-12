<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\{AuthController, UserController, EventController, RedirectController };
use App\Models\User;
use Illuminate\Support\Facades\Redis;

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
        return response()->json(['message' => 'Welcome to Open Tickets Apis'], 200);
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

        Route::get('/{short_link}', [RedirectController::class, 'redirect'])->name('redirect');

        Route::post('users/{id}', [UserController::class, 'show'])->name('show');

        Route::post('events/{id}', [EventController::class, 'show'])->name('show');
    });


    //Declare Authenticated routes
    Route::group(['middleware' => 'auth:api'], static function () {


        Route::prefix('users')->middleware(['role:user'])->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('index');
            Route::put('/{id}', [UserController::class, 'update'])->name(' update');
        });

        Route::prefix('events')->group(function (){
            Route::post('/', [EventController::class, 'store'])->name('store');
        }); 
    });
});
