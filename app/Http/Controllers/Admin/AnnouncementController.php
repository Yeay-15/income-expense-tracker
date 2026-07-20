<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        Announcement::create($validated);

        return back()->with('success', 'Pengumuman berhasil dipublikasikan.');
    }

    public function toggleActive(Announcement $announcement)
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);
        return back()->with('success', 'Status pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}
