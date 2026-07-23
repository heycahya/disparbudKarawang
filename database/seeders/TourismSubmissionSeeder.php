<?php

namespace Database\Seeders;

use App\Models\TourismSubmission;
use App\Models\TourismDestination;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;

class TourismSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicUser1 = User::where('email', 'public1@example.com')->first() ?? User::where('role', 'public')->first();
        $publicUser2 = User::where('email', 'public2@example.com')->first() ?? User::where('role', 'public')->first();
        $adminUser = User::where('role', 'admin')->first();
        $destination = TourismDestination::first();

        if (! $publicUser1) return;

        // 1. Tourism Submission Status: masuk (Pending)
        TourismSubmission::create([
            'user_id' => $publicUser1->id,
            'name' => 'Wisata Air Lembah Hijau Tegalwaru',
            'category' => 'Wisata Alam',
            'address' => 'Desa Mekarbuana, Kec. Tegalwaru, Kabupaten Karawang',
            'description' => 'Spot wisata alam mata air pegunungan yang sangat jernih dilengkapi pemandangan sawah terasering dan udara segar Pegunungan Sanggabuana.',
            'contact' => '081234567890',
            'operating_hours' => '08.00 - 17.00 WIB',
            'ticket_price' => '15000',
            'status' => 'masuk',
            'photo' => CloudinaryService::getSampleUrl('tourism', 0),
        ]);

        // 2. Tourism Submission Status: ditinjau (Process)
        TourismSubmission::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'name' => 'Kampung Ekowisata Mangrove Sedari',
            'category' => 'Wisata Bahari / Pantai',
            'address' => 'Desa Sedari, Kec. Cibuaya, Kabupaten Karawang',
            'description' => 'Kawasan konservasi hutan mangrove dengan jembatan kayu sepanjang 500 meter di atas air laut dan spot foto sunset estetik.',
            'contact' => '082198765432',
            'operating_hours' => '07.00 - 18.00 WIB',
            'ticket_price' => '10000',
            'status' => 'ditinjau',
            'admin_note' => 'Dokumen usulan sedang ditinjau kelayakannya oleh tim survei destinasi baru Disparbud Karawang.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDay(),
            'photo' => CloudinaryService::getSampleUrl('tourism', 1),
        ]);

        // 3. Tourism Submission Status: disetujui (ACC & Converted)
        TourismSubmission::create([
            'user_id' => $publicUser1->id,
            'name' => 'Wisata Edukasi Saung Bambu Karawang',
            'category' => 'Desa Wisata',
            'address' => 'Desa Cimenteng, Kec. Citinggir, Kabupaten Karawang',
            'description' => 'Kawasan wisata edukasi pertanian organik dan kerajinan anyaman bambu khas Karawang.',
            'contact' => '085712345678',
            'operating_hours' => '08.00 - 16.00 WIB',
            'ticket_price' => '20000',
            'status' => 'disetujui',
            'admin_note' => 'Usulan disetujui dan telah dikloning menjadi draft destinasi wisata di katalog publik.',
            'converted_destination_id' => $destination?->id,
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(2),
            'photo' => CloudinaryService::getSampleUrl('tourism', 2),
        ]);

        // 4. Tourism Submission Status: ditolak (Rejected)
        TourismSubmission::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'name' => 'Arena Balap Liar Jalur Industri',
            'category' => 'Lainnya',
            'address' => 'Kawasan Industri KIIC Karawang',
            'description' => 'Permohonan perizinan sirkuit balap liar.',
            'contact' => '081399998888',
            'operating_hours' => '22.00 - 02.00 WIB',
            'ticket_price' => '50000',
            'status' => 'ditolak',
            'admin_note' => 'Usulan ditolak karena tidak memenuhi kriteria destinasi pariwisata aman & melanggar norma keselamatan publik.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(3),
        ]);
    }
}
