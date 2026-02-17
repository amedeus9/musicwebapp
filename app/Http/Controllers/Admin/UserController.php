<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::withCount(['songs', 'playlists'])->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('Admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Prevent deleting your own account
        if ($user->id === $authUser->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account!');
        }

        // Delete user's songs and associated files
        foreach ($user->songs as $song) {
            if ($song->cover_path) {
                Storage::disk('public')->delete($song->cover_path);
            }
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            $song->delete();
        }

        // Delete user's playlists
        $user->playlists()->delete();

        // Delete user
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
