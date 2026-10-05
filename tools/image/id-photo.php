<?php
// Prevent browser caching during active development
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

$pageTitle = "ID & Passport Photo Maker - Sanix Tool";
$pageDesc = "Create passport photos, visa photos, and ID photos with custom dimensions, background colors, and printable A4 sheets.";
require_once __DIR__ . '/../../includes/header.php';
?>

<style>
.id-photo-container {
  max-width: 980px;
  margin: 0 auto;
  background: var(--surface, #1e293b);
  border: 1px solid var(--surface-border, rgba(255,255,255,0.1));
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.id-dropzone {
  border: 2px dashed var(--primary, #00f2fe);
  border-radius: 16px;
  padding: 3.5rem 2rem;
  text-align: center;
  background: var(--input-bg, #0f172a);
  cursor: pointer;
  position: relative;
  transition: all 0.25s ease;
}

.id-dropzone:hover, .id-dropzone.dragover {
  background: rgba(0, 242, 254, 0.08);
  border-color: #00c6ff;
  box-shadow: 0 0 20px rgba(0, 242, 254, 0.25);
}

.id-card {
  background: var(--input-bg, #0f172a);
  border: 1px solid var(--surface-border, rgba(255,255,255,0.1));
  border-radius: 16px;
  padding: 1.5rem;
  margin-top: 1.5rem;
}

.photo-preset-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
  gap: 0.75rem;
  margin-bottom: 1.25rem;
}

.photo-preset-card {
  background: rgba(255,255,255,0.03);
  border: 1px solid var(--surface-border);
  border-radius: 12px;
  padding: 0.85rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.photo-preset-card:hover, .photo-preset-card.active {
  background: rgba(0, 242, 254, 0.12);
  border-color: var(--primary);
  transform: translateY(-2px);
}

.editor-preview-container {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 1.5rem;
  align-items: start;
}

@media (max-width: 768px) {
  .editor-preview-container {
    grid-template-columns: 1fr;
  }
}

.single-photo-box {
  background: #000;
  border: 2px solid var(--primary);
  border-radius: 12px;
  padding: 0.75rem;
  text-align: center;
}

canvas#singlePhotoCanvas {
  max-width: 100%;
  max-height: 320px;
  border-radius: 6px;
}

.a4-preview-box {
  background: #ffffff;
  border: 2px solid var(--surface-border);
  border-radius: 8px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  overflow: auto;
  max-height: 480px;
}

canvas#a4Canvas {
  max-width: 100%;
  height: auto;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="id-photo-container">
    
    <!-- TOOL HEADER -->
    <div class="tool-header" style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--surface-border);">
      <div class="tool-header-info" style="display: flex; align-items: center; gap: 1rem;">
        <div class="tool-icon" style="font-size: 2.2rem;">🪪</div>
        <div>
          <h1 class="tool-header-title" style="font-size: 1.6rem; margin: 0;">ID & Passport Photo Maker</h1>
          <p class="tool-header-desc" style="font-size: 0.95rem; color: var(--text-muted); margin: 0;">Create official passport photos, visa photos, and ID cards with background replacement and printable A4 sheets.</p>
        </div>
      </div>
    </div>

    <!-- PRIVACY NOTIFICATION -->
    <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.4rem; background: rgba(0, 242, 254, 0.05); padding: 0.5rem 0.85rem; border-radius: 8px; border: 1px solid rgba(0, 242, 254, 0.15);">
      🔒 <span>Your photos are processed 100% locally in your browser. Complete privacy guaranteed.</span>
    </div>

    <!-- 1. DROPZONE -->
    <div id="idDropzone" class="id-dropzone">
      <div style="font-size: 3.2rem; margin-bottom: 0.5rem;">📁</div>
      <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">Drop your photo here</div>
      <div style="margin: 0.85rem 0;">
        <button type="button" class="btn btn-primary" style="padding: 0.75rem 1.75rem; font-size: 1.05rem; pointer-events: none;">[ Browse Photo ]</button>
      </div>
      <div style="font-size: 0.88rem; color: var(--text-muted);">Supported Formats: <strong>JPG • PNG • WEBP</strong></div>
      <input type="file" id="idFileInput" accept="image/jpeg,image/png,image/webp" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 20;" onchange="if(this.files && this.files[0] && window.handleIdFile) window.handleIdFile(this.files[0]);">
    </div>

    <!-- 2. READING STATE -->
    <div id="idReading" class="id-card" style="display: none; text-align: center; padding: 2.5rem 1.5rem;">
      <span class="btn btn-secondary" style="pointer-events: none; margin-bottom: 0.75rem;">⚡ Reading photo...</span>
      <h3 id="idReadingFileName" style="font-size: 1.15rem; color: var(--text-main); word-break: break-all;">photo.jpg</h3>
      <small id="idReadingFileSize" style="color: var(--text-muted);">Calculating size...</small>
      <div style="width: 100%; height: 12px; background: rgba(255,255,255,0.08); border-radius: 999px; overflow: hidden; margin: 1.25rem 0 0.5rem 0;">
        <div id="idReadingProgressFill" style="height: 100%; width: 0%; background: linear-gradient(90deg, #00f2fe, #00c6ff); border-radius: 999px; transition: width 0.15s ease;"></div>
      </div>
      <div id="idReadingPercentText" style="font-size: 0.95rem; font-weight: 800; color: var(--primary);">0%</div>
    </div>

    <!-- 3. FILE READY STATE -->
    <div id="idReady" class="id-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <span style="color: #10b981; font-weight: 800; font-size: 0.9rem;">✓ Photo Ready</span>
        <button type="button" class="btn btn-secondary" id="idChangePhotoBtn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">🔄 Change Photo</button>
      </div>

      <div style="display: grid; grid-template-columns: 180px 1fr; gap: 1.5rem; align-items: center;">
        <div style="text-align: center; background: rgba(0,0,0,0.4); padding: 0.75rem; border-radius: 12px; border: 1px solid var(--surface-border);">
          <img id="idReadyPreview" src="" alt="Selected Photo" style="max-width: 100%; max-height: 140px; object-fit: contain; border-radius: 6px;">
        </div>
        <div>
          <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">FILE NAME</small>
          <h4 id="idReadyFileName" style="font-size: 1.1rem; word-break: break-all; color: var(--text-main); margin: 0 0 0.5rem 0;">filename.jpg</h4>
          <div style="display: flex; gap: 2rem;">
            <div>
              <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">FILE SIZE</small>
              <div id="idReadyFileSize" style="font-size: 1.05rem; font-weight: 800; color: var(--text-main);">0 MB</div>
            </div>
            <div>
              <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700;">DIMENSIONS</small>
              <div id="idReadyDimensions" style="font-size: 1.05rem; font-weight: 800; color: var(--primary);">0 × 0 px</div>
            </div>
          </div>
        </div>
      </div>

      <button type="button" id="idProceedBtn" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 1rem; font-size: 1.15rem; font-weight: 800;">
        ➜ PROCEED TO PASSPORT & ID EDITOR
      </button>
    </div>

    <!-- 4. EDITOR & A4 SHEET STUDIO -->
    <div id="idEditorStudio" class="id-card" style="display: none;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--surface-border); padding-bottom: 0.75rem;">
        <h3 style="font-size: 1.15rem; color: var(--primary); margin: 0;">1. Choose Document Type & Dimensions</h3>
        <button type="button" class="btn btn-secondary" id="idResetAllBtn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">↻ Start Over</button>
      </div>

      <!-- DOCUMENT PRESETS -->
      <div class="photo-preset-grid">
        <div class="photo-preset-card active" data-idpreset="passport-3545">
          <div style="font-weight: 800; color: var(--text-main);">Passport Photo</div>
          <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700;">35 × 45 mm</div>
          <small style="color: var(--text-muted); font-size: 0.7rem;">Indian / Standard Passport</small>
        </div>

        <div class="photo-preset-card" data-idpreset="visa-22">
          <div style="font-weight: 800; color: var(--text-main);">Visa Photo</div>
          <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700;">2 × 2 inch (51 × 51 mm)</div>
          <small style="color: var(--text-muted); font-size: 0.7rem;">US / Schengen Visa</small>
        </div>

        <div class="photo-preset-card" data-idpreset="id-card">
          <div style="font-weight: 800; color: var(--text-main);">ID / Stamp Photo</div>
          <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700;">25 × 35 mm</div>
          <small style="color: var(--text-muted); font-size: 0.7rem;">Student / Employee ID</small>
        </div>

        <div class="photo-preset-card" data-idpreset="custom-id">
          <div style="font-weight: 800; color: var(--text-main);">Custom Size</div>
          <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700;">Manual W × H</div>
          <small style="color: var(--text-muted); font-size: 0.7rem;">Configurable Dimensions</small>
        </div>
      </div>

      <!-- CUSTOM DIMENSIONS ROW -->
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px;">
        <div class="form-group" style="margin: 0;">
          <label style="font-weight: 600; font-size: 0.85rem;">Width</label>
          <input type="number" id="idWidthInput" value="35" class="form-control">
        </div>
        <div class="form-group" style="margin: 0;">
          <label style="font-weight: 600; font-size: 0.85rem;">Height</label>
          <input type="number" id="idHeightInput" value="45" class="form-control">
        </div>
        <div class="form-group" style="margin: 0;">
          <label style="font-weight: 600; font-size: 0.85rem;">Unit</label>
          <select id="idUnitSelect" class="form-control">
            <option value="mm" selected>Millimeters (mm)</option>
            <option value="inch">Inches (in)</option>
            <option value="px">Pixels (px)</option>
          </select>
        </div>
        <div class="form-group" style="margin: 0;">
          <label style="font-weight: 600; font-size: 0.85rem;">Print DPI</label>
          <select id="idDpiSelect" class="form-control">
            <option value="150">150 DPI</option>
            <option value="300" selected>300 DPI (Official)</option>
          </select>
        </div>
      </div>

      <!-- EDITOR PREVIEW & CONTROLS -->
      <h3 style="font-size: 1.15rem; color: var(--primary); margin-top: 1.5rem; margin-bottom: 1rem;">2. Photo Adjustments & Background Color</h3>

      <div class="editor-preview-container">
        <!-- SINGLE PHOTO CANVAS PREVIEW -->
        <div class="single-photo-box">
          <small style="color: var(--primary); font-weight: 700; display: block; margin-bottom: 0.4rem;">SINGLE PHOTO PREVIEW</small>
          <canvas id="singlePhotoCanvas"></canvas>
          <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem;" id="singlePhotoMetricsText">35 × 45 mm (413 × 531 px @ 300 DPI)</div>
        </div>

        <!-- CONTROLS PANEL -->
        <div>
          <!-- BACKGROUND COLOR -->
          <div style="margin-bottom: 1rem; background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px;">
            <label style="font-weight: 700; display: block; margin-bottom: 0.5rem; color: var(--text-main);">Background Color:</label>
            <div style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap;">
              <button type="button" class="btn btn-secondary bg-color-btn" data-color="#ffffff" style="background:#ffffff; color:#000; font-weight:800;">White</button>
              <button type="button" class="btn btn-secondary bg-color-btn" data-color="#3b82f6" style="background:#3b82f6; color:#fff; font-weight:800;">Blue</button>
              <button type="button" class="btn btn-secondary bg-color-btn" data-color="#ef4444" style="background:#ef4444; color:#fff; font-weight:800;">Red</button>
              <button type="button" class="btn btn-secondary bg-color-btn" data-color="#000000" style="background:#000000; color:#fff; font-weight:800;">Black</button>
              <input type="color" id="idBgColorPicker" value="#ffffff" style="height: 36px; width: 50px; padding: 2px; cursor: pointer;">
            </div>
          </div>

          <!-- SLIDERS -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; background: rgba(255,255,255,0.03); padding: 1rem; border-radius: 12px;">
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Zoom (<span id="idZoomText">100%</span>)</label>
              <input type="range" id="idZoomRange" min="50" max="250" value="100" class="form-control" style="height: 6px;">
            </div>
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Rotate (<span id="idRotText">0°</span>)</label>
              <input type="range" id="idRotRange" min="-45" max="45" value="0" class="form-control" style="height: 6px;">
            </div>
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Position X (<span id="idPosXText">0px</span>)</label>
              <input type="range" id="idPosXRange" min="-100" max="100" value="0" class="form-control" style="height: 6px;">
            </div>
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Position Y (<span id="idPosYText">0px</span>)</label>
              <input type="range" id="idPosYRange" min="-100" max="100" value="0" class="form-control" style="height: 6px;">
            </div>
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Brightness (<span id="idBrightText">0</span>)</label>
              <input type="range" id="idBrightRange" min="-50" max="50" value="0" class="form-control" style="height: 6px;">
            </div>
            <div>
              <label style="font-weight: 600; font-size: 0.8rem;">Contrast (<span id="idContrastText">0</span>)</label>
              <input type="range" id="idContrastRange" min="-50" max="50" value="0" class="form-control" style="height: 6px;">
            </div>
          </div>
        </div>
      </div>

      <!-- 3. A4 PRINTABLE SHEET STUDIO -->
      <h3 style="font-size: 1.15rem; color: var(--primary); margin-top: 2rem; margin-bottom: 1rem;">3. Printable A4 Sheet Generator</h3>

      <div style="background: rgba(255,255,255,0.03); padding: 1.25rem; border-radius: 12px; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
          <div class="form-group" style="margin: 0;">
            <label style="font-weight: 600;">A4 Page Orientation</label>
            <select id="a4OrientationSelect" class="form-control">
              <option value="portrait" selected>Portrait (210 × 297 mm)</option>
              <option value="landscape">Landscape (297 × 210 mm)</option>
            </select>
          </div>

          <div class="form-group" style="margin: 0;">
            <label style="font-weight: 600;">Page Margin (mm)</label>
            <input type="number" id="a4MarginInput" value="10" min="0" max="30" class="form-control">
          </div>

          <div class="form-group" style="margin: 0;">
            <label style="font-weight: 600;">Gap Between Photos (mm)</label>
            <input type="number" id="a4GapInput" value="4" min="0" max="20" class="form-control">
          </div>

          <div class="form-group" style="margin: 0;">
            <label style="font-weight: 600;">Number of Copies (<span id="maxCopiesCalcText">Max 30</span>)</label>
            <input type="number" id="a4CopiesInput" value="30" min="1" max="100" class="form-control">
          </div>
        </div>

        <!-- A4 CANVAS PREVIEW -->
        <div class="a4-preview-box">
          <small style="color: #000; font-weight: 800; display: block; margin-bottom: 0.5rem;">REALISTIC A4 PRINT PREVIEW (AUTOMATIC ARRANGEMENT WITH CUT LINES)</small>
          <canvas id="a4Canvas"></canvas>
        </div>
      </div>

      <!-- DOWNLOAD & PRINT BUTTONS -->
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button type="button" id="downloadSinglePhotoBtn" class="btn btn-primary" style="flex: 1; padding: 0.95rem; font-weight: 800;">
          ⬇ DOWNLOAD SINGLE PHOTO
        </button>
        <button type="button" id="downloadA4ImageBtn" class="btn btn-primary" style="flex: 1; padding: 0.95rem; font-weight: 800; background: linear-gradient(135deg, #10b981, #059669); border: none;">
          📄 DOWNLOAD A4 SHEET (IMAGE)
        </button>
        <button type="button" id="printA4Btn" class="btn btn-secondary" style="padding: 0.95rem 1.5rem; font-weight: 800;">
          🖨️ PRINT A4 SHEET
        </button>
      </div>

    </div>

  </div>
</div>

<script>
(function() {
  function initIdPhotoController() {
    const dropzone = document.getElementById('idDropzone');
    const readingCard = document.getElementById('idReading');
    const readyCard = document.getElementById('idReady');
    const editorStudio = document.getElementById('idEditorStudio');

    const fileInput = document.getElementById('idFileInput');
    const idChangePhotoBtn = document.getElementById('idChangePhotoBtn');
    const idProceedBtn = document.getElementById('idProceedBtn');
    const idResetAllBtn = document.getElementById('idResetAllBtn');

    const idReadingFileName = document.getElementById('idReadingFileName');
    const idReadingFileSize = document.getElementById('idReadingFileSize');
    const idReadingProgressFill = document.getElementById('idReadingProgressFill');
    const idReadingPercentText = document.getElementById('idReadingPercentText');

    const idReadyPreview = document.getElementById('idReadyPreview');
    const idReadyFileName = document.getElementById('idReadyFileName');
    const idReadyFileSize = document.getElementById('idReadyFileSize');
    const idReadyDimensions = document.getElementById('idReadyDimensions');

    const singleCanvas = document.getElementById('singlePhotoCanvas');
    const sCtx = singleCanvas.getContext('2d');
    const a4Canvas = document.getElementById('a4Canvas');
    const aCtx = a4Canvas.getContext('2d');

    const idWidthInput = document.getElementById('idWidthInput');
    const idHeightInput = document.getElementById('idHeightInput');
    const idUnitSelect = document.getElementById('idUnitSelect');
    const idDpiSelect = document.getElementById('idDpiSelect');

    const idBgColorPicker = document.getElementById('idBgColorPicker');
    const idZoomRange = document.getElementById('idZoomRange');
    const idZoomText = document.getElementById('idZoomText');
    const idRotRange = document.getElementById('idRotRange');
    const idRotText = document.getElementById('idRotText');
    const idPosXRange = document.getElementById('idPosXRange');
    const idPosXText = document.getElementById('idPosXText');
    const idPosYRange = document.getElementById('idPosYRange');
    const idPosYText = document.getElementById('idPosYText');
    const idBrightRange = document.getElementById('idBrightRange');
    const idBrightText = document.getElementById('idBrightText');
    const idContrastRange = document.getElementById('idContrastRange');
    const idContrastText = document.getElementById('idContrastText');

    const a4OrientationSelect = document.getElementById('a4OrientationSelect');
    const a4MarginInput = document.getElementById('a4MarginInput');
    const a4GapInput = document.getElementById('a4GapInput');
    const a4CopiesInput = document.getElementById('a4CopiesInput');
    const maxCopiesCalcText = document.getElementById('maxCopiesCalcText');

    const singlePhotoMetricsText = document.getElementById('singlePhotoMetricsText');

    let activeFile = null;
    let objectUrl = null;
    let sourceImage = null;
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
      editorStudio.style.display = 'none';
      if (card) card.style.display = 'block';
    }

    function resetAll() {
      fileInput.value = '';
      activeFile = null;
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = null;
      sourceImage = null;
      if (progressTimer) clearInterval(progressTimer);
      showState(dropzone);
    }

    function handleIdFile(file) {
      if (!file || !file.type.startsWith('image/')) {
        if (window.showToast) window.showToast("Please select a valid image file", "error");
        return;
      }

      activeFile = file;
      idReadingFileName.textContent = file.name;
      idReadingFileSize.textContent = formatBytes(file.size);
      showState(readingCard);

      let progress = 0;
      idReadingProgressFill.style.width = '0%';
      idReadingPercentText.textContent = '0%';
      if (progressTimer) clearInterval(progressTimer);

      progressTimer = setInterval(() => {
        progress += Math.floor(Math.random() * 25) + 20;
        if (progress >= 100) {
          progress = 100;
          clearInterval(progressTimer);
          finishReading(file);
        }
        idReadingProgressFill.style.width = progress + '%';
        idReadingPercentText.textContent = progress + '%';
      }, 30);
    }
    window.handleIdFile = handleIdFile;

    function finishReading(file) {
      if (objectUrl) URL.revokeObjectURL(objectUrl);
      objectUrl = URL.createObjectURL(file);
      idReadyPreview.src = objectUrl;

      idReadyFileName.textContent = file.name;
      idReadyFileSize.textContent = formatBytes(file.size);

      const img = new Image();
      img.onload = function() {
        sourceImage = img;
        const w = img.naturalWidth || img.width;
        const h = img.naturalHeight || img.height;
        idReadyDimensions.textContent = w + " × " + h + " px";
        showState(readyCard);
      };
      img.src = objectUrl;
    }

    function getPhotoPxDimensions() {
      const unit = idUnitSelect.value;
      const dpi = parseInt(idDpiSelect.value) || 300;
      const valW = parseFloat(idWidthInput.value) || 35;
      const valH = parseFloat(idHeightInput.value) || 45;

      let wPx = valW, hPx = valH;
      if (unit === 'mm') {
        wPx = Math.round((valW * dpi) / 25.4);
        hPx = Math.round((valH * dpi) / 25.4);
      } else if (unit === 'inch') {
        wPx = Math.round(valW * dpi);
        hPx = Math.round(valH * dpi);
      }
      return { wPx, hPx, valW, valH, unit, dpi };
    }

    // Render Single Photo Canvas
    function renderSinglePhoto() {
      if (!sourceImage) return;

      const dim = getPhotoPxDimensions();
      singleCanvas.width = dim.wPx;
      singleCanvas.height = dim.hPx;

      // Fill Background Color
      sCtx.fillStyle = idBgColorPicker.value;
      sCtx.fillRect(0, 0, dim.wPx, dim.hPx);

      // Apply Filter & Transforms
      sCtx.save();
      const zoom = parseFloat(idZoomRange.value) / 100;
      const rot = parseInt(idRotRange.value) * (Math.PI / 180);
      const posX = parseInt(idPosXRange.value);
      const posY = parseInt(idPosYRange.value);
      const bright = parseInt(idBrightRange.value);
      const contrast = parseInt(idContrastRange.value);

      sCtx.filter = `brightness(${100 + bright}%) contrast(${100 + contrast}%)`;

      sCtx.translate(dim.wPx / 2 + posX, dim.hPx / 2 + posY);
      sCtx.rotate(rot);
      sCtx.scale(zoom, zoom);

      // Draw centered image
      const srcW = sourceImage.naturalWidth || sourceImage.width;
      const srcH = sourceImage.naturalHeight || sourceImage.height;
      const coverScale = Math.max(dim.wPx / srcW, dim.hPx / srcH);
      const drawW = srcW * coverScale;
      const drawH = srcH * coverScale;

      sCtx.drawImage(sourceImage, -drawW / 2, -drawH / 2, drawW, drawH);
      sCtx.restore();

      singlePhotoMetricsText.textContent = `${dim.valW} × ${dim.valH} ${dim.unit} (${dim.wPx} × ${dim.hPx} px @ ${dim.dpi} DPI)`;

      renderA4Sheet();
    }

    // Render A4 Printable Sheet Canvas with Dynamic Arrangement & Cut Lines
    function renderA4Sheet() {
      if (!sourceImage) return;

      const dpi = parseInt(idDpiSelect.value) || 300;
      const isLandscape = a4OrientationSelect.value === 'landscape';

      // A4 dimensions in mm: 210 x 297
      const a4Wmm = isLandscape ? 297 : 210;
      const a4Hmm = isLandscape ? 210 : 297;

      const a4Wpx = Math.round((a4Wmm * dpi) / 25.4);
      const a4Hpx = Math.round((a4Hmm * dpi) / 25.4);

      a4Canvas.width = a4Wpx;
      a4Canvas.height = a4Hpx;

      // Draw A4 White Background
      aCtx.fillStyle = "#FFFFFF";
      aCtx.fillRect(0, 0, a4Wpx, a4Hpx);

      const marginMm = parseFloat(a4MarginInput.value) || 10;
      const gapMm = parseFloat(a4GapInput.value) || 4;

      const marginPx = Math.round((marginMm * dpi) / 25.4);
      const gapPx = Math.round((gapMm * dpi) / 25.4);

      const dim = getPhotoPxDimensions();
      const photoWpx = dim.wPx;
      const photoHpx = dim.hPx;

      // Calculate max photos that fit per page
      const cols = Math.floor((a4Wpx - 2 * marginPx + gapPx) / (photoWpx + gapPx));
      const rows = Math.floor((a4Hpx - 2 * marginPx + gapPx) / (photoHpx + gapPx));
      const maxPossible = Math.max(1, cols * rows);

      maxCopiesCalcText.textContent = `Max ${maxPossible} per A4 page`;

      let requestedCopies = parseInt(a4CopiesInput.value) || maxPossible;
      if (requestedCopies > maxPossible) requestedCopies = maxPossible;

      // Render Photos on Grid
      let count = 0;
      for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
          if (count >= requestedCopies) break;

          const x = marginPx + c * (photoWpx + gapPx);
          const y = marginPx + r * (photoHpx + gapPx);

          // Draw single photo instance from singleCanvas
          aCtx.drawImage(singleCanvas, x, y, photoWpx, photoHpx);

          // Draw fine crop outline around each photo
          aCtx.strokeStyle = "rgba(0,0,0,0.25)";
          aCtx.lineWidth = Math.max(1, Math.round(dpi / 300));
          aCtx.strokeRect(x, y, photoWpx, photoHpx);

          count++;
        }
        if (count >= requestedCopies) break;
      }
    }

    idProceedBtn.addEventListener('click', function() {
      showState(editorStudio);
      renderSinglePhoto();
    });

    // Preset Clicks
    document.querySelectorAll('.photo-preset-card').forEach(card => {
      card.addEventListener('click', function() {
        document.querySelectorAll('.photo-preset-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const preset = this.getAttribute('data-idpreset');
        if (preset === 'passport-3545') {
          idUnitSelect.value = 'mm';
          idWidthInput.value = 35;
          idHeightInput.value = 45;
        } else if (preset === 'visa-22') {
          idUnitSelect.value = 'inch';
          idWidthInput.value = 2;
          idHeightInput.value = 2;
        } else if (preset === 'id-card') {
          idUnitSelect.value = 'mm';
          idWidthInput.value = 25;
          idHeightInput.value = 35;
        }
        renderSinglePhoto();
      });
    });

    // Preset Color Buttons
    document.querySelectorAll('.bg-color-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        idBgColorPicker.value = this.getAttribute('data-color');
        renderSinglePhoto();
      });
    });

    idWidthInput.addEventListener('input', renderSinglePhoto);
    idHeightInput.addEventListener('input', renderSinglePhoto);
    idUnitSelect.addEventListener('change', renderSinglePhoto);
    idDpiSelect.addEventListener('change', renderSinglePhoto);

    idBgColorPicker.addEventListener('input', renderSinglePhoto);

    idZoomRange.addEventListener('input', function() { idZoomText.textContent = this.value + '%'; renderSinglePhoto(); });
    idRotRange.addEventListener('input', function() { idRotText.textContent = this.value + '°'; renderSinglePhoto(); });
    idPosXRange.addEventListener('input', function() { idPosXText.textContent = this.value + 'px'; renderSinglePhoto(); });
    idPosYRange.addEventListener('input', function() { idPosYText.textContent = this.value + 'px'; renderSinglePhoto(); });
    idBrightRange.addEventListener('input', function() { idBrightText.textContent = this.value; renderSinglePhoto(); });
    idContrastRange.addEventListener('input', function() { idContrastText.textContent = this.value; renderSinglePhoto(); });

    a4OrientationSelect.addEventListener('change', renderA4Sheet);
    a4MarginInput.addEventListener('input', renderA4Sheet);
    a4GapInput.addEventListener('input', renderA4Sheet);
    a4CopiesInput.addEventListener('input', renderA4Sheet);

    // Download & Print Action Listeners
    document.getElementById('downloadSinglePhotoBtn').addEventListener('click', function() {
      const link = document.createElement('a');
      link.download = 'passport-photo.png';
      link.href = singleCanvas.toDataURL('image/png', 1.0);
      link.click();
      if (window.showToast) window.showToast("Downloaded single photo!", "success");
    });

    document.getElementById('downloadA4ImageBtn').addEventListener('click', function() {
      const link = document.createElement('a');
      link.download = 'passport-photos-a4.png';
      link.href = a4Canvas.toDataURL('image/png', 1.0);
      link.click();
      if (window.showToast) window.showToast("Downloaded printable A4 sheet image!", "success");
    });

    document.getElementById('printA4Btn').addEventListener('click', function() {
      const win = window.open('', '_blank');
      win.document.write(`
        <html>
          <head>
            <title>Print Passport Photos A4 Sheet</title>
            <style>
              body { margin: 0; padding: 0; text-align: center; }
              img { max-width: 100%; height: auto; }
              @page { size: A4 ${a4OrientationSelect.value}; margin: 0; }
            </style>
          </head>
          <body onload="window.print(); window.close();">
            <img src="${a4Canvas.toDataURL('image/png', 1.0)}" />
          </body>
        </html>
      `);
      win.document.close();
    });

    idChangePhotoBtn.addEventListener('click', resetAll);
    idResetAllBtn.addEventListener('click', resetAll);

    resetAll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIdPhotoController);
  } else {
    initIdPhotoController();
  }
})();
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
