<?php
// Dynamic Sitemap Generator for Sanix Tool
require_once __DIR__ . '/config/app.php';

header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?= BASE_URL ?></loc>
    <priority>1.0</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools.php</loc>
    <priority>0.9</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>favorites.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>about.php</loc>
    <priority>0.7</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>contact.php</loc>
    <priority>0.7</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/compressor.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/resizer.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/cropper.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/converter.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/rotator.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/bg-remover.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/id-photo.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/image/image-to-pdf.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/pdf/merger.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/pdf/splitter.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/pdf/rotator.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/pdf/metadata.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/text/counter.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/developer/json.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/calculator/basic.php</loc>
    <priority>0.8</priority>
  </url>
  <url>
    <loc><?= BASE_URL ?>tools/file/info.php</loc>
    <priority>0.8</priority>
  </url>
</urlset>
