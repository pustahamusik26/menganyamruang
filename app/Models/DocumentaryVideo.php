<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentaryVideo extends Model
{
    protected $fillable = [
        'sort_order',
        'code',
        'title',
        'location_subtitle',
        'category',
        'youtube_id',
        'youtube_url',
        'embed_url',
        'thumbnail',
        'duration',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function extractYoutubeId(string $input): string
    {
        $input = trim($input);
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }
        if (preg_match('~(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([a-zA-Z0-9_-]{11})~i', $input, $matches)) {
            return $matches[1];
        }
        return '9No-FiEInLA';
    }
}
