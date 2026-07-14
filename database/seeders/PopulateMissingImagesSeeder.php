<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PopulateMissingImagesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tourism Destinations
        $destinations = [
            'candi-jiwa-percandian-batujaya' => 'https://images.unsplash.com/photo-1508193638397-1c4234db14d8?w=1200&q=80',
            'monumen-perjuangan-rawagede' => 'https://images.unsplash.com/photo-1526958097901-5e6d742d3371?w=1200&q=80',
            'tugu-kebulatan-tekad' => 'https://images.unsplash.com/photo-1555636222-cae831e670b3?w=1200&q=80',
            'pantai-tangkolak-hutan-mangrove' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
            'pantai-tanjung-pakis' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
            'pantai-samudra-baru' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80',
            'makam-syekh-quro' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=1200&q=80',
            'makam-keramat-nagasari' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=1200&q=80',
            'danau-cipule-situ-cipule' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1200&q=80',
            'situ-darwin' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1200&q=80',
            'wisata-danau-wanajaya' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=1200&q=80',
            'kawasan-industri-surya-cipta' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=1200&q=80',
            'kawasan-international-industrial-city-kiic' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=1200&q=80',
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
            'tari-goyang-karawang' => 'https://images.unsplash.com/photo-1508918290772-74d17d890d6b?auto=format&fit=crop&w=800&q=80',
            'seni-ajeng' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=800&q=80',
            'topeng-banjet' => 'https://images.unsplash.com/photo-1507676184212-d03ab07a01bf?auto=format&fit=crop&w=800&q=80',
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
            'batik-karawang-rumah-kreasi-taza' => 'https://images.unsplash.com/photo-1606744888344-493238951221?auto=format&fit=crop&w=800&q=80',
            'kampung-belanja-boneka-cikampek' => 'https://images.unsplash.com/photo-1559251606-c623743a6d76?auto=format&fit=crop&w=800&q=80',
            'oleh-oleh-turubuk' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
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
            'resinda-hotel-karawang-padma-hotels' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80',
            'mercure-karawang' => 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80',
            'brits-hotel-karawang' => 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80',
            'swiss-belhotel-karawang' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80',
            'asialink-premier-hotel-residence' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
            'novotel-karawang' => 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=800&q=80',
            'primebiz-hotel-karawang' => 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=800&q=80',
            'grand-karawang-indah-hotel' => 'https://images.unsplash.com/photo-1498503182468-3b51cbb6cb24?auto=format&fit=crop&w=800&q=80',
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
            'resto-lawasan-caraka' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80',
            'swiss-cafe-restaurant' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
            'zenfuku-restaurant' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=800&q=80',
            'konter-teppanyaki-swiss-belhotel' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?auto=format&fit=crop&w=800&q=80',
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
            'pemkab-karawang-bakal-gelar-festival-dragon-boat-pertama-1500-peserta-siap-ramaikan-danau-cipule' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=800&q=80',
            'bidang-kebudayaan-dan-pora-tukar-tempat-disparbud-karawang-siap-jalani-transisi' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
            'bukan-sekadar-kawasan-industri-karawang-targetkan-penambahan-desa-wisata-baru-di-2026' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80',
            'sebentar-lagi-warga-bisa-akses-informasi-cagar-budaya-karawang-lewat-kode-qr-sagawang' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?auto=format&fit=crop&w=800&q=80',
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
