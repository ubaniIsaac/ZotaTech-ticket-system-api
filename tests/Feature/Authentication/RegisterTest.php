<?php

namespace Tests\Feature\Authentication;

use Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, assertDatabaseCount};

it('Register user', function () {

    $data = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => 'password',
        'confirm_password' => 'password'
    ];

    $response = $this->postJson(route('register', $data));
    $data = $response->json('user');

    //Assertions
    $response->assertStatus(201);
    expect($response['message'])->toBe('User created successfully');
    expect($response->json('user'))->toBeArray();
    $this->assertDatabaseHas('users', $data);
});
