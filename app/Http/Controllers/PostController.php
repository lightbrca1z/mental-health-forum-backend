<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    public function index()
    {
        try {
            Log::info('Fetching posts from database');
            
            // データベース接続をテスト
            DB::connection()->getPdo();
            Log::info('Database connection successful');
            
            // テーブルの存在を確認
            if (!Schema::hasTable('posts')) {
                Log::error('Posts table does not exist');
                return response()->json([
                    'error' => 'Database table not found',
                    'message' => 'Posts table does not exist. Please run migrations.',
                    'suggestion' => 'Run: php artisan migrate'
                ], 500);
            }
            
            // テーブルの構造を確認
            $columns = Schema::getColumnListing('posts');
            Log::info('Posts table columns:', $columns);
            
            $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();
            Log::info('Posts fetched successfully', ['count' => $posts->count()]);
            
            return response()->json($posts);
        } catch (\PDOException $e) {
            Log::error('Database connection error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'code' => $e->getCode()
            ]);
            
            return response()->json([
                'error' => 'Database connection failed',
                'message' => $e->getMessage(),
                'type' => 'PDOException',
                'code' => $e->getCode(),
                'suggestion' => 'Check database configuration and connection'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch posts',
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'debug' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|in:転職,病気,薬,生活,雑談',
                'author' => 'required|string|max:255',
            ]);

            $post = Post::create($validated);
            Log::info('Post created successfully', ['post_id' => $post->id]);
            
            return response()->json($post, 201);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to create post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            $postWithComments = $post->load('comments');
            return response()->json($postWithComments);
        } catch (\Exception $e) {
            Log::error('Error fetching post: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to fetch post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            $validated = $request->validate([
                'title' => 'string|max:255',
                'content' => 'string',
                'category' => 'in:転職,病気,薬,生活,雑談',
                'author' => 'string|max:255',
            ]);

            $post->update($validated);
            Log::info('Post updated successfully', ['post_id' => $post->id]);
            
            return response()->json($post);
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to update post',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            Log::info('Post deleted successfully', ['post_id' => $post->id]);
            
            return response()->json(['message' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Failed to delete post',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 