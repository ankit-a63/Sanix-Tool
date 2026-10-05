<?php
$pageTitle = "Image Cropper - Sanix Tool";
$pageDesc = "Crop images with precision drag handles, aspect ratios (1:1, 16:9, 4:3), zoom and rotation.";
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Vendor Styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/cropper.min.css">

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace" style="max-width: 900px; margin: 0 auto;">
    
    <!-- TOOL HEADER -->
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">✂️</div>
        <div>
          <h1 class="tool-header-title">Image Cropper</h1>
          <p class="tool-header-desc">Crop your photos and graphics into custom aspect ratios with precision.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="image-cropper" title="Favorite this tool">🤍</button>
    </div>

    <!-- STEP 1: FILE SELECTION DROPZONE -->
    <div class="dropzone" id="img-dropzone">
      <div class="dropzone-icon">📁</div>
      <div class="dropzone-text" style="font-size: 1.25rem; font-weight: 700;">Drop your image here</div>
      <div style="margin: 0.75rem 0;">
        <span class="btn btn-primary" style="pointer-events: none;">[ Choose Image ]</span>
      </div>
      <div class="dropzone-subtext">JPG • PNG • WEBP supported</div>
      <input type="file" id="img-input" accept="image/jpeg,image/png,image/webp">
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
              <small style="color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700;">Original Dimensions</small>
              <div id="ready-dimensions" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">0 × 0 px</div>
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
        <span>➜ PROCEED TO CROP WORKSPACE</span>
      </button>
    </div>

    <!-- STEP 4: CROPPER WORKSPACE & CONTROLS -->
    <div id="options-panel" style="display: none; margin-top: 1.5rem;">
      
      <!-- CROPPER TARGET CANVAS CONTAINER -->
      <div class="card" style="padding: 1rem; background: #000; overflow: hidden; margin-bottom: 1.5rem;">
        <div style="max-height: 480px; display: flex; align-items: center; justify-content: center;">
          <img id="crop-target" src="" alt="Crop Target" style="max-width: 100%; display: block;">
        </div>
      </div>

      <div class="card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: var(--primary);">Adjust Crop & Aspect Ratio</h3>

        <div class="tool-controls-grid">
          <div class="form-group">
            <label class="form-label">Aspect Ratio Preset</label>
            <select id="aspect-preset" class="form-control">
              <option value="NaN">Free Crop (Custom)</option>
              <option value="1">1:1 (Square / Avatar)</option>
              <option value="1.7777777777777777">16:9 (Widescreen / Video)</option>
              <option value="1.3333333333333333">4:3 (Standard Photo)</option>
            </select>
          </div>

          <div class="form-group" style="display:flex; gap:0.5rem; align-items:flex-end;">
            <button class="btn btn-secondary" id="rotate-left-btn" style="flex:1;">↪️ Rotate Left 90°</button>
            <button class="btn btn-secondary" id="rotate-right-btn" style="flex:1;">↩️ Rotate Right 90°</button>
          </div>
        </div>

        <button class="btn btn-primary" id="crop-action-btn" style="width: 100%; padding: 0.95rem; font-size: 1.15rem; font-weight: 800; margin-top: 1rem;">
          ✂️ CROP IMAGE NOW
        </button>
      </div>

    </div>

    <!-- STEP 5 & 6: RESULT PANEL -->
    <div id="result-container" class="tool-result-box" style="display: none; margin-top: 1.5rem;">
      <div class="result-header" style="margin-bottom: 1.25rem;">
        <span class="result-title" style="color: var(--success); font-size: 1.25rem; font-weight: 800;">✓ Cropped Result Ready</span>
      </div>

      <div style="display: grid; grid-template-columns: 200px 1fr; gap: 1.5rem; align-items: center;">
        <div style="text-align: center; background: var(--input-bg); padding: 0.75rem; border-radius: 12px; border: 1px solid var(--success-bg);">
          <small style="color: var(--success); font-weight: 700; display: block; margin-bottom: 0.4rem;">CROPPED PREVIEW</small>
          <img id="cropped-result-img" src="" alt="Cropped Result" style="max-width: 100%; max-height: 150px; object-fit: contain; border-radius: 6px;">
        </div>

        <div>
          <div class="card" style="text-align: center; padding: 0.85rem; background: var(--success-bg); border-color: var(--success); margin-bottom: 1.5rem;">
            <small style="color: var(--success); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Status</small>
            <div style="font-size: 1.1rem; font-weight: 800; color: var(--success); margin-top: 0.2rem;">Cropped Successfully</div>
          </div>

          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-primary" id="download-btn" style="flex: 2; padding: 0.9rem; font-size: 1.1rem; font-weight: 800; background: linear-gradient(135deg, var(--success) 0%, #059669 100%); border: none;">
              ⬇️ DOWNLOAD CROPPED IMAGE (.PNG)
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

<!-- Vendor Scripts -->
<script src="<?= BASE_URL ?>assets/vendor/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('image-cropper');

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
  const readyDimensions = document.getElementById('ready-dimensions');
  const readyImgPreview = document.getElementById('ready-img-preview');
  const changeFileBtn = document.getElementById('change-file-btn');
  const proceedBtn = document.getElementById('proceed-btn');

  const optionsPanel = document.getElementById('options-panel');
  const targetImg = document.getElementById('crop-target');
  const aspectPreset = document.getElementById('aspect-preset');
  const rotateLeftBtn = document.getElementById('rotate-left-btn');
  const rotateRightBtn = document.getElementById('rotate-right-btn');
  const cropActionBtn = document.getElementById('crop-action-btn');

  const resultContainer = document.getElementById('result-container');
  const croppedResultImg = document.getElementById('cropped-result-img');
  const downloadBtn = document.getElementById('download-btn');
  const resultResetBtn = document.getElementById('result-reset-btn');

  let cropper = null;
  let croppedBlob = null;
  let currentFile = null;
  let objectUrl = null;
  let readingTimer = null;

  function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function resetState() {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
    dropzone.style.display = 'block';
    readingCard.style.display = 'none';
    fileReadyCard.style.display = 'none';
    optionsPanel.style.display = 'none';
    resultContainer.style.display = 'none';
    imgInput.value = '';

    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = null;
    currentFile = null;
    croppedBlob = null;
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

    const tempImg = new Image();
    tempImg.onload = () => {
      const w = tempImg.naturalWidth || tempImg.width;
      const h = tempImg.naturalHeight || tempImg.height;
      readyDimensions.textContent = `${w} × ${h} px`;

      readingCard.style.display = 'none';
      fileReadyCard.style.display = 'block';

      if (window.Sani) {
        window.Sani.say("File ready! Click Proceed to adjust your crop area.", "happy");
      }
    };
    tempImg.onerror = () => {
      readingCard.style.display = 'none';
      showToast("Unable to read image dimensions", "error");
      resetState();
    };
    tempImg.src = objectUrl;
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
    targetImg.src = objectUrl;

    if (cropper) cropper.destroy();

    cropper = new Cropper(targetImg, {
      aspectRatio: NaN,
      viewMode: 1,
      autoCropArea: 0.85,
      responsive: true
    });

    optionsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    if (window.Sani) {
      window.Sani.say("Drag handles to crop, then click Crop Image Now.", "info");
    }
  });

  aspectPreset.addEventListener('change', () => {
    if (cropper) cropper.setAspectRatio(parseFloat(aspectPreset.value));
  });

  rotateLeftBtn.addEventListener('click', () => { if (cropper) cropper.rotate(-90); });
  rotateRightBtn.addEventListener('click', () => { if (cropper) cropper.rotate(90); });

  cropActionBtn.addEventListener('click', () => {
    if (!cropper) return;

    cropActionBtn.disabled = true;
    cropActionBtn.innerHTML = '⚡ Processing... Please wait';

    setTimeout(() => {
      const canvas = cropper.getCroppedCanvas();
      cropActionBtn.disabled = false;
      cropActionBtn.innerHTML = '✂️ CROP IMAGE NOW';

      if (!canvas) {
        showToast("Failed to generate cropped canvas", "error");
        return;
      }

      canvas.toBlob((blob) => {
        if (!blob) return;
        croppedBlob = blob;
        croppedResultImg.src = URL.createObjectURL(blob);
        resultContainer.style.display = 'block';
        resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        showToast("Cropped image generated!", "success");
      }, 'image/png');
    }, 100);
  });

  downloadBtn.addEventListener('click', () => {
    if (!croppedBlob) return;
    const link = document.createElement('a');
    link.href = URL.createObjectURL(croppedBlob);
    link.download = 'sanix-cropped.png';
    link.click();
    showToast("Downloaded cropped image!", "success");
  });

  changeFileBtn.addEventListener('click', resetState);
  resultResetBtn.addEventListener('click', resetState);
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
