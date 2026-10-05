<?php
$pageTitle = "Image Converter - Sanix Tool";
$pageDesc = "Convert image formats instantly between JPG, PNG, and WEBP directly in your browser.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🔄</div>
        <div>
          <h1 class="tool-header-title">Image Converter</h1>
          <p class="tool-header-desc">Convert images between JPG, PNG, and WEBP formats with 100% privacy.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="image-converter" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="img-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop your image here</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose Image ]</span>
      </div>
      <div class="dropzone-subtext">JPG • PNG • WEBP • GIF • BMP supported</div>
      <input type="file" id="img-input" accept="image/*">
    </div>

    <!-- STEP 2: READING STATE -->
    <div id="reading-card" class="reading-state-card" style="display: none;">
      <div class="state-badge state-badge-info" style="margin-bottom: 0.75rem;">
        <span>⚡ Reading file...</span>
      </div>
      <h3 id="reading-filename" style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.25rem;">image.jpg</h3>
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
        <button class="btn btn-secondary" id="change-file-btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Change Image</button>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1.5rem; align-items: center;">
        <div style="text-align: center; background: var(--input-bg); padding: 0.75rem; border-radius: 12px; border: 1px solid var(--surface-border);">
          <img id="ready-img-preview" src="" alt="Selected Preview" style="max-width: 100%; max-height: 140px; object-fit: contain; border-radius: 6px;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
          <div>
            <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">File Name</small>
            <h4 id="ready-filename" style="font-size: 1.05rem; word-break: break-all; color: var(--text-main);">filename.jpg</h4>
          </div>

          <div style="display: flex; gap: 2rem;">
            <div>
              <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">Current Format</small>
              <div id="ready-format" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">JPG</div>
            </div>
            <div>
              <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">File Size</small>
              <div id="ready-filesize" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">0 MB</div>
            </div>
          </div>
        </div>
      </div>

      <!-- PROCEED BUTTON -->
      <button class="proceed-btn" id="proceed-btn">
        <span>➜ PROCEED TO FORMAT OPTIONS</span>
      </button>
    </div>

    <!-- STEP 4: OPTIONS PANEL -->
    <div id="options-panel" class="card" style="display: none; margin-top: 1.5rem;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.1rem; color: var(--primary);">Choose Target Format</h3>
        <span class="state-badge state-badge-info">Step 4 — Configure</span>
      </div>

      <div class="form-group">
        <label class="form-label">Convert To Format</label>
        <select id="target-format" class="form-control" style="font-size: 1rem; font-weight: 600;">
          <option value="image/png">PNG (.png) — High Quality & Transparency</option>
          <option value="image/jpeg">JPG / JPEG (.jpg) — Universal & Compact</option>
          <option value="image/webp" selected>WEBP (.webp) — Modern Web Format</option>
        </select>
      </div>

      <div style="margin-top: 1rem;">
        <button class="btn btn-primary" id="convert-action-btn" style="width: 100%; padding: 0.95rem; font-size: 1.15rem; font-weight: 800; letter-spacing: 0.5px; box-shadow: var(--glow-shadow);">
          🔄 CONVERT IMAGE FORMAT NOW
        </button>
      </div>
    </div>

    <!-- STEP 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem;">
        <span class="result-title" style="color: var(--success); font-size: 1.25rem; font-weight: 800;">✓ Format Conversion Complete</span>
      </div>

      <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem; align-items: center;">
        <div style="text-align: center; background: var(--input-bg); padding: 0.75rem; border-radius: 12px; border: 1px solid var(--success-bg);">
          <small style="color: var(--success); font-weight: 700; display: block; margin-bottom: 0.4rem;">CONVERTED PREVIEW</small>
          <img id="output-img-preview" src="" alt="Output Preview" style="max-width: 100%; max-height: 150px; object-fit: contain; border-radius: 6px;">
        </div>

        <div>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div class="card" style="text-align: center; padding: 0.85rem; background: var(--accent-glow); border-color: var(--primary);">
              <small style="color: var(--primary); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">New Format</small>
              <div id="res-format-text" style="font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-top: 0.2rem;">WEBP</div>
            </div>

            <div class="card" style="text-align: center; padding: 0.85rem; background: var(--success-bg); border-color: var(--success);">
              <small style="color: var(--success); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Output Size</small>
              <div id="res-size-text" style="font-size: 1.1rem; font-weight: 800; color: var(--success); margin-top: 0.2rem;">0 KB</div>
            </div>
          </div>

          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-primary" id="download-btn" style="flex: 2; padding: 0.9rem; font-size: 1.1rem; font-weight: 800; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border: none;">
              ⬇️ DOWNLOAD CONVERTED IMAGE
            </button>
            <button class="btn btn-secondary" id="result-reset-btn" style="flex: 1; padding: 0.9rem;">
              ↻ Start Over
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('image-converter');

  const dropzone = document.getElementById('img-dropzone');
  const imgInput = document.getElementById('img-input');

  const readingCard = document.getElementById('reading-card');
  const readingFilename = document.getElementById('reading-filename');
  const readingFilesize = document.getElementById('reading-filesize');
  const readingProgressFill = document.getElementById('reading-progress-fill');
  const readingPercentText = document.getElementById('reading-percent-text');

  const fileReadyCard = document.getElementById('file-ready-card');
  const readyFilename = document.getElementById('ready-filename');
  const readyFilesize = document.getElementById('ready-filesize');
  const readyFormat = document.getElementById('ready-format');
  const readyImgPreview = document.getElementById('ready-img-preview');
  const changeFileBtn = document.getElementById('change-file-btn');
  const proceedBtn = document.getElementById('proceed-btn');

  const optionsPanel = document.getElementById('options-panel');
  const targetFormat = document.getElementById('target-format');
  const convertActionBtn = document.getElementById('convert-action-btn');

  const resultContainer = document.getElementById('result-container');
  const outputImgPreview = document.getElementById('output-img-preview');
  const resFormatText = document.getElementById('res-format-text');
  const resSizeText = document.getElementById('res-size-text');
  const downloadBtn = document.getElementById('download-btn');
  const resultResetBtn = document.getElementById('result-reset-btn');

  let currentFile = null;
  let objectUrl = null;
  let convertedBlob = null;
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
    optionsPanel.style.display = 'none';
    resultContainer.style.display = 'none';
    imgInput.value = '';

    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = null;
    currentFile = null;
    convertedBlob = null;
    if (readingTimer) clearInterval(readingTimer);
  }

  function processFileSelection(file) {
    if (!file || !file.type.startsWith('image/')) {
      showToast("Please select a valid image file", "error");
      return;
    }

    currentFile = file;

    dropzone.style.display = 'none';
    fileReadyCard.style.display = 'none';
    optionsPanel.style.display = 'none';
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
        finishFileReading(file);
      }
      readingProgressFill.style.width = `${progress}%`;
      readingPercentText.textContent = `${progress}%`;
    }, 40);
  }

  function finishFileReading(file) {
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = URL.createObjectURL(file);
    readyImgPreview.src = objectUrl;

    readyFilename.textContent = file.name;
    readyFilesize.textContent = formatBytes(file.size);
    readyFormat.textContent = (file.type.split('/')[1] || 'image').toUpperCase();

    readingCard.style.display = 'none';
    fileReadyCard.style.display = 'block';

    if (window.Sani) {
      window.Sani.say("File ready! Click Proceed to select your output format.", "happy");
    }
  }

  imgInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files.length > 0) processFileSelection(e.target.files[0]);
  });

  dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('dragover');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) processFileSelection(e.dataTransfer.files[0]);
  });

  proceedBtn.addEventListener('click', () => {
    optionsPanel.style.display = 'block';
    optionsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    if (window.Sani) {
      window.Sani.say("Choose format and click Convert Image Format Now.", "info");
    }
  });

  convertActionBtn.addEventListener('click', () => {
    if (!currentFile || !objectUrl) return;

    convertActionBtn.disabled = true;
    convertActionBtn.innerHTML = '⚡ Processing... Please wait';

    setTimeout(() => {
      const img = new Image();
      img.onload = () => {
        try {
          const canvas = document.createElement('canvas');
          canvas.width = img.naturalWidth || img.width;
          canvas.height = img.naturalHeight || img.height;
          const ctx = canvas.getContext('2d');

          const mime = targetFormat.value;
          if (mime === 'image/jpeg') {
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
          }
          ctx.drawImage(img, 0, 0);

          canvas.toBlob((blob) => {
            convertActionBtn.disabled = false;
            convertActionBtn.innerHTML = '🔄 CONVERT IMAGE FORMAT NOW';

            if (!blob) {
              showToast("Failed to convert image format", "error");
              return;
            }

            convertedBlob = blob;
            outputImgPreview.src = URL.createObjectURL(blob);
            const ext = mime.split('/')[1].toUpperCase();
            resFormatText.textContent = ext;
            resSizeText.textContent = formatBytes(blob.size);

            resultContainer.style.display = 'block';
            resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            showToast(`Converted to ${ext} format!`, "success");
          }, mime, 0.92);
        } catch (err) {
          convertActionBtn.disabled = false;
          convertActionBtn.innerHTML = '🔄 CONVERT IMAGE FORMAT NOW';
          showToast("Error converting image format", "error");
        }
      };
      img.src = objectUrl;
    }, 150);
  });

  downloadBtn.addEventListener('click', () => {
    if (!convertedBlob) return;
    const ext = targetFormat.value.split('/')[1];
    const baseName = currentFile.name.substring(0, currentFile.name.lastIndexOf('.')) || 'image';
    const link = document.createElement('a');
    link.href = URL.createObjectURL(convertedBlob);
    link.download = `${baseName}-converted.${ext}`;
    link.click();
    showToast("Downloaded converted image!", "success");
  });

  changeFileBtn.addEventListener('click', resetState);
  resultResetBtn.addEventListener('click', resetState);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
