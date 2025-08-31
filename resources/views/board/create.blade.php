@extends('layouts.app')

@section('content')
<style>
  .page-wrap{max-width:720px;margin:32px auto;padding:0 12px}
  .card{background:#fff;border-radius:12px;padding:20px 18px;box-shadow:0 6px 18px rgba(0,0,0,.06)}
  .card-head{display:flex;align-items:baseline;gap:8px;margin-bottom:10px}
  .title{font-size:22px;font-weight:700;margin:0}
  .small-link{margin-left:auto;font-size:13px;color:#2563eb;text-decoration:none}
  .small-link:hover{text-decoration:underline}
  .meta{color:#64748b;font-size:13px;margin:6px 0 14px}
  .field{margin-bottom:14px}
  .label{display:block;font-size:14px;margin-bottom:6px}
  .input{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:10px 12px;font-size:15px}
  .actions{display:flex;gap:12px;justify-content:flex-end;margin-top:10px}
  .btn{appearance:none;border:1px solid #2563eb;background:#2563eb;color:#fff;border-radius:10px;padding:10px 18px;font-weight:700}
  .btn:disabled{opacity:.6;cursor:not-allowed}
  .error{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:8px;padding:8px 10px;margin-bottom:12px}
  .status{background:#ecfeff;color:#155e75;border:1px solid #a5f3fc;border-radius:8px;padding:8px 10px;margin-bottom:12px}
</style>

<div class="page-wrap">
  @if (session('status'))
    <div class="status">{{ session('status') }}</div>
  @endif

  <section class="card">
    <div class="card-head">
      <h1 class="title">掲示板を作成</h1>
      <a href="{{ route('board.index') }}" class="small-link">掲示板一覧へ</a>
    </div>
    <p class="meta">掲示板名を入力して作成します。</p>

    @if ($errors->any())
      <div class="error">
        <ul style="margin:0;padding-left:18px">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('board.store') }}">
      @csrf
      <div class="field">
        <label class="label" for="board_name">掲示板名</label>
        <input id="board_name" name="name" type="text" maxlength="50"
               class="input" value="{{ old('name') }}" required autocomplete="off" placeholder="例）雑談ルーム" />
      </div>

      <div class="actions">
        <button type="submit" class="btn">作成</button>
      </div>
    </form>
  </section>
</div>
@endsection
