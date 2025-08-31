@extends('layouts.app')

@section('content')
<style>
  .board-list { display: grid; gap: 14px; }
  .board-item { background:#fff; border-radius:12px; padding:14px 16px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
  .item-row { display:flex; align-items:center; gap:12px; }
  .item-title { flex:1 1 auto; font-weight:700; text-decoration:none; color:#1f2937; }
  .item-title:hover { text-decoration:underline; }
  .item-actions { flex:0 0 auto; }
  .link-danger { color:#ef4444; font-size:13px; text-decoration:none; background:none; border:none; padding:0; cursor:pointer; }
  .link-danger:hover { text-decoration:underline; }
  .board-meta { color:#64748b; font-size:12px; margin-top:4px; }
</style>

<div class="page">

  <div class="board-header">
    <h2>掲示板一覧</h2>
  </div>

  <div class="board-actions">
    <a href="{{ url('/board/new') }}" class="create-btn">＋ 新規作成</a>
  </div>

  @if (session('status'))
    <div class="status">{{ session('status') }}</div>
  @endif

  <div class="board-list">
    @foreach ($boards as $b)
      <div class="board-item">
        <div class="item-row">
          <a href="{{ url('/board/'.$b->id) }}" class="item-title">{{ $b->name }}</a>

          {{-- 作成者のみ：削除リンク（右側・テキスト） --}}
          @if (Auth::id() === ($b->user_id ?? null))
            <form method="post" action="{{ route('board.destroy', ['id' => $b->id]) }}" class="item-actions"
                  onsubmit="return confirm('この掲示板を削除します。よろしいですか？（投稿も削除されます）');">
              @csrf
              @method('DELETE')
              <button type="submit" class="link-danger">この掲示板を削除する</button>
            </form>
          @endif
        </div>

        <div class="board-meta">
          作成: {{ \Carbon\Carbon::parse($b->created_at)->format('Y-m-d H:i') }}（作成者: {{ $b->owner_name ?? '不明' }}）
        </div>
      </div>
    @endforeach

  </div>

</div>

@endsection
