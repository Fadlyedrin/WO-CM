<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->get();
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        if ($request->has('code')) {
            $request->merge(['code' => strtoupper($request->code)]);
        }

        $request->validate([
            'code' => 'required|string|unique:promos,code',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
        ]);

        Promo::create($request->all());
        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        if ($request->has('code')) {
            $request->merge(['code' => strtoupper($request->code)]);
        }

        $request->validate([
            'code' => 'required|string|unique:promos,code,' . $promo->id,
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
        ]);

        $promo->update($request->all());
        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus.');
    }

    public function validatePromo(Request $request)
    {
        if ($request->has('code')) {
            $request->merge(['code' => strtoupper($request->code)]);
        }

        $request->validate(['code' => 'required|string', 'package_price' => 'required|numeric']);
        
        $promo = Promo::where('code', $request->code)->where('is_active', true)->first();
        
        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Kode promo tidak valid atau tidak aktif.']);
        }

        if ($promo->valid_until && $promo->valid_until->isPast()) {
            return response()->json(['valid' => false, 'message' => 'Kode promo sudah kadaluarsa.']);
        }

        if ($promo->max_uses && $promo->uses >= $promo->max_uses) {
            return response()->json(['valid' => false, 'message' => 'Kuota kode promo sudah habis.']);
        }

        $discount = 0;
        if ($promo->discount_type === 'fixed') {
            $discount = $promo->discount_value;
        } else {
            $discount = ($promo->discount_value / 100) * $request->package_price;
        }

        if ($discount > $request->package_price) {
            $discount = $request->package_price;
        }

        return response()->json([
            'valid' => true,
            'promo_id' => $promo->id,
            'discount_amount' => $discount,
            'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'message' => 'Promo berhasil digunakan!'
        ]);
    }
}
