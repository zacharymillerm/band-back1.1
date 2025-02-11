<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    // Define the table name if different from 'teams'
    protected $table = 'teams';

    protected $fillable = [
        'tag1',
        'tag2',
        'tag3',
        'tag4',
        'tag5',
        'tag6',
        'tag7',
        'tag8',
        'competencies',
        'links',
        'avatar',
        'teamPic',
    ];

    protected $casts = [
        'competencies' => 'array', // Cast competencies as array to handle JSON structure
    ];
}
