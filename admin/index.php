<?php
$pageTitle = "Admin Panel - Sanix Tool";
require_once __DIR__ . '/../includes/header.php';
requireAdmin();

$db = getDB();

$totalUsers = 0;
$totalToolRuns = 0;
$totalFavs = 0;
$unreadMessages = 0;
$topTools = [];

if ($db) {
    try {
        $totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalToolRuns = $db->query("SELECT SUM(usage_count) FROM tool_usage")->fetchColumn() ?? 0;
        $totalFavs = $db->query("SELECT COUNT(*) FROM favorites")->fetchColumn();
        $unreadMessages = $db->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'")->fetchColumn();

        $stmt = $db->query("SELECT tool_name, SUM(usage_count) as total FROM tool_usage GROUP BY tool_name ORDER BY total DESC LIMIT 5");
        $topTools = $stmt->fetchAll();
    } catch (Exception $e) {}
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">

<div class="admin-layout">
  <aside class="admin-sidebar">
    <div style="font-weight:800; color:var(--primary); font-size:1.1rem; margin-bottom:1.5rem; letter-spacing:1px;">ADMIN PANEL</div>
    <ul class="admin-nav">
      <li><a href="<?= BASE_URL ?>admin/index.php" class="admin-nav-link active">📊 Dashboard</a></li>
      <li><a href="<?= BASE_URL ?>admin/users.php" class="admin-nav-link">👥 User Management</a></li>
      <li><a href="<?= BASE_URL ?>admin/tools.php" class="admin-nav-link">📈 Tool Analytics</a></li>
      <li><a href="<?= BASE_URL ?>admin/messages.php" class="admin-nav-link">✉️ Contact Messages (<?= $unreadMessages ?>)</a></li>
    </ul>
  </aside>

  <main class="admin-main">
    <h2 style="margin-bottom: 0.5rem;">Platform Analytics Overview</h2>
    <p style="color:var(--text-muted); margin-bottom: 2rem;">Real-time metrics generated directly from database tables.</p>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div>
          <div class="stat-value"><?= number_format($totalUsers) ?></div>
          <div class="stat-label">Total Registered Users</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">⚡</div>
        <div>
          <div class="stat-value"><?= number_format($totalToolRuns) ?></div>
          <div class="stat-label">Total Tool Executions</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">❤️</div>
        <div>
          <div class="stat-value"><?= number_format($totalFavs) ?></div>
          <div class="stat-label">Active User Favorites</div>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">✉️</div>
        <div>
          <div class="stat-value"><?= number_format($unreadMessages) ?></div>
          <div class="stat-label">Unread Messages</div>
        </div>
      </div>
    </div>

    <h3 style="margin-bottom: 1rem;">Most Popular Tools</h3>
    <div class="data-table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Tool Name</th>
            <th>Total Executions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topTools as $t): ?>
            <tr>
              <td><strong><?= e(ucwords(str_replace('-', ' ', $t['tool_name']))) ?></strong></td>
              <td><span class="tool-badge" style="background:var(--accent-glow); color:var(--primary); font-size:0.85rem; font-weight:700;"><?= number_format($t['total']) ?> runs</span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
