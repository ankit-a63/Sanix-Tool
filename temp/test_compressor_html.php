<?php
$opts = ['http' => ['header' => "Host: sanix-tool.local\r\n"]];
$context = stream_context_create($opts);
$html = file_get_contents('http://127.0.0.1/tools/image/compressor.php', false, $context);

echo "HTTP Status Code: " . ($http_response_header[0] ?? 'UNKNOWN') . "\n";
echo "HTML Length: " . strlen($html) . " bytes\n\n";

// Check DOM element IDs
$ids = ['img-dropzone', 'img-input', 'workspace-panel', 'quality-range', 'quality-val', 'format-select', 'compress-action-btn', 'result-container', 'download-btn', 'reset-btn'];
foreach ($ids as $id) {
    $exists = strpos($html, 'id="' . $id . '"') !== false ? 'YES' : 'NO';
    echo "ID '$id': $exists\n";
}

echo "\n--- SCRIPT TAGS IN COMPRESSOR.PHP ---\n";
preg_match_all('/<script[^>]*src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
foreach ($matches[1] as $src) {
    echo "Script src: $src\n";
}
