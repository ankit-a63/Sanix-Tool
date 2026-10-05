<?php
$pageTitle = "Word & Character Counter - Sanix Tool";
$pageDesc = "Real-time word, character, sentence, paragraph, reading time and speaking time counter.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📝</div>
        <div>
          <h1 class="tool-header-title">Word & Character Counter</h1>
          <p class="tool-header-desc">Count words, characters, sentences, paragraphs and estimate reading time in real-time.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="word-counter" title="Favorite this tool">🤍</button>
    </div>

    <!-- STATS CARDS -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Words</small><h2 id="stat-words" style="color:var(--primary);">0</h2></div>
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Characters (with spaces)</small><h2 id="stat-chars-spaces">0</h2></div>
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Characters (no spaces)</small><h2 id="stat-chars-nospaces">0</h2></div>
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Sentences</small><h2 id="stat-sentences">0</h2></div>
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Paragraphs</small><h2 id="stat-paragraphs">0</h2></div>
      <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Reading Time</small><h2 id="stat-reading" style="font-size:1.4rem;">&lt; 1 min</h2></div>
    </div>

    <div class="form-group">
      <textarea id="text-input" class="form-control" style="min-height: 260px;" placeholder="Type or paste your text here to begin counting..."></textarea>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="clear-btn">🗑️ Clear Text</button>
      <div style="display:flex; gap:0.5rem;">
        <button class="btn btn-secondary" id="copy-btn">📋 Copy Text</button>
        <button class="btn btn-primary" id="download-btn">⬇️ Download TXT</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('word-counter');

  const textInput = document.getElementById('text-input');
  const statWords = document.getElementById('stat-words');
  const statCharsSpaces = document.getElementById('stat-chars-spaces');
  const statCharsNoSpaces = document.getElementById('stat-chars-nospaces');
  const statSentences = document.getElementById('stat-sentences');
  const statParagraphs = document.getElementById('stat-paragraphs');
  const statReading = document.getElementById('stat-reading');

  function updateCounts() {
    const text = textInput.value;

    // Words
    const words = text.trim() ? text.trim().split(/\s+/).length : 0;
    statWords.textContent = words.toLocaleString();

    // Chars
    statCharsSpaces.textContent = text.length.toLocaleString();
    statCharsNoSpaces.textContent = text.replace(/\s/g, '').length.toLocaleString();

    // Sentences
    const sentences = text.trim() ? text.split(/[.!?]+/).filter(s => s.trim().length > 0).length : 0;
    statSentences.textContent = sentences.toLocaleString();

    // Paragraphs
    const paragraphs = text.trim() ? text.split(/\n+/).filter(p => p.trim().length > 0).length : 0;
    statParagraphs.textContent = paragraphs.toLocaleString();

    // Reading time (avg 200 wpm)
    const readMin = Math.ceil(words / 200);
    statReading.textContent = words > 0 ? (readMin <= 1 ? '< 1 min' : `${readMin} mins`) : '0 min';
  }

  textInput.addEventListener('input', updateCounts);

  document.getElementById('copy-btn').addEventListener('click', () => {
    copyToClipboard(textInput.value, "Text copied to clipboard!");
  });

  document.getElementById('clear-btn').addEventListener('click', () => {
    textInput.value = '';
    updateCounts();
    showToast("Text cleared", "info");
  });

  document.getElementById('download-btn').addEventListener('click', () => {
    if (!textInput.value) return;
    const blob = new Blob([textInput.value], { type: 'text/plain;charset=utf-8' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'sanix-text.txt';
    link.click();
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
