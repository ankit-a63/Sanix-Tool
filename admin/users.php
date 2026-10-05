<?php
$pageTitle = "User Management - Admin Panel";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$db = getDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $targetId = intval($_POST['user_id'] ?? 0);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (validateCsrfToken($csrfToken) && $targetId > 0 && $targetId !== currentUser()['id']) {
        if ($_POST['action'] === 'delete') {
            $del = $db->prepare("DELETE FROM users WHERE id = :id");
            $del->execute(['id' => $targetId]);
            $message = "User deleted successfully.";
        } elseif ($_POST['action'] === 'toggle_role') {
            $upd = $db->prepare("UPDATE users SET role = IF(role='admin', 'user', 'admin') WHERE id = :id");
            $upd->execute(['id' => $targetId]);
            $message = "User role toggled.";
        }
    }
}

$usersList = [];
if ($db) {
    $usersList = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div style="font-weight:800; color:var(--primary); font-size:1.1rem; margin-bottom:1.5rem; letter-spacing:1px;">ADMIN PANEL</div>
    <ul class="admin-nav">
      <li><a href="<?= BASE_URL ?>admin/index.php" class="admin-nav-link">📊 Dashboard</a></li>
      <li><a href="<?= BASE_URL ?>admin/users.php" class="admin-nav-link active">👥 User Management</a></li>
      <li><a href="<?= BASE_URL ?>admin/tools.php" class="admin-nav-link">📈 Tool Analytics</a></li>
      <li><a href="<?= BASE_URL ?>admin/messages.php" class="admin-nav-link">✉️ Contact Messages</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h2 style="margin-bottom: 0.5rem;">User Management</h2>
    <p style="color:var(--text-muted); margin-bottom: 2rem;">Manage registered user accounts and roles.</p>

    <?php if ($message): ?>
      <div style="background:var(--success-bg); border:1px solid var(--success); color:var(--success); padding:0.75rem; border-radius:8px; margin-bottom:1.5rem;">
        ✓ <?= e($message) ?>
      </div>
    <?php endif; ?>

    <div class="data-table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Role</th>
            <th>Registered Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usersList as $u): ?>
            <tr>
              <td>#<?= $u['id'] ?></td>
              <td><strong><?= e($u['name']) ?></strong></td>
              <td><?= e($u['email']) ?></td>
              <td><?= e($u['mobile'] ?: '-') ?></td>
              <td><span class="tool-badge" style="background:<?= $u['role'] === 'admin' ? 'var(--accent-glow)' : 'var(--surface-hover)' ?>; color:<?= $u['role'] === 'admin' ? 'var(--primary)' : 'var(--text-muted)' ?>;"><?= strtoupper($u['role']) ?></span></td>
              <td><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
              <td>
                <?php if ($u['id'] !== currentUser()['id']): ?>
                  <form action="" method="POST" style="display:inline-block;">
                    <?= csrfField() ?>
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                    <button type="submit" name="action" value="toggle_role" class="btn btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Toggle Role</button>
                    <button type="submit" name="action" value="delete" class="btn btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem; color:var(--error);" onclick="return confirm('Delete user?');">Delete</button>
                  </form>
                <?php else: ?>
                  <small style="color:var(--text-dim);">Current User</small>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
