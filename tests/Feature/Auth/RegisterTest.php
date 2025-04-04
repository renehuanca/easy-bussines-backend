<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

uses(RefreshDatabase::class, WithFaker::class);

test('user can register with valid data', function () {
    $userData = User::factory()->make()->toArray();
    $userData['password'] = 'password123';
    $userData['password_confirmation'] = 'password123';

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'access_token',
            'user' => [
                'id',
                'name',
                'email',
                'phone_number',
                'address',
                'created_at',
                'updated_at',
            ]
        ]);

    expect(User::where('email', $userData['email'])->first())
        ->name->toBe($userData['name'])
        ->phone_number->toBe($userData['phone_number'])
        ->address->toBe($userData['address']);
});

test('user cannot register with invalid email', function () {
    $userData = User::factory()->make()->toArray();
    $userData['email'] = 'invalid-email';
    $userData['password'] = 'password123';
    $userData['password_confirmation'] = 'password123';

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

test('user cannot register with existing email', function () {
    $existingUser = User::factory()->create();
    $userData = User::factory()->make()->toArray();
    $userData['email'] = $existingUser->email;
    $userData['password'] = 'password123';
    $userData['password_confirmation'] = 'password123';

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['email']);
});

test('user cannot register without password confirmation', function () {
    $userData = User::factory()->make()->toArray();

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['password']);
});
