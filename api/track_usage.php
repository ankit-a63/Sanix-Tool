<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$toolName = trim($_POST['tool_name'] ?? '');
if (empty($toolName)) {
    echo json_encode(['success' => false, 'message' => 'Tool name required']);
    exit;
}

$db = getDB();
if (!$db) {
    echo json_encode(['success' => true]); // Silent fallback
    exit;
}

$userId = isLoggedIn() ? $_SESSION['user_id'] : null;

try {
    // Check if record exists
    $stmt = $db->prepare("SELECT id, usage_count FROM tool_usage WHERE tool_name = :tool_name AND (user_id = :user_id OR (user_id IS NULL AND :user_id IS NULL)) LIMIT 1");
    $stmt->execute(['tool_name' => $toolName, 'user_id' => $userId]);
    $row = $stmt->fetch();

    if ($row) {
        $upd = $db->prepare("UPDATE tool_usage SET usage_count = usage_count + 1, last_used = NOW() WHERE id = :id");
        $upd->execute(['id' => $row['id']]);
    } else {
        $ins = $db->prepare("INSERT INTO tool_usage (user_id, tool_name, usage_count, last_used) VALUES (:user_id, :tool_name, 1, NOW())");
        $ins->execute(['user_id' => $userId, 'tool_name' => $toolName]);
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false]);
}
