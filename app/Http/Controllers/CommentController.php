<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'author' => 'required|string|max:255',
        ]);

        $comment = $post->comments()->create($validated);
        return $comment;
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'string',
            'author' => 'string|max:255',
        ]);

        $comment->update($validated);
        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
} 