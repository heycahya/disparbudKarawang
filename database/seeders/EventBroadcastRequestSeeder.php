<?php

namespace Database\Seeders;

use App\Models\EventBroadcastRequest;
use App\Models\News;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;

class EventBroadcastRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicUser1 = User::where('email', 'public1@example.com')->first() ?? User::where('role', 'public')->first();
        $publicUser2 = User::where('email', 'public2@example.com')->first() ?? User::where('role', 'public')->first();
        $adminUser = User::where('role', 'admin')->first();
        $news = News::first();

        if (! $publicUser1) return;

        // 1. Event Broadcast Status: masuk (Pending)
        EventBroadcastRequest::create([
            'user_id' => $publicUser1->id,
            'organization' => 'Komunitas Seni & Budaya Pasundan Karawang',
            'event_name' => 'Festival Jaipong Nusantara & Pasundan 2026',
            'event_location' => 'Lapangan Karangpawitan, Karawang Barat',
            'event_date' => now()->addDays(15),
            'end_date' => now()->addDays(17),
            'target_audience' => 'Pelajar, Mahasiswa, Pegiat Seni, dan Masyarakat Umum (Target 1.500 pengunjung)',
            'description' => 'Pagelaran tari Jaipong massal gabungan 20 sanggar tari se-Kabupaten Karawang dilengkapi bazar kuliner tradisional Sunda.',
            'status' => 'masuk',
            'attachment' => CloudinaryService::getSampleUrl('culture', 0),
        ]);

        // 2. Event Broadcast Status: ditinjau (Process)
        EventBroadcastRequest::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'organization' => 'Himpunan Pengusaha Kriya & Ekraf Karawang',
            'event_name' => 'Pameran Ekonomi Kreatif & Kriya Karawang',
            'event_location' => 'Gedung Kebudayaan Karawang',
            'event_date' => now()->addDays(20),
            'end_date' => now()->addDays(22),
            'target_audience' => 'Pelaku UMKM, Pengunjung Lokal, dan Wisatawan (Estimasi 800 orang)',
            'description' => 'Pameran hasil produk kriya batik tenun Karawang, kerajinan bambu, dan demonstrasi pembuatan kuliner khas.',
            'status' => 'ditinjau',
            'admin_note' => 'Permohonan siaran acara sedang dievaluasi jadwal pengagendaan kalender event kabupaten.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDay(),
            'attachment' => CloudinaryService::getSampleUrl('ekraf', 0),
        ]);

        // 3. Event Broadcast Status: disetujui (ACC & Converted)
        EventBroadcastRequest::create([
            'user_id' => $publicUser1->id,
            'organization' => 'Karang Taruna Kecamatan Batujaya',
            'event_name' => 'Kirab Budaya & Pentas Seni Candi Jiwa 2026',
            'event_location' => 'Kawasan Cagar Budaya Candi Jiwa Batujaya',
            'event_date' => now()->addDays(30),
            'end_date' => now()->addDays(31),
            'target_audience' => 'Masyarakat Kabupaten Karawang & Wisatawan Sejarah',
            'description' => 'Pawai budaya tradisional, pertunjukan wayang golek, dan pentas musik etnik nusantara.',
            'status' => 'disetujui',
            'admin_note' => 'Permohonan siaran disetujui dan dipublikasikan ke kanal agenda berita Disparbud Karawang.',
            'converted_news_id' => $news?->id,
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(2),
            'attachment' => CloudinaryService::getSampleUrl('culture', 1),
        ]);

        // 4. Event Broadcast Status: ditolak (Rejected)
        EventBroadcastRequest::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'organization' => 'Klub Komersial Promo Produk',
            'event_name' => 'Bazar Penjualan Barang Impor Bekas',
            'event_location' => 'Area Parkir Publik',
            'event_date' => now()->addDays(5),
            'end_date' => now()->addDays(5),
            'target_audience' => 'Pembeli umum',
            'description' => 'Bazar diskon penjualan barang komersial pribadi.',
            'status' => 'ditolak',
            'admin_note' => 'Permohonan ditolak karena bukan merupakan event kebudayaan, pariwisata, atau ekraf resmi.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(3),
        ]);
    }
}
