<?php
$pageTitle = "All Tools Directory - Sanix Tool";
$pageDesc = "Browse all free online tools for images, PDFs, text, developer utilities, calculators and file tools.";
require_once __DIR__ . '/includes/header.php';

$categoryFilter = $_GET['category'] ?? 'all';

$tools = [
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

<div class="container" style="padding-top: 3rem; padding-bottom: 4rem;">
  <div style="text-align: center; margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem;">All Tools Directory</h1>
    <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
      Search and filter through our full suite of professional browser-processed online tools.
    </p>
  </div>

  <div style="max-width: 600px; margin: 0 auto 2rem; position: relative;">
    <input type="text" class="form-control tool-search-input" placeholder="Search tool by name or keyword..." style="padding: 0.85rem 1rem; padding-left: 2.75rem; border-radius: 12px;">
    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);">🔍</span>
  </div>

  <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2.5rem;">
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'all' ? 'active' : '' ?>" data-category="all">All Tools (<?= count($tools) ?>)</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'image' ? 'active' : '' ?>" data-category="image">🖼️ Image Tools</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'pdf' ? 'active' : '' ?>" data-category="pdf">📄 PDF Tools</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'text' ? 'active' : '' ?>" data-category="text">📝 Text Utilities</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'developer' ? 'active' : '' ?>" data-category="developer">🛠️ Developer Tools</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'calculator' ? 'active' : '' ?>" data-category="calculator">🧮 Calculators</button>
    <button class="btn btn-secondary category-tab <?= $categoryFilter === 'file' ? 'active' : '' ?>" data-category="file">📦 File Utilities</button>
  </div>

  <div class="tools-grid">
    <?php foreach ($tools as $t): ?>
      <a href="<?= BASE_URL . $t['link'] ?>" class="tool-card" data-category="<?= $t['cat'] ?>" style="<?= ($categoryFilter !== 'all' && $categoryFilter !== $t['cat']) ? 'display:none;' : '' ?>">
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
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
