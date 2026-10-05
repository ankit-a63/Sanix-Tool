/**
 * SANIX TOOL - Global Main Application Scripts
 * Designed & Developed with ❤️ by Sanni Singh
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Hide Loading Screen Overlay smoothly
  const loader = document.getElementById('sanix-loader');
  if (loader) {
    setTimeout(() => {
      loader.classList.add('hidden');
    }, 250);
  }

  // 2. Global Copy to Clipboard Helper
  window.copyToClipboard = function (text, successMsg = "Copied to clipboard!") {
    if (!text) {
      window.showToast("Nothing to copy!", "warning");
      return;
    }
    navigator.clipboard.writeText(text).then(() => {
      window.showToast(successMsg, "success");
      if (window.Sani) window.Sani.say("Copied to your clipboard!", "happy", 2500);
      window.showThankYouModal("Copy", "Copied to clipboard successfully!");
    }).catch(() => {
      window.showToast("Failed to copy to clipboard", "error");
    });
  };

  // 3. Thank You Pop-up Modal (Designed & Developed by Sanni Singh)
  window.showThankYouModal = function(actionName, customMsg) {
    let modal = document.getElementById('sanix-thankyou-modal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'sanix-thankyou-modal';
      modal.className = 'sanix-modal-overlay';
      modal.innerHTML = `
        <div class="sanix-modal-card">
          <div class="sanix-modal-icon">🎉</div>
          <h3 class="sanix-modal-title">Thank You for using Sanix Tool!</h3>
          <p class="sanix-modal-body" id="sanix-modal-msg">${customMsg || "Your action completed successfully. 100% free, fast & private."}</p>
          <div class="sanix-modal-author">
            ✨ Designed & Developed with ❤️ by <strong>Sanni Singh</strong>
          </div>
          <button class="btn btn-primary sanix-modal-close-btn" onclick="window.closeThankYouModal()">Awesome, Got it!</button>
        </div>
      `;
      document.body.appendChild(modal);
      modal.addEventListener('click', (e) => {
        if (e.target === modal) window.closeThankYouModal();
      });
    } else {
      const msgElem = document.getElementById('sanix-modal-msg');
      if (msgElem && customMsg) msgElem.textContent = customMsg;
    }

    setTimeout(() => {
      modal.classList.add('active');
    }, 50);
  };

  window.closeThankYouModal = function() {
    const modal = document.getElementById('sanix-thankyou-modal');
    if (modal) {
      modal.classList.remove('active');
    }
  };

  // 4. Track Tool Usage & Trigger Thank You Modal
  window.trackToolUsage = function (toolName) {
    if (!toolName) return;
    try {
      const counts = JSON.parse(localStorage.getItem('sanix_tool_usage') || '{}');
      counts[toolName] = (counts[toolName] || 0) + 1;
      localStorage.setItem('sanix_tool_usage', JSON.stringify(counts));
    } catch (e) {}

    window.showThankYouModal(toolName, `Thank you for using ${toolName}! Your result is ready.`);
  };

  // 5. Automatic listener for download / process / export buttons across all tools
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('button, a.btn');
    if (!btn) return;
    
    const text = (btn.textContent || '').toLowerCase();
    const id = (btn.id || '').toLowerCase();
    const isDownloadOrAction = text.includes('download') || text.includes('convert') || text.includes('compress') || text.includes('calculate') || text.includes('generate') || text.includes('merge') || text.includes('split') || id.includes('download') || id.includes('process');

    if (isDownloadOrAction && !btn.classList.contains('fav-btn') && !btn.classList.contains('theme-toggle') && !btn.classList.contains('sanix-modal-close-btn')) {
      setTimeout(() => {
        window.showThankYouModal("Tool Action", "Thank you for using Sanix Tool! Your action was executed successfully.");
      }, 400);
    }
  });
});
