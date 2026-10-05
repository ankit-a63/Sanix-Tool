/**
 * SANIX TOOL - Custom Toast Notification Engine
 */

window.showToast = function (message, type = 'info', duration = 3500) {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.style.cssText = `
      position: fixed;
      bottom: 25px;
      left: 25px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 380px;
      pointer-events: none;
    `;
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  const icons = {
    success: '✓',
    error: '✕',
    warning: '⚠',
    info: 'ℹ'
  };

  const bgColors = {
    success: 'var(--success)',
    error: 'var(--error)',
    warning: 'var(--warning)',
    info: 'var(--info)'
  };

  toast.style.cssText = `
    background: var(--surface);
    border-left: 4px solid ${bgColors[type] || 'var(--primary)'};
    color: var(--text-main);
    padding: 0.85rem 1.15rem;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.92rem;
    font-weight: 500;
    pointer-events: auto;
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    border-top: 1px solid var(--surface-border);
    border-right: 1px solid var(--surface-border);
    border-bottom: 1px solid var(--surface-border);
  `;

  toast.innerHTML = `
    <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:${bgColors[type] || 'var(--primary)'};color:#fff;font-size:0.75rem;font-weight:800;">
      ${icons[type] || 'ℹ'}
    </span>
    <span style="flex:1;">${message}</span>
  `;

  container.appendChild(toast);

  // Trigger Entrance Animation
  requestAnimationFrame(() => {
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(0)';
  });

  // Auto Dismiss
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(-20px)';
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, duration);
};
