<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $functions = [
            [
                'title' => 'Perumusan Kebijakan Strategis',
                'description' => 'Perumusan kebijakan strategis dinas dan penyiapan bahan regulasi daerah di bidang pariwisata, kebudayaan, dan ekonomi kreatif.',
                'order' => 1,
            ],
            [
                'title' => 'Pelaksanaan Urusan Pemerintahan',
                'description' => 'Pelaksanaan dan eksekusi urusan pemerintahan daerah serta pelayanan publik di bidang pariwisata dan kebudayaan.',
                'order' => 2,
            ],
            [
                'title' => 'Pemantauan dan Evaluasi Kinerja',
                'description' => 'Pemantauan, supervisi, evaluasi, dan penyusunan laporan pertanggungjawaban kinerja pelaksanaan program dinas.',
                'order' => 3,
            ],
            [
                'title' => 'Pengelolaan Administrasi Internal',
                'description' => 'Pengelolaan administrasi internal, tata usaha, kepegawaian, dan keuangan dinas.',
                'order' => 4,
            ],
            [
                'title' => 'Pelaksanaan Tugas Afirmatif',
                'description' => 'Pelaksanaan penugasan afirmatif dan tugas pembantuan lainnya dari Kepala Daerah/Bupati.',
                'order' => 5,
            ],
        ];

        foreach ($functions as $function) {
            DB::table('organization_functions')->insert(array_merge($function, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
