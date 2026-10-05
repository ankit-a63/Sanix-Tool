<?php
$pageTitle = "UUID v4 Generator - Sanix Tool";
$pageDesc = "Generate cryptographically secure Version-4 UUID (Universally Unique Identifiers) in single or bulk batches.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🆔</div>
        <div>
          <h1 class="tool-header-title">UUID v4 Generator</h1>
          <p class="tool-header-desc">Generate secure random UUIDs (Universally Unique Identifiers) for database primary keys.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="uuid-generator" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid">
      <div class="form-group">
        <label class="form-label">Quantity to Generate</label>
        <input type="number" id="uuid-count" class="form-control" value="5" min="1" max="100">
      </div>

      <div class="form-group" style="display:flex; align-items:flex-end;">
        <button class="btn btn-primary" id="generate-btn" style="width:100%;">⚡ Generate UUIDs</button>
      </div>
    </div>

    <div class="form-group" style="margin-top:1.5rem;">
      <textarea id="uuid-output" class="form-control code-editor" style="min-height: 220px;" readonly></textarea>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="copy-btn">📋 Copy UUID List</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('uuid-generator');

  function generateUUIDv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
  }

  function generateBatch() {
    const count = parseInt(document.getElementById('uuid-count').value) || 1;
    let res = [];
    for (let i = 0; i < count; i++) res.push(generateUUIDv4());
    document.getElementById('uuid-output').value = res.join('\n');
  }

  document.getElementById('generate-btn').addEventListener('click', generateBatch);
  document.getElementById('copy-btn').addEventListener('click', () => {
    copyToClipboard(document.getElementById('uuid-output').value, "UUIDs copied to clipboard!");
  });

  generateBatch();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
