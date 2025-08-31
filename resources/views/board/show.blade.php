@extends('layouts.app')

@section('content')
<div class="page board-page">

  <div class="board-header">
    <h1 class="board-title">{{ $board->name }}</h1>
    <div class="board-meta">作成: {{ $board->created_at }} ／ 作成者: {{ $board->owner_name ?? '不明' }}さん</div>
    <div class="board-links"><a href="{{ route('board.index') }}">掲示板一覧へ戻る</a></div>
  </div>

  <div class="board-grid">
    <!-- 投稿一覧（左：独立スクロール） -->
    <section class="feed" aria-label="投稿一覧">
      @if (empty($posts))
        <div class="empty">まだ投稿はありません。</div>
      @else
        <ul class="msg-list">
          @foreach ($posts as $p)
            @php
              $isMine = auth()->id() === (int)$p->user_id;
            @endphp
            <li class="msg {{ $isMine ? 'mine' : 'theirs' }}">
              <div class="msg-head">
                <span class="msg-user">{{ $p->user_name ?? $p->name ?? '名無し' }}さん</span>
                <time class="msg-time">{{ $p->created_at ?? $p->posted_at ?? '' }}</time>
              </div>
              <div class="msg-body">
                <p class="msg-text">{{ $p->body ?? $p->content ?? $p->message ?? $p->text ?? '' }}</p>
              @php $img = ltrim($p->image_path ?? '', '/'); @endphp
              @if ($img)
                <img class="msg-image" src="{{ '/storage/'.$img }}" alt="投稿画像">
              @endif
              </div>
              @if ($isMine)
                <form method="POST" action="{{ route('posts.destroy', ['boardId' => $board->id, 'postId' => $p->id]) }}" class="msg-actions">
                  @csrf @method('DELETE')
                  <button type="submit" class="msg-delete">この投稿を削除する</button>
                </form>
              @endif
            </li>
          @endforeach
        </ul>
      @endif
        <div class="feed-fab-wrap">
          <button type="button" class="feed-fab" title="最新へ" aria-label="最新へ">⇩</button>
        </div>
    </section>

    <!-- 右ペイン：新規投稿フォーム（stickyで独立） -->
    <aside class="sidebar" aria-label="新規投稿">
      <div class="post-card">
        <h2 class="post-card-title">新規投稿</h2>
        <div class="post-card-meta">投稿者: {{ auth()->user()->name ?? 'guest' }}さん</div>
        {{-- resources/views/board/show.blade.php の <form> の上あたりに追加 --}}
        @if ($errors->any())
          <div class="errors">
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif
        @if (session('status'))
          <div class="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('posts.store', ['id' => $board->id]) }}" enctype="multipart/form-data" class="post-form">
          @csrf
          <label class="form-label">本文</label>
          <div class="textarea-wrap">
            <textarea name="content" rows="6" class="form-textarea"></textarea>
          </div>

          <label class="form-label">画像（任意 / jpeg, png, webp / 2MBまで）</label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-file">

          <button type="submit" class="primary-btn">投稿</button>
        </form>
      </div>
    </aside>
  </div>

</div>
{{-- 既定で最新メッセージ位置（最下部）へスクロール --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const feed = document.querySelector('.feed');
  const fab  = document.querySelector('.feed-fab');
  if (!feed) return;

  // 画面に対して少し小さく（下に 24px 余白）
  const BOTTOM_GAP = 124;
  const fit = () => {
    const top = feed.getBoundingClientRect().top;
    const h = Math.max(120, window.innerHeight - top - BOTTOM_GAP);
    feed.style.height = h + 'px';
  };
  fit();
  window.addEventListener('resize', fit);

  // ↓以降は「最新へ」ボタン制御
  if (fab) {
    // 初期：一番下へ
    feed.scrollTop = feed.scrollHeight;

    // クリック：最下部へ
    fab.addEventListener('click', () => {
      feed.scrollTo({ top: feed.scrollHeight, behavior: 'smooth' });
    });

    // 表示/非表示の判定
    const toggle = () => {
      const noScroll = feed.scrollHeight <= feed.clientHeight;
      const atBottom = feed.scrollTop + feed.clientHeight >= feed.scrollHeight - 4;
      fab.classList.toggle('is-hidden', noScroll || atBottom);
    };
    feed.addEventListener('scroll', toggle);
    window.addEventListener('resize', () => { fit(); toggle(); });
    toggle();
  }
});
</script>

@endsection

