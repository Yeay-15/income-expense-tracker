<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withCount('transactions')->orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleRole(User $user)
    {
        $this->guardSelf($user);

        $user->update(['role' => $user->role === 'admin' ? 'user' : 'admin']);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    public function toggleBan(User $user)
    {
        $this->guardSelf($user);

        $user->update(['is_banned' => ! $user->is_banned]);

        return back()->with('success', $user->is_banned
            ? "{$user->name} berhasil di-suspend."
            : "{$user->name} berhasil diaktifkan kembali.");
    }

    public function resetPassword(User $user)
    {
        $temporaryPassword = Str::random(10);
        $user->update(['password' => Hash::make($temporaryPassword)]);

        return back()->with('temp_password', $temporaryPassword)
            ->with('success', "Password {$user->name} berhasil direset.");
    }

    private function guardSelf(User $user): void
    {
        if ($user->id === Auth::id()) {
            abort(403, 'Tidak bisa mengubah akun Anda sendiri lewat panel ini.');
        }
    }
}
