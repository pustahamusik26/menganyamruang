<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — Arsip Anyaman: Kebudayaan Tionghoa Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/archive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <!-- ===================================================================
             SIDEBAR NAVIGASI ADMIN
             =================================================================== -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <div class="chinese-seal-box">織</div>
                <div>
                    <span class="sidebar-brand-title">Arsip Anyaman</span>
                    <span class="sidebar-brand-sub">PANEL ADMIN // MEDAN</span>
                </div>
            </a>

            <div class="sidebar-section-label">KELOLA POSTINGAN WEBSITE</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard', ['tab' => 'situs']) }}" class="sidebar-link {{ $activeTab === 'situs' ? 'active' : '' }}">
                    <span>1. Situs &amp; Galeri Budaya</span>
                    <span class="sidebar-badge">{{ $allSitesCount }}</span>
                </a>
                <a href="{{ route('admin.sites.create') }}" class="sidebar-link" style="padding-left: 26px; font-size: 12.5px; color: var(--chinese-gold);">
                    <span>+ Tambah Situs Baru</span>
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'video']) }}" class="sidebar-link {{ $activeTab === 'video' ? 'active' : '' }}">
                    <span>2. Video Dokumenter</span>
                    <span class="sidebar-badge">{{ $videos->count() }}</span>
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'tim']) }}" class="sidebar-link {{ $activeTab === 'tim' ? 'active' : '' }}">
                    <span>3. Profil &amp; Tim Kerja</span>
                    <span class="sidebar-badge">{{ $teamMembers->count() + ($proposer ? 1 : 0) }}</span>
                </a>

                <div class="sidebar-section-label" style="padding-left: 8px; margin-top: 12px;">SISTEM &amp; KEAMANAN</div>
                <a href="{{ route('admin.dashboard', ['tab' => 'akun']) }}" class="sidebar-link {{ $activeTab === 'akun' ? 'active' : '' }}">
                    <span>4. Pengaturan Akun Admin</span>
                </a>
                <a href="{{ route('archive.home') }}" target="_blank" class="sidebar-link">
                    <span>Lihat Website Publik &nearr;</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                    <div class="sidebar-user-role">&#64;{{ auth()->user()->username ?? 'admin' }} &bull; {{ auth()->user()->role ?? 'admin' }}</div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                        KELUAR DARI ADMIN
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===================================================================
             AREA KONTEN UTAMA
             =================================================================== -->
        <div class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-title-group">
                    <div class="topbar-breadcrumb">
                        MENGANYAMRUANG.ID // MANAJEMEN KONTEN &amp; POSTINGAN ARSIP
                    </div>
                    <h1>
                        @if($activeTab === 'situs')
                            Kelola Postingan Situs Budaya, Peta Etnografis &amp; Galeri Digital
                        @elseif($activeTab === 'video')
                            Kelola Postingan Video Dokumenter YouTube
                        @elseif($activeTab === 'tim')
                            Kelola Profil Pengusul &amp; Anggota Tim Kerja
                        @else
                            Pengaturan Akun Administrator
                        @endif
                    </h1>
                </div>

                <div class="topbar-actions">
                    <a href="{{ route('archive.home') }}" target="_blank" class="btn btn-outline">
                        LIHAT WEBSITE &nearr;
                    </a>
                    <a href="{{ route('admin.sites.create') }}" class="btn btn-primary">
                        + TAMBAH SITUS BUDAYA
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Terdapat kesalahan validasi input:</strong>
                        <ul style="margin-top: 6px; padding-left: 18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- RINGKASAN STATISTIK KONTEN -->
                <div class="stats-grid">
                    <div class="stat-card accent-red">
                        <div class="stat-label">TOTAL POSTINGAN SITUS &amp; PETA</div>
                        <div class="stat-value">{{ $allSitesCount }}</div>
                        <div class="stat-meta">Terhubung langsung ke Peta Etnografis &amp; Katalog</div>
                    </div>
                    <div class="stat-card accent-gold">
                        <div class="stat-label">AKTIF DI GALERI DIGITAL 5:4</div>
                        <div class="stat-value">{{ $featuredGalleryCount }}</div>
                        <div class="stat-meta">Dari 5 grup galeri (termasuk Museum China)</div>
                    </div>
                    <div class="stat-card accent-jade">
                        <div class="stat-label">VIDEO DOKUMENTER YOUTUBE</div>
                        <div class="stat-value">{{ $videos->count() }}</div>
                        <div class="stat-meta">Episode aktif di pemutar video utama</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">PROFIL &amp; TIM KERJA</div>
                        <div class="stat-value">{{ $teamMembers->count() + ($proposer ? 1 : 0) }}</div>
                        <div class="stat-meta">1 Pengusul Kajian &amp; {{ $teamMembers->count() }} Anggota Tim</div>
                    </div>
                </div>

                <!-- ===========================================================
                     TAB 1: KELOLA POSTINGAN SITUS & GALERI BUDAYA
                     =========================================================== -->
                @if($activeTab === 'situs')
                    <div class="admin-panel">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Daftar Seluruh Postingan Situs &amp; Objek Budaya ({{ $sites->count() }} Ditampilkan)</div>
                                <div class="panel-subtitle">
                                    Setiap postingan di bawah ini otomatis tampil pada Peta Etnografis, Galeri Digital 5:4, Sorotan Museum China, dan Tabel Katalog Situs.
                                </div>
                            </div>
                            <a href="{{ route('admin.sites.create') }}" class="btn btn-primary">
                                + TAMBAH POSTINGAN SITUS BARU
                            </a>
                        </div>

                        <!-- Filter & Pencarian -->
                        <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
                            <input type="hidden" name="tab" value="situs">
                            <div>
                                <label class="form-label">Pencarian Cepat</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ request('q') }}"
                                    class="form-control"
                                    placeholder="Cari nama situs, kode ARC-XXX, alamat, kecamatan..."
                                >
                            </div>
                            <div>
                                <label class="form-label">Filter Kategori</label>
                                <select name="category" class="form-control">
                                    <option value="all">Semua Kategori ({{ $allSitesCount }})</option>
                                    @foreach($categoryMap as $catName => $cfg)
                                        <option value="{{ $catName }}" {{ request('category') === $catName ? 'selected' : '' }}>
                                            {{ $catName }} ({{ $categoryCounts[$catName] ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Filter Grup Galeri</label>
                                <select name="gallery_group" class="form-control">
                                    <option value="all">Semua Grup Galeri</option>
                                    @foreach($galleryGroups as $grp)
                                        <option value="{{ $grp }}" {{ request('gallery_group') === $grp ? 'selected' : '' }}>
                                            {{ $grp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button type="submit" class="btn btn-wood">FILTER</button>
                                <a href="{{ route('admin.dashboard', ['tab' => 'situs']) }}" class="btn btn-outline">RESET</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th style="width: 84px;">Foto</th>
                                        <th style="width: 105px;">Kode</th>
                                        <th>Nama Situs &amp; Lokasi</th>
                                        <th>Kategori &amp; Sub</th>
                                        <th>Grup Galeri</th>
                                        <th>Koordinat Peta</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sites as $site)
                                        <tr>
                                            <td style="font-family: var(--font-mono); font-weight: 600;">
                                                #{{ $site->marker_number }}
                                            </td>
                                            <td>
                                                <img
                                                    src="{{ asset(ltrim($site->thumbnail ?: '/images/sites/tjong_a_fie_mansion.jpg', '/')) }}"
                                                    alt="{{ $site->name }}"
                                                    class="table-thumb"
                                                >
                                            </td>
                                            <td style="font-family: var(--font-mono); font-size: 12px; color: var(--wood-primary); font-weight: 600;">
                                                {{ $site->site_code }}
                                                <div style="font-size: 10px; color: var(--wood-muted); font-weight: 400;">
                                                    {{ is_array($site->photos) ? count($site->photos) : 1 }} foto
                                                </div>
                                            </td>
                                            <td>
                                                <div style="font-weight: 600; color: var(--wood-deep); font-size: 14px;">
                                                    {{ $site->name }}
                                                </div>
                                                <div style="font-size: 12px; color: var(--wood-medium); margin-top: 2px;">
                                                    {{ $site->desa_kelurahan ? 'Kel. '.$site->desa_kelurahan.', ' : '' }}
                                                    {{ $site->kecamatan ? 'Kec. '.$site->kecamatan : $site->address }}
                                                    ({{ $site->regency_city }})
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge">
                                                    <span class="badge-dot" style="background: {{ $site->color_hex ?: '#B91C1C' }};"></span>
                                                    {{ $site->category }}
                                                </span>
                                                @if($site->sub_category)
                                                    <div style="font-size: 11.5px; color: var(--wood-muted); margin-top: 4px;">
                                                        Sub: {{ $site->sub_category }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge" style="background: #FFFDF9;">
                                                    {{ $site->gallery_group ?: 'Situs' }}
                                                </span>
                                                @if($site->is_featured_gallery)
                                                    <div style="font-size: 10.5px; color: var(--chinese-jade); font-family: var(--font-mono); margin-top: 3px;">
                                                        &bull; Tampil di Galeri
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="font-family: var(--font-mono); font-size: 11.5px; color: var(--wood-medium);">
                                                @if($site->latitude && $site->longitude)
                                                    {{ number_format($site->latitude, 4) }}° N<br>
                                                    {{ number_format($site->longitude, 4) }}° E
                                                @else
                                                    <span style="color: var(--chinese-red);">Belum diatur</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-group">
                                                    <a href="{{ route('admin.sites.edit', $site) }}" class="btn btn-wood btn-sm">
                                                        EDIT
                                                    </a>
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.sites.destroy', $site) }}"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan situs {{ addslashes($site->name) }}?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">HAPUS</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 32px; color: var(--wood-muted);">
                                                Tidak ada postingan situs yang cocok dengan filter pencarian.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- ===========================================================
                     TAB 2: KELOLA POSTINGAN VIDEO DOKUMENTER YOUTUBE
                     =========================================================== -->
                @if($activeTab === 'video')
                    <div class="admin-panel">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Tambah Postingan Video Dokumenter Baru</div>
                                <div class="panel-subtitle">
                                    Masukkan tautan YouTube (contoh: <code>https://www.youtube.com/watch?v=9No-FiEInLA</code>) dan sistem akan otomatis membuat player embed aman.
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label">Kode Video (Unik)</label>
                                        <input type="text" name="code" class="form-control" value="{{ old('code', sprintf('VID-%02d', $videos->count() + 1)) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Urutan Tampil</label>
                                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $videos->count() + 1) }}" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Durasi (MM:SS)</label>
                                        <input type="text" name="duration" class="form-control" value="{{ old('duration', '05:15') }}" placeholder="05:15" required>
                                    </div>
                                </div>

                                <div class="form-grid-2">
                                    <div class="form-group">
                                        <label class="form-label">Judul Video Dokumenter</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Jejak Arsitektur & Harmoni Rumah Tjong A Fie" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Subjudul Lokasi / Kawasan</label>
                                        <input type="text" name="location_subtitle" class="form-control" value="{{ old('location_subtitle') }}" placeholder="Contoh: Kawasan Kesawan, Kec. Medan Barat — Kota Medan">
                                    </div>
                                </div>

                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label">Kategori Video</label>
                                        <input type="text" name="category" class="form-control" value="{{ old('category', 'Situs & Cagar Budaya') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">URL YouTube atau ID Video (11 Karakter)</label>
                                        <input type="text" name="youtube_input" class="form-control" value="{{ old('youtube_input') }}" placeholder="https://www.youtube.com/watch?v=9No-FiEInLA" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Upload Thumbnail Baru (Opsional)</label>
                                        <input type="file" name="thumbnail_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                        <input type="hidden" name="is_active" value="1">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    + SIMPAN POSTINGAN VIDEO BARU
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="admin-panel">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Daftar Postingan Video Dokumenter ({{ $videos->count() }} Episode)</div>
                                <div class="panel-subtitle">Klik tombol EDIT pada baris video untuk mengubah judul, link YouTube, durasi, atau mengganti thumbnail.</div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">Urut</th>
                                        <th style="width: 90px;">Thumb</th>
                                        <th style="width: 95px;">Kode</th>
                                        <th>Judul &amp; Lokasi</th>
                                        <th>Kategori</th>
                                        <th>YouTube ID / Durasi</th>
                                        <th style="width: 170px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $video)
                                        <tr>
                                            <td style="font-family: var(--font-mono); font-weight: 600;">#{{ $video->sort_order }}</td>
                                            <td>
                                                <img src="{{ asset(ltrim($video->thumbnail ?: '/images/sites/tjong_a_fie_mansion.jpg', '/')) }}" alt="{{ $video->title }}" class="table-thumb">
                                            </td>
                                            <td style="font-family: var(--font-mono); font-weight: 600; color: var(--chinese-red);">{{ $video->code }}</td>
                                            <td>
                                                <div style="font-weight: 600; font-size: 14px;">{{ $video->title }}</div>
                                                <div style="font-size: 12px; color: var(--wood-medium);">{{ $video->location_subtitle }}</div>
                                            </td>
                                            <td><span class="badge">{{ $video->category }}</span></td>
                                            <td style="font-family: var(--font-mono); font-size: 12px;">
                                                <a href="{{ $video->youtube_url }}" target="_blank" style="color: var(--wood-primary);">{{ $video->youtube_id }} &nearr;</a>
                                                <div style="color: var(--wood-muted);">{{ $video->duration }}</div>
                                            </td>
                                            <td>
                                                <div class="action-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn-wood btn-sm"
                                                        onclick="var el=document.getElementById('edit-video-{{ $video->id }}'); el.style.display = (el.style.display === 'none' ? 'table-row' : 'none');"
                                                    >
                                                        EDIT
                                                    </button>
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.videos.destroy', $video) }}"
                                                        onsubmit="return confirm('Hapus postingan video {{ addslashes($video->title) }}?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">HAPUS</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="edit-video-{{ $video->id }}" style="display: none; background: #F8F1E5;">
                                            <td colspan="7" style="padding: 18px 22px;">
                                                <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-grid-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode Video</label>
                                                            <input type="text" name="code" class="form-control" value="{{ $video->code }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Urutan</label>
                                                            <input type="number" name="sort_order" class="form-control" value="{{ $video->sort_order }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Durasi</label>
                                                            <input type="text" name="duration" class="form-control" value="{{ $video->duration }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-grid-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Judul Video</label>
                                                            <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Subjudul Lokasi</label>
                                                            <input type="text" name="location_subtitle" class="form-control" value="{{ $video->location_subtitle }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-grid-3">
                                                        <div class="form-group">
                                                            <label class="form-label">Kategori</label>
                                                            <input type="text" name="category" class="form-control" value="{{ $video->category }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">URL / ID YouTube</label>
                                                            <input type="text" name="youtube_input" class="form-control" value="{{ $video->youtube_url }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Ganti Thumbnail (Opsional)</label>
                                                            <input type="file" name="thumbnail_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                            <input type="hidden" name="thumbnail_path" value="{{ $video->thumbnail }}">
                                                            <input type="hidden" name="is_active" value="1">
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; gap: 8px;">
                                                        <button type="submit" class="btn btn-primary btn-sm">SIMPAN PERUBAHAN</button>
                                                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('edit-video-{{ $video->id }}').style.display='none';">BATAL</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- ===========================================================
                     TAB 3: KELOLA PROFIL PENGUSUL & TIM KERJA
                     =========================================================== -->
                @if($activeTab === 'tim')
                    <div class="admin-panel">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Profil Pengusul Program &amp; Ketua Kajian</div>
                                <div class="panel-subtitle">Ditampilkan pada kartu utama seksi Profil Tim Kerja di halaman depan website.</div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('admin.team.proposer.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="form-grid-2">
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap Pengusul</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $proposer->name ?? 'Rio Fernandez Tamba') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Jabatan / Peran Kajian</label>
                                        <input type="text" name="role" class="form-control" value="{{ old('role', $proposer->role ?? 'Pengusul Program & Ketua Kajian') }}" required>
                                    </div>
                                </div>
                                <div class="form-grid-3">
                                    <div class="form-group">
                                        <label class="form-label">Institusi</label>
                                        <input type="text" name="institution" class="form-control" value="{{ old('institution', $proposer->institution ?? 'Politeknik Wilmar Bisnis Indonesia') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">URL Website Resmi</label>
                                        <input type="url" name="website" class="form-control" value="{{ old('website', $proposer->website ?? 'https://riotamba.com') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Label Tautan Website</label>
                                        <input type="text" name="website_label" class="form-control" value="{{ old('website_label', $proposer->website_label ?? 'riotamba.com') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Deskripsi Biografi &amp; Ringkasan Pengusul</label>
                                    <textarea name="bio" class="form-control" rows="3" required>{{ old('bio', $proposer->bio ?? '') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">SIMPAN PROFIL PENGUSUL</button>
                            </form>
                        </div>
                    </div>

                    <div class="admin-panel">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Daftar Anggota Tim Kerja ({{ $teamMembers->count() }} Anggota)</div>
                                <div class="panel-subtitle">Tambah, ubah, atau hapus anggota tim peneliti &amp; pengembang sistem arsip digital.</div>
                            </div>
                        </div>
                        <div class="panel-body" style="border-bottom: 1px solid var(--wood-line); background: #F8F2E7;">
                            <form method="POST" action="{{ route('admin.team.members.store') }}">
                                @csrf
                                <div class="form-grid-3">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">NomorLabel (Contoh: 10)</label>
                                        <input type="text" name="no" class="form-control" value="{{ sprintf('%02d', $teamMembers->count() + 1) }}" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Nama Lengkap Anggota</label>
                                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap anggota tim" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Institusi / Afiliasi</label>
                                        <input type="text" name="institution" class="form-control" placeholder="Contoh: Politeknik Wilmar Bisnis Indonesia" required>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; margin-top: 12px; align-items: end;">
                                    <div>
                                        <label class="form-label">Peran / Tugas dalam Kajian</label>
                                        <input type="text" name="role" class="form-control" placeholder="Contoh: Peneliti & Dokumentator Lapangan" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">+ TAMBAH ANGGOTA TIM</button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70px;">No</th>
                                        <th>Nama Anggota Tim</th>
                                        <th>Institusi / Afiliasi</th>
                                        <th>Peran dalam Kajian</th>
                                        <th style="width: 170px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamMembers as $member)
                                        <tr>
                                            <td style="font-family: var(--font-mono); font-weight: 600;">{{ $member->no }}</td>
                                            <td style="font-weight: 600;">{{ $member->name }}</td>
                                            <td>{{ $member->institution }}</td>
                                            <td>{{ $member->role }}</td>
                                            <td>
                                                <div class="action-group">
                                                    <button
                                                        type="button"
                                                        class="btn btn-wood btn-sm"
                                                        onclick="var el=document.getElementById('edit-member-{{ $member->id }}'); el.style.display = (el.style.display === 'none' ? 'table-row' : 'none');"
                                                    >
                                                        EDIT
                                                    </button>
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.team.members.destroy', $member) }}"
                                                        onsubmit="return confirm('Hapus anggota tim {{ addslashes($member->name) }}?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">HAPUS</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="edit-member-{{ $member->id }}" style="display: none; background: #F8F1E5;">
                                            <td colspan="5" style="padding: 14px 20px;">
                                                <form method="POST" action="{{ route('admin.team.members.update', $member) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-grid-3">
                                                        <div class="form-group">
                                                            <label class="form-label">No</label>
                                                            <input type="text" name="no" class="form-control" value="{{ $member->no }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Nama Anggota</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $member->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Institusi</label>
                                                            <input type="text" name="institution" class="form-control" value="{{ $member->institution }}" required>
                                                        </div>
                                                    </div>
                                                    <div style="display: flex; gap: 10px; align-items: end;">
                                                        <div style="flex: 1;">
                                                            <label class="form-label">Peran</label>
                                                            <input type="text" name="role" class="form-control" value="{{ $member->role }}" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary btn-sm">SIMPAN</button>
                                                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('edit-member-{{ $member->id }}').style.display='none';">BATAL</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- ===========================================================
                     TAB 4: PENGATURAN AKUN ADMINISTRATOR
                     =========================================================== -->
                @if($activeTab === 'akun')
                    <div class="admin-panel" style="max-width: 720px;">
                        <div class="panel-header">
                            <div>
                                <div class="panel-title">Kredensial &amp; Keamanan Akun Administrator</div>
                                <div class="panel-subtitle">Ubah nama tampilan, username, email, atau perbarui password login admin.</div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('admin.account.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="form-label">Nama Tampilan Administrator</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                <div class="form-grid-2">
                                    <div class="form-group">
                                        <label class="form-label">Username Login</label>
                                        <input type="text" name="username" class="form-control" value="{{ old('username', auth()->user()->username) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Email Administrator</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                </div>

                                <hr style="border: none; border-top: 1px solid var(--wood-line); margin: 20px 0;">
                                <div style="font-weight: 600; margin-bottom: 12px; color: var(--wood-deep);">
                                    Ubah Password (Kosongkan jika tidak ingin mengubah password)
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini">
                                </div>
                                <div class="form-grid-2">
                                    <div class="form-group">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    SIMPAN PENGATURAN AKUN
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
