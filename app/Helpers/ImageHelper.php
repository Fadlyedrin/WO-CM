<?php
namespace App\Helpers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageHelper {
    public static function getUrl($path) {
        if (!$path) return '';
        if (Str::startsWith($path, 'http')) return $path;
        return Storage::url($path);
    }
}