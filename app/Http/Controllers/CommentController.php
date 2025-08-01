<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'author' => 'required|string|max:255',
            ]);

            $comment = $post->comments()->create($validated);
            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create comment',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Comment $comment)
    {
        try {
            $validated = $request->validate([
                'content' => 'string',
                'author' => 'string|max:255',
            ]);

            $comment->update($validated);
            return response()->json($comment);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update comment',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete comment',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 