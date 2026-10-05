<?php
$pageTitle = "PDF Merger - Sanix Tool";
$pageDesc = "Merge multiple PDF documents into a single organized PDF file directly in your browser.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">📚</div>
        <div>
          <h1 class="tool-header-title">PDF Merger</h1>
          <p class="tool-header-desc">Combine multiple PDF files into one single PDF document effortlessly.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="pdf-merger" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="pdf-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop your PDF files here (Multiple allowed)</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose PDF Files ]</span>
      </div>
      <div class="dropzone-subtext">Merge unlimited PDF files securely in browser</div>
      <input type="file" id="pdf-input" accept="application/pdf" multiple>
    </div>

    <!-- STEP 2: READING STATE -->
    <div id="reading-card" class="reading-state-card" style="display: none;">
      <div class="state-badge state-badge-info" style="margin-bottom: 0.75rem;">
        <span>⚡ Reading files...</span>
      </div>
      <h3 id="reading-filename" style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">Processing selected PDF files...</h3>
      <small id="reading-filesize" style="color: var(--text-muted); font-size: 0.85rem;">Calculating total size...</small>
      
      <div class="progress-container">
        <div class="progress-bar-fill" id="reading-progress-fill"></div>
      </div>
      <div id="reading-percent-text" style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">0%</div>
    </div>

    <!-- STEP 3: FILES READY & PROCEED -->
    <div id="file-ready-card" class="file-ready-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span class="state-badge state-badge-success" id="files-ready-badge">✓ 0 PDFs Ready</span>
        <div style="display:flex; gap:0.5rem;">
          <button class="btn btn-secondary" id="add-more-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">➕ Add More</button>
          <button class="btn btn-secondary" id="reset-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Clear All</button>
        </div>
      </div>

      <!-- FILE LIST CONTAINER -->
      <h4 style="margin-bottom: 0.75rem; font-size: 0.95rem; color: var(--text-muted);">Reorder Merge Sequence:</h4>
      <div id="file-list-container" class="file-list" style="margin-bottom: 1rem;"></div>

      <!-- PROCEED BUTTON -->
      <button class="proceed-btn" id="proceed-btn">
        <span>➜ PROCEED TO MERGE ACTION</span>
      </button>
    </div>

    <!-- STEP 4: OPTIONS PANEL -->
    <div id="options-panel" class="card" style="display: none; margin-top: 1.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.1rem; color: var(--primary);">Execute Merge</h3>
        <span class="state-badge state-badge-info">Step 4 — Action</span>
      </div>

      <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">
        Your PDF files will be merged in the exact order shown above into a single PDF document.
      </p>

      <div style="margin-top: 1rem;">
        <button class="btn btn-primary" id="merge-action-btn" style="width: 100%; padding: 0.95rem; font-size: 1.15rem; font-weight: 800; letter-spacing: 0.5px; box-shadow: var(--glow-shadow);">
          🔗 MERGE PDFS NOW
        </button>
      </div>
    </div>

    <!-- STEP 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem;">
        <span class="result-title" style="color: var(--success); font-size: 1.25rem; font-weight: 800;">✓ PDF Merge Complete</span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; align-items: center;">
        <div class="card" style="text-align: center; padding: 1rem; background: var(--input-bg);">
          <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Total Merged Pages</small>
          <h3 id="res-total-pages" style="color: var(--primary); margin-top: 0.3rem;">0 Pages</h3>
        </div>

        <div class="card" style="text-align: center; padding: 1rem; background: var(--success-bg); border-color: var(--success);">
          <small style="color: var(--success); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Merged File Size</small>
          <h3 id="res-merged-size" style="color: var(--success); margin-top: 0.3rem;">0 KB</h3>
        </div>

        <button class="btn btn-primary" id="download-merged-btn" style="padding: 0.9rem; font-size: 1.1rem; font-weight: 800; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border: none;">
          ⬇️ DOWNLOAD MERGED PDF
        </button>
      </div>
    </div>

  </div>
</div>

<!-- Vendor Scripts -->
<script src="<?= BASE_URL ?>assets/vendor/pdf-lib.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('pdf-merger');

  const dropzone = document.getElementById('pdf-dropzone');
  const pdfInput = document.getElementById('pdf-input');

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
  const mergeActionBtn = document.getElementById('merge-action-btn');

  const resultContainer = document.getElementById('result-container');
  const resTotalPages = document.getElementById('res-total-pages');
  const resMergedSize = document.getElementById('res-merged-size');
  const downloadMergedBtn = document.getElementById('download-merged-btn');

  let pdfFiles = [];
  let mergedPdfBlob = null;
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
    filesReadyBadge.textContent = `✓ ${pdfFiles.length} PDF(s) Ready`;

    if (pdfFiles.length === 0) {
      dropzone.style.display = 'block';
      readingCard.style.display = 'none';
      fileReadyCard.style.display = 'none';
      optionsPanel.style.display = 'none';
      resultContainer.style.display = 'none';
      return;
    }

    pdfFiles.forEach((file, index) => {
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
        <div style="display:flex; gap:0.4rem;">
          ${index > 0 ? `<button class="btn btn-secondary btn-icon move-up" data-idx="${index}" title="Move Up">⬆️</button>` : ''}
          ${index < pdfFiles.length - 1 ? `<button class="btn btn-secondary btn-icon move-down" data-idx="${index}" title="Move Down">⬇️</button>` : ''}
          <button class="btn btn-secondary btn-icon remove-file" data-idx="${index}" style="color:var(--error);" title="Remove">✕</button>
        </div>
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
    pdfInput.value = '';
    pdfFiles = [];
    mergedPdfBlob = null;
    if (readingTimer) clearInterval(readingTimer);
  }

  function processFileSelection(files) {
    const valid = Array.from(files).filter(f => f.type.includes('pdf') || f.name.toLowerCase().endsWith('.pdf'));
    if (valid.length === 0) {
      showToast("Please select valid PDF files", "error");
      return;
    }

    dropzone.style.display = 'none';
    fileReadyCard.style.display = 'none';
    optionsPanel.style.display = 'none';
    resultContainer.style.display = 'none';

    let totalSize = valid.reduce((acc, f) => acc + f.size, 0);
    readingFilename.textContent = `Reading ${valid.length} selected PDF file(s)...`;
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
        valid.forEach(f => pdfFiles.push(f));
        readingCard.style.display = 'none';
        fileReadyCard.style.display = 'block';
        renderList();
        if (window.Sani) window.Sani.say("PDF files ready! Click Proceed to merge.", "happy");
      }
      readingProgressFill.style.width = `${progress}%`;
      readingPercentText.textContent = `${progress}%`;
    }, 40);
  }

  pdfInput.addEventListener('change', (e) => {
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
    const btn = e.target.closest('button');
    if (!btn) return;
    const idx = parseInt(btn.getAttribute('data-idx'));
    if (btn.classList.contains('remove-file')) {
      pdfFiles.splice(idx, 1);
      renderList();
    } else if (btn.classList.contains('move-up') && idx > 0) {
      const tmp = pdfFiles[idx];
      pdfFiles[idx] = pdfFiles[idx - 1];
      pdfFiles[idx - 1] = tmp;
      renderList();
    } else if (btn.classList.contains('move-down') && idx < pdfFiles.length - 1) {
      const tmp = pdfFiles[idx];
      pdfFiles[idx] = pdfFiles[idx + 1];
      pdfFiles[idx + 1] = tmp;
      renderList();
    }
  });

  addMoreBtn.addEventListener('click', () => pdfInput.click());
  resetBtn.addEventListener('click', resetState);

  proceedBtn.addEventListener('click', () => {
    if (pdfFiles.length < 2) {
      showToast("Please add at least 2 PDF files to merge", "warning");
      return;
    }
    optionsPanel.style.display = 'block';
    optionsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    if (window.Sani) window.Sani.say("Click Merge PDFs Now to create your document.", "info");
  });

  mergeActionBtn.addEventListener('click', async () => {
    if (pdfFiles.length < 2) return;

    if (typeof PDFLib === 'undefined') {
      showToast("PDF Library loading error. Please refresh.", "error");
      return;
    }

    mergeActionBtn.disabled = true;
    mergeActionBtn.innerHTML = '⚡ Processing... Please wait';

    try {
      const mergedPdf = await PDFLib.PDFDocument.create();
      let totalPagesCount = 0;

      for (let file of pdfFiles) {
        const buffer = await file.arrayBuffer();
        const doc = await PDFLib.PDFDocument.load(buffer);
        const copiedPages = await mergedPdf.copyPages(doc, doc.getPageIndices());
        copiedPages.forEach((page) => mergedPdf.addPage(page));
        totalPagesCount += doc.getPageCount();
      }

      const mergedBytes = await mergedPdf.save();
      mergedPdfBlob = new Blob([mergedBytes], { type: 'application/pdf' });

      mergeActionBtn.disabled = false;
      mergeActionBtn.innerHTML = '🔗 MERGE PDFS NOW';

      resTotalPages.textContent = `${totalPagesCount} Pages`;
      resMergedSize.textContent = formatBytes(mergedPdfBlob.size);

      resultContainer.style.display = 'block';
      resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      showToast("PDFs merged successfully!", "success");
    } catch (err) {
      mergeActionBtn.disabled = false;
      mergeActionBtn.innerHTML = '🔗 MERGE PDFS NOW';
      showToast("Error merging PDFs: " + err.message, "error");
    }
  });

  downloadMergedBtn.addEventListener('click', () => {
    if (!mergedPdfBlob) return;
    const link = document.createElement('a');
    link.href = URL.createObjectURL(mergedPdfBlob);
    link.download = 'sanix-merged.pdf';
    link.click();
    showToast("Downloaded merged PDF!", "success");
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
