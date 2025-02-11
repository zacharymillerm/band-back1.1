<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rentals';

    protected $fillable = [
        'cost',
        'files',
    ];

    protected $casts = [
        'files' => 'array', 
    ];
}
