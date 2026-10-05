<?php
$user = currentUser();
?>
<nav class="navbar">
  <div class="container nav-container">
    <a href="<?= BASE_URL ?>" class="nav-brand">
      <img src="<?= BASE_URL ?>assets/images/branding/sanix-logo.svg" alt="Sanix Tool Logo">
    </a>

    <ul class="nav-menu">
      <li><a href="<?= BASE_URL ?>" class="nav-link">Home</a></li>
      <li><a href="<?= BASE_URL ?>tools.php" class="nav-link">All Tools</a></li>
      <li><a href="<?= BASE_URL ?>favorites.php" class="nav-link">Favorites ❤️</a></li>
      <li><a href="<?= BASE_URL ?>about.php" class="nav-link">About</a></li>
      <li><a href="<?= BASE_URL ?>contact.php" class="nav-link">Contact</a></li>
    </ul>

    <div class="nav-actions">
      <button class="theme-toggle" title="Toggle Light/Dark Theme">☀️</button>
      <?php if ($user && isAdmin()): ?>
        <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline" style="padding:0.4rem 0.8rem; font-size:0.85rem;">Admin Panel</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
