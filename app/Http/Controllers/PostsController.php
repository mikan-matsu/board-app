<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    // app/Http/Controllers/PostsController.php
    public function store(Request $request, int $id)
    {
        // 本文または画像のどちらか必須にする
        $validated = $request->validate([
            'content' => ['nullable','string','max:2000','required_without:image'],
            'image'   => ['nullable','image','mimes:jpeg,png,webp','max:2048','required_without:content'],
        ], [
            'content.required_without' => '本文か画像のどちらかは必須です。',
            'image.required_without'   => '本文か画像のどちらかは必須です。',
        ]);

        // 画像保存（あれば）
        $imagePath = null;
        if ($request->hasFile('image')) {
            // publicディスクの images/ 配下に保存（DBには 'images/xxx.webp' の形で入る）
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // DBのcontentはNULL不可なら空文字でOKにする
        $content = $validated['content'] ?? '';

        \App\Models\Post::create([
            'board_id'   => $id,
            'user_id'    => \Auth::id(),
            'name'       => \Auth::user()->name,
            'content'    => $content,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('board.show', ['id' => $id])->with('status', '投稿しました');
    }



    // DELETE /board/{boardId}/posts/{postId}
    public function destroy(Request $req, int $boardId, int $postId)
    {
        // 投稿を取得
        $post = DB::selectOne(
            'SELECT id, board_id, user_id, image_path FROM posts WHERE id = ? AND board_id = ? LIMIT 1',
            [$postId, $boardId]
        );
        if (!$post) {
            abort(404);
        }

        // 権限確認: 投稿者本人のみ
        if ($post->user_id !== Auth::id()) {
            abort(403, '削除権限がありません。');
        }

        // 画像削除
        if (!empty($post->image_path)) {
            $rel = ltrim($post->image_path, '/'); // storage/images/xxx
            if (str_starts_with($rel, 'storage/')) {
                $rel = substr($rel, strlen('storage/')); // images/xxx
            }
            Storage::disk('public')->delete($rel);
        }

        // 投稿削除
        DB::delete('DELETE FROM posts WHERE id = ? AND board_id = ?', [$postId, $boardId]);

        return redirect()->route('board.show', ['id' => $boardId])->with('status', '投稿を削除しました。');
    }
}
