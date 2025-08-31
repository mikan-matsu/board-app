<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;    // 直書きSQL
use Illuminate\Support\Facades\Hash;  // パスワード検証
use Illuminate\Support\Facades\Auth;  // ログイン/ログアウト

class SessionsController extends Controller
{
    // GET /login
    public function create()
    {
        return view('auth.login');
    }

    // POST /login（直書きSQLでユーザー取得→Hash検証→ログイン）
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = DB::selectOne(
            'select id, password, name, email from users where email = ? limit 1',
            [$credentials['email']]
        );

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'メールまたはパスワードが正しくありません。'])
                ->onlyInput('email');
        }

        Auth::loginUsingId($user->id);  // rememberは今回は未対応（必要なら第2引数true）
        return redirect()->intended('/board')->with('status', 'ログインしました。');
    }

    // POST /logout
    public function destroy(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'ログアウトしました。');
    }
}
