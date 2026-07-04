<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organization_profiles')->insert([
            'vision' => 'Terwujudnya Ekonomi Kerakyatan yang Kreatif, Produktif dan Berdaya Saing serta Berbasis pada Potensi Lokal (Derivasi RPJMD 2021-2026 Misi ke-2).',
            'mission' => "1. Mendorong pertumbuhan ekonomi kerakyatan berbasis pariwisata dan ekonomi kreatif.\n2. Melestarikan dan mengembangkan potensi kebudayaan lokal Karawang.\n3. Meningkatkan tata kelola kelembagaan dan kualitas pelayanan publik sektor pariwisata.",
            'history' => 'Institusi ini dikukuhkan pertama kali lewat Perda No. 10/2008, mengalami revisi regulasi lewat Perda No. 14/2016, dirombak lewat Perda No. 11/2021, dengan landasan operasional Perbup No. 70/2021 (diamandemen Perbup No. 430/2023). Berfokus penuh pada pengembangan destinasi pariwisata, seni budaya, serta ekosistem ekonomi kreatif di Kabupaten Karawang.',
            'address' => 'Jl. Alun-Alun Selatan No. 1, Karawang Kulon, Kec. Karawang Barat, Kode Pos 41311',
            'phone' => '(0267) 429800',
            'email' => 'disparbud@karawangkab.go.id',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
