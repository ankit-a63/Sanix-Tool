<?php
$pageTitle = "User Dashboard - Sanix Tool";
require_once __DIR__ . '/includes/header.php';
requireLogin();

$user = currentUser();
$db = getDB();

$recentTools = [];
$favoriteTools = [];
$totalUsageCount = 0;

if ($db) {
    try {
        // Fetch User Recent Tools
        $stmt = $db->prepare("SELECT tool_name, usage_count, last_used FROM tool_usage WHERE user_id = :user_id ORDER BY last_used DESC LIMIT 6");
        $stmt->execute(['user_id' => $user['id']]);
        $recentTools = $stmt->fetchAll();

        // Total usage count
        $stmtSum = $db->prepare("SELECT SUM(usage_count) as total FROM tool_usage WHERE user_id = :user_id");
        $stmtSum->execute(['user_id' => $user['id']]);
        $totalUsageCount = $stmtSum->fetch()['total'] ?? 0;

        // User Favorites
        $stmtFav = $db->prepare("SELECT tool_name FROM favorites WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmtFav->execute(['user_id' => $user['id']]);
        $favoriteTools = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {}
}
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
  <!-- USER WELCOME BANNER -->
  <div class="card" style="margin-bottom: 2rem; background: linear-gradient(135deg, var(--surface) 0%, var(--surface-hover) 100%);">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
      <div>
        <h2>Welcome back, <?= e($user['name']) ?>! 👋</h2>
        <p style="color:var(--text-muted); font-size:0.95rem; margin-top:0.25rem;">Account Email: <strong><?= e($user['email']) ?></strong> (Role: <?= strtoupper($user['role']) ?>)</p>
      </div>
      <div style="display:flex; gap:0.75rem;">
        <a href="<?= BASE_URL ?>profile.php" class="btn btn-secondary">Edit Profile</a>
        <?php if (isAdmin()): ?>
          <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-primary">Admin Control Center</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- STATS OVERVIEW -->
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem; margin-bottom:2.5rem;">
    <div class="card" style="text-align:center;">
      <small style="color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; font-weight:700;">Total Tool Runs</small>
      <h1 style="color:var(--primary); margin-top:0.25rem; font-size:2.5rem;"><?= number_format($totalUsageCount) ?></h1>
    </div>

    <div class="card" style="text-align:center;">
      <small style="color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; font-weight:700;">Saved Favorites</small>
      <h1 style="color:var(--secondary); margin-top:0.25rem; font-size:2.5rem;"><?= count($favoriteTools) ?></h1>
    </div>

    <div class="card" style="text-align:center;">
      <small style="color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; font-weight:700;">Account Status</small>
      <h1 style="color:var(--success); margin-top:0.25rem; font-size:2.5rem;">ACTIVE</h1>
    </div>
  </div>

  <!-- RECENTLY USED TOOLS -->
  <div style="margin-bottom: 3rem;">
    <h3 style="margin-bottom: 1rem;">Recently Used Tools</h3>
    <?php if (empty($recentTools)): ?>
      <div class="card" style="text-align:center; padding:2rem;">
        <p style="color:var(--text-muted);">You haven't used any tools yet while logged in.</p>
        <a href="<?= BASE_URL ?>tools.php" class="btn btn-primary" style="margin-top:1rem;">Explore Tools</a>
      </div>
    <?php else: ?>
      <div class="tools-grid">
        <?php foreach ($recentTools as $rt): ?>
          <div class="card">
            <h4><?= e(ucwords(str_replace('-', ' ', $rt['tool_name']))) ?></h4>
            <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Runs: <?= $rt['usage_count'] ?> | Last used: <?= date('M d, Y', strtotime($rt['last_used'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
