/**
 * SANIX TOOL - Favorites System (Client-side localStorage)
 */

(function () {
  const LOCAL_FAVS_KEY = 'sanix_favorites';

  function getLocalFavorites() {
    try {
      return JSON.parse(localStorage.getItem(LOCAL_FAVS_KEY) || '[]');
    } catch (e) {
      return [];
    }
  }

  function setLocalFavorites(favs) {
    localStorage.setItem(LOCAL_FAVS_KEY, JSON.stringify(favs));
  }

  function toggleFavorite(toolName, btnElement) {
    let favs = getLocalFavorites();
    const index = favs.indexOf(toolName);
    let isFav = false;

    if (index > -1) {
      favs.splice(index, 1);
      isFav = false;
    } else {
      favs.push(toolName);
      isFav = true;
    }

    setLocalFavorites(favs);
    btnElement.classList.toggle('active', isFav);
    btnElement.innerHTML = isFav ? '❤️' : '🤍';
    
    if (window.showToast) {
      window.showToast(isFav ? 'Added to favorites' : 'Removed from favorites', 'info');
    }
    if (window.Sani) {
      window.Sani.say(isFav ? "Added to your favorites!" : "Removed from favorites", "happy");
    }
  }

  function syncFavoritesUI() {
    const favs = getLocalFavorites();
    document.querySelectorAll('.fav-btn').forEach(btn => {
      const tool = btn.getAttribute('data-tool');
      if (favs.includes(tool)) {
        btn.classList.add('active');
        btn.innerHTML = '❤️';
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    syncFavoritesUI();

    document.addEventListener('click', (e) => {
      const favBtn = e.target.closest('.fav-btn');
      if (favBtn) {
        e.preventDefault();
        e.stopPropagation();
        const toolName = favBtn.getAttribute('data-tool');
        if (toolName) toggleFavorite(toolName, favBtn);
      }
    });
  });

  window.SanixFavs = { toggleFavorite, getLocalFavorites };
})();
