<?php

use App\Models\User;

test('public user is redirected to public dashboard after login', function () {
    $user = User::factory()->create([
        'role' => 'public',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
});

test('public user is forbidden from admin dashboard', function () {
    $user = User::factory()->create([
        'role' => 'public',
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertStatus(403);
});

test('admin user is redirected to admin dashboard after login', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin/dashboard');
});

test('admin user is forbidden from account management', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get('/admin/manajemen-akun');

    $response->assertStatus(403);
});

test('super admin can access admin dashboard and account management', function () {
    $user = User::factory()->create([
        'role' => 'super_admin',
    ]);

    $response1 = $this->actingAs($user)->get('/admin/dashboard');
    $response1->assertStatus(200);

    $response2 = $this->actingAs($user)->get('/admin/manajemen-akun');
    $response2->assertStatus(200);
});

test('guest user is redirected to login when accessing public dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('guest user is redirected to login when accessing admin dashboard', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect('/login');
});
