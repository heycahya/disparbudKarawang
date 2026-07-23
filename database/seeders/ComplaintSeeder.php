<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicUser1 = User::where('email', 'public1@example.com')->first() ?? User::where('role', 'public')->first();
        $publicUser2 = User::where('email', 'public2@example.com')->first() ?? User::where('role', 'public')->first();
        $adminUser = User::where('role', 'admin')->first();

        if (! $publicUser1) return;

        // 1. Complaint Status: masuk (Pending)
        Complaint::create([
            'user_id' => $publicUser1->id,
            'subject' => 'Kerusakan Penerangan Jalan Akses Candi Jiwa',
            'category' => 'Fasilitas Destinasi Wisata',
            'location' => 'Kompleks Candi Jiwa Batujaya, Karawang',
            'description' => 'Lampu penerangan jalan menuju cagar budaya Candi Jiwa mati sejak minggu lalu sehingga jalan sangat gelap di malam hari dan berisiko bagi pengunjung.',
            'status' => 'masuk',
            'attachment' => CloudinaryService::getSampleUrl('tourism', 0),
        ]);

        // 2. Complaint Status: ditinjau (Process)
        Complaint::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'subject' => 'Penumpukan Sampah di Area Parkir Pantai Pakis',
            'category' => 'Kebersihan & Keamanan Area',
            'location' => 'Area Parkir Utama Pantai Pakis Karawang',
            'description' => 'Terdapat penumpukan sampah sisa sosis dan plastik di sudut parkir pantai yang mengganggu pemandangan dan menimbulkan bau tidak sedap.',
            'status' => 'ditinjau',
            'admin_note' => 'Tim kebersihan bidang pariwisata sedang berkoordinasi dengan pengelola pantai untuk pembersihan angkut sampah.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDay(),
            'attachment' => CloudinaryService::getSampleUrl('tourism', 1),
        ]);

        // 3. Complaint Status: disetujui (ACC / Approved)
        Complaint::create([
            'user_id' => $publicUser1->id,
            'subject' => 'Usulan Pemasangan Papan Petunjuk Arah Situs Kebudayaan',
            'category' => 'Pelestarian Cagar Budaya',
            'location' => 'Persimpangan Jalur Karawang - Batujaya',
            'description' => 'Diperlukan papan penunjuk arah yang lebih jelas menuju Situs Kebudayaan Candi Blandongan agar wisatawan luar kota tidak tersesat.',
            'status' => 'disetujui',
            'admin_note' => 'Laporan disetujui dan diajukan ke dinas perhubungan & bidang kebudayaan untuk pencetakan plang petunjuk jalan.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(2),
            'attachment' => CloudinaryService::getSampleUrl('culture', 0),
        ]);

        // 4. Complaint Status: ditolak (Rejected)
        Complaint::create([
            'user_id' => $publicUser2?->id ?? $publicUser1->id,
            'subject' => 'Pengaduan Harga Parkir Liar di Area Privat',
            'category' => 'Lainnya',
            'location' => 'Kawasan Pertokoan Ruko Karawang Barat',
            'description' => 'Adanya tarif parkir tidak resmi di area ruko pertokoan swasta.',
            'status' => 'ditolak',
            'admin_note' => 'Pengaduan tidak termasuk kewenangan Disparbud Karawang (area privat pertokoan). Silakan adukan ke Satpol PP / Dinas Perhubungan.',
            'reviewed_by' => $adminUser?->id,
            'reviewed_at' => now()->subDays(3),
        ]);
    }
}
