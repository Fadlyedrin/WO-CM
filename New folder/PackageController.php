<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Package;
use App\Models\PackageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    // Admin functions
    public function index()
    {
        $packages = Package::with('category')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $package = Package::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'is_available' => $request->has('is_available'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('packages', 'public');
                PackageImage::create([
                    'package_id' => $package->id,
                    'image_path' => $path,
                ]);
                // Keep the first image as the primary image_url for the main thumbnail
                if ($index === 0) {
                    $package->update(['image_url' => $path]);
                }
            }
        }

        if ($request->has('components') && is_array($request->components)) {
            foreach ($request->components as $comp) {
                if (!empty($comp['name']) && isset($comp['price'])) {
                    \App\Models\PackageComponent::create([
                        'package_id' => $package->id,
                        'name' => $comp['name'],
                        'icon' => $comp['icon'] ?? 'bi-star',
                        'price' => $comp['price'],
                        'description' => $comp['description'] ?? null,
                        'is_optional' => $comp['is_optional'] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        $categories = Category::all();
        $package->load('images', 'components');
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $package->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'is_available' => $request->has('is_available'),
        ]);

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = PackageImage::find($imageId);
                if ($img && $img->package_id == $package->id) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($img->image_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
                    }
                    $img->delete();
                }
            }
            // If primary image_url was deleted, update it
            if ($package->images()->count() > 0) {
                $package->update(['image_url' => $package->images()->first()->image_path]);
            } else {
                $package->update(['image_url' => null]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('packages', 'public');
                PackageImage::create([
                    'package_id' => $package->id,
                    'image_path' => $path,
                ]);
                // If there's no primary image_url, set it
                if (!$package->image_url) {
                    $package->update(['image_url' => $path]);
                }
            }
        }

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        foreach($package->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        if ($package->image_url && !Str::startsWith($package->image_url, 'http') && Storage::disk('public')->exists($package->image_url)) {
            Storage::disk('public')->delete($package->image_url);
        }
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus.');
    }

    // Public functions
    public function publicIndex(Request $request)
    {
        $query = Package::where('is_available', true);
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        $packages = $query->get();
        $categories = Category::all();

        return view('packages.index', compact('packages', 'categories'));
    }

    public function show(Package $package)
    {
        if (!$package->is_available) {
            abort(404);
        }
        $package->load('images', 'components');
        return view('packages.show', compact('package'));
    }

    public function builder(Package $package)
    {
        if (!$package->is_available) {
            abort(404);
        }
        $package->load('images', 'components');
        return view('packages.builder', compact('package'));
    }
}