<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Define the table name if different from 'reviews'
    protected $table = 'reviews';

    protected $fillable = [
        'type',
        'name',
        'file',
        'content',
        'displayType',
        'queue',
        'avatar',
    ];

    protected $casts = [
        'displayType' => 'array', // Cast displayType as array to handle JSON structure
    ];
}
