<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // GET /board 一覧（最新20件）
    public function index()
    {
        $boards = DB::select("
            SELECT b.id, b.name, b.created_at, b.user_id,
                u.name AS owner_name
            FROM boards b
            LEFT JOIN users u ON u.id = b.user_id
            ORDER BY b.created_at DESC
            LIMIT 20
        ");
        return view('board.index', ['boards' => $boards]);
    }

    // GET /board/new 作成画面
    public function create()
    {
        return view('board.create');
    }

    // POST /board 掲示板を作成（直書きSQL、RETURNINGでid取得）
    public function store(Request $req)
    {
        $v = $req->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $uid = Auth::id(); // 未ログインならnullで作成可
        $row = DB::selectOne(
            'INSERT INTO boards (name, user_id, created_at, updated_at)
             VALUES (?, ?, NOW(), NOW())
             RETURNING id',
            [$v['name'], $uid]
        );

        return redirect()->route('board.index')
            ->with('status', '掲示板を作成しました（ID: ' . $row->id . '）');
    }

    public function show(int $id)
    {
        $board = DB::selectOne(
            'SELECT b.id, b.name, b.user_id, b.created_at, u.name AS owner_name
            FROM boards b
            LEFT JOIN users u ON u.id = b.user_id
            WHERE b.id = ?
            LIMIT 1',
            [$id]
        );
        if (!$board) abort(404);

        $posts = DB::select("
            SELECT
                p.id,
                p.board_id,
                p.user_id,
                u.name AS user_name,
                p.content AS body,         
                p.created_at,
                p.image_path
            FROM posts p
            JOIN users u ON u.id = p.user_id
            WHERE p.board_id = ?
            ORDER BY p.created_at ASC
            ", [$id]);

        return view('board.show', ['board' => $board, 'posts' => $posts]);
    }

    // DELETE /board/{id} 掲示板削除（作成ユーザーのみ）
    public function destroy(int $id)
    {
        $uid = Auth::id();  // ← 先頭の use Auth が効く
        if (!$uid) {
            return redirect()->route('login.create')->withErrors(['auth' => 'ログインが必要です。']);
        }

        // 自分が作成者のときだけ削除
        $deleted = DB::delete(
            'DELETE FROM boards WHERE id = ? AND user_id = ?',
            [$id, $uid]
        );

        if ($deleted === 1) {
            return redirect()->route('board.index')->with('status', '掲示板を削除しました。');
        }
        return back()->withErrors(['delete' => '削除権限がありません。作成ユーザーのみ削除できます。']);
    }
}
