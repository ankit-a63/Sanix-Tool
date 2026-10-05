/**
 * SANIX TOOL - Favorites System
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

  async function toggleFavorite(toolName, btnElement) {
    const isLoggedIn = document.body.hasAttribute('data-logged-in');

    if (isLoggedIn) {
      try {
        const formData = new FormData();
        formData.append('tool_name', toolName);
        formData.append('csrf_token', window.CSRF_TOKEN || '');

        const response = await fetch('/api/favorites.php', {
          method: 'POST',
          body: formData
        });
        const data = await response.json();

        if (data.success) {
          btnElement.classList.toggle('active', data.is_favorite);
          btnElement.innerHTML = data.is_favorite ? '❤️' : '🤍';
          window.showToast(data.message, 'success');
          if (window.Sani) window.Sani.say(data.is_favorite ? "Added to your favorites!" : "Removed from favorites", "happy");
        } else {
          window.showToast(data.message || 'Error updating favorites', 'error');
        }
      } catch (err) {
        window.showToast('Network error saving favorite', 'error');
      }
    } else {
      // Guest localStorage state
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
      window.showToast(isFav ? 'Added to favorites (Saved in browser)' : 'Removed from favorites', 'info');
    }
  }

  function syncFavoritesUI() {
    const isLoggedIn = document.body.hasAttribute('data-logged-in');
    if (!isLoggedIn) {
      const favs = getLocalFavorites();
      document.querySelectorAll('.fav-btn').forEach(btn => {
        const tool = btn.getAttribute('data-tool');
        if (favs.includes(tool)) {
          btn.classList.add('active');
          btn.innerHTML = '❤️';
        }
      });
    }
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
