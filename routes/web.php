<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\PostsController;


// ルートアクセス時はログイン画面へ
Route::get('/', function () {
    return redirect()->route('login');
});

// ========================
// 認証不要ルート
// ========================
Route::get('/signup', [UsersController::class, 'create'])->name('signup.create'); // サインアップ表示
Route::post('/signup', [UsersController::class, 'store'])->name('signup.store');  // サインアップ登録

Route::get('/login',  [SessionsController::class, 'create'])->name('login');  // ログイン表示
Route::post('/login', [SessionsController::class, 'store'])->name('login.store');    // ログイン実行
Route::post('/logout', [SessionsController::class, 'destroy'])->name('logout');      // ログアウト


// ========================
// 認証必須ルート
// ========================
Route::middleware('auth')->group(function () {
    // 掲示板
    Route::get('/board', [BoardController::class, 'index'])->name('board.index');
    Route::get('/board/new', [BoardController::class, 'create'])->name('board.create');
    Route::post('/board', [BoardController::class, 'store'])->name('board.store');
    Route::get('/board/{id}', [BoardController::class, 'show'])->name('board.show');
    Route::delete('/board/{id}', [BoardController::class, 'destroy'])->name('board.destroy');

    // 投稿
    Route::post('/board/{id}/posts', [PostsController::class, 'store'])->name('posts.store');
    Route::delete('/board/{boardId}/posts/{postId}', [PostsController::class, 'destroy'])->name('posts.destroy');
});
