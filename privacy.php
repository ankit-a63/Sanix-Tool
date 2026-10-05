<?php
$pageTitle = "Privacy Policy - Sanix Tool";
$pageDesc = "Sanix Tool Privacy Policy - Local client-side processing, zero data selling, full user privacy.";
require_once __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; max-width:800px;">
  <h1 style="margin-bottom:1rem;">Privacy Policy</h1>
  <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:2rem;">Last Updated: October 2026</p>

  <div class="card" style="padding:2rem; line-height:1.7;">
    <h3>1. Client-Side Processing Commitment</h3>
    <p style="color:var(--text-muted); margin-bottom:1.5rem;">
      Sanix Tool prioritizes your privacy above all else. Most tools on this platform (including image compression, PDF operations, text tools, and developer utilities) operate 100% locally within your browser using JavaScript and HTML5 APIs. Your files and inputs are never uploaded to remote servers or stored in cloud databases.
    </p>

    <h3>2. No Registration Required</h3>
    <p style="color:var(--text-muted); margin-bottom:1.5rem;">
      Sanix Tool is completely open and free to use without requiring any account registration or login. You can access 100% of the tools immediately without creating an account or providing personal details.
    </p>

    <h3>3. Local Storage & Preferences</h3>
    <p style="color:var(--text-muted); margin-bottom:1.5rem;">
      We use browser `localStorage` solely to store your theme preferences (Light/Dark mode) and your favorited tools locally on your device.
    </p>

    <h3>4. Contact Form</h3>
    <p style="color:var(--text-muted);">
      Messages submitted via our contact form are stored securely in our MySQL database solely for customer support and platform improvements. We never sell or share your information.
    </p>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
