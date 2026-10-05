<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/security.php';

$pageTitle = $pageTitle ?? 'Sanix Tool - Free Online Tools';
$pageDesc = $pageDesc ?? 'Sanix Tool provides free online tools for images, PDFs, text, developers and everyday digital tasks.';
$currentCategory = $currentCategory ?? 'all';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDesc) ?>">
  
  <!-- OpenGraph Metadata -->
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDesc) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= BASE_URL ?>">
  <meta property="og:image" content="<?= BASE_URL ?>assets/images/branding/og-preview.png">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>assets/images/branding/favicon.svg">
  <link rel="alternate icon" type="image/png" href="<?= BASE_URL ?>assets/images/branding/favicon.png">

  <!-- CSS Stylesheets -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/themes.css?v=<?= APP_VERSION ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= APP_VERSION ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/sani.css?v=<?= APP_VERSION ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/tools.css?v=<?= APP_VERSION ?>">
  
  <!-- Vendor CSS where needed -->
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/cropper.min.css">

  <script>
    window.CSRF_TOKEN = '<?= generateCsrfToken() ?>';
    window.BASE_URL = '<?= BASE_URL ?>';
    window.showToast = window.showToast || function(msg, type) { console.log("[" + (type||'info') + "] " + msg); };
    window.trackToolUsage = window.trackToolUsage || function(name) { console.log("Track: " + name); };
  </script>
</head>
<body <?= isLoggedIn() ? 'data-logged-in="true"' : '' ?>>

<!-- SANIX TOOL LOADING SCREEN -->
<div id="sanix-loader">
  <img src="<?= BASE_URL ?>assets/images/branding/sanix-logo-mark.svg" alt="Sanix Tool" style="width:70px; height:70px;">
  <div class="loader-spinner"></div>
  <p style="margin-top:1rem; color:var(--text-muted); font-weight:600; letter-spacing:0.5px;">Preparing your tools...</p>
</div>

<?php require_once __DIR__ . '/navbar.php'; ?>
<main style="flex:1;">
