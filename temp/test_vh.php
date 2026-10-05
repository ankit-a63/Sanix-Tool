<?php
$opts = ['http' => ['header' => "Host: sanix-tool.local\r\n"]];
$context = stream_context_create($opts);
$response = file_get_contents('http://127.0.0.1/', false, $context);
echo "STATUS CODE: " . ($http_response_header[0] ?? 'NONE') . "\n";
echo "CONTENT SNIPPET:\n" . substr($response, 0, 400) . "\n";
