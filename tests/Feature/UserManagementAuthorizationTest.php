<?php

use App\Models\User;

test('admin biasa tidak bisa mengakses halaman manajemen user', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(403);
});

test('super admin bisa mengakses halaman manajemen user', function () {
    $superAdmin = User::factory()->create(['role' => 'super_admin']);

    $response = $this->actingAs($superAdmin)->get(route('admin.users.index'));

    $response->assertStatus(200);
});
