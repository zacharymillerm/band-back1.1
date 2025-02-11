<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'name',
        'blog_type',
        'startDate',
        'endDate',
        'guests',
        'venue',
        'video',
        'images',
        'tags',
        'checkbox',
        'cities',
        'features',
        'queue',
        'solution',
        'title',
        'description',
        'keyword',
        'three_id',
        'site_id',
        'checked',
        'equipment_type',
        'site_type',
        'eventTitle',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'checkbox' => 'array',
        'cities' => 'array',
        'solution' => 'array',
        'blog_type' => 'array',
        'site_type' => 'array',
        'equipment_type' => 'array',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'blog_equipment');
    }


    public function three()
    {
        return $this->belongsTo(Three::class, 'three_id');
    }
}
