<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CulturalSite;
use App\Models\DocumentaryVideo;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public const CATEGORY_MAP = [
        'Tempat Ibadah' => [
            'color_name' => 'Merah (Tempat Ibadah)',
            'color_hex' => '#B91C1C',
            'gallery_group' => 'Rumah Ibadah',
        ],
        'Kuliner' => [
            'color_name' => 'Emas (Kuliner & Gastronomi)',
            'color_hex' => '#D97706',
            'gallery_group' => 'Situs',
        ],
        'Pemakaman' => [
            'color_name' => 'Ungu (Pemakaman & Makam)',
            'color_hex' => '#6D28D9',
            'gallery_group' => 'Titik Makam',
        ],
        'Perkampungan & Rumah Bersejarah' => [
            'color_name' => 'Hijau (Perkampungan & Ruang Hidup)',
            'color_hex' => '#15803D',
            'gallery_group' => 'Perkampungan',
        ],
        'Tokoh Sejarah' => [
            'color_name' => 'Biru Tua (Pendidikan, Organisasi & Tokoh)',
            'color_hex' => '#1D4ED8',
            'gallery_group' => 'Situs',
        ],
        'Museum & Cagar Budaya' => [
            'color_name' => 'Coklat Kayu (Situs & Cagar Budaya)',
            'color_hex' => '#78350F',
            'gallery_group' => 'Museum China',
        ],
        'Bangunan Bersejarah' => [
            'color_name' => 'Coklat Kayu (Situs & Cagar Budaya)',
            'color_hex' => '#78350F',
            'gallery_group' => 'Situs',
        ],
        'Pendidikan & Lembaga Sosial' => [
            'color_name' => 'Biru Tua (Pendidikan, Organisasi & Tokoh)',
            'color_hex' => '#1D4ED8',
            'gallery_group' => 'Situs',
        ],
        'Organisasi & Perkumpulan' => [
            'color_name' => 'Biru Tua (Pendidikan, Organisasi & Tokoh)',
            'color_hex' => '#1D4ED8',
            'gallery_group' => 'Situs',
        ],
        'Taman & Ruang Publik' => [
            'color_name' => 'Hijau (Perkampungan & Ruang Hidup)',
            'color_hex' => '#15803D',
            'gallery_group' => 'Perkampungan',
        ],
        'Pusat Perdagangan Bersejarah' => [
            'color_name' => 'Coklat Kayu (Situs & Cagar Budaya)',
            'color_hex' => '#78350F',
            'gallery_group' => 'Situs',
        ],
    ];

    public const GALLERY_GROUPS = [
        'Rumah Ibadah',
        'Perkampungan',
        'Titik Makam',
        'Situs',
        'Museum China',
    ];

    public function index(Request $request): View
    {
        $activeTab = $request->string('tab', 'situs')->toString();
        if (!in_array($activeTab, ['situs', 'video', 'tim', 'akun'], true)) {
            $activeTab = 'situs';
        }

        $sitesQuery = CulturalSite::query()->orderBy('marker_number');

        if ($request->filled('q')) {
            $q = addcslashes(mb_substr(trim($request->string('q')), 0, 100), '%_\\');
            $sitesQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('site_code', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('sub_category', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('kecamatan', 'like', "%{$q}%")
                    ->orWhere('desa_kelurahan', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category') && $request->string('category') !== 'all') {
            $sitesQuery->where('category', $request->string('category'));
        }

        if ($request->filled('gallery_group') && $request->string('gallery_group') !== 'all') {
            $sitesQuery->where('gallery_group', $request->string('gallery_group'));
        }

        $sites = $sitesQuery->get();
        $allSitesCount = CulturalSite::count();
        $featuredGalleryCount = CulturalSite::where('is_featured_gallery', true)->count();

        $videos = DocumentaryVideo::orderBy('sort_order')->orderBy('id')->get();
        $proposer = TeamMember::where('is_proposer', true)->first();
        $teamMembers = TeamMember::where('is_proposer', false)->orderBy('sort_order')->orderBy('id')->get();

        $categoryCounts = CulturalSite::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('admin.dashboard', [
            'activeTab' => $activeTab,
            'sites' => $sites,
            'allSitesCount' => $allSitesCount,
            'featuredGalleryCount' => $featuredGalleryCount,
            'videos' => $videos,
            'proposer' => $proposer,
            'teamMembers' => $teamMembers,
            'categoryMap' => self::CATEGORY_MAP,
            'galleryGroups' => self::GALLERY_GROUPS,
            'categoryCounts' => $categoryCounts,
        ]);
    }

    public function createSite(): View
    {
        $nextMarker = ((int) CulturalSite::max('marker_number')) + 1;
        $nextCode = sprintf('ARC-%03d', $nextMarker);

        $site = new CulturalSite([
            'marker_number' => $nextMarker,
            'site_code' => $nextCode,
            'category' => 'Tempat Ibadah',
            'gallery_group' => 'Rumah Ibadah',
            'color_name' => 'Merah (Tempat Ibadah)',
            'color_hex' => '#B91C1C',
            'regency_city' => 'Kota Medan',
            'latitude' => 3.5852400,
            'longitude' => 98.6755980,
            'is_featured_gallery' => true,
            'sheet_origin' => 'Admin Panel',
        ]);

        return view('admin.site-form', [
            'site' => $site,
            'isEdit' => false,
            'categoryMap' => self::CATEGORY_MAP,
            'galleryGroups' => self::GALLERY_GROUPS,
        ]);
    }

    public function storeSite(Request $request): RedirectResponse
    {
        $validated = $this->validateSiteRequest($request);

        $site = new CulturalSite();
        $this->populateAndSaveSite($site, $validated, $request);
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'situs'])
            ->with('success', "Postingan situs \"{$site->name}\" ({$site->site_code}) berhasil ditambahkan.");
    }

    public function editSite(CulturalSite $site): View
    {
        return view('admin.site-form', [
            'site' => $site,
            'isEdit' => true,
            'categoryMap' => self::CATEGORY_MAP,
            'galleryGroups' => self::GALLERY_GROUPS,
        ]);
    }

    public function updateSite(Request $request, CulturalSite $site): RedirectResponse
    {
        $validated = $this->validateSiteRequest($request, $site->id);

        $this->populateAndSaveSite($site, $validated, $request);
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'situs'])
            ->with('success', "Postingan situs \"{$site->name}\" ({$site->site_code}) berhasil diperbarui.");
    }

    public function destroySite(CulturalSite $site): RedirectResponse
    {
        $name = $site->name;
        $code = $site->site_code;
        $site->delete();
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'situs'])
            ->with('success', "Postingan situs \"{$name}\" ({$code}) telah dihapus.");
    }

    public function storeVideo(Request $request): RedirectResponse
    {
        $validated = $this->validateVideoRequest($request);

        $video = new DocumentaryVideo();
        $this->populateAndSaveVideo($video, $validated, $request);
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'video'])
            ->with('success', "Postingan video dokumenter \"{$video->title}\" ({$video->code}) berhasil ditambahkan.");
    }

    public function updateVideo(Request $request, DocumentaryVideo $video): RedirectResponse
    {
        $validated = $this->validateVideoRequest($request, $video->id);

        $this->populateAndSaveVideo($video, $validated, $request);
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'video'])
            ->with('success', "Postingan video dokumenter \"{$video->title}\" ({$video->code}) berhasil diperbarui.");
    }

    public function destroyVideo(DocumentaryVideo $video): RedirectResponse
    {
        $title = $video->title;
        $video->delete();
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'video'])
            ->with('success', "Postingan video \"{$title}\" berhasil dihapus.");
    }

    public function updateProposer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'role' => ['required', 'string', 'max:150'],
            'institution' => ['nullable', 'string', 'max:180'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'website_label' => ['nullable', 'string', 'max:120'],
            'bio' => ['required', 'string', 'max:2000'],
        ]);

        TeamMember::updateOrCreate(
            ['is_proposer' => true],
            [
                'sort_order' => 0,
                'no' => '00',
                'name' => $validated['name'],
                'role' => $validated['role'],
                'institution' => $validated['institution'] ?? 'Politeknik Wilmar Bisnis Indonesia',
                'website' => $validated['website'] ?? 'https://riotamba.com',
                'website_label' => $validated['website_label'] ?? 'riotamba.com',
                'bio' => $validated['bio'],
            ]
        );

        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'tim'])
            ->with('success', 'Profil Pengusul Program & Ketua Kajian berhasil diperbarui.');
    }

    public function storeTeamMember(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:150'],
            'institution' => ['required', 'string', 'max:180'],
            'role' => ['required', 'string', 'max:180'],
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $nextOrder = $validated['sort_order'] ?? (((int) TeamMember::where('is_proposer', false)->max('sort_order')) + 1);

        TeamMember::create([
            'sort_order' => $nextOrder,
            'no' => $validated['no'],
            'name' => $validated['name'],
            'institution' => $validated['institution'],
            'role' => $validated['role'],
            'is_proposer' => false,
        ]);

        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'tim'])
            ->with('success', "Anggota tim \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function updateTeamMember(Request $request, TeamMember $member): RedirectResponse
    {
        $validated = $request->validate([
            'no' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:150'],
            'institution' => ['required', 'string', 'max:180'],
            'role' => ['required', 'string', 'max:180'],
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $member->update([
            'sort_order' => $validated['sort_order'] ?? $member->sort_order,
            'no' => $validated['no'],
            'name' => $validated['name'],
            'institution' => $validated['institution'],
            'role' => $validated['role'],
        ]);

        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'tim'])
            ->with('success', "Data anggota tim \"{$member->name}\" berhasil diperbarui.");
    }

    public function destroyTeamMember(TeamMember $member): RedirectResponse
    {
        $name = $member->name;
        $member->delete();
        $this->syncJsonBackups();

        return redirect()->route('admin.dashboard', ['tab' => 'tim'])
            ->with('success', "Anggota tim \"{$name}\" berhasil dihapus.");
    }

    protected function validateSiteRequest(Request $request, ?int $ignoreId = null): array
    {
        $uniqueCodeRule = 'unique:cultural_sites,site_code'.($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            'marker_number' => ['required', 'integer', 'min:1', 'max:9999'],
            'site_code' => ['required', 'string', 'max:24', 'regex:/^[A-Za-z0-9_-]+$/', $uniqueCodeRule],
            'name' => ['required', 'string', 'max:255'],
            'maps_name' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:120'],
            'sub_category' => ['nullable', 'string', 'max:120'],
            'gallery_group' => ['required', 'string', 'max:100'],
            'color_name' => ['nullable', 'string', 'max:100'],
            'color_hex' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'year_period' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:1000'],
            'desa_kelurahan' => ['nullable', 'string', 'max:150'],
            'kecamatan' => ['nullable', 'string', 'max:150'],
            'regency_city' => ['nullable', 'string', 'max:150'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'uniqueness' => ['nullable', 'string', 'max:5000'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url:http,https', 'max:1000'],
            'gmaps_url' => ['nullable', 'url:http,https', 'max:1000'],
            'thumbnail_path' => ['nullable', 'string', 'max:500'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'photo_files' => ['nullable', 'array'],
            'photo_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photos' => ['nullable', 'array'],
            'remove_photos.*' => ['string'],
            'is_featured_gallery' => ['nullable', 'boolean'],
        ]);
    }

    protected function populateAndSaveSite(CulturalSite $site, array $validated, Request $request): void
    {
        $catConfig = self::CATEGORY_MAP[$validated['category']] ?? [
            'color_name' => 'Merah (Tempat Ibadah)',
            'color_hex' => '#B91C1C',
            'gallery_group' => 'Situs',
        ];

        $uploadDir = public_path('images/sites/uploads');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        $existingPhotos = is_array($site->photos) ? $site->photos : [];
        $removePhotos = $request->input('remove_photos', []);
        if (!empty($removePhotos) && is_array($removePhotos)) {
            $existingPhotos = array_values(array_diff($existingPhotos, $removePhotos));
        }

        $thumbnail = $site->thumbnail ?: '/images/sites/tjong_a_fie_mansion.jpg';
        if (!empty($validated['thumbnail_path'])) {
            $cleanPath = trim($validated['thumbnail_path']);
            if (Str::startsWith($cleanPath, ['/images/', 'https://', 'http://'])) {
                $thumbnail = $cleanPath;
            }
        }

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = Str::slug($validated['site_code']).'-thumb-'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $this->mirrorUploadToDocumentRoot($filename);
            $thumbnail = '/images/sites/uploads/'.$filename;
            if (!in_array($thumbnail, $existingPhotos, true)) {
                array_unshift($existingPhotos, $thumbnail);
            }
        }

        if ($request->hasFile('photo_files')) {
            foreach ($request->file('photo_files') as $idx => $photoFile) {
                $filename = Str::slug($validated['site_code']).'-photo-'.time().'-'.($idx + 1).'.'.$photoFile->getClientOriginalExtension();
                $photoFile->move($uploadDir, $filename);
                $this->mirrorUploadToDocumentRoot($filename);
                $photoUrl = '/images/sites/uploads/'.$filename;
                $existingPhotos[] = $photoUrl;
            }
        }

        if (empty($existingPhotos)) {
            $existingPhotos = [$thumbnail];
        }

        $site->fill([
            'marker_number' => (int) $validated['marker_number'],
            'site_code' => strtoupper(trim($validated['site_code'])),
            'name' => $validated['name'],
            'maps_name' => ($validated['maps_name'] ?? null) ?: $validated['name'],
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'] ?? null,
            'gallery_group' => ($validated['gallery_group'] ?? null) ?: $catConfig['gallery_group'],
            'color_name' => ($validated['color_name'] ?? null) ?: $catConfig['color_name'],
            'color_hex' => ($validated['color_hex'] ?? null) ?: $catConfig['color_hex'],
            'year_period' => ($validated['year_period'] ?? null) ?: 'Tidak tercatat',
            'address' => $validated['address'] ?? null,
            'desa_kelurahan' => $validated['desa_kelurahan'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'regency_city' => ($validated['regency_city'] ?? null) ?: 'Kota Medan',
            'latitude' => isset($validated['latitude']) ? (float) $validated['latitude'] : null,
            'longitude' => isset($validated['longitude']) ? (float) $validated['longitude'] : null,
            'uniqueness' => $validated['uniqueness'] ?? null,
            'source_name' => $validated['source_name'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'gmaps_url' => $validated['gmaps_url'] ?? null,
            'thumbnail' => $thumbnail,
            'photos' => array_values(array_unique($existingPhotos)),
            'is_featured_gallery' => $request->boolean('is_featured_gallery', true),
            'sheet_origin' => $site->sheet_origin ?: 'Admin Panel',
        ]);

        $site->save();
    }

    protected function validateVideoRequest(Request $request, ?int $ignoreId = null): array
    {
        $uniqueCodeRule = 'unique:documentary_videos,code'.($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:999'],
            'code' => ['required', 'string', 'max:24', 'regex:/^[A-Za-z0-9_-]+$/', $uniqueCodeRule],
            'title' => ['required', 'string', 'max:255'],
            'location_subtitle' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'youtube_input' => ['required', 'string', 'max:500'],
            'duration' => ['required', 'string', 'max:24'],
            'thumbnail_path' => ['nullable', 'string', 'max:500'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    protected function populateAndSaveVideo(DocumentaryVideo $video, array $validated, Request $request): void
    {
        $ytId = DocumentaryVideo::extractYoutubeId($validated['youtube_input']);
        $youtubeUrl = "https://www.youtube.com/watch?v={$ytId}";
        $embedUrl = "https://www.youtube-nocookie.com/embed/{$ytId}?rel=0&modestbranding=1";

        $thumbnail = $video->thumbnail ?: '/images/sites/tjong_a_fie_mansion.jpg';
        if (!empty($validated['thumbnail_path'])) {
            $cleanPath = trim($validated['thumbnail_path']);
            if (Str::startsWith($cleanPath, ['/images/', 'https://', 'http://'])) {
                $thumbnail = $cleanPath;
            }
        }

        if ($request->hasFile('thumbnail_file')) {
            $uploadDir = public_path('images/sites/uploads');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $file = $request->file('thumbnail_file');
            $filename = Str::slug($validated['code']).'-video-'.time().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $this->mirrorUploadToDocumentRoot($filename);
            $thumbnail = '/images/sites/uploads/'.$filename;
        }

        $nextOrder = $validated['sort_order'] ?? ($video->sort_order ?: (((int) DocumentaryVideo::max('sort_order')) + 1));

        $video->fill([
            'sort_order' => (int) $nextOrder,
            'code' => strtoupper(trim($validated['code'])),
            'title' => $validated['title'],
            'location_subtitle' => $validated['location_subtitle'] ?? '',
            'category' => $validated['category'],
            'youtube_id' => $ytId,
            'youtube_url' => $youtubeUrl,
            'embed_url' => $embedUrl,
            'thumbnail' => $thumbnail,
            'duration' => $validated['duration'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $video->save();
    }

    protected function mirrorUploadToDocumentRoot(string $filename): void
    {
        try {
            $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
            $src = public_path('images/sites/uploads/'.$filename);
            if ($docRoot && is_dir($docRoot) && File::exists($src)) {
                $dstDir = rtrim($docRoot, '/\\').'/images/sites/uploads';
                if (realpath($dstDir) !== realpath(dirname($src))) {
                    File::ensureDirectoryExists($dstDir);
                    @File::copy($src, $dstDir.'/'.$filename);
                }
            }
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    protected function syncJsonBackups(): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        try {
            $sites = CulturalSite::orderBy('marker_number')->get()->makeHidden(['id', 'created_at', 'updated_at'])->toArray();
            $sitesJson = json_encode($sites, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            File::put(storage_path('app/cultural_sites.json'), $sitesJson);
            if (File::isDirectory(database_path('data'))) {
                File::put(database_path('data/cultural_sites.json'), $sitesJson);
            }

            $videos = DocumentaryVideo::orderBy('sort_order')->get()->toArray();
            File::put(
                storage_path('app/documentary_videos.json'),
                json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );

            $team = TeamMember::orderBy('sort_order')->get()->toArray();
            File::put(
                storage_path('app/team_members.json'),
                json_encode($team, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        } catch (\Throwable $e) {
            // Ignore write errors in read-only environments
        }
    }
}
