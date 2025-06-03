<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // テスト投稿を作成
        $post = Post::create([
            'title' => '双極性障害について',
            'content' => '最近、双極性障害の症状が悪化してきています。特に睡眠のリズムが崩れやすく、困っています。',
            'category' => '病気',
            'author' => 'ユーザー1',
        ]);

        // テストコメントを作成
        Comment::create([
            'content' => '私も同じような症状で悩んでいます。',
            'author' => 'ユーザー2',
            'post_id' => $post->id,
        ]);

        // 別のテスト投稿を作成
        Post::create([
            'title' => 'リチウムの副作用について',
            'content' => 'リチウムを服用し始めてから、手の震えが気になります。',
            'category' => '薬',
            'author' => 'ユーザー3',
        ]);
    }
}
