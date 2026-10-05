</main> <!-- End Main -->

<?php require_once __DIR__ . '/sani.php'; ?>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="<?= BASE_URL ?>assets/images/branding/sanix-logo.svg" alt="Sanix Tool">
        <p>One Platform. Every Tool. Privacy-first online utilities running directly in your browser with high speed and zero data collection.</p>
      </div>

      <div class="footer-col">
        <h5>Tool Categories</h5>
        <ul class="footer-links">
          <li><a href="<?= BASE_URL ?>tools.php?category=image">Image Tools</a></li>
          <li><a href="<?= BASE_URL ?>tools.php?category=pdf">PDF Tools</a></li>
          <li><a href="<?= BASE_URL ?>tools.php?category=text">Text Utilities</a></li>
          <li><a href="<?= BASE_URL ?>tools.php?category=developer">Developer Tools</a></li>
          <li><a href="<?= BASE_URL ?>tools.php?category=calculator">Calculators</a></li>
          <li><a href="<?= BASE_URL ?>tools.php?category=file">File Utilities</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Quick Links</h5>
        <ul class="footer-links">
          <li><a href="<?= BASE_URL ?>tools.php">All Tools Directory</a></li>
          <li><a href="<?= BASE_URL ?>favorites.php">Your Favorites</a></li>
          <li><a href="<?= BASE_URL ?>about.php">About Sanix Tool</a></li>
          <li><a href="<?= BASE_URL ?>contact.php">Contact & Feedback</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Legal & Privacy</h5>
        <ul class="footer-links">
          <li><a href="<?= BASE_URL ?>privacy.php">Privacy Policy</a></li>
          <li><a href="<?= BASE_URL ?>terms.php">Terms of Service</a></li>
          <li><a href="<?= BASE_URL ?>">Sanix Tool Platform</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <strong>SANIX TOOL</strong>. Designed & Built for High Performance. Designed & Developed with ❤️ by <strong>Sanni Singh</strong>. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- JavaScript Scripts -->
<script src="<?= BASE_URL ?>assets/js/theme.js?v=<?= APP_VERSION ?>"></script>
<script src="<?= BASE_URL ?>assets/js/toast.js?v=<?= APP_VERSION ?>"></script>
<script src="<?= BASE_URL ?>assets/js/sani.js?v=<?= APP_VERSION ?>"></script>
<script src="<?= BASE_URL ?>assets/js/favorites.js?v=<?= APP_VERSION ?>"></script>
<script src="<?= BASE_URL ?>assets/js/search.js?v=<?= APP_VERSION ?>"></script>
<script src="<?= BASE_URL ?>assets/js/app.js?v=<?= APP_VERSION ?>"></script>
</body>
</html>
