<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:転職,病気,薬,生活,雑談',
            'author' => 'required|string|max:255',
        ]);

        $post = Post::create($validated);
        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post->load('comments'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'category' => 'in:転職,病気,薬,生活,雑談',
            'author' => 'string|max:255',
        ]);

        $post->update($validated);
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
} 