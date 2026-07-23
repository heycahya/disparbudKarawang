<?php

namespace Database\Seeders;

use App\Services\CloudinaryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateMissingImagesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tourism Destinations
        $destinations = [
            'candi-jiwa-percandian-batujaya' => CloudinaryService::getSampleUrl('tourism', 0),
            'monumen-perjuangan-rawagede' => CloudinaryService::getSampleUrl('tourism', 1),
            'tugu-kebulatan-tekad' => CloudinaryService::getSampleUrl('tourism', 2),
            'pantai-tangkolak-hutan-mangrove' => CloudinaryService::getSampleUrl('tourism', 3),
            'pantai-tanjung-pakis' => CloudinaryService::getSampleUrl('tourism', 0),
            'pantai-samudra-baru' => CloudinaryService::getSampleUrl('tourism', 1),
            'makam-syekh-quro' => CloudinaryService::getSampleUrl('culture', 0),
            'makam-keramat-nagasari' => CloudinaryService::getSampleUrl('culture', 1),
            'danau-cipule-situ-cipule' => CloudinaryService::getSampleUrl('tourism', 2),
            'situ-darwin' => CloudinaryService::getSampleUrl('tourism', 3),
            'wisata-danau-wanajaya' => CloudinaryService::getSampleUrl('tourism', 0),
            'kawasan-industri-surya-cipta' => CloudinaryService::getSampleUrl('accommodation', 0),
            'kawasan-international-industrial-city-kiic' => CloudinaryService::getSampleUrl('accommodation', 1),
        ];

        foreach ($destinations as $slug => $imageUrl) {
            DB::table('tourism_destinations')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('cover_image')->orWhere('cover_image', '');
                })
                ->update(['cover_image' => $imageUrl]);
        }

        // 2. Cultures
        $cultures = [
            'tari-goyang-karawang' => CloudinaryService::getSampleUrl('culture', 0),
            'seni-ajeng' => CloudinaryService::getSampleUrl('culture', 1),
            'topeng-banjet' => CloudinaryService::getSampleUrl('culture', 2),
        ];

        foreach ($cultures as $slug => $imageUrl) {
            DB::table('cultures')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('cover_image')->orWhere('cover_image', '');
                })
                ->update(['cover_image' => $imageUrl]);
        }

        // 3. Creative Economies
        $ekraf = [
            'batik-karawang-rumah-kreasi-taza' => CloudinaryService::getSampleUrl('ekraf', 0),
            'kampung-belanja-boneka-cikampek' => CloudinaryService::getSampleUrl('ekraf', 1),
            'oleh-oleh-turubuk' => CloudinaryService::getSampleUrl('ekraf', 2),
        ];

        foreach ($ekraf as $slug => $imageUrl) {
            DB::table('creative_economies')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('cover_image')->orWhere('cover_image', '');
                })
                ->update(['cover_image' => $imageUrl]);
        }

        // 4. Accommodations
        $accommodations = [
            'resinda-hotel-karawang-padma-hotels' => CloudinaryService::getSampleUrl('accommodation', 0),
            'mercure-karawang' => CloudinaryService::getSampleUrl('accommodation', 1),
            'brits-hotel-karawang' => CloudinaryService::getSampleUrl('accommodation', 2),
            'swiss-belhotel-karawang' => CloudinaryService::getSampleUrl('accommodation', 0),
            'asialink-premier-hotel-residence' => CloudinaryService::getSampleUrl('accommodation', 1),
            'novotel-karawang' => CloudinaryService::getSampleUrl('accommodation', 2),
            'primebiz-hotel-karawang' => CloudinaryService::getSampleUrl('accommodation', 0),
            'grand-karawang-indah-hotel' => CloudinaryService::getSampleUrl('accommodation', 1),
        ];

        foreach ($accommodations as $slug => $imageUrl) {
            DB::table('accommodations')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('cover_image')->orWhere('cover_image', '');
                })
                ->update(['cover_image' => $imageUrl]);
        }

        // 5. Culinary Places
        $culinary = [
            'resto-lawasan-caraka' => CloudinaryService::getSampleUrl('culinary', 0),
            'swiss-cafe-restaurant' => CloudinaryService::getSampleUrl('culinary', 1),
            'zenfuku-restaurant' => CloudinaryService::getSampleUrl('culinary', 2),
            'konter-teppanyaki-swiss-belhotel' => CloudinaryService::getSampleUrl('culinary', 0),
        ];

        foreach ($culinary as $slug => $imageUrl) {
            DB::table('culinary_places')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('cover_image')->orWhere('cover_image', '');
                })
                ->update(['cover_image' => $imageUrl]);
        }

        // 6. News
        $news = [
            'pemkab-karawang-bakal-gelar-festival-dragon-boat-pertama-1500-peserta-siap-ramaikan-danau-cipule' => CloudinaryService::getSampleUrl('news', 0),
            'bidang-kebudayaan-dan-pora-tukar-tempat-disparbud-karawang-siap-jalani-transisi' => CloudinaryService::getSampleUrl('news', 1),
            'bukan-sekadar-kawasan-industri-karawang-targetkan-penambahan-desa-wisata-baru-di-2026' => CloudinaryService::getSampleUrl('news', 2),
            'sebentar-lagi-warga-bisa-akses-informasi-cagar-budaya-karawang-lewat-kode-qr-sagawang' => CloudinaryService::getSampleUrl('news', 0),
        ];

        foreach ($news as $slug => $imageUrl) {
            DB::table('news')
                ->where('slug', $slug)
                ->where(function ($query) {
                    $query->whereNull('thumbnail')->orWhere('thumbnail', '');
                })
                ->update(['thumbnail' => $imageUrl]);
        }
    }
}
