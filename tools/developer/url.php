<?php
$pageTitle = "URL Encoder & Decoder - Sanix Tool";
$pageDesc = "Encode or decode URLs and query string parameters with standard URI escape protocols.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🌐</div>
        <div>
          <h1 class="tool-header-title">URL Encoder & Decoder</h1>
          <p class="tool-header-desc">Safely encode special characters in web links and decode percentage-encoded query parameters.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="url-encoder" title="Favorite this tool">🤍</button>
    </div>

    <div class="form-group">
      <label class="form-label">Input URL or Text</label>
      <textarea id="url-input" class="form-control code-editor" style="min-height: 200px;" placeholder="Type URL or query string parameters here... e.g. https://sanix-tool.local/?search=hello world"></textarea>
    </div>

    <div class="tool-controls-grid">
      <button class="btn btn-primary" id="btn-encode">🔒 Encode URL Component</button>
      <button class="btn btn-secondary" id="btn-decode">🔓 Decode URL Component</button>
    </div>

    <div class="tool-action-bar">
      <button class="btn btn-secondary" id="clear-btn">🗑️ Clear Input</button>
      <button class="btn btn-secondary" id="copy-btn">📋 Copy Result</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('url-encoder');

  const urlInput = document.getElementById('url-input');

  document.getElementById('btn-encode').addEventListener('click', () => {
    try {
      urlInput.value = encodeURIComponent(urlInput.value);
      showToast("URL Component Encoded!", "success");
    } catch (e) {
      showToast("Error encoding URL", "error");
    }
  });

  document.getElementById('btn-decode').addEventListener('click', () => {
    try {
      urlInput.value = decodeURIComponent(urlInput.value);
      showToast("URL Component Decoded!", "success");
    } catch (e) {
      showToast("Invalid encoded URL syntax", "error");
    }
  });

  document.getElementById('copy-btn').addEventListener('click', () => copyToClipboard(urlInput.value));
  document.getElementById('clear-btn').addEventListener('click', () => { urlInput.value = ''; });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
