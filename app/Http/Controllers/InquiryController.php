<?php
namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    // Public store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        Inquiry::create($request->all());

        return redirect()->back()->with('success', 'Terima kasih atas pesannya! Kami akan segera menghubungi Anda.');
    }

    // Admin Index
    public function index()
    {
        $inquiries = Inquiry::latest()->get();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    // Admin Show
    public function show(Inquiry $inquiry)
    {
        if (!$inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }
        return view('admin.inquiries.show', compact('inquiry'));
    }

    // Admin Mark as Read
    public function markAsRead(Inquiry $inquiry)
    {
        $inquiry->update(['is_read' => true]);
        return redirect()->route('admin.inquiries.index')->with('success', 'Pesan ditandai sudah dibaca.');
    }

    // Admin Destroy
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Pesan berhasil dihapus.');
    }
}