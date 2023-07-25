<?php

use App\Helper\Helper;
use App\Models\Event;
use App\Models\Payment;
use App\Services\PaymentService;
use Mockery\MockInterface;


it('verifies a successful transaction', function () {

    $paymentServiceMock = Mockery::mock(PaymentService::class, function (MockInterface $mock) {
        $mock->shouldReceive('verifyTransaction')
            ->with('ab2123jej34')
            ->andReturn(['status' => true]);
    });

    $helperMock = Mockery::mock(Helper::class, function (MockInterface $mock) {
        // $mock->shouldReceive('getDomain')
        //     ->andReturn('127.0.0.1');
        $mock->shouldReceive('generateLink')
            ->with('testTitle')
            ->andReturn([
                'long_url' => 'http://longurl/1234.com',
                'short_id' => '1234',
                'short_url' => 'http://shorturl.com',
            ]);
    });
    // dd($helperMock);
    $this->instance(Helper::class, $helperMock);

    $payment = Payment::factory()->create(['reference' => 'ab2123jej34', 'status' => 'pending']);

    $this->instance(PaymentService::class, $paymentServiceMock);

    $response =  $this->json(
        'GET',
        '/api/v1/verifyTransaction',
        ['reference' => 'ab2123jej34']
    );

    expect($response->getStatusCode())->toBeTruthy();
    expect($response->getData()->message)->toBe('Payment Successful. Ticket booked');
});
