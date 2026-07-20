<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryManagementController extends Controller
{
    public function index()
    {
        // Perubahan: Dikelompokkan berdasarkan tipe (agar view tidak error),
        // lalu "Lainnya" ditaruh paling bawah, sisanya sesuai abjad
        $categories = Category::whereNull('user_id')
            ->orderBy('type')
            ->orderByRaw("CASE WHEN name = 'Lainnya' THEN 1 ELSE 0 END")
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        $validated['user_id'] = null; // eksplisit: ini kategori default/global
        Category::create($validated);

        return back()->with('success', 'Kategori default berhasil ditambahkan.');
    }

    public function destroy(Category $category)
    {
        if (! is_null($category->user_id)) {
            abort(403, 'Ini bukan kategori default.');
        }

        if ($category->transactions()->exists()) {
            return back()->with('error', 'Tidak bisa menghapus kategori default yang sudah dipakai transaksi.');
        }

        $category->delete();
        return back()->with('success', 'Kategori default berhasil dihapus.');
    }
}
