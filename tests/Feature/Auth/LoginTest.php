<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

uses(RefreshDatabase::class);

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123')
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password123'
    ]);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'access_token'
        ]);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123')
    ]);

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials'
        ]);
});

test('user cannot login with non-existent email', function () {
    $response = $this->postJson(route('login'), [
        'email' => 'nonexistent@example.com',
        'password' => 'password123'
    ]);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'message' => 'Invalid credentials'
        ]);
});

test('user can logout successfully', function () {
    $user = User::factory()->create();
    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->deleteJson(route('logout'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Logged out successfully'
        ]);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

test('user cannot logout without authentication', function () {
    $response = $this->deleteJson(route('logout'));

    $response->assertStatus(Response::HTTP_UNAUTHORIZED);
});
