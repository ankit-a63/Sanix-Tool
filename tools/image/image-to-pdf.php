<?php
// Prevent browser caching during active development
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$pageTitle = "Image to PDF Converter - Sanix Tool";
$pageDesc = "Convert single or multiple JPG, PNG and WEBP images into a clean, professional PDF document with page size, orientation, and margin controls.";
require_once __DIR__ . '/../../includes/header.php';
?>

<style>
.pdf-conv-container {
  max-width: 960px;
  margin: 0 auto;
  background: var(--surface, #1e293b);
  border: 1px solid var(--surface-border, rgba(255,255,255,0.1));
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.pdf-dropzone {
  border: 2px dashed var(--primary, #00f2fe);
  border-radius: 16px;
  padding: 3.5rem 2rem;
  text-align: center;
  background: var(--input-bg, #0f172a);
  cursor: pointer;
  position: relative;
  transition: all 0.25s ease;
}

.pdf-dropzone:hover, .pdf-dropzone.dragover {
  background: rgba(0, 242, 254, 0.08);
  border-color: #00c6ff;
  box-shadow: 0 0 20px rgba(0, 242, 254, 0.25);
}

.pdf-card {
  background: var(--input-bg, #0f172a);
  border: 1px solid var(--surface-border, rgba(255,255,255,0.1));
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 1.5rem;
}

.image-item-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(255,255,255,0.03);
  border: 1px solid var(--surface-border);
  border-radius: 10px;
  padding: 0.75rem 1rem;
  margin-bottom: 0.6rem;
}

.image-item-thumb {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid var(--surface-border);
}
</style>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="pdf-conv-container">
    
    <!-- TOOL HEADER -->
    <div class="tool-header" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--surface-border);">
      <div class="tool-header-info" style="display: flex; align-items: center; gap: 1rem;">
        <div class="tool-icon" style="font-size: 2.2rem;">📄</div>
        <div>
          <h1 class="tool-header-title" style="font-size: 1.6rem; margin: 0;">Image to PDF Converter</h1>
          <p class="tool-header-desc" style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">Combine multiple photos, scanned documents, or graphics into a single organized PDF file.</p>
        </div>
      </div>
    </div>

    <!-- PRIVACY NOTIFICATION -->
    <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.4rem; background: rgba(0, 242, 254, 0.05); padding: 0.5rem 0.85rem; border-radius: 8px; border: 1px solid rgba(0, 242, 254, 0.15);">
      🔒 <span>Your images are processed 100% locally in your browser. No files uploaded.</span>
    </div>

    <!-- 1. DROPZONE -->
    <div id="pdfDropzone" class="pdf-dropzone">
      <div style="font-size: 3.2rem; margin-bottom: 0.5rem;">📁</div>
      <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">Drop your images here (Multiple allowed)</div>
      <div style="margin: 0.85rem 0;">
        <button type="button" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-size: 1.05rem; pointer-events: none;">[ Choose Image Files ]</button>
      </div>
      <div style="font-size: 0.88rem; color: var(--text-muted);">Supported Formats: <strong>JPG • PNG • WEBP</strong></div>
      <input type="file" id="pdfFileInput" accept="image/*" multiple style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 20;" onchange="if(this.files && this.files.length > 0 && window.handlePdfFiles) window.handlePdfFiles(this.files);">
    </div>

    <!-- 2. READING STATE -->
    <div id="pdfReading" class="pdf-card" style="display: none; text-align: center; padding: 2.5rem 1.5rem;">
      <span class="btn btn-secondary" style="pointer-events: none; margin-bottom: 0.75rem;">⚡ Reading files...</span>
      <h3 id="pdfReadingFileName" style="font-size: 1.15rem; color: var(--text-main);">Reading selected images...</h3>
      <small id="pdfReadingFileSize" style="color: var(--text-muted);">Calculating size...</small>
      <div style="width: 100%; height: 12px; background: rgba(255,255,255,0.08); border-radius: 999px; overflow: hidden; margin: 1.25rem 0 0.5rem 0;">
        <div id="pdfReadingProgressFill" style="height: 100%; width: 0%; background: linear-gradient(90deg, #00f2fe, #00c6ff); border-radius: 999px; transition: width 0.15s ease;"></div>
      </div>
      <div id="pdfReadingPercentText" style="font-size: 0.95rem; font-weight: 800; color: var(--primary);">0%</div>
    </div>

    <!-- 3. FILES READY & MANAGEMENT -->
    <div id="pdfReady" class="pdf-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span id="pdfReadyBadge" style="color: #10b981; font-weight: 800; font-size: 0.95rem;">✓ 0 Image(s) Ready</span>
        <div style="display: flex; gap: 0.5rem;">
          <button type="button" class="btn btn-secondary" id="pdfAddMoreBtn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">➕ Add More Images</button>
          <button type="button" class="btn btn-secondary" id="pdfClearAllBtn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Clear All</button>
        </div>
      </div>

      <small style="color: var(--text-muted); display: block; margin-bottom: 0.75rem; font-weight: 700;">REORDER OR REMOVE IMAGES:</small>
      <div id="pdfFileListContainer"></div>

      <button type="button" id="pdfProceedBtn" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 1rem; font-size: 1.15rem; font-weight: 800;">
        ➜ PROCEED TO CONFIGURE PDF PAGE LAYOUT
      </button>
    </div>

    <!-- 4. OPTIONS STATE -->
    <div id="pdfOptions" class="pdf-card" style="display: none;">
      <h3 style="font-size: 1.15rem; color: var(--primary); margin-top: 0; margin-bottom: 1.25rem;">Configure PDF Document Options</h3>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
        <div class="form-group">
          <label style="font-weight: 600;">Page Size</label>
          <select id="pdfPageSizeSelect" class="form-control">
            <option value="A4" selected>A4 Standard (595 × 842 pt)</option>
            <option value="A5">A5 Compact (420 × 595 pt)</option>
            <option value="LETTER">US Letter (612 × 792 pt)</option>
            <option value="LEGAL">US Legal (612 × 1008 pt)</option>
            <option value="FIT">Fit Page to Image Dimensions</option>
          </select>
        </div>

        <div class="form-group">
          <label style="font-weight: 600;">Page Orientation</label>
          <select id="pdfOrientationSelect" class="form-control">
            <option value="portrait" selected>Portrait (Vertical)</option>
            <option value="landscape">Landscape (Horizontal)</option>
          </select>
        </div>

        <div class="form-group">
          <label style="font-weight: 600;">Page Margins</label>
          <select id="pdfMarginSelect" class="form-control">
            <option value="0">No Margin (Full Bleed)</option>
            <option value="15" selected>Small Margin (15 pt)</option>
            <option value="30">Medium Margin (30 pt)</option>
            <option value="50">Large Margin (50 pt)</option>
          </select>
        </div>

        <div class="form-group">
          <label style="font-weight: 600;">Image Fitting Mode</label>
          <select id="pdfFitSelect" class="form-control">
            <option value="fit" selected>Fit inside Margins (Maintain Ratio)</option>
            <option value="fill">Fill Page (Cover)</option>
            <option value="original">Original Image Dimensions</option>
          </select>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="form-group">
          <label style="font-weight: 600;">Image Quality</label>
          <select id="pdfQualitySelect" class="form-control">
            <option value="0.95" selected>High Quality (95%)</option>
            <option value="0.75">Medium Quality (75% - Compressed)</option>
            <option value="0.50">Low Quality (50% - Small File)</option>
          </select>
        </div>

        <div class="form-group">
          <label style="font-weight: 600;">PDF Filename</label>
          <input type="text" id="pdfFilenameInput" value="sanix-document.pdf" class="form-control">
        </div>
      </div>

      <button type="button" id="generatePdfBtn" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1.15rem; font-weight: 800;">
        📄 GENERATE PDF DOCUMENT NOW
      </button>
    </div>

    <!-- 5. PROCESSING STATE -->
    <div id="pdfProcessing" class="pdf-card" style="display: none; text-align: center; padding: 2rem;">
      <span class="btn btn-secondary" style="pointer-events: none;">⚡ Generating PDF Document... Please wait</span>
    </div>

    <!-- 6. RESULT STATE -->
    <div id="pdfResult" class="pdf-card" style="display: none; background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.3);">
      <div style="margin-bottom: 1.25rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span style="color: #10b981; font-size: 1.25rem; font-weight: 800;">✓ PDF Document Generated Successfully</span>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="text-align: center; padding: 1rem; background: var(--input-bg);">
          <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">TOTAL PAGES</small>
          <h3 id="resPdfPagesText" style="color: var(--primary); margin: 0.3rem 0 0 0;">0 Pages</h3>
        </div>

        <div class="card" style="text-align: center; padding: 1rem; background: var(--success-bg); border-color: var(--success);">
          <small style="color: #10b981; font-size: 0.75rem; font-weight: 700;">GENERATED PDF SIZE</small>
          <h3 id="resPdfSizeText" style="color: #10b981; margin: 0.3rem 0 0 0;">0 KB</h3>
        </div>
      </div>

      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a id="downloadPdfFileBtn" href="#" download="sanix-document.pdf" class="btn btn-primary" style="flex: 2; padding: 0.95rem; font-size: 1.1rem; font-weight: 800; background: linear-gradient(135deg, #10b981, #059669); border: none; text-decoration: none; text-align: center;">
          ⬇ DOWNLOAD PDF DOCUMENT
        </a>
        <button type="button" id="pdfResetBtn" class="btn btn-secondary" style="flex: 1; padding: 0.95rem;">
          ↻ START OVER
        </button>
      </div>
    </div>

  </div>
</div>

<!-- Vendor PDF-Lib Script -->
<script src="<?= BASE_URL ?>assets/vendor/pdf-lib.min.js"></script>
<script>
(function() {
  function initImageToPdfController() {
    const dropzone = document.getElementById('pdfDropzone');
    const readingCard = document.getElementById('pdfReading');
    const readyCard = document.getElementById('pdfReady');
    const optionsCard = document.getElementById('pdfOptions');
    const processingCard = document.getElementById('pdfProcessing');
    const resultCard = document.getElementById('pdfResult');

    const fileInput = document.getElementById('pdfFileInput');
    const pdfAddMoreBtn = document.getElementById('pdfAddMoreBtn');
    const pdfClearAllBtn = document.getElementById('pdfClearAllBtn');
    const pdfProceedBtn = document.getElementById('pdfProceedBtn');

    const pdfReadingFileName = document.getElementById('pdfReadingFileName');
    const pdfReadingFileSize = document.getElementById('pdfReadingFileSize');
    const pdfReadingProgressFill = document.getElementById('pdfReadingProgressFill');
    const pdfReadingPercentText = document.getElementById('pdfReadingPercentText');

    const pdfReadyBadge = document.getElementById('pdfReadyBadge');
    const fileListContainer = document.getElementById('pdfFileListContainer');

    const pdfPageSizeSelect = document.getElementById('pdfPageSizeSelect');
    const pdfOrientationSelect = document.getElementById('pdfOrientationSelect');
    const pdfMarginSelect = document.getElementById('pdfMarginSelect');
    const pdfFitSelect = document.getElementById('pdfFitSelect');
    const pdfQualitySelect = document.getElementById('pdfQualitySelect');
    const pdfFilenameInput = document.getElementById('pdfFilenameInput');
    const generatePdfBtn = document.getElementById('generatePdfBtn');

    const resPdfPagesText = document.getElementById('resPdfPagesText');
    const resPdfSizeText = document.getElementById('resPdfSizeText');
    const downloadPdfFileBtn = document.getElementById('downloadPdfFileBtn');
    const pdfResetBtn = document.getElementById('pdfResetBtn');

    let imageFiles = [];
    let generatedPdfBlobUrl = null;
    let progressTimer = null;

    function formatBytes(bytes) {
      if (!bytes || bytes === 0) return '0 Bytes';
      const k = 1024;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showState(card) {
      dropzone.style.display = 'none';
      readingCard.style.display = 'none';
      readyCard.style.display = 'none';
      optionsCard.style.display = 'none';
      processingCard.style.display = 'none';
      resultCard.style.display = 'none';
      if (card) card.style.display = 'block';
    }

    function resetAll() {
      fileInput.value = '';
      imageFiles = [];
      if (generatedPdfBlobUrl) URL.revokeObjectURL(generatedPdfBlobUrl);
      generatedPdfBlobUrl = null;
      if (progressTimer) clearInterval(progressTimer);
      showState(dropzone);
    }

    function renderFileList() {
      fileListContainer.innerHTML = '';
      pdfReadyBadge.textContent = `✓ ${imageFiles.length} Image(s) Ready`;

      if (imageFiles.length === 0) {
        resetAll();
        return;
      }

      imageFiles.forEach((file, index) => {
        const row = document.createElement('div');
        row.className = 'image-item-row';

        const thumbUrl = URL.createObjectURL(file);

        row.innerHTML = `
          <div style="display: flex; align-items: center; gap: 0.85rem;">
            <span style="font-weight: 800; color: var(--primary);">#${index + 1}</span>
            <img src="${thumbUrl}" class="image-item-thumb" alt="Thumb">
            <div>
              <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; word-break: break-all;">${file.name}</div>
              <small style="color: var(--text-muted); font-size: 0.78rem;">${formatBytes(file.size)}</small>
            </div>
          </div>
          <div style="display: flex; gap: 0.4rem;">
            ${index > 0 ? `<button type="button" class="btn btn-secondary move-up-btn" data-idx="${index}" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;" title="Move Up">⬆️</button>` : ''}
            ${index < imageFiles.length - 1 ? `<button type="button" class="btn btn-secondary move-down-btn" data-idx="${index}" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;" title="Move Down">⬇️</button>` : ''}
            <button type="button" class="btn btn-secondary remove-item-btn" data-idx="${index}" style="padding: 0.25rem 0.6rem; font-size: 0.8rem; color: #ef4444;" title="Remove">✕</button>
          </div>
        `;
        fileListContainer.appendChild(row);
      });
    }

    function handlePdfFiles(files) {
      const valid = Array.from(files).filter(f => f.type.startsWith('image/'));
      if (valid.length === 0) {
        if (window.showToast) window.showToast("Please select valid image files", "error");
        return;
      }

      pdfReadingFileName.textContent = `Reading ${valid.length} selected image file(s)...`;
      const totalBytes = valid.reduce((acc, f) => acc + f.size, 0);
      pdfReadingFileSize.textContent = formatBytes(totalBytes);
      showState(readingCard);

      let progress = 0;
      pdfReadingProgressFill.style.width = '0%';
      pdfReadingPercentText.textContent = '0%';
      if (progressTimer) clearInterval(progressTimer);

      progressTimer = setInterval(() => {
        progress += Math.floor(Math.random() * 25) + 20;
        if (progress >= 100) {
          progress = 100;
          clearInterval(progressTimer);
          valid.forEach(f => imageFiles.push(f));
          showState(readyCard);
          renderFileList();
        }
        pdfReadingProgressFill.style.width = progress + '%';
        pdfReadingPercentText.textContent = progress + '%';
      }, 30);
    }
    window.handlePdfFiles = handlePdfFiles;

    fileListContainer.addEventListener('click', function(e) {
      const btn = e.target.closest('button');
      if (!btn) return;

      const idx = parseInt(btn.getAttribute('data-idx'));
      if (btn.classList.contains('remove-item-btn')) {
        imageFiles.splice(idx, 1);
        renderFileList();
      } else if (btn.classList.contains('move-up-btn') && idx > 0) {
        const tmp = imageFiles[idx];
        imageFiles[idx] = imageFiles[idx - 1];
        imageFiles[idx - 1] = tmp;
        renderFileList();
      } else if (btn.classList.contains('move-down-btn') && idx < imageFiles.length - 1) {
        const tmp = imageFiles[idx];
        imageFiles[idx] = imageFiles[idx + 1];
        imageFiles[idx + 1] = tmp;
        renderFileList();
      }
    });

    pdfAddMoreBtn.addEventListener('click', () => fileInput.click());
    pdfClearAllBtn.addEventListener('click', resetAll);

    pdfProceedBtn.addEventListener('click', function() {
      if (imageFiles.length === 0) return;
      readyCard.style.display = 'block';
      optionsCard.style.display = 'block';
      optionsCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    // Generate PDF Execution
    generatePdfBtn.addEventListener('click', async function() {
      if (imageFiles.length === 0) return;
      if (typeof PDFLib === 'undefined') {
        if (window.showToast) window.showToast("PDF-Lib engine not loaded. Please refresh.", "error");
        return;
      }

      generatePdfBtn.disabled = true;
      showState(processingCard);

      try {
        const pdfDoc = await PDFLib.PDFDocument.create();
        const margin = parseInt(pdfMarginSelect.value) || 0;
        const isLandscape = pdfOrientationSelect.value === 'landscape';
        const sizeMode = pdfPageSizeSelect.value;
        const quality = parseFloat(pdfQualitySelect.value) || 0.95;

        for (let file of imageFiles) {
          let pdfImg;

          if (file.type.includes('png')) {
            const buffer = await file.arrayBuffer();
            pdfImg = await pdfDoc.embedPng(buffer);
          } else {
            // Convert WEBP or JPG via Canvas to JPEG buffer
            const canvas = document.createElement('canvas');
            const img = await new Promise((res) => {
              const i = new Image();
              i.onload = () => res(i);
              i.src = URL.createObjectURL(file);
            });

            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);

            const jpgBlob = await new Promise(r => canvas.toBlob(r, 'image/jpeg', quality));
            const jpgBuffer = await jpgBlob.arrayBuffer();
            pdfImg = await pdfDoc.embedJpg(jpgBuffer);
          }

          let pageWidth = 595.28; // A4 default
          let pageHeight = 841.89;

          if (sizeMode === 'A5') { pageWidth = 420; pageHeight = 595; }
          else if (sizeMode === 'LETTER') { pageWidth = 612; pageHeight = 792; }
          else if (sizeMode === 'LEGAL') { pageWidth = 612; pageHeight = 1008; }
          else if (sizeMode === 'FIT') { pageWidth = pdfImg.width + margin * 2; pageHeight = pdfImg.height + margin * 2; }

          if (isLandscape && sizeMode !== 'FIT') {
            const tmp = pageWidth;
            pageWidth = pageHeight;
            pageHeight = tmp;
          }

          const page = pdfDoc.addPage([pageWidth, pageHeight]);
          const availW = pageWidth - margin * 2;
          const availH = pageHeight - margin * 2;

          const fitMode = pdfFitSelect.value;
          let drawW = availW, drawH = availH;

          if (fitMode === 'original') {
            drawW = pdfImg.width;
            drawH = pdfImg.height;
          } else if (fitMode === 'fit') {
            const scale = Math.min(availW / pdfImg.width, availH / pdfImg.height);
            drawW = pdfImg.width * scale;
            drawH = pdfImg.height * scale;
          }

          const x = (pageWidth - drawW) / 2;
          const y = (pageHeight - drawH) / 2;

          page.drawImage(pdfImg, { x, y, width: drawW, height: drawH });
        }

        const pdfBytes = await pdfDoc.save();
        const pdfBlob = new Blob([pdfBytes], { type: 'application/pdf' });

        if (generatedPdfBlobUrl) URL.revokeObjectURL(generatedPdfBlobUrl);
        generatedPdfBlobUrl = URL.createObjectURL(pdfBlob);

        let filename = pdfFilenameInput.value.trim() || 'sanix-document.pdf';
        if (!filename.endsWith('.pdf')) filename += '.pdf';

        downloadPdfFileBtn.href = generatedPdfBlobUrl;
        downloadPdfFileBtn.download = filename;

        resPdfPagesText.textContent = imageFiles.length + " Page(s)";
        resPdfSizeText.textContent = formatBytes(pdfBlob.size);

        generatePdfBtn.disabled = false;
        showState(resultCard);
        if (window.showToast) window.showToast("PDF document generated successfully!", "success");
      } catch (err) {
        generatePdfBtn.disabled = false;
        if (window.showToast) window.showToast("Error generating PDF: " + err.message, "error");
      }
    });

    pdfResetBtn.addEventListener('click', resetAll);

    resetAll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initImageToPdfController);
  } else {
    initImageToPdfController();
  }
})();
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
