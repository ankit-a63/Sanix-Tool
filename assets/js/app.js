/**
 * SANIX TOOL - Global Main Application Scripts
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
    }).catch(() => {
      window.showToast("Failed to copy to clipboard", "error");
    });
  };

  // 3. Track Tool Usage (Anonymous Local Storage Counter)
  window.trackToolUsage = function (toolName) {
    if (!toolName) return;
    try {
      const counts = JSON.parse(localStorage.getItem('sanix_tool_usage') || '{}');
      counts[toolName] = (counts[toolName] || 0) + 1;
      localStorage.setItem('sanix_tool_usage', JSON.stringify(counts));
    } catch (e) {}
  };
});
