<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = [
        'sort_order',
        'no',
        'name',
        'institution',
        'role',
        'is_proposer',
        'website',
        'website_label',
        'bio',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_proposer' => 'boolean',
    ];
}
