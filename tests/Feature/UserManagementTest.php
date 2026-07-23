<?php

use App\Models\User;
use App\Models\CreativeEconomy;
use App\Models\Accommodation;
use App\Models\CulinaryPlace;

test('public detail endpoints increment view counts real-time', function () {
    $ekraf = CreativeEconomy::create([
        'name' => 'Kerajinan Batik Karawang',
        'slug' => 'kerajinan-batik-karawang',
        'description' => 'Batik khas Karawang',
        'status' => 'published',
        'views' => 10,
    ]);

    $accommodation = Accommodation::create([
        'name' => 'Hotel Resinda Karawang',
        'slug' => 'hotel-resinda-karawang',
        'type' => 'hotel',
        'address' => 'Jl. Resinda Raya',
        'description' => 'Hotel berbintang',
        'status' => 'published',
        'views' => 20,
    ]);

    $culinary = CulinaryPlace::create([
        'name' => 'Soto Gempol Karawang',
        'slug' => 'soto-gempol-karawang',
        'type' => 'restoran',
        'address' => 'Jl. Gempol',
        'description' => 'Kuliner legendaris',
        'status' => 'published',
        'views' => 30,
    ]);

    $this->get(route('public.ekraf.show', $ekraf->slug))->assertStatus(200);
    $this->assertDatabaseHas('creative_economies', ['id' => $ekraf->id, 'views' => 11]);

    $this->get(route('public.accommodation.show', $accommodation->slug))->assertStatus(200);
    $this->assertDatabaseHas('accommodations', ['id' => $accommodation->id, 'views' => 21]);

    $this->get(route('public.culinary.show', $culinary->slug))->assertStatus(200);
    $this->assertDatabaseHas('culinary_places', ['id' => $culinary->id, 'views' => 31]);
});

test('admin can manage users list filter by role and reset password', function () {
    $admin = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $publicUser = User::create([
        'name' => 'Masyarakat Biasa',
        'email' => 'masyarakat@example.com',
        'password' => bcrypt('password'),
        'role' => 'public',
    ]);

    // Admin can view user list
    $response = $this->actingAs($admin)->get(route('admin.users.index'));
    $response->assertStatus(200);

    // Admin can filter by role=public
    $responseFilter = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'public']));
    $responseFilter->assertStatus(200);

    // Admin can reset user password
    $resetResponse = $this->actingAs($admin)->post(route('admin.users.reset-password', $publicUser->id), [
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $resetResponse->assertRedirect();
    
    // User can login with new password
    $this->post(route('login'), [
        'email' => 'masyarakat@example.com',
        'password' => 'newpassword123',
    ])->assertRedirect();
});
