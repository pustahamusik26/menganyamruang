<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (
            $this->app->environment('production') ||
            request()->isSecure() ||
            request()->header('X-Forwarded-Proto') === 'https'
        ) {
            URL::forceScheme('https');
        }

        // Ensure storage/app/cultural_sites.json always exists (prevents FileNotFoundException on fresh deploy)
        try {
            $storageJson = storage_path('app/cultural_sites.json');
            $dbDataJson = database_path('data/cultural_sites.json');
            if (!File::exists($storageJson) && File::exists($dbDataJson)) {
                File::ensureDirectoryExists(dirname($storageJson));
                File::copy($dbDataJson, $storageJson);
            }

            // If Hostinger splits laravel_app/public and public_html, auto-sync updated CSS, JS, Logos & Audio to DOCUMENT_ROOT
            $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
            if ($docRoot && is_dir($docRoot) && realpath($docRoot) !== realpath(base_path('public'))) {
                $syncFiles = [
                    'css/archive.css',
                    'css/admin.css',
                    'css/leaflet.css',
                    'js/archive.js',
                    'js/leaflet.js',
                    'favicon.ico',
                    'images/logos/icon-menganyam-ruang-512.png',
                    'images/logos/icon-menganyam-ruang-192.png',
                    'audio/opening-page.wav',
                    'audio/main-page.wav',
                    'audio/content-page.wav',
                    'audio/jingle-1.wav',
                    'audio/jingle-2.wav',
                    'audio/jingle-3.wav',
                ];
                foreach ($syncFiles as $rel) {
                    $src = base_path('public/' . $rel);
                    $dst = rtrim($docRoot, '/\\') . '/' . $rel;
                    if (File::exists($src) && (!File::exists($dst) || File::size($src) !== File::size($dst))) {
                        File::ensureDirectoryExists(dirname($dst));
                        @File::copy($src, $dst);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore filesystem restrictions if any
        }
    }
}
