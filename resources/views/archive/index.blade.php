<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Anyaman: Kebudayaan Tionghoa Medan — Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya</title>
    <meta name="description" content="Menganyam Ruang mendokumentasikan dan memetakan ekspresi kebudayaan Tionghoa di Kota Medan, mulai dari ritus, seni, tradisi lisan, hingga kuliner, beserta ruang tempat praktek itu hidup.">
    <link rel="icon" type="image/png" href="{{ asset('images/logos/icon-menganyam-ruang-512.png') }}">

    <!-- Google Fonts Fallback for Monospace, Chinese Serif & Intro Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&family=JetBrains+Mono:wght@400;500;600;700&family=Noto+Serif+SC:wght@500;700&family=Young+Serif&display=swap" rel="stylesheet">

    <!-- Main Archival Stylesheet (Inlined directly from laravel_app/public so public_html separation never serves stale CSS/Leaflet) -->
    <style>{!! file_get_contents(base_path('public/css/archive.css')) !!}</style>
</head>
<body>
    <!-- =======================================================================
         ANIMATED INTRO OVERLAY (Mengayam Ruang — Intro.html + Logo Dana Indonesiana)
         ======================================================================= -->
    <div class="intro-overlay" id="introOverlay" role="presentation">
        <button class="intro-skip-btn" id="introSkipBtn" type="button">Lewati &rarr;</button>

        <div class="intro-stage-wrap">
            <div class="intro-svg-stage" id="introSvgStage"></div>
            <div class="intro-brand-text">
                <div class="intro-brand-title">Menganyam Ruang</div>
                <div class="intro-brand-sub">Arsip Anyaman: Kebudayaan Tionghoa dalam Kota Multikultural Medan</div>
            </div>
        </div>

        <div class="intro-bottom-sponsors">
            <div class="intro-sponsor-caption">
                DUKUNGAN KEGIATAN OLEH KEMENTERIAN KEBUDAYAAN: DANA INDONESIANA 2025
            </div>
            <div class="intro-sponsor-logos">
                <img src="{{ asset('images/logos/kemenkebud-white.png') }}" alt="Kementerian Kebudayaan" />
                <img src="{{ asset('images/logos/dana-indonesiana-white.png') }}" alt="Dana Indonesiana" class="intro-dana-logo" />
                <img src="{{ asset('images/logos/lpdp-color-white.png') }}" alt="LPDP" />
            </div>
        </div>
    </div>

    <!-- Custom Cursor (seunghyuk.com interactive micro-interaction) -->
    <div id="cursorDot" class="custom-cursor-dot"></div>
    <div id="cursorRing" class="custom-cursor-ring"></div>

    <!-- =======================================================================
         TOP NAVIGATION BAR (Top-Left Navigation + Mobile Dropdown + 3-Jingle Loop)
         ======================================================================= -->
    <header class="site-header">
        <div class="header-left">
            <a href="#beranda" data-nav-page="beranda" class="brand-identity">
                <img src="{{ asset('images/logos/icon-menganyam-ruang-512.png') }}" alt="Logo Menganyam Ruang" class="brand-logo-icon" />
                <div class="brand-text-wrap">
                    <span class="brand-title">Arsip Anyaman // Medan</span>
                    <span class="brand-domain">menganyamruang.id</span>
                </div>
            </a>

            <!-- Desktop Navigation Pills -->
            <nav class="topleft-nav-Quick" aria-label="Navigasi Utama Sebelah Kiri Atas">
                <a href="#beranda" data-nav-page="beranda" class="nav-pill-link {{ $activeSection === 'beranda' ? 'active' : '' }}">
                    <span class="nav-num"></span>Homepage
                </a>
                <a href="#ringkasan-kajian" data-nav-page="ringkasan-kajian" class="nav-pill-link {{ $activeSection === 'ringkasan-kajian' ? 'active' : '' }}">
                    <span class="nav-num"></span>Ringkasan
                </a>
                <a href="#peta-etnografis" data-nav-page="peta-etnografis" class="nav-pill-link {{ $activeSection === 'peta-etnografis' ? 'active' : '' }}">
                    <span class="nav-num"></span>Peta Etnografis
                </a>
                <a href="#galeri-digital" data-nav-page="galeri-digital" class="nav-pill-link {{ $activeSection === 'galeri-digital' ? 'active' : '' }}">
                    <span class="nav-num"></span>Galeri Digital
                </a>
                <a href="#video-dokumenter" data-nav-page="video-dokumenter" class="nav-pill-link {{ $activeSection === 'video-dokumenter' ? 'active' : '' }}">
                    <span class="nav-num"></span>Video
                </a>
                <a href="#profil-tim" data-nav-page="profil-tim" class="nav-pill-link {{ $activeSection === 'profil-tim' ? 'active' : '' }}">
                    <span class="nav-num"></span>Profil Tim Kerja
                </a>
                <a href="#katalog-situs" data-nav-page="katalog-situs" class="nav-pill-link {{ $activeSection === 'katalog-situs' ? 'active' : '' }}">
                    <span class="nav-num"></span>Data Situs ({{ $sites->count() }})
                </a>
            </nav>
        </div>

        <!-- Right Header: Mobile Dropdown Navigation + 3-Jingle Loop Controller -->
        <div class="header-right">
            <div class="mobile-nav-dropdown-wrap">
                <select id="mobileNavSelect" class="mobile-nav-select" aria-label="Menu Navigasi Halaman">
                    <option value="beranda" {{ $activeSection === 'beranda' ? 'selected' : '' }}>Menu: Homepage</option>
                    <option value="ringkasan-kajian" {{ $activeSection === 'ringkasan-kajian' ? 'selected' : '' }}>Menu: Ringkasan</option>
                    <option value="peta-etnografis" {{ $activeSection === 'peta-etnografis' ? 'selected' : '' }}>Menu: Peta Etnografis</option>
                    <option value="galeri-digital" {{ $activeSection === 'galeri-digital' ? 'selected' : '' }}>Menu: Galeri Digital</option>
                    <option value="video-dokumenter" {{ $activeSection === 'video-dokumenter' ? 'selected' : '' }}>Menu: Video</option>
                    <option value="profil-tim" {{ $activeSection === 'profil-tim' ? 'selected' : '' }}>Menu: Profil Tim Kerja</option>
                    <option value="katalog-situs" {{ $activeSection === 'katalog-situs' ? 'selected' : '' }}>Menu: Data Situs ({{ $sites->count() }})</option>
                </select>
            </div>

            <div id="jingleController" class="jingle-controller" title="BackSound: 3 Jingle Loop">
                <button type="button" id="soundToggleBtn" class="sound-toggle-btn">
                    <span class="sound-bars">
                        <span></span><span></span><span></span><span></span>
                    </span>
                    <span id="soundStateLabel">SOUND OFF</span>
                </button>
                <div class="jingle-select-group" aria-label="Pilih 3 Jingle Loop">
                    @foreach($jingles as $jingle)
                        <button
                            type="button"
                            class="jingle-btn {{ $loop->first ? 'active' : '' }}"
                            data-jingle-id="{{ $jingle['id'] }}"
                            title="{{ $jingle['title'] }} ({{ $jingle['instrument'] }})"
                        >
                            J{{ $jingle['id'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <!-- =======================================================================
         HOMEPAGE HERO: SEUNGHYUK.COM INTERACTIVE RADIAL CANVAS
         ("Points, Lines, and Planes" + Nuansa Coklat Kayu & Putih Gading)
         ======================================================================= -->
    <section id="beranda" data-page-section="beranda" class="hero-seunghyuk page-view-section {{ $activeSection === 'beranda' ? 'active-page-view' : '' }}">
        <div id="heroCanvasStage" class="hero-canvas-stage">
            <canvas id="seunghyukCanvas"></canvas>

            <!-- Architectural Corner Readouts (seunghyuk.com layout) -->
            <div class="hero-corner top-left">
                <div>KAJIAN OBJEK PEMAJUAN KEBUDAYAAN &amp; CAGAR BUDAYA</div>
                <div style="color: var(--chinese-red); margin-top: 2px;">ARSIP ANYAMAN: KEBUDAYAAN TIONGHOA MEDAN</div>
            </div>

            <div class="hero-corner top-right">
                <div id="heroLiveCoords">POS // 3.5852° N, 98.6756° E</div>
                <div id="activeJingleTitle" style="color: var(--chinese-gold); margin-top: 2px;">OPENING PAGE 1.1 — Profil Tim Kerja &amp; Data Situs</div>
            </div>

            <div class="hero-corner bottom-left">
                <div>KOTA MULTIKULTURAL MEDAN &amp; DELI SERDANG — SUMATERA UTARA</div>
                <div>TOTAL TERINVENTARISASI: {{ $sites->count() }} OBJEK &amp; SITUS BUDAYA</div>
            </div>

            <div class="hero-corner bottom-right">
                <div>DANA INDONESIANA 2025 // KEMENTERIAN KEBUDAYAAN</div>
                <div>POLITEKNIK WILMAR BISNIS INDONESIA &gt;&lt; INSTITUT TEKNOLOGI DEL</div>
            </div>

            <!-- Interactive Radial Nodes Layer (Points, Lines, and Planes) -->
            <div class="radial-nodes-layer">
                <!-- Center Anchor Square (like seunghyuk.com central point) -->
                <a href="#ringkasan-kajian" data-nav-page="ringkasan-kajian" id="radialCenterAnchor" class="radial-center-anchor" title="Klik untuk membaca Ringkasan Kegiatan">
                    <div class="center-square-node"></div>
                    <span class="center-anchor-caption">MENGANYAM RUANG // MEDAN</span>
                </a>

                <!-- Radial Peripheral Nodes (8 Cultural & Navigation Vectors) -->
                <a href="#peta-etnografis" class="radial-node-item" data-x="22" data-y="24" data-filter-category="Tempat Ibadah" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#B91C1C;"></span>
                    <span class="node-label">Tempat Ibadah (Vihara, Kelenteng, Masjid)</span>
                    <span class="node-count">[26]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="76" data-y="22" data-filter-category="Kuliner" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#D97706;"></span>
                    <span class="node-label">Kuliner &amp; Gastronomi Pecinan</span>
                    <span class="node-count">[16]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="16" data-y="52" data-filter-category="Pemakaman" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#6D28D9;"></span>
                    <span class="node-label">Pemakaman &amp; Ritual Cheng Beng</span>
                    <span class="node-count">[11]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="83" data-y="48" data-filter-category="Perkampungan & Rumah Bersejarah" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#15803D;"></span>
                    <span class="node-label">Perkampungan &amp; Rumah Bersejarah</span>
                    <span class="node-count">[02]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="23" data-y="78" data-filter-category="Bangunan Bersejarah" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#78350F;"></span>
                    <span class="node-label">Kawasan Kesawan &amp; Bangunan Bersejarah</span>
                    <span class="node-count">[04]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="78" data-y="76" data-filter-category="Museum & Cagar Budaya" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#78350F;"></span>
                    <span class="node-label">Museum Situs Kota Cina &amp; Perdagangan</span>
                    <span class="node-count">[03]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="47" data-y="16" data-filter-category="Pendidikan & Lembaga Sosial" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#1D4ED8;"></span>
                    <span class="node-label">Pendidikan, RS &amp; Perkumpulan Sosial</span>
                    <span class="node-count">[06]</span>
                </a>

                <a href="#peta-etnografis" class="radial-node-item" data-x="54" data-y="84" data-filter-category="Tokoh Sejarah" data-target-section="peta-etnografis">
                    <span class="node-dot" style="background:#1D4ED8;"></span>
                    <span class="node-label">Tokoh Sejarah &amp; Filantropis Medan</span>
                    <span class="node-count">[03]</span>
                </a>
            </div>
        </div>

        <!-- Bottom Status Bar -->
        <div class="hero-bottom-bar">
            <span>ARSIP DIGITAL // KOTA MEDAN &amp; DELI SERDANG</span>
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 10px;">
                <a href="#ringkasan-kajian" data-nav-page="ringkasan-kajian" class="scroll-indicator-btn">
                    EKSPLORASI DOKUMENTASI &rarr;
                </a>
                <a
                    href="https://www.instagram.com/menganyamruang.id"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="scroll-indicator-btn"
                    style="border-color: var(--chinese-red); color: var(--chinese-red);"
                    title="Instagram Resmi @menganyamruang.id"
                >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    <span>&#64;menganyamruang.id &nearr;</span>
                </a>
            </div>
            <span>1. PETA ETNOGRAFIS • 2. GALERI DIGITAL • 3. VIDEO • 4. TIM KERJA</span>
        </div>
    </section>

    <!-- =======================================================================
         JUDUL PROJECT, SUB JUDUL & RINGKASAN KEGIATAN (EXACT PDF PAGE 1)
         ======================================================================= -->
    <section id="ringkasan-kajian" data-page-section="ringkasan-kajian" class="narrative-section page-view-section {{ $activeSection === 'ringkasan-kajian' ? 'active-page-view' : '' }}">
        <div class="container-arch">
            <div class="chinese-lattice-frame">
                <div class="kajian-overline">
                    <span>■</span>
                    <span>Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya</span>
                </div>

                <h1 class="project-main-title">
                    Arsip Anyaman: Kebudayaan Tionghoa Medan
                </h1>

                <p class="project-subtitle-lead">
                    Menganyam Ruang mendokumentasikan dan memetakan ekspresi kebudayaan Tionghoa di Kota Medan, mulai dari ritus, seni, tradisi lisan, hingga kuliner, beserta ruang tempat praktek itu hidup: kelenteng, rumah, perkumpulan, dan jalanan kota.
                </p>

                <div class="ringkasan-grid">
                    <!-- Left Metadata Column -->
                    <div class="ringkasan-meta-col">
                        <div class="meta-label-sm">Ringkasan Kegiatan &amp; Cakupan</div>
                        <p style="font-size: 13px; color: var(--wood-medium); margin-bottom: 16px;">
                            Dokumentasi terpadu dalam bentuk Peta Etnografis, Galeri Foto Presisi 5:4, Video Dokumenter, dan Basis Data Situs Cagar Budaya.
                        </p>

                        <div class="stats-mini-grid">
                            <div class="stat-box">
                                <div class="stat-number">{{ $sites->count() }}</div>
                                <div class="stat-caption">Titik Lokasi &amp; Objek Budaya</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">26</div>
                                <div class="stat-caption">Vihara, Kelenteng &amp; Masjid</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">16</div>
                                <div class="stat-caption">Titik Kuliner Legendaris</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-number">11</div>
                                <div class="stat-caption">Situs Makam &amp; Pemakaman</div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--wood-line);">
                            <div class="meta-label-sm">Pengusul Kajian</div>
                            <div style="font-size: 14px; font-weight: 600; color: var(--wood-deep);">Rio Fernandez Tamba</div>
                            <a href="https://riotamba.com" target="_blank" rel="noopener noreferrer" style="font-family: var(--font-mono); font-size: 11.5px; color: var(--chinese-red); text-decoration: none;">
                                riotamba.com ↗
                            </a>
                        </div>

                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--wood-line);">
                            <div class="meta-label-sm">Instagram Resmi</div>
                            <a
                                href="https://www.instagram.com/menganyamruang.id"
                                target="_blank"
                                rel="noopener noreferrer"
                                style="display: inline-flex; align-items: center; gap: 7px; font-family: var(--font-mono); font-size: 12.5px; font-weight: 600; color: var(--chinese-red); text-decoration: none; margin-top: 2px;"
                            >
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                <span>&#64;menganyamruang.id ↗</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Prose Column (Exact 3 Paragraphs from PDF Page 1) -->
                    <div class="ringkasan-prose">
                        <div class="meta-label-sm" style="margin-bottom: 12px;">Narasi &amp; Ringkasan Kegiatan</div>
                        <p>
                            Medan tumbuh sebagai kota multikultural, tempat komunitas Melayu, Batak, Jawa, India, Arab, dan Tionghoa hidup berdampingan dalam ruang kota yang sama. Ekspresi kebudayaan Tionghoa di kota ini tidak sekadar mengulang tradisi daratan Tiongkok, tetapi tumbuh melalui adaptasi panjang dengan konteks lokal Sumatera Utara.
                        </p>
                        <p>
                            <strong>Menganyam Ruang dan Warna Ekspresi Kebudayaan Tionghoa dalam Kota Multikultural Medan</strong> adalah kegiatan kajian yang menginventarisasi Objek Pemajuan Kebudayaan Tionghoa di Medan, seperti perayaan Imlek dan Cap Go Meh, barongsai, tradisi tutur, kuliner, dan pengetahuan tradisional. Kegiatan ini juga memetakan ruang tempat praktek tersebut berlangsung, dari kelenteng tua, gedung perkumpulan, hingga kawasan bersejarah Kesawan.
                        </p>
                        <p>
                            Situs ini menyajikan hasil dokumentasi tersebut dalam bentuk peta, foto, dan cerita, agar dapat diakses oleh komunitas, peneliti, pendidik, dan masyarakat luas.
                        </p>
                    </div>
                </div>

                <!-- Dukungan Kegiatan oleh Kementerian Kebudayaan: Dana Indonesiana 2025 + Support Kampus -->
                <div class="support-endorsement-bar">
                    <div>
                        <div class="meta-label-sm" style="color: var(--wood-deep); font-weight: 600; margin-bottom: 12px;">
                            Dukungan kegiatan oleh Kementerian Kebudayaan: Dana Indonesiana 2025
                        </div>
                        <div class="support-logos-row">
                            <img src="{{ asset('images/logos/kemenkebud-color.png') }}" alt="Logo Kementerian Kebudayaan" class="support-logo-item" />
                            <img src="{{ asset('images/logos/dana-indonesiana-color.png') }}" alt="Logo Dana Indonesiana" class="support-logo-item" />
                            <img src="{{ asset('images/logos/lpdp-color-black.png') }}" alt="Logo LPDP" class="support-logo-item" style="height: 40px;" />
                        </div>
                    </div>

                    <!-- Support dan Develop web ini didukung oleh: (kecil saja) Politeknik Wilmar Bisnis Indonesia >< Institut Teknologi Del -->
                    <div class="dev-support-small">
                        <span>Support dan Develop web ini didukung oleh:</span>
                        <strong>Politeknik Wilmar Bisnis Indonesia &gt;&lt; Institut Teknologi Del</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =======================================================================
         NAVIGATION 1: PETA ETNOGRAFIS (PDF PAGES 2 & 3)
         References: https://thechurchatlas.com/ & https://petraverso.com/
         ======================================================================= -->
    <section id="peta-etnografis" data-page-section="peta-etnografis" class="arch-section page-view-section {{ $activeSection === 'peta-etnografis' ? 'active-page-view' : '' }}">
        <div class="container-arch">
            <div class="section-header-bar">
                <div>
                    <span class="section-index-tag">NAVIGATION 01 // REFERENSI: THECHURCHATLAS.COM &amp; PETRAVERSO.COM</span>
                    <h2 class="section-main-heading">1. Peta Etnografis Kota Medan &amp; Deli Serdang</h2>
                </div>
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px;">
                    <span id="mapCurrentZoomBadge" style="font-family: var(--font-mono); font-size: 11px; background: var(--ivory-surface); border: 1px solid var(--wood-line-strong); padding: 6px 12px;">
                        ZOOM 12x (LOCKED MEDAN &amp; DELI SERDANG)
                    </span>
                    <button type="button" id="toggleOverlayLabelsBtn" class="cat-chip" style="padding: 6px 12px;">
                        Tampilkan Overlay Nama/Kategori
                    </button>
                    <button type="button" id="resetMedanBoundsBtn" class="cat-chip" style="padding: 6px 12px; background: var(--wood-deep); color: var(--ivory-bg);">
                        Reset Fokus Peta Medan &amp; Deli Serdang
                    </button>
                </div>
            </div>

            <!-- Interactive Map Workspace (Map + Petraverso Right Sidebar) -->
            <div class="map-workspace-shell">
                <div class="map-canvas-container">
                    <!-- Top-Left Floating Navigation & Search/Category Bar (PDF Page 2 & 3: "Navigation sebelah kiri atas juga" + "bar pencarian kategori: Tempat Makan, Klenteng dll") -->
                    <div class="map-topleft-panel">
                        <div class="map-topleft-nav-row">
                            <span style="font-family: var(--font-mono); font-size: 10.5px; font-weight: 700; color: var(--chinese-red);">
                                NAVIGASI &amp; FILTER PETA
                            </span>
                            <div class="map-Quick-nav">
                                <a href="#beranda" data-nav-page="beranda">Home</a>
                                <a href="#peta-etnografis" data-nav-page="peta-etnografis" class="active">1. Peta</a>
                                <a href="#galeri-digital" data-nav-page="galeri-digital">2. Galeri</a>
                                <a href="#video-dokumenter" data-nav-page="video-dokumenter">3. Video</a>
                                <a href="#profil-tim" data-nav-page="profil-tim">4. Tim</a>
                            </div>
                        </div>

                        <!-- Search Input for Category / Sub-category / Desa / Kecamatan -->
                        <div class="map-search-box">
                            <input
                                type="search"
                                id="mapSearchInput"
                                class="map-search-input"
                                placeholder="Cari Tempat Makan, Klenteng, Vihara, Desa, Kecamatan..."
                                aria-label="Pencarian Kategori dan Lokasi"
                            />
                        </div>

                        <!-- Quick Sub-Category Filter Pills (Tempat Makan, Klenteng, Vihara, dll.) -->
                        <div style="margin-bottom: 8px; display: flex; flex-wrap: wrap; gap: 4px;">
                            <span style="font-family: var(--font-mono); font-size: 9.5px; color: var(--wood-muted); width: 100%;">
                                FILTER CEPAT SUB-KATEGORI:
                            </span>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Kuliner">🍜 Tempat Makan / Kuliner</button>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Kelenteng">🏮 Klenteng</button>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Vihara">🪷 Vihara</button>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Masjid">🕌 Masjid Tionghoa</button>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Makam">🪦 Makam / Kuburan</button>
                            <button type="button" class="cat-chip subcat-quick-btn" data-subcat="Sekolah">🏫 Sekolah &amp; RS</button>
                        </div>

                        <!-- Main Category Filter Chips with Color Tags -->
                        <div class="map-filter-chips">
                            <button type="button" class="cat-chip active" data-map-category="all">
                                <span class="chip-dot" style="background: var(--wood-deep);"></span>
                                Semua ({{ $sites->count() }})
                            </button>
                            @foreach($categorySummary as $catItem)
                                <button type="button" class="cat-chip" data-map-category="{{ $catItem['name'] }}">
                                    <span class="chip-dot" style="background: {{ $catItem['color_hex'] }};"></span>
                                    {{ $catItem['name'] }} ({{ $catItem['count'] }})
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom-Left Color Legend (PDF Page 3: "Kategori di peta bisa beberapa warna tag: Merah (tempat ibadah), Hijau (Perkampungan), ...") -->
                    <div class="map-bottomleft-legend">
                        <div style="font-family: var(--font-mono); font-size: 10px; font-weight: 700; color: var(--wood-deep); text-transform: uppercase;">
                            LEGENDA WARNA TAG KATEGORI (ARAHKAN KURSOR / KLIK TITIK UNTUK THUMBNAIL, DESA, KECAMATAN &amp; KOORDINAT):
                        </div>
                        <div class="legend-items-row">
                            <span class="legend-tag"><span class="chip-dot" style="background:#B91C1C;"></span> Merah: Tempat Ibadah</span>
                            <span class="legend-tag"><span class="chip-dot" style="background:#15803D;"></span> Hijau: Perkampungan &amp; Ruang Hidup</span>
                            <span class="legend-tag"><span class="chip-dot" style="background:#D97706;"></span> Emas: Kuliner / Tempat Makan</span>
                            <span class="legend-tag"><span class="chip-dot" style="background:#6D28D9;"></span> Ungu: Pemakaman</span>
                            <span class="legend-tag"><span class="chip-dot" style="background:#78350F;"></span> Coklat: Situs &amp; Cagar Budaya</span>
                            <span class="legend-tag"><span class="chip-dot" style="background:#1D4ED8;"></span> Biru: Pendidikan, Organisasi &amp; Tokoh</span>
                        </div>
                    </div>

                    <!-- Leaflet Map Container -->
                    <div id="ethnographicMap" aria-label="Peta Etnografis Kota Medan dan Deli Serdang"></div>
                </div>

                <!-- Right Sidebar: Petraverso-style Places in View + Active Site Detail Drawer -->
                <aside class="map-right-sidebar">
                    <div class="sidebar-top-header">
                        <span style="font-family: var(--font-mono); font-size: 11px; letter-spacing: 0.08em;">
                            CAKUPAN TITIK PETA MEDAN &amp; DELI SERDANG
                        </span>
                        <span id="sidebarPlacesCount" style="font-family: var(--font-mono); font-size: 11px; color: var(--chinese-gold); font-weight: 700;">
                            {{ $sites->count() }} TITIK LOKASI
                        </span>
                    </div>

                    <!-- Scrollable List of Numbered Sites -->
                    <div id="sidebarPlacesList" class="sidebar-places-list"></div>

                    <!-- Active Site Inspector Drawer -->
                    <div id="sidebarDetailDrawer" class="sidebar-detail-drawer"></div>
                </aside>
            </div>
        </div>
    </section>

    <!-- =======================================================================
         NAVIGATION 2: GALERI DIGITAL (PDF PAGES 3 & 4)
         Reference: https://aparelho.studio/?ref=siteinspire
         - Top filmstrip: B&W 5:4 thumbnails -> turn original color when clicked
         - Clicking loads the photos belonging to that thumbnail in 5:4 precision stage
         - No description on stage, only subtitle of location name
         - Includes: Rumah Ibadah, Perkampungan, Titik Makam, Situs, Museum China
         ======================================================================= -->
    <section id="galeri-digital" data-page-section="galeri-digital" class="arch-section page-view-section {{ $activeSection === 'galeri-digital' ? 'active-page-view' : '' }}">
        <div class="container-arch">
            <div class="section-header-bar">
                <div>
                    <span class="section-index-tag">NAVIGATION 02 // REFERENSI: APARELHO.STUDIO (RASIO PRESISI 5:4)</span>
                    <h2 class="section-main-heading">2. Galeri Digital Arsip Budaya</h2>
                </div>
                <p style="font-size: 13.5px; color: var(--wood-medium); max-width: 520px;">
                    Thumbnail hitam-putih berasio <strong>5:4</strong> di bar atas akan berubah menjadi warna asli saat diklik dan memuat rangkaian foto dokumentasi lokasi tersebut beserta subjudul nama lokasinya.
                </p>
            </div>

            <div class="aparelho-stage-wrapper">
                <!-- Category Filter Tabs matching PDF Page 4 list -->
                <div class="aparelho-top-controls">
                    <div class="gallery-group-tabs" role="tablist" aria-label="Kategori Galeri Digital">
                        <button type="button" class="group-tab-btn active" data-gallery-group="all">
                            Semua Aset ({{ $sites->count() }})
                        </button>
                        <button type="button" class="group-tab-btn" data-gallery-group="Rumah Ibadah">
                            Rumah Ibadah ({{ $galleryGroups['Rumah Ibadah']->count() }})
                        </button>
                        <button type="button" class="group-tab-btn" data-gallery-group="Perkampungan">
                            Perkampungan ({{ $galleryGroups['Perkampungan']->count() }})
                        </button>
                        <button type="button" class="group-tab-btn" data-gallery-group="Titik Makam">
                            Titik Makam ({{ $galleryGroups['Titik Makam']->count() }})
                        </button>
                        <button type="button" class="group-tab-btn" data-gallery-group="Situs">
                            Situs ({{ $galleryGroups['Situs']->count() }})
                        </button>
                        <button type="button" class="group-tab-btn" data-gallery-group="Museum China">
                            Museum China ({{ $galleryGroups['Museum China']->count() }})
                        </button>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" id="galleryPrevBtn" class="group-tab-btn" title="Lokasi Sebelumnya">← PREV</button>
                        <button type="button" id="galleryNextBtn" class="group-tab-btn" title="Lokasi Berikutnya">NEXT →</button>
                    </div>
                </div>

                <!-- Aparelho Studio Top Horizontal Filmstrip (5:4 B&W Thumbnails -> Color on Click) -->
                <div id="galleryFilmstripBar" class="aparelho-filmstrip-bar" aria-label="Bar Thumbnail Foto 5:4 Hitam Putih"></div>

                <!-- Main 5:4 Precision Photo Stage -->
                <div class="aparelho-main-viewer">
                    <div class="precision-5-4-stage">
                        <img id="galleryMainImage" src="{{ $sites->first()?->thumbnail }}" alt="{{ $sites->first()?->name }}" />
                    </div>

                    <!-- Multi-Photo Switcher for the Clicked Location (loads the photos belonging to that thumbnail) -->
                    <div id="gallerySubphotoStrip" class="subphoto-strip" aria-label="Rangkaian Foto Lokasi Terpilih"></div>

                    <!-- Location Subtitle Bar ONLY (No description clutter, as specified in PDF Page 4: "tidak usah ada deskripsi tapi ada subtitle nama lokasinya apa") -->
                    <div class="stage-location-subtitle-bar">
                        <h3 id="galleryLocationSubtitle" class="stage-location-name">
                            {{ $sites->first()?->name }}
                        </h3>
                        <span id="galleryLocationMeta" class="stage-location-meta">
                            {{ $sites->first()?->gallery_group }} • RASIO PRESISI 5:4
                        </span>
                    </div>
                </div>

                <!-- Special Highlight for "Museum China: Sejarah, keunggulannya apa" (PDF Page 4 bullet item 5) -->
                <div id="museumChinaSpotlight" class="museum-china-spotlight">
                    <div style="aspect-ratio: 5 / 4; overflow: hidden; border: 1px solid rgba(250, 246, 238, 0.25);">
                        <img src="{{ asset('images/sites/site_024_a.svg') }}" alt="Museum Situs Kota Cina Medan Marelan" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <div>
                        <div style="font-family: var(--font-mono); font-size: 11px; color: var(--chinese-gold); letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 6px;">
                            SOROTAN KHUSUS ASET // MUSEUM CHINA (MUSEUM SITUS KOTA CINA — MEDAN MARELAN)
                        </div>
                        <h4 style="font-size: 22px; font-weight: 500; color: var(--ivory-bg); margin-bottom: 10px;">
                            Museum Situs Kota Cina: Sejarah &amp; Keunggulannya
                        </h4>
                        <p style="font-size: 14px; line-height: 1.7; color: rgba(250, 246, 238, 0.85); margin-bottom: 10px;">
                            <strong>Sejarah:</strong> Dibangun pada tahun <strong>2008</strong> oleh sejarawan <strong>Ichwan Azhari</strong> di Jl. Kota Cina No. 65, Kelurahan Paya Pasir, Kecamatan Medan Marelan. Kawasan ini sebelumnya merupakan <strong>bandar dan dermaga dagang internasional kuno</strong> yang sangat strategis di Pantai Timur Sumatera pada <strong>abad ke-11 hingga ke-14 Masehi</strong>, jauh sebelum berdirinya Kesultanan Deli maupun Kota Medan modern.
                        </p>
                        <p style="font-size: 14px; line-height: 1.7; color: rgba(250, 246, 238, 0.85);">
                            <strong>Keunggulan Utama:</strong> Menyimpan bukti arkeologis interaksi maritim langsung antara Nusantara, Tiongkok, India Selatan, dan Timur Tengah berupa ribuan artefak <strong>keramik Dinasti Song dan Dinasti Yuan</strong>, koin kepeng tembaga kuno Tiongkok &amp; Sriwijaya, arca batu Buddha dan Hindu (Amitabha &amp; Wisnu), serpihan kayu kapal kuno, serta struktur reruntuhan candi bata kuno di bawah permukaan tanah Paya Pasir.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =======================================================================
         NAVIGATION 3: VIDEO (PDF PAGE 4)
         "Sama referensi diatas tapi akan berupa source youtube link saja:
          buat dummy video random saja dulu, di klik bar nya akan ada diatas
          pemutaran video, estimasi video yang akan di web sekitar 7 video"
         ======================================================================= -->
    <section id="video-dokumenter" data-page-section="video-dokumenter" class="arch-section page-view-section {{ $activeSection === 'video-dokumenter' ? 'active-page-view' : '' }}">
        <div class="container-arch">
            <div class="section-header-bar">
                <div>
                    <span class="section-index-tag">NAVIGATION 03 // 7 VIDEO DOKUMENTER (BAR PILIHAN DI ATAS PEMUTARAN VIDEO)</span>
                    <h2 class="section-main-heading">3. Dokumentasi Video Kebudayaan Tionghoa Medan</h2>
                </div>
                <p style="font-size: 13.5px; color: var(--wood-medium); max-width: 500px;">
                    Pilih salah satu dari <strong>7 video</strong> pada bar thumbnail di bagian atas untuk memutar sumber tautan YouTube pada layar pemutaran utama di bawahnya.
                </p>
            </div>

            <div class="aparelho-stage-wrapper">
                <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-family: var(--font-mono); font-size: 11px; color: var(--chinese-gold); letter-spacing: 0.1em;">
                        KLIK BAR VIDEO DI BAWAH INI UNTUK MEMUTAR (01 — 07 VIDEO YOUTUBE):
                    </span>
                    <a
                        id="videoYoutubeSourceLink"
                        href="{{ $videos[0]['youtube_url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="font-family: var(--font-mono); font-size: 11px; color: var(--ivory-bg); text-decoration: none; border: 1px solid var(--stage-border); padding: 4px 10px;"
                    >
                        BUKA SUMBER YOUTUBE ↗
                    </a>
                </div>

                <!-- Top Bar Selector Above Video Player (7 Videos in 5:4 B&W -> Color on Active) -->
                <div id="videoTopSelectorBar" class="aparelho-filmstrip-bar" aria-label="Bar Pilihan 7 Video di Atas Pemutaran">
                    @foreach($videos as $vid)
                        <button
                            type="button"
                            class="filmstrip-thumb-btn {{ $loop->first ? 'active' : '' }}"
                            data-video-id="{{ $vid['id'] }}"
                            title="{{ $vid['code'] }}: {{ $vid['title'] }}"
                            style="flex: 0 0 145px;"
                        >
                            <img src="{{ $vid['thumbnail'] }}" alt="{{ $vid['title'] }}" loading="lazy" />
                            <span class="thumb-index">{{ $vid['code'] }} • {{ $vid['duration'] }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Main Video Playback Stage Below the Selector Bar -->
                <div class="aparelho-main-viewer">
                    <div class="precision-5-4-stage" style="aspect-ratio: 16 / 9;">
                        <iframe
                            id="videoYoutubeIframe"
                            src="{{ $videos[0]['embed_url'] }}"
                            title="{{ $videos[0]['title'] }}"
                            sandbox="allow-scripts allow-same-origin allow-presentation"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </div>

                    <div class="stage-location-subtitle-bar">
                        <h3 id="videoActiveTitle" class="stage-location-name">
                            {{ $videos[0]['code'] }} — {{ $videos[0]['title'] }}
                        </h3>
                        <span id="videoActiveSubtitle" class="stage-location-meta">
                            {{ $videos[0]['location_subtitle'] }} • DURASI {{ $videos[0]['duration'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =======================================================================
         NAVIGATION 4: PROFIL TIM KERJA WEB (PDF PAGES 4 & 5)
         - Pengusul: Rio Fernandez Tamba (ke landing page: riotamba.com)
         - Keterangan lengkap
         - 9 Anggota Tim Kerja (IT Del & Politeknik Wilmar Bisnis Indonesia)
         ======================================================================= -->
    <section id="profil-tim" data-page-section="profil-tim" class="arch-section page-view-section {{ $activeSection === 'profil-tim' ? 'active-page-view' : '' }}">
        <div class="container-arch">
            <div class="section-header-bar">
                <div>
                    <span class="section-index-tag">NAVIGATION 04 // PENGUSUL &amp; TIM KERJA KAJIAN</span>
                    <h2 class="section-main-heading">4. Profil Tim Kerja Web</h2>
                </div>
                <div style="font-family: var(--font-mono); font-size: 11.5px; color: var(--wood-medium);">
                    POLITEKNIK WILMAR BISNIS INDONESIA &gt;&lt; INSTITUT TEKNOLOGI DEL
                </div>
            </div>

            <!-- Pengusul Spotlight Card (Rio Fernandez Tamba -> riotamba.com) -->
            <div class="proposer-spotlight-card">
                <div>
                    <div style="font-family: var(--font-mono); font-size: 11.5px; color: var(--chinese-gold); letter-spacing: 0.14em; text-transform: uppercase; margin-bottom: 8px;">
                        PENGUSUL PROGRAM DANA INDONESIANA 2025
                    </div>
                    <h3 style="font-size: clamp(26px, 3vw, 36px); font-weight: 500; margin-bottom: 14px; color: var(--ivory-bg);">
                        {{ $team['proposer']['name'] }}
                    </h3>
                    <p style="font-size: 16px; line-height: 1.75; color: rgba(250, 246, 238, 0.9); max-width: 820px;">
                        {{ $team['proposer']['bio'] }}
                    </p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ $team['proposer']['website'] }}" target="_blank" rel="noopener noreferrer" class="proposer-link-btn">
                        <span>KUNJUNGI LANDING PAGE: {{ strtoupper($team['proposer']['website_label']) }}</span>
                        <span>↗</span>
                    </a>
                    <a
                        href="https://www.instagram.com/menganyamruang.id"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="proposer-link-btn"
                        style="background: transparent; border-color: var(--chinese-gold); color: var(--chinese-gold); justify-content: center;"
                    >
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        <span>INSTAGRAM: &#64;MENGANYAMRUANG.ID ↗</span>
                    </a>
                </div>
            </div>

            <!-- 9 Anggota Tim Kerja Grid -->
            <div style="margin-bottom: 16px; font-family: var(--font-mono); font-size: 12px; color: var(--chinese-red); letter-spacing: 0.1em; text-transform: uppercase;">
                ANGGOTA TIM KERJA &amp; PENELITI LAPANGAN (9 PERSONEL):
            </div>

            <div class="team-roster-grid">
                @foreach($team['members'] as $member)
                    <div class="team-member-card">
                        <div class="member-num">TIM KERJA // {{ $member['no'] }}</div>
                        <h4 class="member-name">{{ $member['name'] }}</h4>
                        <div class="member-inst">{{ $member['institution'] }}</div>
                        <div class="member-role">{{ $member['role'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- =======================================================================
         NAVIGATION 5: KATALOG DIREKTORI LENGKAP DATA SITUS (EXCEL ACUAN)
         ======================================================================= -->
    <section id="katalog-situs" data-page-section="katalog-situs" class="arch-section page-view-section {{ $activeSection === 'katalog-situs' ? 'active-page-view' : '' }}" style="background: var(--ivory-surface);">
        <div class="container-arch">
            <div class="section-header-bar">
                <div>
                    <span class="section-index-tag">BASIS DATA LENGKAP // ACUAN: DATA SITUS UNTUK WEB.XLSX</span>
                    <h2 class="section-main-heading">Indeks Arsip {{ $sites->count() }} Situs &amp; Objek Budaya</h2>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <input
                        type="search"
                        id="catalogSearchInput"
                        placeholder="Cari nama situs, keunikan, desa..."
                        style="padding: 8px 14px; border: 1px solid var(--wood-line-strong); background: var(--ivory-card); font-family: var(--font-sans); font-size: 13px; min-width: 240px;"
                    />
                    <select
                        id="catalogCategorySelect"
                        style="padding: 8px 14px; border: 1px solid var(--wood-line-strong); background: var(--ivory-card); font-family: var(--font-sans); font-size: 13px;"
                    >
                        <option value="all">Semua Kategori ({{ $sites->count() }})</option>
                        @foreach($categorySummary as $catItem)
                            <option value="{{ $catItem['name'] }}">{{ $catItem['name'] }} ({{ $catItem['count'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="overflow-x: auto; border: 1px solid var(--wood-dark); background: var(--ivory-card);">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="background: var(--wood-deep); color: var(--ivory-bg); font-family: var(--font-mono); font-size: 11px; text-transform: uppercase;">
                            <th style="padding: 12px 14px;">No</th>
                            <th style="padding: 12px 14px;">Kategori &amp; Sub-Kategori</th>
                            <th style="padding: 12px 14px;">Nama Lokasi / Objek</th>
                            <th style="padding: 12px 14px;">Tahun/Periode</th>
                            <th style="padding: 12px 14px;">Desa/Kelurahan &amp; Kecamatan</th>
                            <th style="padding: 12px 14px;">Keunikan / Catatan Sejarah</th>
                            <th style="padding: 12px 14px;">Aksi Peta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                            <tr class="catalog-data-row" data-row-category="{{ $site->category }}" style="border-bottom: 1px solid var(--wood-line);">
                                <td style="padding: 12px 14px; font-family: var(--font-mono); font-weight: 700; color: var(--chinese-red);">
                                    #{{ $site->marker_number }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    <span style="display: inline-block; background: {{ $site->color_hex }}; color: #FAF6EE; font-size: 10.5px; font-weight: 600; padding: 2px 7px; margin-bottom: 3px;">
                                        {{ $site->category }}
                                    </span>
                                    <div style="font-size: 11.5px; color: var(--wood-medium);">{{ $site->sub_category }}</div>
                                </td>
                                <td style="padding: 12px 14px; font-weight: 600; color: var(--wood-deep);">
                                    {{ $site->name }}
                                    <div style="font-family: var(--font-mono); font-size: 10.5px; color: var(--wood-muted); font-weight: 400;">
                                        {{ number_format($site->latitude, 4) }}° N, {{ number_format($site->longitude, 4) }}° E
                                    </div>
                                </td>
                                <td style="padding: 12px 14px; font-family: var(--font-mono); font-size: 11.5px; white-space: nowrap;">
                                    {{ $site->year_period }}
                                </td>
                                <td style="padding: 12px 14px;">
                                    <strong>Kel. {{ $site->desa_kelurahan }}</strong>
                                    <div style="font-size: 11.5px; color: var(--wood-medium);">{{ $site->kecamatan }}, {{ $site->regency_city }}</div>
                                </td>
                                <td style="padding: 12px 14px; max-width: 380px; line-height: 1.5; color: var(--wood-dark);">
                                    {{ \Illuminate\Support\Str::limit($site->uniqueness, 160) }}
                                </td>
                                <td style="padding: 12px 14px; white-space: nowrap;">
                                    <button
                                        type="button"
                                        onclick="window.focusSiteOnMap({{ $site->id }})"
                                        style="background: var(--wood-deep); color: var(--ivory-bg); border: none; padding: 6px 10px; font-family: var(--font-mono); font-size: 10.5px; cursor: pointer;"
                                    >
                                        LIHAT DI PETA →
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- =======================================================================
         FOOTER (DANA INDONESIANA 2025 + POLITEKNIK WBI >< IT DEL)
         ======================================================================= -->
    <footer class="site-footer">
        <div class="container-arch">
            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 32px; padding-bottom: 32px; border-bottom: 1px solid rgba(250, 246, 238, 0.16);">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                        <img src="{{ asset('images/logos/icon-menganyam-ruang-512.png') }}" alt="Logo Menganyam Ruang" class="brand-logo-icon" />
                        <div>
                            <div style="font-size: 16px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;">
                                Arsip Anyaman: Kebudayaan Tionghoa Medan
                            </div>
                            <div style="font-family: var(--font-mono); font-size: 11.5px; color: var(--chinese-gold);">
                                menganyamruang.id • menganyamruang.com
                            </div>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: rgba(250, 246, 238, 0.72); max-width: 540px; margin-bottom: 14px;">
                        Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya — Menganyam Ruang dan Warna Ekspresi Kebudayaan Tionghoa dalam Kota Multikultural Medan.
                    </p>
                    <a
                        href="https://www.instagram.com/menganyamruang.id"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; background: rgba(250, 246, 238, 0.08); border: 1px solid var(--chinese-gold); color: var(--ivory-bg); text-decoration: none; font-family: var(--font-mono); font-size: 12px; font-weight: 600;"
                    >
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--chinese-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        <span>Instagram Resmi: <strong style="color: var(--chinese-gold);">&#64;menganyamruang.id</strong> &nearr;</span>
                    </a>
                </div>

                <!-- Official White Logos for Dark Wood Footer -->
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 24px;">
                    <img src="{{ asset('images/logos/kemenkebud-white.png') }}" alt="Kementerian Kebudayaan" style="height: 44px; width: auto;" />
                    <img src="{{ asset('images/logos/dana-indonesiana-white.png') }}" alt="Dana Indonesiana" style="height: 44px; width: auto;" />
                    <img src="{{ asset('images/logos/lpdp-color-white.png') }}" alt="LPDP" style="height: 38px; width: auto;" />
                </div>
            </div>

            <div style="padding-top: 24px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; font-size: 11.5px; color: rgba(250, 246, 238, 0.65);">
                <div>
                    &copy; 2025–2026 Menganyam Ruang — Dukungan Kegiatan oleh Kementerian Kebudayaan: Dana Indonesiana 2025.
                    <span style="margin: 0 8px; opacity: 0.4;">|</span>
                    <button type="button" id="introReplayBtn" style="background: none; border: none; color: var(--chinese-gold); font-family: var(--font-mono); font-size: 11px; cursor: pointer; padding: 0;">
                        PUTAR ULANG INTRO
                    </button>
                    <span style="margin: 0 8px; opacity: 0.4;">|</span>
                    <a href="{{ auth()->check() ? route('admin.dashboard') : route('admin.login') }}" style="color: var(--chinese-gold); text-decoration: none; font-family: var(--font-mono); font-size: 11px;">
                        {{ auth()->check() ? 'PANEL ADMIN (AKTIF) ↗' : 'LOGIN ADMIN' }}
                    </a>
                </div>
                <div style="font-family: var(--font-mono); font-size: 11px; color: var(--chinese-gold);">
                    Support dan Develop web ini didukung oleh: Politeknik Wilmar Bisnis Indonesia &gt;&lt; Institut Teknologi Del
                </div>
            </div>
        </div>
    </footer>

    <!-- Pass Structured Dataset to Frontend JS Engine -->
    <script>
        window.ARCHIVE_DATA = {
            sites: @json($sites),
            videos: @json($videos),
            jingles: @json($jingles),
            activeSection: @json($activeSection)
        };
    </script>

    <!-- Main Archival Interactive Engine (Inlined directly from laravel_app/public so public_html separation never serves stale JS/Leaflet) -->
    <script>{!! file_get_contents(base_path('public/js/archive.js')) !!}</script>
</body>
</html>
