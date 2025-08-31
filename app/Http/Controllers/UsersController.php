<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\DB; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // GET /signup
    public function create()
    {
        return view('auth.signup');
    }

    // POST /signup
    public function store(SignupRequest $request)
    {
        $name = (string)$request->input('name');
        $email = (string)$request->input('email');
        $hashed = Hash::make((string)$request->input('password'));

        // PostgreSQL: RETURNINGでid取得
        $row = DB::selectOne(
            'insert into users (name, email, password, created_at, updated_at, update_date)
             values (?, ?, ?, now(), now(), now())
             returning id',
            [$name, $email, $hashed]
        );

        Auth::loginUsingId($row->id);
        return redirect()->route('board.index')->with('status', 'サインアップしました。ようこそ！');
    }
}
