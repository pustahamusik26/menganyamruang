<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Ensure PhpOffice PSR-4 autoloader is active
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'PhpOffice\\PhpWord\\')) {
        $rel = substr($class, strlen('PhpOffice\\PhpWord\\'));
        $file = __DIR__ . '/../vendor/phpoffice/phpword/src/PhpWord/' . str_replace('\\', '/', $rel) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    if (str_starts_with($class, 'PhpOffice\\Math\\')) {
        $rel = substr($class, strlen('PhpOffice\\Math\\'));
        $file = __DIR__ . '/../vendor/phpoffice/math/src/Math/' . str_replace('\\', '/', $rel) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    return false;
});

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\IOFactory;

// CRITICAL FIX: Enable XML entity output escaping so '&', '<', '>' in text never corrupt Word XML!
Settings::setOutputEscapingEnabled(true);

echo "Initializing PHPWord with OutputEscapingEnabled...\n";
$phpWord = new PhpWord();

// Define Colors
$colorDarkBrown = '1A1614';
$colorTerra = '8C6D48';
$colorGold = 'C5A059';
$colorRed = 'C92A2A';
$colorIvoryBg = 'FAF6EE';
$colorIvoryAlt = 'F3ECE0';
$colorTextDark = '2C2218';
$colorMuted = '6E6259';

// Document Properties
$properties = $phpWord->getDocInfo();
$properties->setCreator('Rio Fernandez Tamba - Menganyam Ruang');
$properties->setTitle('Laporan Kerangka Kerja dan Langkah Kerja Website Arsip Budaya');
$properties->setSubject('Menganyam Ruang: Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya Tionghoa di Kota Medan dan Deli Serdang');
$properties->setDescription('Dokumen Laporan Pengembangan Website Arsip Budaya Berbasis Laravel 12, MySQL, dan Leaflet GIS');

// Typography Styles
$phpWord->setDefaultFontName('Calibri');
$phpWord->setDefaultFontSize(11);

$phpWord->addTitleStyle(1, ['name' => 'Georgia', 'size' => 18, 'bold' => true, 'color' => $colorDarkBrown], ['spaceBefore' => 240, 'spaceAfter' => 120]);
$phpWord->addTitleStyle(2, ['name' => 'Georgia', 'size' => 14, 'bold' => true, 'color' => $colorTerra], ['spaceBefore' => 180, 'spaceAfter' => 80]);
$phpWord->addTitleStyle(3, ['name' => 'Calibri', 'size' => 12, 'bold' => true, 'color' => $colorDarkBrown], ['spaceBefore' => 120, 'spaceAfter' => 60]);

// Explicit Bullet List Style (Compatible with MS Word, WPS, LibreOffice, Google Docs)
$phpWord->addNumberingStyle(
    'bulletList',
    [
        'type' => 'singleLevel',
        'levels' => [
            [
                'format' => 'bullet',
                'text' => '•',
                'left' => 360,
                'hanging' => 180,
                'font' => 'Calibri',
            ],
        ],
    ]
);

// Table Styles
$tableStyle = [
    'borderColor' => 'CCCCCC',
    'borderSize' => 6,
    'cellMarginTop' => 100,
    'cellMarginBottom' => 100,
    'cellMarginLeft' => 140,
    'cellMarginRight' => 140,
    'alignment' => JcTable::CENTER,
];
$phpWord->addTableStyle('CustomTable', $tableStyle);

// Cell styles
$thCellStyle = ['bgColor' => $colorDarkBrown, 'valign' => 'center'];
$tdNormCellStyle = ['valign' => 'top'];
$tdAltCellStyle = ['bgColor' => $colorIvoryAlt, 'valign' => 'top'];

// Text styles
$thTextStyle = ['name' => 'Calibri', 'size' => 10, 'bold' => true, 'color' => 'FFFFFF'];
$tdTextStyle = ['name' => 'Calibri', 'size' => 10, 'color' => $colorTextDark];
$tdMutedStyle = ['name' => 'Calibri', 'size' => 9.5, 'color' => $colorMuted];

// Section setup (A4 standard margins 1 inch = 1440 twips)
$section = $phpWord->addSection([
    'marginTop' => 1440,
    'marginBottom' => 1440,
    'marginLeft' => 1440,
    'marginRight' => 1440,
]);

// Header & Footer (Use clean strings; Settings::setOutputEscapingEnabled escapes '&' properly, and addText avoids addPreserveText XML pitfalls)
$header = $section->addHeader();
$header->addText('Menganyam Ruang — Laporan Pengembangan Website Arsip Budaya', ['size' => 8.5, 'color' => '888888', 'italic' => true], ['alignment' => Jc::RIGHT]);

$footer = $section->addFooter();
$footerTextRun = $footer->addTextRun(['alignment' => Jc::CENTER]);
$footerTextRun->addText('Halaman ', ['size' => 8.5, 'color' => '888888']);
$footerTextRun->addField('PAGE', [], [], null, ['size' => 8.5, 'color' => '888888']);
$footerTextRun->addText(' dari ', ['size' => 8.5, 'color' => '888888']);
$footerTextRun->addField('NUMPAGES', [], [], null, ['size' => 8.5, 'color' => '888888']);
$footerTextRun->addText('  |  Dana Indonesiana 2025 — Kementerian Kebudayaan RI & LPDP', ['size' => 8.5, 'color' => '888888']);

// ==========================================
// COVER / TITLE BLOCK
// ==========================================
$section->addText('DOKUMEN LAPORAN PENGEMBANGAN SISTEM', ['name' => 'Calibri', 'size' => 11.5, 'bold' => true, 'color' => $colorTerra], ['alignment' => Jc::CENTER, 'spaceAfter' => 80]);
$section->addText('ARSIP ANYAMAN: KEBUDAYAAN TIONGHOA MEDAN', ['name' => 'Georgia', 'size' => 22, 'bold' => true, 'color' => $colorDarkBrown], ['alignment' => Jc::CENTER, 'spaceAfter' => 60]);
$section->addText('Menganyam Ruang: Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya Tionghoa di Kota Medan dan Kabupaten Deli Serdang', ['name' => 'Georgia', 'size' => 12.5, 'italic' => true, 'color' => $colorTextDark], ['alignment' => Jc::CENTER, 'spaceAfter' => 200]);

// Box Profil Hibah & Kemitraan
$metaTable = $section->addTable(['borderColor' => $colorGold, 'borderSize' => 12, 'cellMargin' => 140, 'alignment' => JcTable::CENTER]);
$metaTable->addRow();
$metaCell = $metaTable->addCell(9000, ['bgColor' => $colorIvoryBg]);
$metaCell->addText('IDENTITAS RISET & PENGEMBANGAN SISTEM', ['bold' => true, 'size' => 10, 'color' => $colorRed], ['spaceAfter' => 80]);
$metaCell->addText('• Pengusul & Kurator Utama  : Rio Fernandez Tamba (riotamba.com)', ['size' => 9.5, 'color' => $colorTextDark]);
$metaCell->addText('• Program Pendanaan        : Dana Indonesiana 2025 — Kementerian Kebudayaan RI & LPDP', ['size' => 9.5, 'color' => $colorTextDark]);
$metaCell->addText('• Kolaborasi Perguruan Tinggi : Politeknik Wilmar Bisnis Indonesia (WBI) x Institut Teknologi Del (IT Del)', ['size' => 9.5, 'color' => $colorTextDark]);
$metaCell->addText('• Basis Data Objek Budaya   : 71 Entitas Cagar Budaya & OPK Tionghoa Medan - Deli Serdang', ['size' => 9.5, 'color' => $colorTextDark]);
$metaCell->addText('• Domain Publikasi          : menganyamruang.id / menganyamruang.com', ['size' => 9.5, 'color' => $colorTextDark]);
$section->addTextBreak(1);

// ==========================================
// BOX TAUTAN DESAIN MOCKUP CANVA (4K PRESISI WEBSITE)
// ==========================================
$section->addTitle('TAUTAN MOCKUP DESAIN WEBSITE DI CANVA', 2);
$section->addText('Desain mockup visual website telah dibuat dalam format Widescreen 16:9 beresolusi tinggi (4K UHD 3840x2160 px aset) yang menampilkan persis keseluruhan tampilan website aktual dari Header, Hero Radial Canvas, Ringkasan Kajian, Peta Etnografis 71 Situs, Galeri Digital 5:4, Video Dokumenter, Profil Tim, Indeks Tabel, hingga Footer:');

$canvaTable = $section->addTable('CustomTable');
$canvaTable->addRow();
$canvaTable->addCell(3200, $thCellStyle)->addText('Jenis Tautan & Berkas', $thTextStyle);
$canvaTable->addCell(5800, $thCellStyle)->addText('URL / Lokasi Berkas', $thTextStyle);

$canvaTable->addRow();
$canvaTable->addCell(3200, $tdNormCellStyle)->addText('Canva Mockup Lengkap (View)', ['bold' => true, 'size' => 9.5]);
$canvaTable->addCell(5800, $tdNormCellStyle)->addLink('https://www.canva.com/d/1HyBfrUXEMIlC8U', 'https://www.canva.com/d/1HyBfrUXEMIlC8U', ['size' => 9.5, 'color' => '0055AA', 'underline' => 'single']);

$canvaTable->addRow();
$canvaTable->addCell(3200, $tdAltCellStyle)->addText('Canva Mockup Lengkap (Edit)', ['bold' => true, 'size' => 9.5]);
$canvaTable->addCell(5800, $tdAltCellStyle)->addLink('https://www.canva.com/d/DES8iCHnTnvmrz6', 'https://www.canva.com/d/DES8iCHnTnvmrz6', ['size' => 9.5, 'color' => '0055AA', 'underline' => 'single']);

$canvaTable->addRow();
$canvaTable->addCell(3200, $tdNormCellStyle)->addText('Canva Mockup Lengkap (ID)', ['bold' => true, 'size' => 9.5]);
$canvaTable->addCell(5800, $tdNormCellStyle)->addText('DAHazApXn3M (9 Halaman Full Website Mockup, 1920x1080 / Aset 4K 3840x2160)', ['size' => 9.5]);

$canvaTable->addRow();
$canvaTable->addCell(3200, $tdAltCellStyle)->addText('Berkas Lokal PDF & PNG', ['bold' => true, 'size' => 9.5]);
$canvaTable->addCell(5800, $tdAltCellStyle)->addText('dokumen/Mockup_Website_Lengkap_Arsip_Budaya.pdf & .png', ['size' => 9.5]);
$section->addTextBreak(1);

// ==========================================
// BAB I: KERANGKA KERJA PENGEMBANGAN (FRAMEWORK)
// ==========================================
$section->addTitle('BAB I: KERANGKA KERJA PENGEMBANGAN (FRAMEWORK)', 1);

$section->addTitle('1.1 Landasan Filosofis, Regulasi, dan Kebijakan Budaya', 2);
$section->addText('Pengembangan sistem arsip budaya "Menganyam Ruang" berpedoman pada kaidah pemajuan kebudayaan nasional yang diamanatkan dalam peraturan perundang-undangan:');
$section->addListItem('Undang-Undang Nomor 5 Tahun 2017 tentang Pemajuan Kebudayaan: Rujukan utama inventarisasi 10 Objek Pemajuan Kebudayaan (OPK) meliputi tradisi lisan, adat istiadat, ritus, pengetahuan tradisional, teknologi tradisional, seni, bahasa, permainan rakyat, olahraga tradisional, dan cagar budaya.', 0, null, 'bulletList');
$section->addListItem('Filosofi Multikultural Medan - Deli Serdang: Mengangkat nilai koeksistensi antara komunitas Melayu, Batak, Jawa, India, Arab, dan Tionghoa yang membentuk lanskap kebudayaan unik di Sumatera Timur.', 0, null, 'bulletList');
$section->addListItem('Skema Dana Indonesiana 2025: Dukungan Kementerian Kebudayaan RI dan LPDP dalam pelestarian arsip kebudayaan berbasis teknologi digital modern.', 0, null, 'bulletList');

$section->addTitle('1.2 Kerangka Konseptual & Kurasi 6 Kategori Utama (71 Situs)', 2);
$section->addText('Berdasarkan dokumen primer "Data Situs untuk Web.xlsx" (Sheet 1 Data Situs Fix & Sheet 2 DATA), sebanyak 71 entitas budaya telah diklasifikasikan beserta koordinat GPS presisi:');

$clusterTable = $section->addTable('CustomTable');
$clusterTable->addRow();
$clusterTable->addCell(700, $thCellStyle)->addText('No', $thTextStyle);
$clusterTable->addCell(2800, $thCellStyle)->addText('Kategori Situs Budaya', $thTextStyle);
$clusterTable->addCell(1500, $thCellStyle)->addText('Tag Warna', $thTextStyle);
$clusterTable->addCell(4000, $thCellStyle)->addText('Cakupan Objek & Lokasi', $thTextStyle);

$clusters = [
    ['1', 'Tempat Ibadah (26 Titik)', 'Merah (#C92A2A)', 'Vihara Gunung Timur, Maha Vihara Maitreya, Kelenteng Siu San Keng, Masjid Lama Gang Bengkok.'],
    ['2', 'Kuliner & Gastronomi (16 Titik)', 'Emas (#D97706)', 'Mie Khek Selat Panjang SP5, Kwetiau Ateng, Mie Tiong Sim, Chasio Asan Sun Yat Sen, Tip Top.'],
    ['3', 'Pemakaman & Cheng Beng (11 Titik)', 'Ungu (#7C3AED)', 'Kompleks Pemakaman Tionghoa Kedai Durian, Makam Tjong Yong Hian, Krematorium Deli Tua.'],
    ['4', 'Situs & Cagar Budaya (8 Titik)', 'Coklat (#92400E)', 'Rumah Tjong A Fie, Museum Situs Kota Cina, Kantor Siang Hwee, Pajak Ikan Lama, Toko Seng Hap.'],
    ['5', 'Perkampungan Bersejarah (2 Titik)', 'Hijau (#2B8A3E)', 'Perkampungan Bagan Deli, Kawasan Ruang Hidup Labuhan Deli & Taman Tjong Yong Hian.'],
    ['6', 'Pendidikan, RS & Tokoh (8 Titik)', 'Biru (#2563EB)', 'Sekolah Sutomo, RS Methodist / Delima, Perkumpulan Marga, dan Tokoh Filantropis Medan.']
];

foreach ($clusters as $i => $row) {
    $cStyle = ($i % 2 == 1) ? $tdAltCellStyle : $tdNormCellStyle;
    $clusterTable->addRow();
    $clusterTable->addCell(700, $cStyle)->addText($row[0], $tdTextStyle);
    $clusterTable->addCell(2800, $cStyle)->addText($row[1], ['bold' => true, 'size' => 9.5]);
    $clusterTable->addCell(1500, $cStyle)->addText($row[2], $tdTextStyle);
    $clusterTable->addCell(4000, $cStyle)->addText($row[3], $tdMutedStyle);
}
$section->addTextBreak(1);

$section->addTitle('1.3 Kerangka Arsitektur Sistem Informasi (3-Tier Architecture)', 2);
$section->addListItem('Presentation Layer (Frontend): Antarmuka bergaya seunghyuk.com (Hero Radial Canvas "Points, Lines, and Planes") dan aparelho.studio (Galeri Filmstrip Presisi 5:4) dengan palet gading (#FAF6EE) & coklat kayu pekat (#1C1612). Dilengkapi peta interaktif Leaflet self-hosted dan pemutar audio 3 jingle loop.', 0, null, 'bulletList');
$section->addListItem('Application Logic Layer (Backend Laravel 12 & PHP 8.2): Menerapkan pola Model-View-Controller (MVC), routing aman, serta endpoint REST API /api/sites untuk suplai data dinamis JSON.', 0, null, 'bulletList');
$section->addListItem('Data Layer (MySQL RDBMS): Tabel terstruktur cultural_sites menyimpan 71 entitas dengan koordinat decimal(10,7), administrasi Kelurahan/Kecamatan, keunikan sejarah, serta cadangan otomatis storage/app/cultural_sites.json.', 0, null, 'bulletList');

$section->addTitle('1.4 Kerangka Keamanan Informasi (Defense-in-Depth)', 2);
$secTable = $section->addTable('CustomTable');
$secTable->addRow();
$secTable->addCell(2800, $thCellStyle)->addText('Lapisan Proteksi', $thTextStyle);
$secTable->addCell(3400, $thCellStyle)->addText('Mekanisme Teknis', $thTextStyle);
$secTable->addCell(2800, $thCellStyle)->addText('Ancaman Dicegah', $thTextStyle);

$secItems = [
    ['Content Security Policy (CSP)', "base-uri 'self'; frame-ancestors 'self'; form-action 'self'", 'Clickjacking & Base Tag Hijacking.'],
    ['Anti-Open Redirect', "sanitizeUrl() memvalidasi skema http/https & menolak prefix // atau /\\", 'Pengalihan halaman ilegal (Phishing Redirect).'],
    ['DOM XSS Output Encoding', 'escapeHtml() pada seluruh injeksi string dinamis di archive.js', 'Serangan Cross-Site Scripting (XSS).'],
    ['Iframe Hardened Sandbox', 'sandbox="allow-scripts allow-same-origin allow-presentation"', 'Pembajakan navigasi window oleh iframe YouTube.'],
    ['Rel Link & HTTP Headers', 'rel="noopener noreferrer", X-Content-Type-Options: nosniff', 'Reverse Tabnabbing & MIME-sniffing.']
];

foreach ($secItems as $i => $row) {
    $cStyle = ($i % 2 == 1) ? $tdAltCellStyle : $tdNormCellStyle;
    $secTable->addRow();
    $secTable->addCell(2800, $cStyle)->addText($row[0], ['bold' => true, 'size' => 9.5]);
    $secTable->addCell(3400, $cStyle)->addText($row[1], $tdMutedStyle);
    $secTable->addCell(2800, $cStyle)->addText($row[2], $tdTextStyle);
}
$section->addTextBreak(1);

// ==========================================
// BAB II: LANGKAH KERJA PENGEMBANGAN
// ==========================================
$section->addTitle('BAB II: LANGKAH KERJA PELAKSANAAN SISTEM', 1);

$section->addTitle('2.1 Tahap 1: Inisiasi, Analisis Dokumen & Kurasi Data Excel', 2);
$section->addListItem('Memetakan struktur 5 halaman acuan dari Homepage - Chinese.pdf (Hero Canvas, Peta Etnografis, Galeri Digital 5:4, Etalase 7 Video YouTube, dan Profil Tim Kerja).', 0, null, 'bulletList');
$section->addListItem('Mengekstrak, memvalidasi koordinat lintang/bujur GPS, dan mengklasifikasikan 71 situs dari Data Situs untuk Web.xlsx.', 0, null, 'bulletList');

$section->addTitle('2.2 Tahap 2: Rekayasa Basis Data MySQL & Backend Laravel 12', 2);
$section->addListItem('Konfigurasi Laravel 12 dengan database MySQL (arsip_budaya), membuat migrasi skema tabel cultural_sites, model Eloquent, serta seeder otomatis 71 lokasi.', 0, null, 'bulletList');
$section->addListItem('Membangun ArchiveController untuk melayani rute web dan REST API JSON beserta fallback JSON.', 0, null, 'bulletList');

$section->addTitle('2.3 Tahap 3: Pengembangan Antarmuka Interaktif, GIS Leaflet & Audio Engine', 2);
$section->addListItem('Mengembangkan Canvas interaktif 8-vektor kategori ala seunghyuk.com lengkap dengan pembacaan koordinat langsung.', 0, null, 'bulletList');
$section->addListItem('Mengintegrasikan Leaflet GIS (aset self-hosted tanpa ketergantungan skrip eksternal berbahaya) dengan 71 pin bernomor #1-#71, filter sub-kategori, pencarian desa/kecamatan, label otomatis zoom >= 14, dan drawer samping ala Petraverso.', 0, null, 'bulletList');
$section->addListItem('Membangun Galeri Digital filmstrip 5:4 hitam-putih ke warna asli ala aparelho.studio beserta kartu sorotan Museum Situs Kota Cina, 7 Video Dokumenter YouTube, serta 3 Jingle Loop BackSound.', 0, null, 'bulletList');

$section->addTitle('2.4 Tahap 4: Audit Keamanan, Hardening & Uji Coba Otomatis', 2);
$section->addListItem('Menerapkan SecurityHeadersMiddleware serta melakukan sanitasi URL/DOM pada JavaScript untuk menutup celah XSS dan Open Redirect.', 0, null, 'bulletList');
$section->addListItem('Menjalankan 6 test suite pengujian otomatis PHPUnit (ArchiveWebsiteTest.php) dengan total 54 assertions yang lulus 100%.', 0, null, 'bulletList');

$section->addTitle('2.5 Tahap 5: Penyusunan Mockup Resolusi Tinggi (Canva & PDF) dan Deployment', 2);
$section->addListItem('Menghasilkan tangkapan layar website utuh beresolusi tinggi (4K UHD 3840x2160 per bagian & 2560 px full-page) untuk diunggah ke Canva dan lampiran laporan.', 0, null, 'bulletList');
$section->addListItem('Menyusun spesifikasi server produksi minimum (Cloud VPS 1 vCPU, 2 GB RAM, 20 GB SSD, Nginx, PHP 8.2-FPM, MySQL 8.0, SSL Certbot).', 0, null, 'bulletList');

// ==========================================
// BAB III: PANDUAN STYLE GUIDE & DESIGN SYSTEM
// ==========================================
$section->addTitle('BAB III: PANDUAN STYLE GUIDE & DESIGN SYSTEM', 1);

$styleTable = $section->addTable('CustomTable');
$styleTable->addRow();
$styleTable->addCell(2600, $thCellStyle)->addText('Token Desain', $thTextStyle);
$styleTable->addCell(2400, $thCellStyle)->addText('Nilai Resmi CSS', $thTextStyle);
$styleTable->addCell(4000, $thCellStyle)->addText('Penggunaan Antarmuka', $thTextStyle);

$styles = [
    ['--ivory-bg (Putih Gading)', '#FAF6EE', 'Latar belakang utama halaman & kanvas Hero.'],
    ['--ivory-surface (Krem)', '#F3ECE0', 'Permukaan kartu statistik, tabel, & latar sekunder.'],
    ['--wood-deep (Coklat Pekat)', '#1C1612', 'Latar Galeri Digital Museum, Sidebar header, & Footer.'],
    ['--chinese-red (Merah Segel)', '#C92A2A', 'Logo stempel 織, badge tim, kategori tempat ibadah.'],
    ['--gold-accent (Emas Pecinan)', '#C6922C', 'Garis aksen bingkai Museum China & label kordinat aktif.'],
    ['Font Display & Heading', 'Cinzel / Georgia Serif', 'Judul utama proyek dan heading editorial.'],
    ['Font Body & Teknis', 'Plus Jakarta Sans & JetBrains Mono', 'Teks narasi paragraf serta koordinat GPS & kode ID.']
];

foreach ($styles as $i => $row) {
    $cStyle = ($i % 2 == 1) ? $tdAltCellStyle : $tdNormCellStyle;
    $styleTable->addRow();
    $styleTable->addCell(2600, $cStyle)->addText($row[0], ['bold' => true, 'size' => 9.5]);
    $styleTable->addCell(2400, $cStyle)->addText($row[1], $tdTextStyle);
    $styleTable->addCell(4000, $cStyle)->addText($row[2], $tdMutedStyle);
}
$section->addTextBreak(1);

// ==========================================
// BAB IV: LAMPIRAN VISUAL MOCKUP WEBSITE AKTUAL
// ==========================================
$section->addPageBreak();
$section->addTitle('BAB IV: LAMPIRAN VISUAL MOCKUP WEBSITE AKTUAL', 1);
$section->addText('Berikut adalah lampiran visual antarmuka website aktual (http://127.0.0.1:9000) yang sesuai persis dengan implementasi kode program saat ini:');
$section->addTextBreak(1);

$slidesDir = __DIR__ . '/../dokumen/mockup_slides';
$slideCaptions = [
    ['01_hero.png', 'Gambar 4.1 Bagian Hero — Radial Interactive Canvas (8 Vektor Kategori) & Header Permanen'],
    ['02_ringkasan.png', 'Gambar 4.2 Bagian Ringkasan Kajian — Statistik Cagar Budaya (71, 26, 16, 11) & Narasi Multikultural'],
    ['03_peta_etnografis.png', 'Gambar 4.3 Bagian Peta Etnografis — Peta Spasial 71 Situs Medan-Deli Serdang & Sidebar Petraverso'],
    ['04_galeri_digital.png', 'Gambar 4.4 Bagian Galeri Digital — Filmstrip 5:4 Monokrom-ke-Warna & Stage Utama'],
    ['05_galeri_museum_china.png', 'Gambar 4.5 Sorotan Khusus Aset — Museum Situs Kota Cina (Medan Marelan)'],
    ['06_video_dokumenter.png', 'Gambar 4.6 Bagian Dokumentasi Video — Pemilih 7 Video Dokumenter & Pemutar YouTube Terisolasi'],
    ['07_profil_tim.png', 'Gambar 4.7 Bagian Profil Tim Kerja — Pengusul Rio Fernandez Tamba & 9 Personel Peneliti'],
    ['08_katalog.png', 'Gambar 4.8 Bagian Katalog Lengkap — Tabel Indeks 71 Situs Budaya & Aksi Peta'],
    ['09_footer.png', 'Gambar 4.9 Bagian Penutup — Baris Akhir Katalog & Footer Sponsor Dana Indonesiana 2025']
];

foreach ($slideCaptions as $item) {
    $imgPath = $slidesDir . '/' . $item[0];
    if (file_exists($imgPath)) {
        // 450 pt wide x 253 pt tall fits inside A4 printable width (approx 450 pt) cleanly in 16:9 ratio
        $section->addImage($imgPath, [
            'width' => 450,
            'height' => 253,
            'alignment' => Jc::CENTER,
        ]);
        $section->addText($item[1], ['size' => 9.5, 'italic' => true, 'color' => $colorMuted], ['alignment' => Jc::CENTER, 'spaceAfter' => 220]);
    }
}

$section->addTextBreak(1);
$section->addText('Medan, November 2026', ['size' => 10, 'italic' => true], ['alignment' => Jc::RIGHT]);
$section->addText('Tim Peneliti & Pengembang Sistem "Menganyam Ruang"', ['size' => 10, 'bold' => true], ['alignment' => Jc::RIGHT]);
$section->addText('Politeknik Wilmar Bisnis Indonesia x Institut Teknologi Del', ['size' => 9.5, 'color' => $colorMuted], ['alignment' => Jc::RIGHT]);

// Output directory check
$outputDir = __DIR__ . '/../dokumen';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$outputFile = $outputDir . '/Kerangka_Kerja_dan_Langkah_Kerja_Website_Arsip_Budaya.docx';
echo "Saving Word document to: $outputFile\n";

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save($outputFile);

echo "Successfully generated clean DOCX: $outputFile\n";
