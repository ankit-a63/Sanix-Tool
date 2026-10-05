<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please log in to save favorites to your account']);
    exit;
}

$user = currentUser();
$toolName = trim($_POST['tool_name'] ?? '');
$csrfToken = $_POST['csrf_token'] ?? '';

if (!validateCsrfToken($csrfToken)) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

if (empty($toolName)) {
    echo json_encode(['success' => false, 'message' => 'Tool name is required']);
    exit;
}

$db = getDB();
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // Check if already favorited
    $stmt = $db->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND tool_name = :tool_name");
    $stmt->execute(['user_id' => $user['id'], 'tool_name' => $toolName]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Remove favorite
        $del = $db->prepare("DELETE FROM favorites WHERE id = :id");
        $del->execute(['id' => $existing['id']]);
        echo json_encode(['success' => true, 'is_favorite' => false, 'message' => 'Removed from favorites']);
    } else {
        // Add favorite
        $ins = $db->prepare("INSERT INTO favorites (user_id, tool_name) VALUES (:user_id, :tool_name)");
        $ins->execute(['user_id' => $user['id'], 'tool_name' => $toolName]);
        echo json_encode(['success' => true, 'is_favorite' => true, 'message' => 'Added to favorites']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
