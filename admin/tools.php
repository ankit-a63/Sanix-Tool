<?php
$pageTitle = "Tool Analytics - Admin Panel";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$db = getDB();
$toolStats = [];
if ($db) {
    $toolStats = $db->query("SELECT tool_name, SUM(usage_count) as total_runs, MAX(last_used) as last_run FROM tool_usage GROUP BY tool_name ORDER BY total_runs DESC")->fetchAll();
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div style="font-weight:800; color:var(--primary); font-size:1.1rem; margin-bottom:1.5rem; letter-spacing:1px;">ADMIN PANEL</div>
    <ul class="admin-nav">
      <li><a href="<?= BASE_URL ?>admin/index.php" class="admin-nav-link">📊 Dashboard</a></li>
      <li><a href="<?= BASE_URL ?>admin/users.php" class="admin-nav-link">👥 User Management</a></li>
      <li><a href="<?= BASE_URL ?>admin/tools.php" class="admin-nav-link active">📈 Tool Analytics</a></li>
      <li><a href="<?= BASE_URL ?>admin/messages.php" class="admin-nav-link">✉️ Contact Messages</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h2 style="margin-bottom: 0.5rem;">Tool Usage Analytics</h2>
    <p style="color:var(--text-muted); margin-bottom: 2rem;">Real-time usage breakdown of all platform tools.</p>

    <div class="data-table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Tool Identifier</th>
            <th>Tool Name</th>
            <th>Total Executions</th>
            <th>Last Executed</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($toolStats as $ts): ?>
            <tr>
              <td><code><?= e($ts['tool_name']) ?></code></td>
              <td><strong><?= e(ucwords(str_replace('-', ' ', $ts['tool_name']))) ?></strong></td>
              <td><span class="tool-badge" style="background:var(--accent-glow); color:var(--primary); font-weight:700;"><?= number_format($ts['total_runs']) ?> runs</span></td>
              <td><?= date('Y-m-d H:i', strtotime($ts['last_run'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
