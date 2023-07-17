<?php

use App\Http\Controllers\api\PaymentController;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Tests\TestCase;

beforeEach(function () {
    $this->paymentService = mock(PaymentService::class);
    $this->paymentController = new PaymentController();
});

it('makes a payment and returns JSON response', function () {
    $user = User::factory()->create();
    Auth::shouldReceive('user')->andReturn($user);

    $request = PaymentRequest::create('/api/pay', 'POST', [
        'amount' => 10,
        'event_id' => 1,
        'ticket_type' => 'general',
        'quantity' => 2,
    ]);

    $this->paymentService
        ->shouldReceive('initializeTransaction')
        ->withArgs(function ($data) use ($request) {
            return $data['amount'] === $request->amount * 100 &&
                $data['email'] === $request->user()->email &&
                $data['user_id'] === $request->user()->id &&
                $data['event_id'] === $request->event_id &&
                $data['ticket_type'] === $request->ticket_type &&
                $data['quantity'] === $request->quantity;
        })
        ->andReturn(['transaction_id' => 123, 'transaction_url' => 'https://payment-url']);

    $response = $this->paymentController->makePayment($request);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toEqual([
        'message' => 'Payment initialized',
        'data' => ['transaction_id' => 123, 'transaction_url' => 'https://payment-url'],
        'status' => 200,
    ]);

    // Assert that the payment is stored in the database
    expect(Payment::where('amount', $request->amount * 100)->exists())->toBeTrue();
});

it('verifies a successful transaction and returns JSON response', function () {
    $request = Request::create('/api/verifyTransaction', 'POST', ['reference' => 'abc123']);

    $this->paymentService
        ->shouldReceive('verifyTransaction')
        ->with('abc123')
        ->andReturn(['status' => true]);

    $payment = Payment::factory()->create(['reference' => 'abc123', 'status' => 'pending']);

    $response = $this->paymentController->verifyTransaction($request);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getData())->toEqual([
        'message' => 'Payment Successful. Ticket booked',
        'data' => $payment->ticket->toArray(),
        'status' => 200,
    ]);

    // Assert that the payment status is updated to 'successful'
    expect($payment->fresh()->status)->toBe('successful');
});

it('verifies a failed transaction and returns JSON response', function () {
    $request = Request::create('/api/verify-transaction', 'POST', ['reference' => 'abc123']);

    $this->paymentService
        ->shouldReceive('verifyTransaction')
        ->with('abc123')
        ->andReturn(['status' => false]);

    $response = $this->paymentController->verifyTransaction($request);

    expect($response)->toBeInstanceOf(JsonResponse::class);
    expect($response->getStatusCode())->toBe(400);
    expect($response->getData())->toEqual([
        'message' => 'Payment failed',
        'status' => 400,
    ]);
});
