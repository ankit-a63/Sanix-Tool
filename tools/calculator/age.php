<?php
$pageTitle = "Age Calculator - Sanix Tool";
$pageDesc = "Calculate exact age in years, months, days, total days, hours, and next birthday countdown.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🎂</div>
        <div>
          <h1 class="tool-header-title">Age Calculator</h1>
          <p class="tool-header-desc">Calculate exact age in years, months, days, and next birthday countdown.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="age-calculator" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid">
      <div class="form-group">
        <label class="form-label">Date of Birth</label>
        <input type="date" id="dob-input" class="form-control">
      </div>

      <div class="form-group" style="display:flex; align-items:flex-end;">
        <button class="btn btn-primary" id="calc-age-btn" style="width:100%;">🎂 Calculate Age</button>
      </div>
    </div>

    <div id="age-result-panel" style="display:none; margin-top:1.5rem;">
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1rem;">
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Age</small><h2 id="res-years" style="color:var(--primary);">0</h2><span style="font-size:0.85rem; color:var(--text-muted);">Years Old</span></div>
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Months & Days</small><h2 id="res-md">0m 0d</h2></div>
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Total Days</small><h2 id="res-days">0</h2><span style="font-size:0.85rem; color:var(--text-muted);">Days Lived</span></div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('age-calculator');

  const dobInput = document.getElementById('dob-input');
  const panel = document.getElementById('age-result-panel');

  document.getElementById('calc-age-btn').addEventListener('click', () => {
    if (!dobInput.value) { showToast("Please select your birth date", "warning"); return; }

    const dob = new Date(dobInput.value);
    const now = new Date();

    let years = now.getFullYear() - dob.getFullYear();
    let months = now.getMonth() - dob.getMonth();
    let days = now.getDate() - dob.getDate();

    if (days < 0) {
      months--;
      const prevMonth = new Date(now.getFullYear(), now.getMonth(), 0);
      days += prevMonth.getDate();
    }
    if (months < 0) {
      years--;
      months += 12;
    }

    const totalDays = Math.floor((now - dob) / (1000 * 60 * 60 * 24));

    document.getElementById('res-years').textContent = years;
    document.getElementById('res-md').textContent = `${months}m ${days}d`;
    document.getElementById('res-days').textContent = totalDays.toLocaleString();

    panel.style.display = 'block';
    if (window.Sani) window.Sani.say(`You are ${years} years old!`, "happy");
  });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
