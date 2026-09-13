<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageComponent;
use Illuminate\Http\Request;

class PackageComponentController extends Controller
{
    public function store(Request $request, Package $package)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'is_optional' => 'boolean',
            'sort_order'  => 'integer',
        ]);

        $package->components()->create([
            'name'        => $request->name,
            'icon'        => $request->icon,
            'description' => $request->description,
            'price'       => $request->price,
            'is_optional' => $request->boolean('is_optional', true),
            'sort_order'  => $request->sort_order ?? $package->components()->count(),
        ]);

        return redirect()->route('admin.packages.edit', $package)
            ->with('success', 'Komponen layanan berhasil ditambahkan.');
    }

    public function update(Request $request, PackageComponent $component)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'icon'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'is_optional' => 'boolean',
        ]);

        $component->update([
            'name'        => $request->name,
            'icon'        => $request->icon,
            'description' => $request->description,
            'price'       => $request->price,
            'is_optional' => $request->boolean('is_optional', true),
        ]);

        return redirect()->route('admin.packages.edit', $component->package)
            ->with('success', 'Komponen berhasil diperbarui.');
    }

    public function destroy(PackageComponent $component)
    {
        $package = $component->package;
        $component->delete();

        return redirect()->route('admin.packages.edit', $package)
            ->with('success', 'Komponen berhasil dihapus.');
    }
}
