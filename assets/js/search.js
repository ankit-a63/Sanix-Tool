/**
 * SANIX TOOL - Live Search & Keyboard Shortcuts
 */

(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const searchInputs = document.querySelectorAll('.tool-search-input');
    const categoryBtns = document.querySelectorAll('.category-tab');
    const toolCards = document.querySelectorAll('.tool-card');

    function filterTools() {
      const activeSearch = (document.querySelector('.tool-search-input')?.value || '').toLowerCase().trim();
      const activeCategory = document.querySelector('.category-tab.active')?.getAttribute('data-category') || 'all';

      toolCards.forEach(card => {
        const title = (card.querySelector('.tool-card-title')?.textContent || '').toLowerCase();
        const desc = (card.querySelector('.tool-card-desc')?.textContent || '').toLowerCase();
        const category = card.getAttribute('data-category') || '';

        const matchesSearch = !activeSearch || title.includes(activeSearch) || desc.includes(activeSearch);
        const matchesCategory = activeCategory === 'all' || category === activeCategory;

        if (matchesSearch && matchesCategory) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }

    searchInputs.forEach(input => {
      input.addEventListener('input', filterTools);
    });

    categoryBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        categoryBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        filterTools();
      });
    });

    // Keyboard Shortcut (Ctrl+K or /)
    document.addEventListener('keydown', (e) => {
      if ((e.ctrlKey && e.key === 'k') || (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA')) {
        e.preventDefault();
        const searchInput = document.querySelector('.tool-search-input');
        if (searchInput) {
          searchInput.focus();
          searchInput.select();
        }
      }
    });
  });
})();
