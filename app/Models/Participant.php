<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    // Define the table name if different from 'participants'
    protected $table = 'participants';

    protected $fillable = [
        'image',
        'name',
        'link'
    ];
}
