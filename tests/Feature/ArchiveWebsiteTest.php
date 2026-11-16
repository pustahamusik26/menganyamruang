<?php

namespace Tests\Feature;

use App\Models\CulturalSite;
use Database\Seeders\CulturalSiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiveWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CulturalSiteSeeder::class);
    }

    public function test_homepage_renders_all_required_content_from_pdf(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Page 1 requirements
        $response->assertSee('Kajian Objek Pemajuan Kebudayaan dan Cagar Budaya');
        $response->assertSee('Arsip Anyaman: Kebudayaan Tionghoa Medan');
        $response->assertSee('Menganyam Ruang mendokumentasikan dan memetakan ekspresi kebudayaan Tionghoa di Kota Medan');
        $response->assertSee('Medan tumbuh sebagai kota multikultural, tempat komunitas Melayu, Batak, Jawa, India, Arab, dan Tionghoa hidup berdampingan');
        $response->assertSee('Menganyam Ruang dan Warna Ekspresi Kebudayaan Tionghoa dalam Kota Multikultural Medan');
        $response->assertSee('Dukungan kegiatan oleh Kementerian Kebudayaan: Dana Indonesiana 2025');
        $response->assertSee('Politeknik Wilmar Bisnis Indonesia');
        $response->assertSee('Institut Teknologi Del');

        // Navigation & Sections (Pages 2-5)
        $response->assertSee('1. Peta Etnografis');
        $response->assertSee('2. Galeri Digital');
        $response->assertSee('3. Dokumentasi Video');
        $response->assertSee('4. Profil Tim Kerja Web');

        // Team & Proposer (Pages 4-5)
        $response->assertSee('Rio Fernandez Tamba');
        $response->assertSee('riotamba.com');
        $response->assertSee('Chandro Pardede');
        $response->assertSee('Rizky Ramadhansyah');
        $response->assertSee('Muhammad Roestian Fahmi');
        $response->assertSee('Septian Simatupang');
        $response->assertSee('Viqki Maulana');
        $response->assertSee('Dafi Alkhafis Siregar');
        $response->assertSee('Hajutomar Rahel M. Sinaga');
        $response->assertSee('Alvin Limtan Winata');
        $response->assertSee('Alfi Armansyah');
    }

    public function test_all_excel_sites_are_seeded_with_coordinates_and_admin_areas(): void
    {
        $count = CulturalSite::count();
        $this->assertEquals(71, $count);

        $missingCoords = CulturalSite::whereNull('latitude')->orWhereNull('longitude')->count();
        $this->assertEquals(0, $missingCoords);

        $missingDesa = CulturalSite::whereNull('desa_kelurahan')->orWhereNull('kecamatan')->count();
        $this->assertEquals(0, $missingDesa);
    }

    public function test_subroutes_and_api_endpoints_work(): void
    {
        $this->get('/peta-etnografis')->assertStatus(200);
        $this->get('/galeri-digital')->assertStatus(200);
        $this->get('/video')->assertStatus(200);
        $this->get('/profil-tim')->assertStatus(200);

        $apiResponse = $this->getJson('/api/sites?category=Tempat+Ibadah');
        $apiResponse->assertStatus(200)
            ->assertJsonPath('total', 26);

        $detailResponse = $this->getJson('/api/sites/ARC-001');
        $detailResponse->assertStatus(200)
            ->assertJsonPath('data.site_code', 'ARC-001');
    }

    public function test_security_headers_and_injection_immunity(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("base-uri 'self'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
        $this->assertStringContainsString("form-action 'self'", $csp);

        // Test with XSS payload in search query parameter
        $xssResponse = $this->getJson('/api/sites?q=' . urlencode('<script>alert("xss")</script>'));
        $xssResponse->assertStatus(200)
            ->assertJsonPath('total', 0);

        // Test with SQL Injection payload in query parameter
        $sqliResponse = $this->getJson('/api/sites?q=' . urlencode("' OR '1'='1' -- "));
        $sqliResponse->assertStatus(200)
            ->assertJsonPath('total', 0);
    }
}
