<?php
$pageTitle = "ZIP Creator & Archive Tool - Sanix Tool";
$pageDesc = "Compress single or multiple files into a clean .zip archive file in browser.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📦</div>
        <div>
          <h1 class="tool-header-title">ZIP Creator & Archive Tool</h1>
          <p class="tool-header-desc">Package multiple files into a compressed .zip archive directly in your browser.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="zip-creator" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="file-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop files here to compress into ZIP</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose Files ]</span>
      </div>
      <div class="dropzone-subtext">Select multiple files (Images, Documents, Text, PDFs)</div>
      <input type="file" id="file-input" multiple>
    </div>

    <!-- STEP 2: READING STATE -->
    <div id="reading-card" class="reading-state-card" style="display: none;">
      <div class="state-badge state-badge-info" style="margin-bottom: 0.75rem;">
        <span>⚡ Reading files...</span>
      </div>
      <h3 id="reading-filename" style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">Processing selected files...</h3>
      <small id="reading-filesize" style="color: var(--text-muted); font-size: 0.85rem;">Calculating total size...</small>
      
      <div class="progress-container">
        <div class="progress-bar-fill" id="reading-progress-fill"></div>
      </div>
      <div id="reading-percent-text" style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">0%</div>
    </div>

    <!-- STEP 3: FILES READY & PROCEED -->
    <div id="file-ready-card" class="file-ready-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span class="state-badge state-badge-success" id="files-ready-badge">✓ 0 Files Ready</span>
        <div style="display:flex; gap:0.5rem;">
          <button class="btn btn-secondary" id="add-more-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">➕ Add More</button>
          <button class="btn btn-secondary" id="reset-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Clear All</button>
        </div>
      </div>

      <!-- FILE LIST CONTAINER -->
      <h4 style="margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--text-muted);">Files to include in archive:</h4>
      <div id="file-list-container" class="file-list" style="margin-bottom: 1rem;"></div>

      <!-- PROCEED BUTTON -->
      <button class="proceed-btn" id="proceed-btn">
        <span>➜ PROCEED TO CREATE ZIP ARCHIVE</span>
      </button>
    </div>

    <!-- STEP 4: OPTIONS PANEL -->
    <div id="options-panel" class="card" style="display: none; margin-top: 1.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.1rem; color: var(--primary);">Generate ZIP Archive</h3>
        <span class="state-badge state-badge-info">Step 4 — Action</span>
      </div>

      <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
        All selected files will be bundled into a standard compressed <code>.zip</code> file in browser memory.
      </p>

      <div style="margin-top: 1rem;">
        <button class="btn btn-primary" id="create-zip-action-btn" style="width: 100%; padding: 0.95rem; font-size: 1.15rem; font-weight: 800; letter-spacing: 0.5px; box-shadow: var(--glow-shadow);">
          📦 CREATE .ZIP ARCHIVE NOW
        </button>
      </div>
    </div>

    <!-- STEP 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem;">
        <span class="result-title" style="color: var(--success); font-size: 1.25rem; font-weight: 800;">✓ ZIP Archive Created</span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; align-items: center;">
        <div class="card" style="text-align: center; padding: 1rem; background: var(--success-bg); border-color: var(--success);">
          <small style="color: var(--success); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Compressed ZIP Size</small>
          <h3 id="res-zip-size" style="color: var(--success); margin-top: 0.3rem;">0 KB</h3>
        </div>

        <button class="btn btn-primary" id="download-zip-btn" style="padding: 0.9rem; font-size: 1.1rem; font-weight: 800; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border: none;">
          ⬇️ DOWNLOAD ZIP ARCHIVE
        </button>
      </div>
    </div>

  </div>
</div>

<!-- Vendor Scripts -->
<script src="<?= BASE_URL ?>assets/vendor/jszip.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('zip-creator');

  const dropzone = document.getElementById('file-dropzone');
  const fileInput = document.getElementById('file-input');

  const readingCard = document.getElementById('reading-card');
  const readingFilename = document.getElementById('reading-filename');
  const readingFilesize = document.getElementById('reading-filesize');
  const readingProgressFill = document.getElementById('reading-progress-fill');
  const readingPercentText = document.getElementById('reading-percent-text');

  const fileReadyCard = document.getElementById('file-ready-card');
  const filesReadyBadge = document.getElementById('files-ready-badge');
  const fileListContainer = document.getElementById('file-list-container');
  const addMoreBtn = document.getElementById('add-more-btn');
  const resetBtn = document.getElementById('reset-btn');
  const proceedBtn = document.getElementById('proceed-btn');

  const optionsPanel = document.getElementById('options-panel');
  const createZipActionBtn = document.getElementById('create-zip-action-btn');

  const resultContainer = document.getElementById('result-container');
  const resZipSize = document.getElementById('res-zip-size');
  const downloadZipBtn = document.getElementById('download-zip-btn');

  let selectedFiles = [];
  let generatedZipBlob = null;
  let readingTimer = null;

  function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function renderList() {
    fileListContainer.innerHTML = '';
    filesReadyBadge.textContent = `✓ ${selectedFiles.length} File(s) Ready`;

    if (selectedFiles.length === 0) {
      dropzone.style.display = 'block';
      readingCard.style.display = 'none';
      fileReadyCard.style.display = 'none';
      optionsPanel.style.display = 'none';
      resultContainer.style.display = 'none';
      return;
    }

    selectedFiles.forEach((file, index) => {
      const item = document.createElement('div');
      item.className = 'file-item';
      item.innerHTML = `
        <div class="file-item-info">
          <span style="font-weight:700; color:var(--primary);">#${index + 1}</span>
          <div>
            <div class="file-item-name">${file.name}</div>
            <div class="file-item-size">${formatBytes(file.size)}</div>
          </div>
        </div>
        <button class="btn btn-secondary btn-icon remove-file" data-idx="${index}" style="color:var(--error);" title="Remove">✕</button>
      `;
      fileListContainer.appendChild(item);
    });
  }

  function resetState() {
    dropzone.style.display = 'block';
    readingCard.style.display = 'none';
    fileReadyCard.style.display = 'none';
    optionsPanel.style.display = 'none';
    resultContainer.style.display = 'none';
    fileInput.value = '';
    selectedFiles = [];
    generatedZipBlob = null;
    if (readingTimer) clearInterval(readingTimer);
  }

  function processFileSelection(files) {
    if (files.length === 0) return;

    dropzone.style.display = 'none';
    fileReadyCard.style.display = 'none';
    optionsPanel.style.display = 'none';
    resultContainer.style.display = 'none';

    let totalSize = Array.from(files).reduce((acc, f) => acc + f.size, 0);
    readingFilename.textContent = `Reading ${files.length} selected file(s)...`;
    readingFilesize.textContent = formatBytes(totalSize);
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
        Array.from(files).forEach(f => selectedFiles.push(f));
        readingCard.style.display = 'none';
        fileReadyCard.style.display = 'block';
        renderList();
        if (window.Sani) window.Sani.say("Files ready! Click Proceed to create your archive.", "happy");
      }
      readingProgressFill.style.width = `${progress}%`;
      readingPercentText.textContent = `${progress}%`;
    }, 40);
  }

  fileInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files.length > 0) processFileSelection(e.target.files);
  });

  dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) processFileSelection(e.dataTransfer.files);
  });

  fileListContainer.addEventListener('click', (e) => {
    const btn = e.target.closest('.remove-file');
    if (btn) {
      const idx = parseInt(btn.getAttribute('data-idx'));
      selectedFiles.splice(idx, 1);
      renderList();
    }
  });

  addMoreBtn.addEventListener('click', () => fileInput.click());
  resetBtn.addEventListener('click', resetState);

  proceedBtn.addEventListener('click', () => {
    if (selectedFiles.length === 0) {
      showToast("Please add at least one file", "warning");
      return;
    }
    optionsPanel.style.display = 'block';
    optionsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    if (window.Sani) window.Sani.say("Click Create ZIP Archive Now.", "info");
  });

  createZipActionBtn.addEventListener('click', async () => {
    if (selectedFiles.length === 0) return;

    if (typeof JSZip === 'undefined') {
      showToast("JSZip library loading error. Please refresh.", "error");
      return;
    }

    createZipActionBtn.disabled = true;
    createZipActionBtn.innerHTML = '⚡ Processing... Please wait';

    try {
      const zip = new JSZip();
      for (let file of selectedFiles) {
        const buffer = await file.arrayBuffer();
        zip.file(file.name, buffer);
      }

      generatedZipBlob = await zip.generateAsync({ type: 'blob' });

      createZipActionBtn.disabled = false;
      createZipActionBtn.innerHTML = '📦 CREATE .ZIP ARCHIVE NOW';

      resZipSize.textContent = formatBytes(generatedZipBlob.size);
      resultContainer.style.display = 'block';
      resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      showToast("ZIP archive created successfully!", "success");
    } catch (err) {
      createZipActionBtn.disabled = false;
      createZipActionBtn.innerHTML = '📦 CREATE .ZIP ARCHIVE NOW';
      showToast("Error creating ZIP archive: " + err.message, "error");
    }
  });

  downloadZipBtn.addEventListener('click', () => {
    if (!generatedZipBlob) return;
    const link = document.createElement('a');
    link.href = URL.createObjectURL(generatedZipBlob);
    link.download = 'sanix-archive.zip';
    link.click();
    showToast("Downloaded ZIP archive!", "success");
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
