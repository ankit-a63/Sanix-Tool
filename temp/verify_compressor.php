<?php
$opts = ['http' => ['header' => "Host: sanix-tool.local\r\n"]];
$context = stream_context_create($opts);
$html = file_get_contents('http://127.0.0.1/tools/image/compressor.php', false, $context);

$requiredIds = [
    'img-dropzone',
    'img-input',
    'workspace-panel',
    'orig-filename',
    'orig-size-text',
    'orig-dims-text',
    'orig-img-preview',
    'quality-range',
    'quality-val',
    'format-select',
    'compress-action-btn',
    'result-container',
    'comp-img-preview',
    'res-orig-size',
    'res-comp-size',
    'res-savings-percent',
    'download-btn',
    'reset-btn',
    'result-reset-btn'
];

$missing = [];
foreach ($requiredIds as $id) {
    if (strpos($html, 'id="' . $id . '"') === false) {
        $missing[] = $id;
    }
}

if (count($missing) === 0) {
    echo "SUCCESS: All 19 required DOM Element IDs exist in compressor.php HTML!\n";
} else {
    echo "ERROR: Missing IDs: " . implode(', ', $missing) . "\n";
}
