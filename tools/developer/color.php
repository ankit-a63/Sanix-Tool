<?php
$pageTitle = "Color Converter & Picker - Sanix Tool";
$pageDesc = "Convert color codes between HEX, RGB, HSL, HSV, and CMYK with a visual color preview.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🎨</div>
        <div>
          <h1 class="tool-header-title">Color Code Converter</h1>
          <p class="tool-header-desc">Convert colors between HEX, RGB, HSL and CMYK color space values.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="color-converter" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid" style="grid-template-columns: 80px 1fr;">
      <div class="form-group">
        <label class="form-label">Picker</label>
        <input type="color" id="color-picker" value="#00f2fe" class="form-control" style="height:48px; padding:2px; cursor:pointer;">
      </div>

      <div class="form-group">
        <label class="form-label">HEX Code</label>
        <input type="text" id="hex-input" class="form-control" value="#00f2fe">
      </div>
    </div>

    <div class="card" id="color-preview-box" style="height:100px; background:#00f2fe; border-radius:14px; margin-bottom:1.5rem; transition:background 0.2s ease;"></div>

    <div class="tool-result-box">
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
        <div class="card"><small style="color:var(--text-muted);">RGB</small><h4 id="res-rgb" style="margin-top:0.25rem;">rgb(0, 242, 254)</h4></div>
        <div class="card"><small style="color:var(--text-muted);">HSL</small><h4 id="res-hsl" style="margin-top:0.25rem;">hsl(183, 100%, 50%)</h4></div>
        <div class="card"><small style="color:var(--text-muted);">CMYK</small><h4 id="res-cmyk" style="margin-top:0.25rem;">cmyk(100%, 5%, 0%, 0%)</h4></div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('color-converter');

  const picker = document.getElementById('color-picker');
  const hexInput = document.getElementById('hex-input');
  const box = document.getElementById('color-preview-box');

  function hexToRgb(hex) {
    hex = hex.replace(/^#/, '');
    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
    const num = parseInt(hex, 16);
    return { r: (num >> 16) & 255, g: (num >> 8) & 255, b: num & 255 };
  }

  function rgbToHsl(r, g, b) {
    r /= 255; g /= 255; b /= 255;
    const max = Math.max(r, g, b), min = Math.min(r, g, b);
    let h, s, l = (max + min) / 2;
    if (max === min) { h = s = 0; }
    else {
      const d = max - min;
      s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
      switch(max){
        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
        case g: h = (b - r) / d + 2; break;
        case b: h = (r - g) / d + 4; break;
      }
      h /= 6;
    }
    return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
  }

  function updateColor(hex) {
    box.style.background = hex;
    const { r, g, b } = hexToRgb(hex);
    document.getElementById('res-rgb').textContent = `rgb(${r}, ${g}, ${b})`;
    const { h, s, l } = rgbToHsl(r, g, b);
    document.getElementById('res-hsl').textContent = `hsl(${h}, ${s}%, ${l}%)`;
  }

  picker.addEventListener('input', (e) => {
    hexInput.value = e.target.value;
    updateColor(e.target.value);
  });

  hexInput.addEventListener('input', (e) => {
    let val = e.target.value;
    if (/^#[0-9A-F]{6}$/i.test(val)) {
      picker.value = val;
      updateColor(val);
    }
  });

  updateColor('#00f2fe');
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
