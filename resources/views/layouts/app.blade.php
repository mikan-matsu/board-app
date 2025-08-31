<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>掲示板アプリ</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
  <div class="app-header" style="position:relative;">
    @auth
      <div class="user-info">
        <div>ようこそ、{{ auth()->user()->name }} さん</div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-btn">ログアウト</button>
        </form>
      </div>
    @endauth

    <div class="app-brand">
      <span class="brand-badge">B</span>
      <span class="app-title-text">掲示板アプリ</span>
    </div>
    <div class="app-subtitle">シンプルで使いやすい、みんなの掲示板</div>
  </div>

  <main>
    @yield('content')
  </main>

  <div class="footer-note">© {{ date('Y') }} Board App</div>
</body>
</html>
