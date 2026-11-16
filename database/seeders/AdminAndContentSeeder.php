<?php

namespace Database\Seeders;

use App\Models\DocumentaryVideo;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminAndContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Default Admin Account (username: admin | password: admin@1231)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Arsip Budaya',
                'email' => 'admin@menganyamruang.id',
                'role' => 'admin',
                'password' => Hash::make('admin@1231'),
            ]
        );

        // 2. Seed Documentary Videos if empty
        if (DocumentaryVideo::count() === 0) {
            $videos = [
                [
                    'sort_order' => 1,
                    'code' => 'VID-01',
                    'title' => 'Jejak Arsitektur & Harmoni Rumah Tjong A Fie',
                    'location_subtitle' => 'Kawasan Kesawan, Kec. Medan Barat — Kota Medan',
                    'category' => 'Situs & Cagar Budaya',
                    'youtube_id' => '9No-FiEInLA',
                    'youtube_url' => 'https://www.youtube.com/watch?v=9No-FiEInLA',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/9No-FiEInLA?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/tjong_a_fie_mansion.jpg',
                    'duration' => '04:32',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 2,
                    'code' => 'VID-02',
                    'title' => 'Ritus & Spiritualitas Vihara Gunung Timur Medan',
                    'location_subtitle' => 'Jl. Hang Tuah, Madras Hulu, Kec. Medan Polonia',
                    'category' => 'Rumah Ibadah',
                    'youtube_id' => 'ysz5S6PUM-U',
                    'youtube_url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/ysz5S6PUM-U?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/vihara_gunung_timur.jpg',
                    'duration' => '05:18',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 3,
                    'code' => 'VID-03',
                    'title' => 'Akulturasi Budaya: Masjid Lama Gang Bengkok (1874)',
                    'location_subtitle' => 'Jl. Mesjid No. 62, Kesawan, Kec. Medan Barat',
                    'category' => 'Rumah Ibadah',
                    'youtube_id' => 'jNQXAC9IVRw',
                    'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/masjid_gang_bengkok.jpg',
                    'duration' => '03:45',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 4,
                    'code' => 'VID-04',
                    'title' => 'Gastronomi Pecinan: Lorong Kuliner Jalan Selat Panjang',
                    'location_subtitle' => 'Pandau Hulu II, Kec. Medan Area — Kota Medan',
                    'category' => 'Kuliner & Tradisi',
                    'youtube_id' => 'aqz-KE-bpKQ',
                    'youtube_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/aqz-KE-bpKQ?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/kuliner_mie_hokkien.jpg',
                    'duration' => '06:10',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 5,
                    'code' => 'VID-05',
                    'title' => 'Museum Situs Kota Cina: Dermaga Kuno Abad ke-11 di Marelan',
                    'location_subtitle' => 'Jl. Kota Cina No. 65, Paya Pasir, Kec. Medan Marelan',
                    'category' => 'Museum China',
                    'youtube_id' => 'ScMzIvxBSi4',
                    'youtube_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/ScMzIvxBSi4?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/site_024_a.svg',
                    'duration' => '07:24',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 6,
                    'code' => 'VID-06',
                    'title' => 'Tradisi Cheng Beng & Lanskap Pemakaman Kedai Durian',
                    'location_subtitle' => 'Kedai Durian, Kec. Medan Johor & Kec. Deli Tua',
                    'category' => 'Titik Makam',
                    'youtube_id' => 'LXb3EKWsInQ',
                    'youtube_url' => 'https://www.youtube.com/watch?v=LXb3EKWsInQ',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/LXb3EKWsInQ?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/tjong_a_fie_interior.jpg',
                    'duration' => '04:55',
                    'is_active' => true,
                ],
                [
                    'sort_order' => 7,
                    'code' => 'VID-07',
                    'title' => 'Menganyam Ruang: Koridor Bersejarah Kesawan & Perkampungan Sukaramai',
                    'location_subtitle' => 'Kawasan Kesawan & Kawasan Sukaramai — Kota Medan',
                    'category' => 'Perkampungan & Situs',
                    'youtube_id' => 'fLexgOxsZu0',
                    'youtube_url' => 'https://www.youtube.com/watch?v=fLexgOxsZu0',
                    'embed_url' => 'https://www.youtube-nocookie.com/embed/fLexgOxsZu0?rel=0&modestbranding=1',
                    'thumbnail' => '/images/sites/kawasan_kesawan.jpg',
                    'duration' => '05:40',
                    'is_active' => true,
                ],
            ];

            foreach ($videos as $v) {
                DocumentaryVideo::create($v);
            }

            File::put(
                storage_path('app/documentary_videos.json'),
                json_encode(DocumentaryVideo::orderBy('sort_order')->get()->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        // 3. Seed Team Members if empty
        if (TeamMember::count() === 0) {
            TeamMember::create([
                'sort_order' => 0,
                'no' => '00',
                'name' => 'Rio Fernandez Tamba',
                'institution' => 'Politeknik Wilmar Bisnis Indonesia',
                'role' => 'Pengusul Program & Ketua Kajian',
                'is_proposer' => true,
                'website' => 'https://riotamba.com',
                'website_label' => 'riotamba.com',
                'bio' => 'Pengusul adalah Dosen Pengelolaan Konvensi dan Acara dengan minat kajian Tata Kelola Seni dan Budaya. Program Dukungan Dana Indonesiana 2025 ini dengan judul Menganyam Ruang dan Warna Ekspresi Kebudayaan Tionghoa dalam Kota Multikultural Medan',
            ]);

            $members = [
                ['no' => '01', 'name' => 'Chandro Pardede', 'institution' => 'Institut Teknologi Del', 'role' => 'Tim Riset & Pengembangan Sistem Arsip Digital'],
                ['no' => '02', 'name' => 'Rizky Ramadhansyah', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Pemetaan Budaya'],
                ['no' => '03', 'name' => 'Muhammad Roestian Fahmi', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Dokumentasi Visual'],
                ['no' => '04', 'name' => 'Septian Simatupang', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Kurasi Data Situs'],
                ['no' => '05', 'name' => 'Viqki Maulana', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '06', 'name' => 'Dafi Alkhafis Siregar', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '07', 'name' => 'Hajutomar Rahel M. Sinaga', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '08', 'name' => 'Alvin Limtan Winata', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '09', 'name' => 'Alfi Armansyah', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
            ];

            foreach ($members as $idx => $m) {
                TeamMember::create([
                    'sort_order' => $idx + 1,
                    'no' => $m['no'],
                    'name' => $m['name'],
                    'institution' => $m['institution'],
                    'role' => $m['role'],
                    'is_proposer' => false,
                ]);
            }

            File::put(
                storage_path('app/team_members.json'),
                json_encode(TeamMember::orderBy('sort_order')->get()->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }
    }
}
