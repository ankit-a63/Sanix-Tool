<?php
$pageTitle = "Base64 Encoder & Decoder - Sanix Tool";
$pageDesc = "Encode text and files to Base64 format or decode Base64 strings back to original content.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🔐</div>
        <div>
          <h1 class="tool-header-title">Base64 Encoder & Decoder</h1>
          <p class="tool-header-desc">Encode string data or binary files into Base64 format, or decode back to plain text.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="base64-encoder" title="Favorite this tool">🤍</button>
    </div>

    <div class="form-group">
      <label class="form-label">Input Content / Base64</label>
      <textarea id="base64-input" class="form-control code-editor" style="min-height: 200px;" placeholder="Type text or paste Base64 string here..."></textarea>
    </div>

    <div class="tool-controls-grid">
      <button class="btn btn-primary" id="btn-encode">🔒 Encode to Base64</button>
      <button class="btn btn-secondary" id="btn-decode">🔓 Decode Base64</button>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="clear-btn">🗑️ Clear Input</button>
      <button class="btn btn-secondary" id="copy-btn">📋 Copy Result</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('base64-encoder');

  const base64Input = document.getElementById('base64-input');

  document.getElementById('btn-encode').addEventListener('click', () => {
    try {
      base64Input.value = btoa(unescape(encodeURIComponent(base64Input.value)));
      showToast("Encoded to Base64!", "success");
    } catch (err) {
      showToast("Encoding error", "error");
    }
  });

  document.getElementById('btn-decode').addEventListener('click', () => {
    try {
      base64Input.value = decodeURIComponent(escape(atob(base64Input.value.trim())));
      showToast("Decoded Base64 string!", "success");
    } catch (err) {
      showToast("Invalid Base64 string", "error");
    }
  });

  document.getElementById('copy-btn').addEventListener('click', () => copyToClipboard(base64Input.value));
  document.getElementById('clear-btn').addEventListener('click', () => { base64Input.value = ''; });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
