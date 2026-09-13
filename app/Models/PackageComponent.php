<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageComponent extends Model
{
    protected $fillable = [
        'package_id', 'name', 'icon', 'description',
        'price', 'is_optional', 'sort_order'
    ];

    protected $casts = [
        'is_optional' => 'boolean',
        'price' => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
