<?php
$pageTitle = "Duplicate Line Remover & Sorter - Sanix Tool";
$pageDesc = "Remove duplicate lines, sort lines alphabetically or by length, and trim line breaks.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📋</div>
        <div>
          <h1 class="tool-header-title">Duplicate Line Remover & Sorter</h1>
          <p class="tool-header-desc">Clean up list items by removing duplicate lines and sorting them.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="line-tools" title="Favorite this tool">🤍</button>
    </div>

    <div class="form-group">
      <textarea id="text-input" class="form-control" style="min-height: 240px;" placeholder="Paste list or lines of text here..."></textarea>
    </div>

    <div class="tool-controls-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
      <button class="btn btn-secondary" id="btn-dedupe">❌ Remove Duplicates</button>
      <button class="btn btn-secondary" id="btn-sort-az">🔤 Sort A → Z</button>
      <button class="btn btn-secondary" id="btn-sort-za">🔤 Sort Z → A</button>
      <button class="btn btn-secondary" id="btn-sort-len">📏 Sort by Length</button>
      <button class="btn btn-secondary" id="btn-reverse">↩️ Reverse Order</button>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="clear-btn">🗑️ Clear</button>
      <div style="display:flex; gap:0.5rem;">
        <button class="btn btn-secondary" id="copy-btn">📋 Copy Lines</button>
        <button class="btn btn-primary" id="download-btn">⬇️ Download TXT</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('line-tools');

  const textInput = document.getElementById('text-input');

  document.getElementById('btn-dedupe').addEventListener('click', () => {
    const lines = textInput.value.split('\n');
    const unique = [...new Set(lines)];
    textInput.value = unique.join('\n');
    const removed = lines.length - unique.length;
    showToast(`Removed ${removed} duplicate line(s)`, "success");
    if (window.Sani) window.Sani.say(`Removed ${removed} duplicates!`, "happy");
  });

  document.getElementById('btn-sort-az').addEventListener('click', () => {
    textInput.value = textInput.value.split('\n').sort((a, b) => a.localeCompare(b)).join('\n');
  });

  document.getElementById('btn-sort-za').addEventListener('click', () => {
    textInput.value = textInput.value.split('\n').sort((a, b) => b.localeCompare(a)).join('\n');
  });

  document.getElementById('btn-sort-len').addEventListener('click', () => {
    textInput.value = textInput.value.split('\n').sort((a, b) => a.length - b.length).join('\n');
  });

  document.getElementById('btn-reverse').addEventListener('click', () => {
    textInput.value = textInput.value.split('\n').reverse().join('\n');
  });

  document.getElementById('copy-btn').addEventListener('click', () => copyToClipboard(textInput.value));
  document.getElementById('clear-btn').addEventListener('click', () => { textInput.value = ''; });
  document.getElementById('download-btn').addEventListener('click', () => {
    if (!textInput.value) return;
    const blob = new Blob([textInput.value], { type: 'text/plain;charset=utf-8' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'sanix-cleaned-lines.txt';
    a.click();
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
