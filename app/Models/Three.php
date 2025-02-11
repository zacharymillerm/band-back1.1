<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Three extends Model
{
    use HasFactory;

    protected $table = 'threes';

    protected $fillable = [
        'title1',
        'content1',
        'title2',
        'content2',
        'video',
        'links',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class); 
    }
}
