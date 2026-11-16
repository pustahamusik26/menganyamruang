<?php

namespace Database\Seeders;

use App\Models\CulturalSite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CulturalSiteSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = storage_path('app/cultural_sites.json');
        if (!File::exists($jsonPath)) {
            $jsonPath = database_path('data/cultural_sites.json');
        }

        if (!File::exists($jsonPath)) {
            return;
        }

        $items = json_decode(File::get($jsonPath), true) ?? [];
        if (empty($items)) {
            return;
        }

        CulturalSite::query()->delete();

        foreach ($items as $item) {
            CulturalSite::create($item);
        }
    }
}
