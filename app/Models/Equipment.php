<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';
    
    protected $fillable = [
        'name',
        'equipment_type',
        'categoryType',
        'brand',
        'description',
        'manufacturer',
        'weight',
        'series',
        'dimension',
        'queue',
        'file',
        'images',
        'blog_type',
        'site_type',
    ];

    protected $casts = [
        'dimension' => 'array',
        'images' => 'array',
        'blog_type' => 'array',
        'site_type' => 'array',
        'equipment_type' => 'array',
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class,'blog_equipment');
    }

    public static function getByType($type, $limit)
    {
        return self::where('equipment_type', $type)
            ->orderBy('queue', 'desc')
            ->select('images', 'name', 'description')
            ->limit($limit)
            ->get();
    }
}
