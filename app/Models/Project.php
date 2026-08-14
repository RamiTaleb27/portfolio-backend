<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model {
    protected $fillable = [
        'name', 'slug', 'tagline', 'description', 'image',
        'featured', 'status', 'tags', 'live_url', 'github_url', 'sort_order'
    ];
    protected $casts = [
        'featured' => 'boolean',
        'tags' => 'array',
    ];
}