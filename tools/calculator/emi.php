<?php
$pageTitle = "Loan EMI Calculator - Sanix Tool";
$pageDesc = "Calculate monthly loan EMI, total interest payable, and total payment breakdown.";
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">
  <div class="tool-workspace">
    <div class="tool-header">
      <div class="tool-header-info">
        <div class="tool-icon">🏦</div>
        <div>
          <h1 class="tool-header-title">Loan EMI Calculator</h1>
          <p class="tool-header-desc">Calculate monthly installments (EMI), interest payable, and total loan cost.</p>
        </div>
      </div>
      <button class="fav-btn" data-tool="emi-calculator" title="Favorite this tool">🤍</button>
    </div>

    <div class="tool-controls-grid">
      <div class="form-group">
        <label class="form-label">Loan Amount ($ / ₹)</label>
        <input type="number" id="emi-amount" class="form-control" value="100000" min="100">
      </div>

      <div class="form-group">
        <label class="form-label">Interest Rate (% p.a.)</label>
        <input type="number" id="emi-rate" class="form-control" value="8.5" step="0.1" min="0.1">
      </div>

      <div class="form-group">
        <label class="form-label">Loan Tenure (Years)</label>
        <input type="number" id="emi-tenure" class="form-control" value="5" min="1" max="40">
      </div>
    </div>

    <div class="tool-result-box" style="margin-top:1.5rem;">
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Monthly EMI</small><h2 id="res-emi" style="color:var(--primary);">0</h2></div>
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Total Interest Payable</small><h2 id="res-interest" style="color:var(--warning);">0</h2></div>
        <div class="card" style="text-align:center;"><small style="color:var(--text-muted);">Total Payment Amount</small><h2 id="res-total" style="color:var(--success);">0</h2></div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  trackToolUsage('emi-calculator');

  const amountInput = document.getElementById('emi-amount');
  const rateInput = document.getElementById('emi-rate');
  const tenureInput = document.getElementById('emi-tenure');

  function calculateEMI() {
    const p = parseFloat(amountInput.value) || 0;
    const r = (parseFloat(rateInput.value) || 0) / 12 / 100;
    const n = (parseFloat(tenureInput.value) || 0) * 12;

    if (p <= 0 || r <= 0 || n <= 0) return;

    const emi = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
    const totalPayment = emi * n;
    const totalInterest = totalPayment - p;

    document.getElementById('res-emi').textContent = Math.round(emi).toLocaleString();
    document.getElementById('res-interest').textContent = Math.round(totalInterest).toLocaleString();
    document.getElementById('res-total').textContent = Math.round(totalPayment).toLocaleString();
  }

  amountInput.addEventListener('input', calculateEMI);
  rateInput.addEventListener('input', calculateEMI);
  tenureInput.addEventListener('input', calculateEMI);

  calculateEMI();
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
