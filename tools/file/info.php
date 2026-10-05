<?php
$pageTitle = "File Info & Type Checker - Sanix Tool";
$pageDesc = "Inspect exact MIME type, byte size, file extension, and attributes securely in browser.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📁</div>
        <div>
          <h1 class="tool-header-title">File Information & Type Checker</h1>
          <p class="tool-header-desc">Inspect file metadata, size, MIME type, and last modified date without uploading to any server.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="file-info" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="file-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop any file to analyze</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose Any File ]</span>
      </div>
      <div class="dropzone-subtext">All file formats supported — 100% Client-Side Privacy</div>
      <input type="file" id="file-input">
    </div>

    <!-- STEP 2: READING STATE -->
    <div id="reading-card" class="reading-state-card" style="display: none;">
      <div class="state-badge state-badge-info" style="margin-bottom: 0.75rem;">
        <span>⚡ Reading file...</span>
      </div>
      <h3 id="reading-filename" style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">file.dat</h3>
      <small id="reading-filesize" style="color: var(--text-muted); font-size: 0.85rem;">Calculating size...</small>
      
      <div class="progress-container">
        <div class="progress-bar-fill" id="reading-progress-fill"></div>
      </div>
      <div id="reading-percent-text" style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">0%</div>
    </div>

    <!-- STEP 3: FILE READY & PROCEED -->
    <div id="file-ready-card" class="file-ready-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span class="state-badge state-badge-success">✓ File Ready</span>
        <button class="btn btn-secondary" id="change-file-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Change File</button>
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.5rem; background: var(--input-bg); padding: 1rem; border-radius: 12px; border: 1px solid var(--surface-border);">
        <div>
          <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">File Name</small>
          <h4 id="ready-filename" style="font-size: 1.05rem; word-break: break-all; color: var(--text-main);">file.dat</h4>
        </div>

        <div>
          <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">File Size</small>
          <div id="ready-filesize" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">0 MB</div>
        </div>
      </div>

      <!-- PROCEED BUTTON -->
      <button class="proceed-btn" id="proceed-btn">
        <span>➜ PROCEED TO FILE ANALYSIS</span>
      </button>
    </div>

    <!-- STEP 4, 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <span class="result-title" style="color: var(--primary); font-size: 1.25rem; font-weight: 800;">📁 File Analysis Report</span>
        <button class="btn btn-secondary" id="result-reset-btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">↻ Start Over</button>
      </div>

      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem;">
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">File Name</small><h4 id="file-name" style="margin-top:0.3rem; word-break:break-all; color:var(--text-main);">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">MIME Type</small><h4 id="file-mime" style="color:var(--primary); margin-top:0.3rem;">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Exact Byte Size</small><h4 id="file-size" style="margin-top:0.3rem; color:var(--text-main);">0 Bytes</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Extension</small><h4 id="file-ext" style="margin-top:0.3rem; color:var(--success); font-weight:800;">-</h4></div>
        <div class="card" style="grid-column: span 2;"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Last Modified</small><h4 id="file-date" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('file-info');

  const dropzone = document.getElementById('file-dropzone');
  const fileInput = document.getElementById('file-input');

  const readingCard = document.getElementById('reading-card');
  const readingFilename = document.getElementById('reading-filename');
  const readingFilesize = document.getElementById('reading-filesize');
  const readingProgressFill = document.getElementById('reading-progress-fill');
  const readingPercentText = document.getElementById('reading-percent-text');

  const fileReadyCard = document.getElementById('file-ready-card');
  const readyFilename = document.getElementById('ready-filename');
  const readyFilesize = document.getElementById('ready-filesize');
  const changeFileBtn = document.getElementById('change-file-btn');
  const proceedBtn = document.getElementById('proceed-btn');

  const resultContainer = document.getElementById('result-container');
  const resultResetBtn = document.getElementById('result-reset-btn');

  let currentFile = null;
  let readingTimer = null;

  function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function resetState() {
    dropzone.style.display = 'block';
    readingCard.style.display = 'none';
    fileReadyCard.style.display = 'none';
    resultContainer.style.display = 'none';
    fileInput.value = '';
    currentFile = null;
    if (readingTimer) clearInterval(readingTimer);
  }

  function processFileSelection(file) {
    if (!file) return;
    currentFile = file;

    dropzone.style.display = 'none';
    fileReadyCard.style.display = 'none';
    resultContainer.style.display = 'none';

    readingFilename.textContent = file.name;
    readingFilesize.textContent = formatBytes(file.size);
    readingCard.style.display = 'block';

    let progress = 0;
    readingProgressFill.style.width = '0%';
    readingPercentText.textContent = '0%';

    if (readingTimer) clearInterval(readingTimer);

    readingTimer = setInterval(() => {
      progress += Math.floor(Math.random() * 25) + 15;
      if (progress >= 100) {
        progress = 100;
        clearInterval(readingTimer);
        readyFilename.textContent = file.name;
        readyFilesize.textContent = formatBytes(file.size);

        readingCard.style.display = 'none';
        fileReadyCard.style.display = 'block';

        if (window.Sani) {
          window.Sani.say("File ready! Click Proceed to inspect analysis report.", "happy");
        }
      }
      readingProgressFill.style.width = `${progress}%`;
      readingPercentText.textContent = `${progress}%`;
    }, 40);
  }

  fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) processFileSelection(e.target.files[0]);
  });

  dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) processFileSelection(e.dataTransfer.files[0]);
  });

  proceedBtn.addEventListener('click', () => {
    if (!currentFile) return;

    document.getElementById('file-name').textContent = currentFile.name;
    document.getElementById('file-mime').textContent = currentFile.type || 'Unknown / Binary';
    document.getElementById('file-size').textContent = `${currentFile.size.toLocaleString()} Bytes (${formatBytes(currentFile.size)})`;

    const ext = currentFile.name.includes('.') ? currentFile.name.split('.').pop().toUpperCase() : 'NONE';
    document.getElementById('file-ext').textContent = ext;
    document.getElementById('file-date').textContent = new Date(currentFile.lastModified).toLocaleString();

    resultContainer.style.display = 'block';
    resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    showToast("File metadata analyzed!", "success");
  });

  changeFileBtn.addEventListener('click', resetState);
  resultResetBtn.addEventListener('click', resetState);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
