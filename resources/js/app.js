/** パスワード表示/非表示 切替 */
document.addEventListener("click", (e) => {
  const t = e.target;
  if (t && t.matches("[data-action='toggle-password']")) {
    const input = document.getElementById(t.getAttribute("data-target"));
    if (!input) return;
    const toText = input.type === "password";
    input.type = toText ? "text" : "password";
    t.textContent = toText ? "非表示" : "表示";
  }
});

// ファイル名表示
document.addEventListener("change", (e) => {
  if (e.target && e.target.matches("[data-file='post-image']")) {
    const out = document.querySelector("[data-file-name='post-image']");
    if (out) out.textContent = e.target.files?.[0]?.name || "選択されていません";
  }
});

