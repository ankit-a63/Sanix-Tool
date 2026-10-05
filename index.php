<?php
$pageTitle = "Sanix Tool - Free Online Tools Platform";
$pageDesc = "Sanix Tool provides free online tools for images, PDFs, text, developers and everyday digital tasks.";
require_once __DIR__ . '/includes/header.php';

// Fetch top popular tools baseline from database
$db = getDB();
$popularTools = [];
if ($db) {
    try {
        $stmt = $db->query("SELECT tool_name, SUM(usage_count) as total_usage FROM tool_usage GROUP BY tool_name ORDER BY total_usage DESC LIMIT 8");
        $popularTools = $stmt->fetchAll();
    } catch (Exception $e) {}
}

$allToolsList = [
  ['id' => 'image-compressor', 'name' => 'Image Compressor', 'desc' => 'Compress JPG, PNG & WEBP images while retaining visual quality.', 'cat' => 'image', 'icon' => '🗜️', 'link' => 'tools/image/compressor.php'],
  ['id' => 'image-resizer', 'name' => 'Image Resizer', 'desc' => 'Resize width, height & DPI with document presets and aspect ratio locking.', 'cat' => 'image', 'icon' => '📐', 'link' => 'tools/image/resizer.php'],
  ['id' => 'image-cropper', 'name' => 'Image Cropper', 'desc' => 'Crop images with 1:1, 16:9, 4:3 aspect ratio presets.', 'cat' => 'image', 'icon' => '✂️', 'link' => 'tools/image/cropper.php'],
  ['id' => 'image-converter', 'name' => 'Image Converter', 'desc' => 'Convert between JPG, PNG and WEBP image formats.', 'cat' => 'image', 'icon' => '🔄', 'link' => 'tools/image/converter.php'],
  ['id' => 'image-rotator', 'name' => 'Image Rotator', 'desc' => 'Rotate 90°, 180° or mirror flip images horizontally & vertically.', 'cat' => 'image', 'icon' => '🔃', 'link' => 'tools/image/rotator.php'],
  ['id' => 'bg-remover', 'name' => 'Background Remover', 'desc' => 'Isolate subjects, replace backgrounds with colors, gradients, images or blur.', 'cat' => 'image', 'icon' => '🪄', 'link' => 'tools/image/bg-remover.php'],
  ['id' => 'id-photo', 'name' => 'ID / Passport Photo Maker', 'desc' => 'Create official passport & visa photos with custom dimensions & A4 print sheets.', 'cat' => 'image', 'icon' => '🪪', 'link' => 'tools/image/id-photo.php'],
  ['id' => 'image-to-pdf', 'name' => 'Image to PDF', 'desc' => 'Combine multiple photos or documents into a single PDF with layout options.', 'cat' => 'image', 'icon' => '📄', 'link' => 'tools/image/image-to-pdf.php'],
  ['id' => 'pdf-merger', 'name' => 'PDF Merger', 'desc' => 'Merge multiple PDF files into one single PDF document.', 'cat' => 'pdf', 'icon' => '📚', 'link' => 'tools/pdf/merger.php'],
  ['id' => 'pdf-splitter', 'name' => 'PDF Splitter', 'desc' => 'Extract selected pages or split PDF document ranges.', 'cat' => 'pdf', 'icon' => '✂️', 'link' => 'tools/pdf/splitter.php'],
  ['id' => 'pdf-rotator', 'name' => 'PDF Page Rotator', 'desc' => 'Rotate specific or all pages in a PDF document.', 'cat' => 'pdf', 'icon' => '🔄', 'link' => 'tools/pdf/rotator.php'],
  ['id' => 'pdf-metadata', 'name' => 'PDF Metadata Viewer', 'desc' => 'Inspect author, title, page count and creation date details.', 'cat' => 'pdf', 'icon' => '🔍', 'link' => 'tools/pdf/metadata.php'],
  ['id' => 'word-counter', 'name' => 'Word & Character Counter', 'desc' => 'Count words, characters, sentences, paragraphs and reading time.', 'cat' => 'text', 'icon' => '📝', 'link' => 'tools/text/counter.php'],
  ['id' => 'case-converter', 'name' => 'Text Case Converter', 'desc' => 'Convert text to UPPERCASE, lowercase, Title Case & Sentence case.', 'cat' => 'text', 'icon' => '🔤', 'link' => 'tools/text/case-converter.php'],
  ['id' => 'line-tools', 'name' => 'Duplicate Line Remover', 'desc' => 'Remove duplicate lines and sort lists alphabetically or by length.', 'cat' => 'text', 'icon' => '📋', 'link' => 'tools/text/line-tools.php'],
  ['id' => 'text-cleaner', 'name' => 'Text Cleaner & Find Replace', 'desc' => 'Strip extra spaces, HTML tags, line breaks and find & replace text.', 'cat' => 'text', 'icon' => '🧹', 'link' => 'tools/text/cleaner.php'],
  ['id' => 'slug-generator', 'name' => 'SEO Text Slug Generator', 'desc' => 'Convert article headlines into clean, URL-safe SEO slugs.', 'cat' => 'text', 'icon' => '🔗', 'link' => 'tools/text/slug-generator.php'],
  ['id' => 'lorem-ipsum', 'name' => 'Lorem Ipsum Generator', 'desc' => 'Generate customizable placeholder dummy text for design mockups.', 'cat' => 'text', 'icon' => '📄', 'link' => 'tools/text/lorem-ipsum.php'],
  ['id' => 'json-formatter', 'name' => 'JSON Formatter & Validator', 'desc' => 'Format, validate syntax, prettify and minify JSON payloads.', 'cat' => 'developer', 'icon' => '🛠️', 'link' => 'tools/developer/json.php'],
  ['id' => 'base64-encoder', 'name' => 'Base64 Encoder & Decoder', 'desc' => 'Encode text and files to Base64 or decode Base64 strings.', 'cat' => 'developer', 'icon' => '🔐', 'link' => 'tools/developer/base64.php'],
  ['id' => 'url-encoder', 'name' => 'URL Encoder & Decoder', 'desc' => 'Safely encode URL components and decode query string params.', 'cat' => 'developer', 'icon' => '🌐', 'link' => 'tools/developer/url.php'],
  ['id' => 'uuid-generator', 'name' => 'UUID v4 Generator', 'desc' => 'Generate cryptographically secure UUID v4 unique keys.', 'cat' => 'developer', 'icon' => '🆔', 'link' => 'tools/developer/uuid.php'],
  ['id' => 'hash-generator', 'name' => 'Cryptographic Hash Generator', 'desc' => 'Calculate SHA-256, SHA-512, SHA-1 and MD5 hashes.', 'cat' => 'developer', 'icon' => '🔑', 'link' => 'tools/developer/hash.php'],
  ['id' => 'timestamp-converter', 'name' => 'Unix Timestamp Converter', 'desc' => 'Convert Unix epoch timestamps to UTC and local human dates.', 'cat' => 'developer', 'icon' => '⏰', 'link' => 'tools/developer/timestamp.php'],
  ['id' => 'color-converter', 'name' => 'Color Code Converter', 'desc' => 'Convert color values between HEX, RGB, HSL and CMYK.', 'cat' => 'developer', 'icon' => '🎨', 'link' => 'tools/developer/color.php'],
  ['id' => 'regex-tester', 'name' => 'Regular Expression Tester', 'desc' => 'Test and debug Regex pattern matches against sample text.', 'cat' => 'developer', 'icon' => '🔍', 'link' => 'tools/developer/regex.php'],
  ['id' => 'basic-calculator', 'name' => 'Scientific Calculator', 'desc' => 'Full visual calculator with history log and scientific functions.', 'cat' => 'calculator', 'icon' => '🧮', 'link' => 'tools/calculator/basic.php'],
  ['id' => 'percentage-calculator', 'name' => 'Percentage Calculator', 'desc' => 'Calculate percentage of numbers and percentage changes.', 'cat' => 'calculator', 'icon' => 'percent', 'link' => 'tools/calculator/percentage.php'],
  ['id' => 'age-calculator', 'name' => 'Age Calculator', 'desc' => 'Calculate exact age in years, months, days and next birthday.', 'cat' => 'calculator', 'icon' => '🎂', 'link' => 'tools/calculator/age.php'],
  ['id' => 'emi-calculator', 'name' => 'Loan EMI Calculator', 'desc' => 'Calculate monthly EMI, interest payable and loan breakdown.', 'cat' => 'calculator', 'icon' => '🏦', 'link' => 'tools/calculator/emi.php'],
  ['id' => 'unit-converter', 'name' => 'Data Storage Converter', 'desc' => 'Convert data sizes between Bytes, KB, MB, GB and TB.', 'cat' => 'calculator', 'icon' => '💾', 'link' => 'tools/calculator/unit.php'],
  ['id' => 'file-info', 'name' => 'File Info & Type Checker', 'desc' => 'Inspect exact byte size, MIME type, extension and file attributes.', 'cat' => 'file', 'icon' => '📁', 'link' => 'tools/file/info.php'],
  ['id' => 'zip-creator', 'name' => 'ZIP Creator & Archive Tool', 'desc' => 'Compress multiple files into a compressed .zip archive.', 'cat' => 'file', 'icon' => '📦', 'link' => 'tools/file/zip.php'],
  ['id' => 'file-hash', 'name' => 'File Checksum Generator', 'desc' => 'Calculate SHA-256 and SHA-1 checksum hashes for any binary file.', 'cat' => 'file', 'icon' => '🔒', 'link' => 'tools/file/hash.php']
];
?>

<!-- HERO SECTION -->
<section class="hero-section">
  <div class="container">
    <h1 class="hero-title">
      Every Digital Tool You Need,<br>
      <span class="gradient-text">All In One Place.</span>
    </h1>
    <p class="hero-subtitle">
      Fast, private, and 100% free online utilities running directly inside your browser. No file uploads to external servers.
    </p>

    <!-- LIVE SEARCH BAR -->
    <div style="max-width: 620px; margin: 0 auto; position: relative;">
      <input type="text" class="form-control tool-search-input" placeholder="Search 30+ tools... (Press Ctrl + K or /)" style="padding: 1rem 1.25rem; padding-left: 3rem; font-size: 1.1rem; border-radius: 14px; box-shadow: var(--glow-shadow);">
      <span style="position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); font-size: 1.2rem; color: var(--text-muted);">🔍</span>
    </div>
  </div>
</section>

<!-- ALL TOOLS SECTION -->
<section class="container" style="padding-bottom: 4rem;">
  <!-- CATEGORY FILTER TABS -->
  <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem;">
    <button class="btn btn-secondary category-tab active" data-category="all">All Tools (<?= count($allToolsList) ?>)</button>
    <button class="btn btn-secondary category-tab" data-category="image">🖼️ Image Tools</button>
    <button class="btn btn-secondary category-tab" data-category="pdf">📄 PDF Tools</button>
    <button class="btn btn-secondary category-tab" data-category="text">📝 Text Utilities</button>
    <button class="btn btn-secondary category-tab" data-category="developer">🛠️ Developer Tools</button>
    <button class="btn btn-secondary category-tab" data-category="calculator">🧮 Calculators</button>
    <button class="btn btn-secondary category-tab" data-category="file">📦 File Utilities</button>
  </div>

  <!-- TOOLS GRID -->
  <div class="tools-grid">
    <?php foreach ($allToolsList as $t): ?>
      <a href="<?= BASE_URL . $t['link'] ?>" class="tool-card" data-category="<?= $t['cat'] ?>">
        <div>
          <div class="tool-card-header">
            <div class="tool-icon"><?= $t['icon'] ?></div>
            <button class="fav-btn" data-tool="<?= $t['id'] ?>" title="Favorite tool">🤍</button>
          </div>
          <h3 class="tool-card-title"><?= e($t['name']) ?></h3>
          <p class="tool-card-desc"><?= e($t['desc']) ?></p>
        </div>
        <div class="tool-card-footer">
          <span class="tool-badge"><?= strtoupper($t['cat']) ?></span>
          <span style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">Open Tool →</span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
