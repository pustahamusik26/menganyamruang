<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEdit ? 'Edit Postingan Situs: '.$site->name : 'Tambah Postingan Situs Baru' }} — Panel Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>{!! file_get_contents(base_path('public/css/archive.css')) !!}</style>
    <style>{!! file_get_contents(base_path('public/css/admin.css')) !!}</style>
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <div class="chinese-seal-box">織</div>
                <div>
                    <span class="sidebar-brand-title">Arsip Anyaman</span>
                    <span class="sidebar-brand-sub">PANEL ADMIN // MEDAN</span>
                </div>
            </a>

            <div class="sidebar-section-label">NAVIGASI FORMULIR</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard', ['tab' => 'situs']) }}" class="sidebar-link active">
                    <span>&larr; Kembali ke Daftar Situs</span>
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'video']) }}" class="sidebar-link">
                    <span>Video Dokumenter</span>
                </a>
                <a href="{{ route('admin.dashboard', ['tab' => 'tim']) }}" class="sidebar-link">
                    <span>Profil &amp; Tim Kerja</span>
                </a>
                <a href="{{ route('archive.home') }}" target="_blank" class="sidebar-link">
                    <span>Lihat Website Publik &nearr;</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
                    <div class="sidebar-user-role">&#64;{{ auth()->user()->username ?? 'admin' }}</div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">KELUAR DARI ADMIN</button>
                </form>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-title-group">
                    <div class="topbar-breadcrumb">
                        FORMULIR POSTINGAN SITUS &amp; OBJEK BUDAYA // {{ $isEdit ? $site->site_code : 'ENTRI BARU' }}
                    </div>
                    <h1>{{ $isEdit ? 'Edit Postingan Situs: '.$site->name : 'Tambah Postingan Situs & Objek Budaya Baru' }}</h1>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('admin.dashboard', ['tab' => 'situs']) }}" class="btn btn-outline">
                        &larr; BATAL &amp; KEMBALI
                    </a>
                </div>
            </header>

            <main class="admin-content">
                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Mohon periksa kembali isian formulir:</strong>
                        <ul style="margin-top: 6px; padding-left: 18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ $isEdit ? route('admin.sites.update', $site) : route('admin.sites.store') }}"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div style="display: grid; grid-template-columns: 1.55fr 1fr; gap: 24px; align-items: start;">
                        <!-- KOLOM KIRI: DATA UTAMA, LOKASI & NARASI -->
                        <div>
                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div>
                                        <div class="panel-title">1. Identitas &amp; Klasifikasi Situs Budaya</div>
                                        <div class="panel-subtitle">Informasi utama penanda situs pada peta etnografis dan katalog arsip.</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Urut Marker Peta</label>
                                            <input type="number" name="marker_number" class="form-control" value="{{ old('marker_number', $site->marker_number) }}" min="1" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kode Situs (Unik)</label>
                                            <input type="text" name="site_code" class="form-control" value="{{ old('site_code', $site->site_code) }}" placeholder="ARC-072" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tahun / Periode Sejarah</label>
                                            <input type="text" name="year_period" class="form-control" value="{{ old('year_period', $site->year_period) }}" placeholder="Contoh: Abad ke-19 / 1890">
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Nama Situs / Objek Budaya</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $site->name) }}" placeholder="Contoh: Rumah Tjong A Fie" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Nama pada Google Maps (Opsional)</label>
                                            <input type="text" name="maps_name" class="form-control" value="{{ old('maps_name', $site->maps_name) }}" placeholder="Kosongkan jika sama dengan nama situs">
                                        </div>
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label class="form-label">Kategori Utama</label>
                                            <select name="category" id="categorySelect" class="form-control" required>
                                                @foreach($categoryMap as $catName => $cfg)
                                                    <option
                                                        value="{{ $catName }}"
                                                        data-color-name="{{ $cfg['color_name'] }}"
                                                        data-color-hex="{{ $cfg['color_hex'] }}"
                                                        data-gallery-group="{{ $cfg['gallery_group'] }}"
                                                        {{ old('category', $site->category) === $catName ? 'selected' : '' }}
                                                    >
                                                        {{ $catName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Sub-Kategori Spesifik</label>
                                            <input type="text" name="sub_category" class="form-control" value="{{ old('sub_category', $site->sub_category) }}" placeholder="Contoh: Vihara / Klenteng / Bakmi">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Grup Galeri Digital 5:4</label>
                                            <select name="gallery_group" id="galleryGroupSelect" class="form-control" required>
                                                @foreach($galleryGroups as $grp)
                                                    <option value="{{ $grp }}" {{ old('gallery_group', $site->gallery_group) === $grp ? 'selected' : '' }}>
                                                        {{ $grp }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Label Warna Kategori</label>
                                            <input type="text" name="color_name" id="colorNameInput" class="form-control" value="{{ old('color_name', $site->color_name) }}">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Kode Warna Pin Peta (HEX)</label>
                                            <div style="display: flex; gap: 8px; align-items: center;">
                                                <input type="color" id="colorPicker" value="{{ old('color_hex', $site->color_hex ?: '#B91C1C') }}" style="width: 44px; height: 38px; border: 1px solid var(--wood-line-strong); background: #fff; cursor: pointer;">
                                                <input type="text" name="color_hex" id="colorHexInput" class="form-control" value="{{ old('color_hex', $site->color_hex ?: '#B91C1C') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div>
                                        <div class="panel-title">2. Lokasi Administratif &amp; Titik Koordinat Peta Etnografis</div>
                                        <div class="panel-subtitle">Klik pada peta di bawah atau geser pin untuk menentukan titik koordinat Latitude &amp; Longitude secara presisi.</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $site->address) }}" placeholder="Jl. Jend. Ahmad Yani No. 105, Kesawan...">
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-group">
                                            <label class="form-label">Desa / Kelurahan</label>
                                            <input type="text" name="desa_kelurahan" class="form-control" value="{{ old('desa_kelurahan', $site->desa_kelurahan) }}" placeholder="Contoh: Kesawan">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kecamatan</label>
                                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $site->kecamatan) }}" placeholder="Contoh: Medan Barat">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Kota / Kabupaten</label>
                                            <input type="text" name="regency_city" class="form-control" value="{{ old('regency_city', $site->regency_city ?: 'Kota Medan') }}">
                                        </div>
                                    </div>

                                    <div class="form-grid-2">
                                        <div class="form-group">
                                            <label class="form-label">Latitude (Lintang Utara)</label>
                                            <input type="number" step="any" name="latitude" id="latInput" class="form-control" value="{{ old('latitude', $site->latitude) }}" placeholder="3.5852400">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Longitude (Bujur Timur)</label>
                                            <input type="number" step="any" name="longitude" id="lngInput" class="form-control" value="{{ old('longitude', $site->longitude) }}" placeholder="98.6755980">
                                        </div>
                                    </div>

                                    <!-- Interactive Map Picker -->
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Pilih Titik Lokasi pada Peta (Klik Peta atau Geser Pin)</label>
                                        <div id="adminMapPicker" style="width: 100%; height: 290px; border: 1px solid var(--wood-line-strong);"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div>
                                        <div class="panel-title">3. Deskripsi Keunikan Budaya &amp; Referensi Sumber</div>
                                        <div class="panel-subtitle">Narasi etnografis dan tautan rujukan yang tampil pada panel detail situs.</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="form-label">Narasi Keunikan / Nilai Sejarah &amp; Budaya</label>
                                        <textarea name="uniqueness" class="form-control" rows="4" placeholder="Tuliskan sejarah singkat, fungsi ruang budaya, arsitektur, atau keunikan kuliner/tradisi situs ini...">{{ old('uniqueness', $site->uniqueness) }}</textarea>
                                    </div>

                                    <div class="form-grid-3">
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Nama Sumber / Referensi</label>
                                            <input type="text" name="source_name" class="form-control" value="{{ old('source_name', $site->source_name) }}" placeholder="Contoh: Tim Kajian Lapangan 2025">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Tautan Artikel / Sumber (URL)</label>
                                            <input type="url" name="source_url" class="form-control" value="{{ old('source_url', $site->source_url) }}" placeholder="https://...">
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Tautan Google Maps (URL)</label>
                                            <input type="url" name="gmaps_url" class="form-control" value="{{ old('gmaps_url', $site->gmaps_url) }}" placeholder="https://maps.google.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: MEDIA FOTO & PUBLIKASI -->
                        <div>
                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div>
                                        <div class="panel-title">4. Foto Thumbnail Utama</div>
                                        <div class="panel-subtitle">Gambar sampul utama pada kartu peta dan galeri.</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    @if($site->thumbnail)
                                        <div style="margin-bottom: 14px;">
                                            <img
                                                src="{{ asset(ltrim($site->thumbnail, '/')) }}"
                                                alt="Thumbnail saat ini"
                                                style="width: 100%; height: 190px; object-fit: cover; border: 1px solid var(--wood-line-strong);"
                                            >
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="form-label">Upload Foto Thumbnail Baru (JPG/PNG/WEBP, Maks 5MB)</label>
                                        <input type="file" name="thumbnail_file" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                        <span class="form-hint">Jika diunggah, foto ini otomatis menjadi sampul utama.</span>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label">Atau Gunakan Path Gambar yang Ada</label>
                                        <input type="text" name="thumbnail_path" class="form-control" value="{{ old('thumbnail_path', $site->thumbnail ?: '/images/sites/tjong_a_fie_mansion.jpg') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div>
                                        <div class="panel-title">5. Koleksi Multi-Foto Galeri Situs</div>
                                        <div class="panel-subtitle">Unggah dokumentasi foto tambahan untuk ditampilkan pada modal detail &amp; filmstrip.</div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="form-label">Tambah Foto Dokumentasi Baru (Bisa Pilih Banyak File)</label>
                                        <input type="file" name="photo_files[]" class="form-control" accept=".jpg,.jpeg,.png,.webp" multiple>
                                    </div>

                                    @if($isEdit && is_array($site->photos) && count($site->photos) > 0)
                                        <label class="form-label" style="margin-top: 12px;">Foto Tersimpan ({{ count($site->photos) }} Foto) — Centang untuk Menghapus</label>
                                        <div class="photo-grid">
                                            @foreach($site->photos as $photoUrl)
                                                <div class="photo-item">
                                                    <img src="{{ asset(ltrim($photoUrl, '/')) }}" alt="Foto Situs">
                                                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--chinese-red); font-weight: 600;">
                                                        <input type="checkbox" name="remove_photos[]" value="{{ $photoUrl }}">
                                                        <span>Hapus Foto</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="admin-panel">
                                <div class="panel-header">
                                    <div class="panel-title">6. Status Publikasi &amp; Simpan</div>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600;">
                                            <input type="hidden" name="is_featured_gallery" value="0">
                                            <input
                                                type="checkbox"
                                                name="is_featured_gallery"
                                                value="1"
                                                {{ old('is_featured_gallery', $site->is_featured_gallery) ? 'checked' : '' }}
                                                style="width: 18px; height: 18px;"
                                            >
                                            <span>Tampilkan pada Galeri Digital 5:4 Utama</span>
                                        </label>
                                    </div>

                                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 18px;">
                                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 13px;">
                                            {{ $isEdit ? 'SIMPAN PERUBAHAN POSTINGAN' : 'PUBLIKASIKAN POSTINGAN SITUS BARU' }}
                                        </button>
                                        <a href="{{ route('admin.dashboard', ['tab' => 'situs']) }}" class="btn btn-outline" style="width: 100%;">
                                            BATAL &amp; KEMBALI KE DAFTAR
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>{!! file_get_contents(base_path('public/js/leaflet.js')) !!}</script>
    <script>
        (function () {
            var catSelect = document.getElementById('categorySelect');
            var colorNameInput = document.getElementById('colorNameInput');
            var colorHexInput = document.getElementById('colorHexInput');
            var colorPicker = document.getElementById('colorPicker');
            var galleryGroupSelect = document.getElementById('galleryGroupSelect');

            if (catSelect) {
                catSelect.addEventListener('change', function () {
                    var opt = catSelect.options[catSelect.selectedIndex];
                    if (!opt) return;
                    var cName = opt.getAttribute('data-color-name');
                    var cHex = opt.getAttribute('data-color-hex');
                    var gGrp = opt.getAttribute('data-gallery-group');
                    if (cName && colorNameInput) colorNameInput.value = cName;
                    if (cHex && colorHexInput) colorHexInput.value = cHex;
                    if (cHex && colorPicker) colorPicker.value = cHex;
                    if (gGrp && galleryGroupSelect) galleryGroupSelect.value = gGrp;
                });
            }

            if (colorPicker && colorHexInput) {
                colorPicker.addEventListener('input', function () {
                    colorHexInput.value = colorPicker.value.toUpperCase();
                });
                colorHexInput.addEventListener('input', function () {
                    if (/^#[0-9A-Fa-f]{6}$/.test(colorHexInput.value)) {
                        colorPicker.value = colorHexInput.value;
                    }
                });
            }

            // Leaflet Map Coordinate Picker
            var latInput = document.getElementById('latInput');
            var lngInput = document.getElementById('lngInput');
            var mapEl = document.getElementById('adminMapPicker');

            if (mapEl && typeof L !== 'undefined') {
                var initLat = parseFloat(latInput.value) || 3.58524;
                var initLng = parseFloat(lngInput.value) || 98.675598;

                var map = L.map(mapEl).setView([initLat, initLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);

                var marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

                marker.on('dragend', function (e) {
                    var pos = e.target.getLatLng();
                    latInput.value = pos.lat.toFixed(7);
                    lngInput.value = pos.lng.toFixed(7);
                });

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    latInput.value = e.latlng.lat.toFixed(7);
                    lngInput.value = e.latlng.lng.toFixed(7);
                });

                function syncFromInputs() {
                    var lt = parseFloat(latInput.value);
                    var ln = parseFloat(lngInput.value);
                    if (!isNaN(lt) && !isNaN(ln)) {
                        marker.setLatLng([lt, ln]);
                        map.panTo([lt, ln]);
                    }
                }

                latInput.addEventListener('change', syncFromInputs);
                lngInput.addEventListener('change', syncFromInputs);
            }
        })();
    </script>
</body>
</html>
