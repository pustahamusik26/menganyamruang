<?php

namespace Tests\Feature;

use App\Models\CulturalSite;
use App\Models\DocumentaryVideo;
use App\Models\User;
use Database\Seeders\AdminAndContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AdminAndContentSeeder::class);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_with_configured_credentials(): void
    {
        $user = User::where('username', 'admin')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('admin@1231', $user->password));

        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'admin@1231',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_access_all_dashboard_tabs_and_crud(): void
    {
        $admin = User::where('username', 'admin')->firstOrFail();

        $this->actingAs($admin)->get('/admin?tab=situs')->assertOk();
        $this->actingAs($admin)->get('/admin?tab=video')->assertOk();
        $this->actingAs($admin)->get('/admin?tab=tim')->assertOk();
        $this->actingAs($admin)->get('/admin?tab=akun')->assertOk();
        $this->actingAs($admin)->get('/admin/sites/create')->assertOk();

        // Create Site
        $storeResp = $this->actingAs($admin)->post('/admin/sites', [
            'marker_number' => 72,
            'site_code' => 'ARC-072',
            'name' => 'Klenteng Uji Admin',
            'category' => 'Tempat Ibadah',
            'sub_category' => 'Klenteng',
            'gallery_group' => 'Rumah Ibadah',
            'address' => 'Jl. Kesawan No. 10',
            'desa_kelurahan' => 'Kesawan',
            'kecamatan' => 'Medan Barat',
            'regency_city' => 'Kota Medan',
            'latitude' => 3.5852,
            'longitude' => 98.6755,
            'uniqueness' => 'Situs pengujian admin.',
            'is_featured_gallery' => 1,
        ]);

        $storeResp->assertRedirect('/admin?tab=situs');
        $site = CulturalSite::where('site_code', 'ARC-072')->first();
        $this->assertNotNull($site);

        // Edit page & Update Site
        $this->actingAs($admin)->get("/admin/sites/{$site->id}/edit")->assertOk();
        $this->actingAs($admin)->put("/admin/sites/{$site->id}", [
            'marker_number' => 72,
            'site_code' => 'ARC-072',
            'name' => 'Klenteng Uji Admin Diperbarui',
            'category' => 'Tempat Ibadah',
            'gallery_group' => 'Rumah Ibadah',
            'is_featured_gallery' => 1,
        ])->assertRedirect('/admin?tab=situs');

        $this->assertEquals('Klenteng Uji Admin Diperbarui', $site->fresh()->name);

        // Delete Site
        $this->actingAs($admin)->delete("/admin/sites/{$site->id}")->assertRedirect('/admin?tab=situs');
        $this->assertDatabaseMissing('cultural_sites', ['id' => $site->id]);

        // Create Video
        $this->actingAs($admin)->post('/admin/videos', [
            'code' => 'VID-08',
            'sort_order' => 8,
            'title' => 'Dokumenter Uji Admin',
            'location_subtitle' => 'Kota Medan',
            'category' => 'Rumah Ibadah',
            'youtube_input' => 'https://www.youtube.com/watch?v=9No-FiEInLA',
            'duration' => '04:20',
            'is_active' => 1,
        ])->assertRedirect('/admin?tab=video');

        $this->assertNotNull(DocumentaryVideo::where('code', 'VID-08')->first());
    }
}
