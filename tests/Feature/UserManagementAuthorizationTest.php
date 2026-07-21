<?php

use App\Models\User;

test('admin bisa mengakses halaman manajemen user', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
});
