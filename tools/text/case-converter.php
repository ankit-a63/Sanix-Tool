<?php
$pageTitle = "Text Case Converter - Sanix Tool";
$pageDesc = "Convert text to UPPERCASE, lowercase, Title Case, Sentence case, Capitalized Case, and aLtErNaTiNg case.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🔤</div>
        <div>
          <h1 class="tool-header-title">Text Case Converter</h1>
          <p class="tool-header-desc">Transform text case formats instantly with 1-click conversion options.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="case-converter" title="Favorite this tool">🤍</button>
    </div>

    <div class="form-group">
      <textarea id="text-input" class="form-control" style="min-height: 220px;" placeholder="Type or paste your text here to convert case..."></textarea>
    </div>

    <div class="tool-controls-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
      <button class="btn btn-secondary" id="btn-upper">UPPERCASE</button>
      <button class="btn btn-secondary" id="btn-lower">lowercase</button>
      <button class="btn btn-secondary" id="btn-title">Title Case</button>
      <button class="btn btn-secondary" id="btn-sentence">Sentence case</button>
      <button class="btn btn-secondary" id="btn-capitalized">Capitalized Case</button>
      <button class="btn btn-secondary" id="btn-alternating">aLtErNaTiNg cAsE</button>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="clear-btn">🗑️ Clear</button>
      <div style="display:flex; gap:0.5rem;">
        <button class="btn btn-secondary" id="copy-btn">📋 Copy Text</button>
        <button class="btn btn-primary" id="download-btn">⬇️ Download TXT</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('case-converter');

  const textInput = document.getElementById('text-input');

  document.getElementById('btn-upper').addEventListener('click', () => {
    textInput.value = textInput.value.toUpperCase();
    if (window.Sani) window.Sani.say("Converted to UPPERCASE!", "happy");
  });

  document.getElementById('btn-lower').addEventListener('click', () => {
    textInput.value = textInput.value.toLowerCase();
    if (window.Sani) window.Sani.say("Converted to lowercase!", "happy");
  });

  document.getElementById('btn-title').addEventListener('click', () => {
    textInput.value = textInput.value.toLowerCase().split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    if (window.Sani) window.Sani.say("Converted to Title Case!", "happy");
  });

  document.getElementById('btn-sentence').addEventListener('click', () => {
    textInput.value = textInput.value.toLowerCase().replace(/(^\s*|\.\s*)([a-z])/g, (m, p1, p2) => p1 + p2.toUpperCase());
    if (window.Sani) window.Sani.say("Converted to Sentence case!", "happy");
  });

  document.getElementById('btn-capitalized').addEventListener('click', () => {
    textInput.value = textInput.value.replace(/\b\w/g, c => c.toUpperCase());
  });

  document.getElementById('btn-alternating').addEventListener('click', () => {
    textInput.value = textInput.value.split('').map((c, i) => i % 2 === 0 ? c.toLowerCase() : c.toUpperCase()).join('');
  });

  document.getElementById('copy-btn').addEventListener('click', () => copyToClipboard(textInput.value));
  document.getElementById('clear-btn').addEventListener('click', () => { textInput.value = ''; });
  document.getElementById('download-btn').addEventListener('click', () => {
    if (!textInput.value) return;
    const blob = new Blob([textInput.value], { type: 'text/plain;charset=utf-8' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'sanix-converted-case.txt';
    a.click();
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
