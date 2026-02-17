<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleLike(Request $request, string $type, int $id)
    {
        $user = Auth::user();
        $model = $this->getModel($type, $id);

        $like = $model->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        } else {
            $model->likes()->create(['user_id' => $user->id]);
            $status = 'liked';
        }

        return back()->with('status', "Item $status successfully.");
    }

    public function storeComment(Request $request, string $type, int $id)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $model = $this->getModel($type, $id);

        $model->comments()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        return back()->with('status', 'Comment added successfully.');
    }

    public function deleteComment(Comment $comment)
    {
        $user = Auth::user();

        if ($comment->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('status', 'Comment deleted successfully.');
    }

    protected function getModel(string $type, int $id)
    {
        return match ($type) {
            'song' => Song::findOrFail($id),
            'album' => Album::findOrFail($id),
            'playlist' => Playlist::findOrFail($id),
            default => abort(404),
        };
    }
}
