<?php

namespace Database\Seeders;

use App\Models\CulturalSite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CulturalSiteSeeder extends Seeder
{
    public function run(): void
    {
        CulturalSite::query()->delete();

        $jsonPath = storage_path('app/cultural_sites.json');
        $items = json_decode(File::get($jsonPath), true);

        foreach ($items as $item) {
            CulturalSite::create($item);
        }
    }
}
