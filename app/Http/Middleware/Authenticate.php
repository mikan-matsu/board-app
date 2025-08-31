<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * ユーザーが認証されていない場合にリダイレクトする場所を取得
     */
    protected function redirectTo($request): ?string
{
    if (! $request->expectsJson()) {
        // login.create に変更
        return route('login');
    }

    return null;
}

}
