@extends('layouts.app')

@section('content')

@if (session('status'))
  <div class="status">{{ session('status') }}</div>
@endif

@if ($errors->any())
  <div class="errors">
    <ul>
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="login-wrap">
  <div class="login-card">
    <h2>ログイン画面</h2>

    <form method="post" action="{{ url('/login') }}">
      @csrf

      <div class="form-row">
        <label for="email">メールアドレス</label>
        <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required>
      </div>

      <div class="form-row">
        <label for="password">パスワード</label>
        <div class="pw-row">
          <input id="password" class="pw-input" type="password" name="password" required>
          <button type="button"
                  class="pw-toggle"
                  data-action="toggle-password"
                  data-target="password">表示</button>
        </div>
      </div>

      <button type="submit" class="btn">ログイン</button>

      <div class="links" aria-label="補助リンク">
        <a href="{{ route('signup.create') }}">サインアップ</a>
      </div>
    </form>
  </div>
</div>

@endsection
