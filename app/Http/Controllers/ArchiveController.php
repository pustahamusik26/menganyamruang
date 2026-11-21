<?php

namespace App\Http\Controllers;

use App\Models\CulturalSite;
use App\Models\DocumentaryVideo;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ArchiveController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderArchive($request, 'beranda');
    }

    public function summary(Request $request): View
    {
        return $this->renderArchive($request, 'ringkasan-kajian');
    }

    public function map(Request $request): View
    {
        return $this->renderArchive($request, 'peta-etnografis');
    }

    public function gallery(Request $request): View
    {
        return $this->renderArchive($request, 'galeri-digital');
    }

    public function video(Request $request): View
    {
        return $this->renderArchive($request, 'video-dokumenter');
    }

    public function team(Request $request): View
    {
        return $this->renderArchive($request, 'profil-tim');
    }

    public function catalog(Request $request): View
    {
        return $this->renderArchive($request, 'katalog-situs');
    }

    protected function getSitesCollection()
    {
        try {
            if (!Schema::hasTable('cultural_sites') && !app()->runningUnitTests()) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }

            if (Schema::hasTable('cultural_sites')) {
                $dbSites = CulturalSite::orderBy('marker_number')->get();
                if ($dbSites->isEmpty() && !app()->runningUnitTests()) {
                    (new \Database\Seeders\CulturalSiteSeeder())->run();
                    (new \Database\Seeders\AdminAndContentSeeder())->run();
                    $dbSites = CulturalSite::orderBy('marker_number')->get();
                }
                if ($dbSites->isNotEmpty()) {
                    return $dbSites;
                }
            }
        } catch (\Throwable $e) {
            // Fallback to JSON file below
        }

        $jsonPath = storage_path('app/cultural_sites.json');
        if (!file_exists($jsonPath)) {
            $jsonPath = database_path('data/cultural_sites.json');
        }

        if (file_exists($jsonPath)) {
            $raw = json_decode(file_get_contents($jsonPath), true) ?? [];
            return collect($raw)->map(function ($item, $idx) {
                $model = new CulturalSite($item);
                $model->id = $idx + 1;
                return $model;
            });
        }

        return collect();
    }

    protected function getVideosArray(): array
    {
        try {
            if (Schema::hasTable('documentary_videos')) {
                $dbVideos = DocumentaryVideo::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
                if ($dbVideos->isNotEmpty()) {
                    return $dbVideos->map(fn ($v) => [
                        'id' => $v->id,
                        'code' => $v->code,
                        'title' => $v->title,
                        'location_subtitle' => $v->location_subtitle,
                        'category' => $v->category,
                        'youtube_id' => $v->youtube_id,
                        'youtube_url' => $v->youtube_url,
                        'embed_url' => $v->embed_url,
                        'thumbnail' => $v->thumbnail,
                        'duration' => $v->duration,
                    ])->values()->all();
                }
            }
        } catch (\Throwable $e) {
            // Fallback below
        }

        $jsonPath = storage_path('app/documentary_videos.json');
        if (file_exists($jsonPath)) {
            $raw = json_decode(file_get_contents($jsonPath), true) ?? [];
            if (!empty($raw)) {
                return $raw;
            }
        }

        return [
            [
                'id' => 1,
                'code' => 'VID-01',
                'title' => 'Jejak Arsitektur & Harmoni Rumah Tjong A Fie',
                'location_subtitle' => 'Kawasan Kesawan, Kec. Medan Barat — Kota Medan',
                'category' => 'Situs & Cagar Budaya',
                'youtube_id' => '9No-FiEInLA',
                'youtube_url' => 'https://www.youtube.com/watch?v=9No-FiEInLA',
                'embed_url' => 'https://www.youtube-nocookie.com/embed/9No-FiEInLA?rel=0&modestbranding=1',
                'thumbnail' => '/images/sites/tjong_a_fie_mansion.jpg',
                'duration' => '04:32',
            ],
        ];
    }

    protected function getTeamArray(): array
    {
        try {
            if (Schema::hasTable('team_members')) {
                $proposer = TeamMember::where('is_proposer', true)->first();
                $members = TeamMember::where('is_proposer', false)->orderBy('sort_order')->orderBy('id')->get();
                if ($proposer && $members->isNotEmpty()) {
                    return [
                        'proposer' => [
                            'name' => $proposer->name,
                            'role' => $proposer->role,
                            'website' => $proposer->website ?: 'https://riotamba.com',
                            'website_label' => $proposer->website_label ?: 'riotamba.com',
                            'bio' => $proposer->bio,
                        ],
                        'members' => $members->map(fn ($m) => [
                            'no' => $m->no,
                            'name' => $m->name,
                            'institution' => $m->institution,
                            'role' => $m->role,
                        ])->values()->all(),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Fallback below
        }

        return [
            'proposer' => [
                'name' => 'Rio Fernandez Tamba',
                'role' => 'Pengusul Program & Ketua Kajian',
                'website' => 'https://riotamba.com',
                'website_label' => 'riotamba.com',
                'bio' => 'Pengusul adalah Dosen Pengelolaan Konvensi dan Acara dengan minat kajian Tata Kelola Seni dan Budaya. Program Dukungan Dana Indonesiana 2025 ini dengan judul Menganyam Ruang dan Warna Ekspresi Kebudayaan Tionghoa dalam Kota Multikultural Medan',
            ],
            'members' => [
                ['no' => '01', 'name' => 'Chandro Pardede', 'institution' => 'Institut Teknologi Del', 'role' => 'Tim Riset & Pengembangan Sistem Arsip Digital'],
                ['no' => '02', 'name' => 'Rizky Ramadhansyah', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Pemetaan Budaya'],
                ['no' => '03', 'name' => 'Muhammad Roestian Fahmi', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Dokumentasi Visual'],
                ['no' => '04', 'name' => 'Septian Simatupang', 'institution' => 'Politeknik Wilmar Bisnis Indonesia', 'role' => 'Tim Kajian Lapangan & Kurasi Data Situs'],
                ['no' => '05', 'name' => 'Viqki Maulana', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '06', 'name' => 'Dafi Alkhafis Siregar', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '07', 'name' => 'Hajutomar Rahel M. Sinaga', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '08', 'name' => 'Alvin Limtan Winata', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
                ['no' => '09', 'name' => 'Alfi Armansyah', 'institution' => 'Tim Kerja Menganyam Ruang', 'role' => 'Peneliti & Dokumentator Lapangan'],
            ],
        ];
    }

    protected function renderArchive(Request $request, string $activeSection = 'beranda'): View
    {
        $sites = $this->getSitesCollection();

        $categorySummary = $sites->groupBy('category')->map(function ($group, $cat) {
            $first = $group->first();
            return [
                'name' => $cat,
                'count' => $group->count(),
                'color_name' => $first->color_name,
                'color_hex' => $first->color_hex,
                'sub_categories' => $group->pluck('sub_category')->filter()->unique()->values()->all(),
            ];
        })->values();

        $galleryGroups = [
            'Rumah Ibadah' => $sites->where('gallery_group', 'Rumah Ibadah')->values(),
            'Perkampungan' => $sites->where('gallery_group', 'Perkampungan')->values(),
            'Titik Makam' => $sites->where('gallery_group', 'Titik Makam')->values(),
            'Situs' => $sites->where('gallery_group', 'Situs')->values(),
            'Museum China' => $sites->where('gallery_group', 'Museum China')->values(),
        ];

        $videos = $this->getVideosArray();
        $team = $this->getTeamArray();

        $jingles = [
            [
                'id' => 1,
                'title' => 'OPENING PAGE 1.1 — Profil Tim Kerja & Data Situs',
                'src' => '/audio/opening-page.wav',
                'instrument' => 'Opening Page Theme (OPENING PAGE 1.1.wav)',
                'pages' => ['beranda', 'profil-tim', 'katalog-situs'],
            ],
            [
                'id' => 2,
                'title' => 'MAIN PAGE 1.1 — Peta & Galeri Digital',
                'src' => '/audio/main-page.wav',
                'instrument' => 'Main Page Theme (MAIN PAGE 1.1.wav)',
                'pages' => ['peta-etnografis', 'galeri-digital', 'video-dokumenter'],
            ],
            [
                'id' => 3,
                'title' => 'CONTENT PAGE 1.1 — Ringkasan & Peta Etnografis',
                'src' => '/audio/content-page.wav',
                'instrument' => 'Content Page Theme (CONTENT PAGE 1.1.wav)',
                'pages' => ['ringkasan-kajian', 'peta-etnografis'],
            ],
        ];

        return view('archive.index', [
            'sites' => $sites,
            'categorySummary' => $categorySummary,
            'galleryGroups' => $galleryGroups,
            'videos' => $videos,
            'team' => $team,
            'jingles' => $jingles,
            'activeSection' => $activeSection,
        ]);
    }

    public function apiSites(Request $request): JsonResponse
    {
        $query = CulturalSite::query()->orderBy('marker_number');

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $category = mb_substr(trim($request->string('category')), 0, 80);
            $query->where('category', $category);
        }

        if ($request->filled('sub_category') && $request->string('sub_category') !== 'all') {
            $subCategory = addcslashes(mb_substr(trim($request->string('sub_category')), 0, 80), '%_\\');
            $query->where('sub_category', 'like', "%{$subCategory}%");
        }

        if ($request->filled('gallery_group') && $request->string('gallery_group') !== 'all') {
            $galleryGroup = mb_substr(trim($request->string('gallery_group')), 0, 80);
            $query->where('gallery_group', $galleryGroup);
        }

        if ($request->filled('q')) {
            $rawQ = mb_substr(trim($request->string('q')), 0, 100);
            $q = addcslashes($rawQ, '%_\\');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('sub_category', 'like', "%{$q}%")
                    ->orWhere('desa_kelurahan', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('uniqueness', 'like', "%{$q}%");
            });
        }

        $results = $query->get();

        return response()->json([
            'total' => $results->count(),
            'data' => $results,
        ]);
    }

    public function apiSiteDetail(string $siteCode): JsonResponse
    {
        $sanitizedCode = mb_substr(trim($siteCode), 0, 30);
        $site = CulturalSite::where('site_code', $sanitizedCode)
            ->orWhere('id', is_numeric($sanitizedCode) ? (int)$sanitizedCode : 0)
            ->firstOrFail();

        return response()->json([
            'data' => $site,
        ]);
    }
}
