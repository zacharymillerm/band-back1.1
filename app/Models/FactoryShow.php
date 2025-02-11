<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryShow extends Model
{
    use HasFactory;

    // Define the table name if different from 'factory_shows'
    protected $table = 'factory_shows';

    protected $fillable = [
        'title',
        'video',
        'queue',
        'description',
        'links',
    ];
}
