<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $structures = [
            [
                'name' => 'Abas Sudrajat, S.Sos., M.P.',
                'position' => 'Kepala Dinas',
                'photo' => null,
                'order' => 1,
            ],
            [
                'name' => 'H. Jaeni, S.Pd., M.M.Pd.',
                'position' => 'Sekretaris Dinas',
                'photo' => null,
                'order' => 2,
            ],
            [
                'name' => 'Waya Karmila',
                'position' => 'Kepala Bidang Kebudayaan',
                'photo' => null,
                'order' => 3,
            ],
            [
                'name' => 'Dede Pramiadi Asmara, S.T., M.T.',
                'position' => 'Kepala Bidang Destinasi Wisata/Pemasaran',
                'photo' => null,
                'order' => 4,
            ],
            [
                'name' => 'H. Fazrian Wardana',
                'position' => 'Kepala Bidang Ekonomi Kreatif',
                'photo' => null,
                'order' => 5,
            ],
        ];

        foreach ($structures as $structure) {
            DB::table('organization_structures')->insert(array_merge($structure, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
