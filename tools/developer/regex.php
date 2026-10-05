<?php
$pageTitle = "Regex Tester & Debugger - Sanix Tool";
$pageDesc = "Test regular expressions in real-time with pattern match highlighting and capture group details.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🔍</div>
        <div>
          <h1 class="tool-header-title">Regular Expression Tester</h1>
          <p class="tool-header-desc">Test and debug Regex patterns against sample text strings in real-time.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="regex-tester" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid" style="grid-template-columns: 1fr 120px;">
      <div class="form-group">
        <label class="form-label">Regex Pattern</label>
        <input type="text" id="regex-pattern" class="form-control code-editor" value="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" placeholder="e.g. \d+">
      </div>

      <div class="form-group">
        <label class="form-label">Flags</label>
        <input type="text" id="regex-flags" class="form-control" value="gi" placeholder="g, i, m">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label">Test String</label>
      <textarea id="regex-text" class="form-control" style="min-height: 180px;" placeholder="Type or paste test text here...">Contact support at admin@sanix-tool.local or sales@sanix-tool.local for details.</textarea>
    </div>

    <div class="tool-result-box">
      <div class="result-header">
        <span class="result-title">Matches Count: <span id="match-count" style="color:var(--primary);">0</span></span>
      </div>
      <div id="match-results" class="code-editor" style="min-height:120px; white-space:pre-wrap;"></div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('regex-tester');

  const patternInput = document.getElementById('regex-pattern');
  const flagsInput = document.getElementById('regex-flags');
  const textInput = document.getElementById('regex-text');
  const matchCount = document.getElementById('match-count');
  const matchResults = document.getElementById('match-results');

  function testRegex() {
    const pat = patternInput.value;
    const flags = flagsInput.value;
    const text = textInput.value;

    if (!pat || !text) {
      matchCount.textContent = '0';
      matchResults.textContent = 'Enter pattern and test string above.';
      return;
    }

    try {
      const regex = new RegExp(pat, flags);
      const matches = text.match(regex);
      if (matches) {
        matchCount.textContent = matches.length;
        matchResults.textContent = matches.map((m, i) => `Match #${i + 1}: ${m}`).join('\n');
      } else {
        matchCount.textContent = '0';
        matchResults.textContent = 'No matches found.';
      }
    } catch (e) {
      matchCount.textContent = 'Error';
      matchResults.textContent = `Regex Error: ${e.message}`;
    }
  }

  patternInput.addEventListener('input', testRegex);
  flagsInput.addEventListener('input', testRegex);
  textInput.addEventListener('input', testRegex);

  testRegex();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
