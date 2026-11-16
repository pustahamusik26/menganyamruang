<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalSite extends Model
{
    protected $fillable = [
        'marker_number',
        'site_code',
        'name',
        'maps_name',
        'category',
        'sub_category',
        'gallery_group',
        'color_name',
        'color_hex',
        'year_period',
        'address',
        'desa_kelurahan',
        'kecamatan',
        'regency_city',
        'latitude',
        'longitude',
        'uniqueness',
        'source_name',
        'source_url',
        'gmaps_url',
        'thumbnail',
        'photos',
        'is_featured_gallery',
        'sheet_origin',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'photos' => 'array',
        'is_featured_gallery' => 'boolean',
    ];
}
