<?php
$pageTitle = "Data Storage & Unit Converter - Sanix Tool";
$pageDesc = "Convert data storage units (Bytes, KB, MB, GB, TB, PB) and measurement units instantly.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">💾</div>
        <div>
          <h1 class="tool-header-title">Data Storage & Unit Converter</h1>
          <p class="tool-header-desc">Convert data sizes between Bytes, Kilobytes (KB), Megabytes (MB), Gigabytes (GB), and Terabytes (TB).</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="unit-converter" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid" style="grid-template-columns: 1fr 150px;">
      <div class="form-group">
        <label class="form-label">Value</label>
        <input type="number" id="unit-value" class="form-control" value="1024" min="0">
      </div>

      <div class="form-group">
        <label class="form-label">Unit</label>
        <select id="unit-from" class="form-control">
          <option value="B">Bytes (B)</option>
          <option value="KB">Kilobytes (KB)</option>
          <option value="MB" selected>Megabytes (MB)</option>
          <option value="GB">Gigabytes (GB)</option>
          <option value="TB">Terabytes (TB)</option>
        </select>
      </div>
    </div>

    <div class="tool-result-box" style="margin-top:1.5rem;">
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1rem;">
        <div class="card"><small style="color:var(--text-muted);">Bytes (B)</small><h4 id="u-b" style="margin-top:0.25rem;">0</h4></div>
        <div class="card"><small style="color:var(--text-muted);">Kilobytes (KB)</small><h4 id="u-kb" style="margin-top:0.25rem;">0</h4></div>
        <div class="card"><small style="color:var(--text-muted);">Megabytes (MB)</small><h4 id="u-mb" style="margin-top:0.25rem; color:var(--primary);">0</h4></div>
        <div class="card"><small style="color:var(--text-muted);">Gigabytes (GB)</small><h4 id="u-gb" style="margin-top:0.25rem;">0</h4></div>
        <div class="card"><small style="color:var(--text-muted);">Terabytes (TB)</small><h4 id="u-tb" style="margin-top:0.25rem;">0</h4></div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('unit-converter');

  const valInput = document.getElementById('unit-value');
  const unitSelect = document.getElementById('unit-from');

  const mult = { B: 1, KB: 1024, MB: 1024*1024, GB: 1024*1024*1024, TB: 1024*1024*1024*1024 };

  function convertUnits() {
    const val = parseFloat(valInput.value) || 0;
    const unit = unitSelect.value;
    const bytes = val * mult[unit];

    document.getElementById('u-b').textContent = (bytes).toLocaleString();
    document.getElementById('u-kb').textContent = (bytes / mult.KB).toLocaleString();
    document.getElementById('u-mb').textContent = (bytes / mult.MB).toLocaleString();
    document.getElementById('u-gb').textContent = (bytes / mult.GB).toLocaleString();
    document.getElementById('u-tb').textContent = (bytes / mult.TB).toLocaleString();
  }

  valInput.addEventListener('input', convertUnits);
  unitSelect.addEventListener('change', convertUnits);

  convertUnits();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
