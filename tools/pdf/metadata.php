<?php
$pageTitle = "PDF Metadata Viewer - Sanix Tool";
$pageDesc = "Inspect title, author, subject, creation date and producer metadata inside any PDF document.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🔍</div>
        <div>
          <h1 class="tool-header-title">PDF Metadata Viewer</h1>
          <p class="tool-header-desc">Inspect structural metadata, author details, page count and creation date.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="pdf-metadata" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="pdf-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop your PDF file here</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose PDF File ]</span>
      </div>
      <div class="dropzone-subtext">Inspect title, author, creator, and total page count</div>
      <input type="file" id="pdf-input" accept="application/pdf">
    </div>

    <!-- STEP 2: READING STATE -->
    <div id="reading-card" class="reading-state-card" style="display: none;">
      <div class="state-badge state-badge-info" style="margin-bottom: 0.75rem;">
        <span>⚡ Reading file...</span>
      </div>
      <h3 id="reading-filename" style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">document.pdf</h3>
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
        <button class="btn btn-secondary" id="change-file-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Change PDF</button>
      </div>

      <div style="display: flex; flex-direction: column; gap: 0.5rem; background: var(--input-bg); padding: 1rem; border-radius: 12px; border: 1px solid var(--surface-border);">
        <div>
          <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">PDF Document Name</small>
          <h4 id="ready-filename" style="font-size: 1.05rem; word-break: break-all; color: var(--text-main);">document.pdf</h4>
        </div>

        <div>
          <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">File Size</small>
          <div id="ready-filesize" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">0 MB</div>
        </div>
      </div>

      <!-- PROCEED BUTTON -->
      <button class="proceed-btn" id="proceed-btn">
        <span>➜ PROCEED TO INSPECT METADATA</span>
      </button>
    </div>

    <!-- STEP 4, 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
        <span class="result-title" style="color: var(--primary); font-size: 1.25rem; font-weight: 800;">📄 Extracted Document Metadata</span>
        <button class="btn btn-secondary" id="result-reset-btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">↻ Start Over</button>
      </div>

      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem;">
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Title</small><h4 id="meta-title" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Author</small><h4 id="meta-author" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Subject</small><h4 id="meta-subject" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Creator Application</small><h4 id="meta-creator" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
        <div class="card"><small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; font-size:0.75rem;">PDF Producer</small><h4 id="meta-producer" style="margin-top:0.3rem; color:var(--text-main);">-</h4></div>
        <div class="card" style="background: var(--success-bg); border-color: var(--success);"><small style="color:var(--success); font-weight:700; text-transform:uppercase; font-size:0.75rem;">Total Page Count</small><h3 id="meta-pages" style="color:var(--success); margin-top:0.3rem; font-weight:800;">0</h3></div>
      </div>
    </div>

  </div>
</div>

<!-- Vendor Scripts -->
<script src="<?= BASE_URL ?>assets/vendor/pdf-lib.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('pdf-metadata');

  const dropzone = document.getElementById('pdf-dropzone');
  const pdfInput = document.getElementById('pdf-input');

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
    pdfInput.value = '';
    currentFile = null;
    if (readingTimer) clearInterval(readingTimer);
  }

  function processFileSelection(file) {
    if (!file || !file.type.includes('pdf')) {
      showToast("Please select a valid PDF file", "error");
      return;
    }

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
          window.Sani.say("PDF loaded! Click Proceed to inspect metadata.", "happy");
        }
      }
      readingProgressFill.style.width = `${progress}%`;
      readingPercentText.textContent = `${progress}%`;
    }, 40);
  }

  pdfInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files.length > 0) processFileSelection(e.target.files[0]);
  });

  dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) processFileSelection(e.dataTransfer.files[0]);
  });

  proceedBtn.addEventListener('click', async () => {
    if (!currentFile) return;

    if (typeof PDFLib === 'undefined') {
      showToast("PDF Library loading error. Please refresh.", "error");
      return;
    }

    try {
      const buffer = await currentFile.arrayBuffer();
      const pdfDoc = await PDFLib.PDFDocument.load(buffer, { updateMetadata: false });

      document.getElementById('meta-title').textContent = pdfDoc.getTitle() || 'Not specified';
      document.getElementById('meta-author').textContent = pdfDoc.getAuthor() || 'Not specified';
      document.getElementById('meta-subject').textContent = pdfDoc.getSubject() || 'Not specified';
      document.getElementById('meta-creator').textContent = pdfDoc.getCreator() || 'Not specified';
      document.getElementById('meta-producer').textContent = pdfDoc.getProducer() || 'Not specified';
      document.getElementById('meta-pages').textContent = pdfDoc.getPageCount();

      resultContainer.style.display = 'block';
      resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      showToast("Extracted PDF metadata successfully!", "success");
      if (window.Sani) window.Sani.say("Done! Here is the structural metadata of your PDF.", "success");
    } catch (err) {
      showToast("Error reading PDF metadata", "error");
    }
  });

  changeFileBtn.addEventListener('click', resetState);
  resultResetBtn.addEventListener('click', resetState);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
