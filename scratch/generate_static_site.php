<?php
// SANIX TOOL - Static HTML Generator for Netlify Deployment
// This script compiles all PHP pages into self-contained static HTML pages.

$rootDir = realpath(__DIR__ . '/..');

$pages = [
    // Core pages
    'index.php' => 'index.html',
    'tools.php' => 'tools.html',
    'about.php' => 'about.html',
    'contact.php' => 'contact.html',
    'privacy.php' => 'privacy.html',
    'terms.php' => 'terms.html',
    'favorites.php' => 'favorites.html',

    // Image tools
    'tools/image/compressor.php' => 'tools/image/compressor.html',
    'tools/image/resizer.php' => 'tools/image/resizer.html',
    'tools/image/bg-remover.php' => 'tools/image/bg-remover.html',
    'tools/image/id-photo.php' => 'tools/image/id-photo.html',
    'tools/image/passport-photo.php' => 'tools/image/passport-photo.html',
    'tools/image/image-to-pdf.php' => 'tools/image/image-to-pdf.html',
    'tools/image/cropper.php' => 'tools/image/cropper.html',
    'tools/image/converter.php' => 'tools/image/converter.html',
    'tools/image/rotator.php' => 'tools/image/rotator.html',

    // PDF tools
    'tools/pdf/merger.php' => 'tools/pdf/merger.html',
    'tools/pdf/splitter.php' => 'tools/pdf/splitter.html',
    'tools/pdf/rotator.php' => 'tools/pdf/rotator.html',
    'tools/pdf/metadata.php' => 'tools/pdf/metadata.html',

    // Text tools
    'tools/text/counter.php' => 'tools/text/counter.html',
    'tools/text/case-converter.php' => 'tools/text/case-converter.html',
    'tools/text/line-tools.php' => 'tools/text/line-tools.html',
    'tools/text/cleaner.php' => 'tools/text/cleaner.html',
    'tools/text/slug-generator.php' => 'tools/text/slug-generator.html',
    'tools/text/lorem-ipsum.php' => 'tools/text/lorem-ipsum.html',

    // Developer tools
    'tools/developer/json.php' => 'tools/developer/json.html',
    'tools/developer/base64.php' => 'tools/developer/base64.html',
    'tools/developer/url.php' => 'tools/developer/url.html',
    'tools/developer/uuid.php' => 'tools/developer/uuid.html',
    'tools/developer/hash.php' => 'tools/developer/hash.html',
    'tools/developer/timestamp.php' => 'tools/developer/timestamp.html',
    'tools/developer/color.php' => 'tools/developer/color.html',
    'tools/developer/regex.php' => 'tools/developer/regex.html',

    // Calculator tools
    'tools/calculator/basic.php' => 'tools/calculator/basic.html',
    'tools/calculator/percentage.php' => 'tools/calculator/percentage.html',
    'tools/calculator/age.php' => 'tools/calculator/age.html',
    'tools/calculator/emi.php' => 'tools/calculator/emi.html',
    'tools/calculator/unit.php' => 'tools/calculator/unit.html',

    // File tools
    'tools/file/info.php' => 'tools/file/info.html',
    'tools/file/zip.php' => 'tools/file/zip.html',
    'tools/file/hash.php' => 'tools/file/hash.html',
];

echo "Starting Static HTML Generation...\n";

foreach ($pages as $phpRel => $htmlRel) {
    $phpFile = $rootDir . '/' . $phpRel;
    $htmlFile = $rootDir . '/' . $htmlRel;

    if (!file_exists($phpFile)) {
        echo "Skipping non-existent file: $phpRel\n";
        continue;
    }

    // Determine relative path depth to root for clean relative URLs
    $depth = substr_count($htmlRel, '/');
    $relPrefix = $depth > 0 ? str_repeat('../', $depth) : './';

    // Mock server variables for clean include execution
    $_SERVER['HTTPS'] = 'off';
    $_SERVER['HTTP_HOST'] = 'sanix-tool.local';
    $_SERVER['SCRIPT_NAME'] = '/' . $phpRel;
    $_SERVER['REQUEST_URI'] = '/' . $phpRel;

    // Reset title & desc before each page
    $pageTitle = null;
    $pageDesc = null;

    // Buffer PHP execution output
    ob_start();
    try {
        include $phpFile;
        $content = ob_get_clean();
    } catch (Throwable $e) {
        ob_end_clean();
        echo "Error executing $phpRel: " . $e->getMessage() . "\n";
        continue;
    }

    // 1. Fix BASE_URL references to relative prefix or static paths
    $content = str_replace('http://sanix-tool.local/', $relPrefix, $content);

    // 2. Inject Page Title if empty
    if (strpos($content, '<title></title>') !== false) {
      $cleanTitle = ucwords(str_replace(['tools/', '/', '.php', '-'], ['', ' - ', '', ' '], $phpRel)) . ' - Sanix Tool';
      $content = str_replace('<title></title>', '<title>' . $cleanTitle . '</title>', $content);
    }

    // 2. Replace .php extension links with .html extension links
    $content = preg_replace('/href=(["\'])([a-zA-Z0-9_\-\/]+)\.php(\?[^"\']*)?(["\'])/', 'href=$1$2.html$3$4', $content);

    // 3. Update form submit on contact page for Netlify Forms
    if ($htmlRel === 'contact.html') {
      $content = str_replace('<form id="contact-form">', '<form id="contact-form" name="contact" method="POST" data-netlify="true">', $content);
    }

    // Ensure target directory exists
    $targetDir = dirname($htmlFile);
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    file_put_contents($htmlFile, $content);
    echo "Generated: $htmlRel\n";
}

echo "Static HTML Generation Completed Successfully!\n";
