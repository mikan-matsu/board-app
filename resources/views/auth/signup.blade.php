@extends('layouts.app')

@section('content')

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
    <h2>サインアップ画面</h2>

    <form method="post" action="{{ route('signup.store') }}">
      @csrf

      <div class="form-row">
        <label for="name">名前</label>
        <input id="name" class="input" type="text" name="name" value="{{ old('name') }}" required>
      </div>

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

      <div class="form-row">
        <label for="password_confirmation">パスワード（確認）</label>
        <div class="pw-row">
          <input id="password_confirmation" class="pw-input" type="password" name="password_confirmation" required>
          <button type="button"
                  class="pw-toggle"
                  data-action="toggle-password"
                  data-target="password_confirmation">表示</button>
        </div>
      </div>

      <button type="submit" class="btn">サインアップ</button>

      <div class="links">
        <a href="{{ route('login') }}">ログインに戻る</a>
      </div>
    </form>
  </div>
</div>

@endsection
