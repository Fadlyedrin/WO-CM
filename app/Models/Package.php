<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Package extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'image_url', 'is_available'];
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(PackageImage::class); }
    public function components() { return $this->hasMany(PackageComponent::class)->orderBy('sort_order'); }
}