<?php
$pageTitle = "Contact Messages - Admin Panel";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$db = getDB();
$messageNotice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $msgId = intval($_POST['msg_id'] ?? 0);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (validateCsrfToken($csrfToken) && $msgId > 0) {
        if ($_POST['action'] === 'delete') {
            $del = $db->prepare("DELETE FROM contact_messages WHERE id = :id");
            $del->execute(['id' => $msgId]);
            $messageNotice = "Message deleted.";
        } elseif ($_POST['action'] === 'mark_read') {
            $upd = $db->prepare("UPDATE contact_messages SET status = 'read' WHERE id = :id");
            $upd->execute(['id' => $msgId]);
            $messageNotice = "Message marked as read.";
        }
    }
}

$messagesList = [];
if ($db) {
    $messagesList = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div style="font-weight:800; color:var(--primary); font-size:1.1rem; margin-bottom:1.5rem; letter-spacing:1px;">ADMIN PANEL</div>
    <ul class="admin-nav">
      <li><a href="<?= BASE_URL ?>admin/index.php" class="admin-nav-link">📊 Dashboard</a></li>
      <li><a href="<?= BASE_URL ?>admin/users.php" class="admin-nav-link">👥 User Management</a></li>
      <li><a href="<?= BASE_URL ?>admin/tools.php" class="admin-nav-link">📈 Tool Analytics</a></li>
      <li><a href="<?= BASE_URL ?>admin/messages.php" class="admin-nav-link active">✉️ Contact Messages</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h2 style="margin-bottom: 0.5rem;">Contact Messages</h2>
    <p style="color:var(--text-muted); margin-bottom: 2rem;">User feedback and support inquiries sent through the contact form.</p>

    <?php if ($messageNotice): ?>
      <div style="background:var(--success-bg); border:1px solid var(--success); color:var(--success); padding:0.75rem; border-radius:8px; margin-bottom:1.5rem;">
        ✓ <?= e($messageNotice) ?>
      </div>
    <?php endif; ?>

    <div class="data-table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messagesList as $m): ?>
            <tr>
              <td>#<?= $m['id'] ?></td>
              <td><strong><?= e($m['name']) ?></strong></td>
              <td><?= e($m['email']) ?></td>
              <td style="max-width:300px;"><?= e($m['message']) ?></td>
              <td><span class="tool-badge" style="background:<?= $m['status'] === 'unread' ? 'var(--warning-bg)' : 'var(--surface-hover)' ?>; color:<?= $m['status'] === 'unread' ? 'var(--warning)' : 'var(--text-muted)' ?>;"><?= strtoupper($m['status']) ?></span></td>
              <td><?= date('Y-m-d H:i', strtotime($m['created_at'])) ?></td>
              <td>
                <form action="" method="POST" style="display:inline-block;">
                  <?= csrfField() ?>
                  <input type="hidden" name="msg_id" value="<?= $m['id'] ?>">
                  <?php if ($m['status'] === 'unread'): ?>
                    <button type="submit" name="action" value="mark_read" class="btn btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem;">Mark Read</button>
                  <?php endif; ?>
                  <button type="submit" name="action" value="delete" class="btn btn-secondary" style="padding:0.25rem 0.6rem; font-size:0.75rem; color:var(--error);" onclick="return confirm('Delete message?');">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
