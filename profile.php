<?php
$pageTitle = "User Profile - Sanix Tool";
require_once __DIR__ . '/includes/header.php';
requireLogin();

$user = currentUser();
$db = getDB();
$successMsg = '';
$errorMsg = '';

if ($db) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $user['id']]);
    $userRecord = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!validateCsrfToken($csrfToken)) {
        $errorMsg = "Security token invalid.";
    } elseif (empty($name)) {
        $errorMsg = "Full Name cannot be empty.";
    } else {
        try {
            if (!empty($newPassword)) {
                $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
                $upd = $db->prepare("UPDATE users SET name = :name, mobile = :mobile, password = :password WHERE id = :id");
                $upd->execute(['name' => $name, 'mobile' => $mobile, 'password' => $hashed, 'id' => $user['id']]);
            } else {
                $upd = $db->prepare("UPDATE users SET name = :name, mobile = :mobile WHERE id = :id");
                $upd->execute(['name' => $name, 'mobile' => $mobile, 'id' => $user['id']]);
            }

            $_SESSION['user_name'] = $name;
            $successMsg = "Profile updated successfully!";

            // Refresh userRecord
            $stmt->execute(['id' => $user['id']]);
            $userRecord = $stmt->fetch();
        } catch (Exception $e) {
            $errorMsg = "Failed to update profile.";
        }
    }
}
?>

<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; display:flex; justify-content:center;">
  <div class="card" style="width:100%; max-width:540px; padding:2.5rem;">
    <h2 style="margin-bottom:0.5rem;">Edit Profile Details</h2>
    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem;">Update your personal account information</p>

    <?php if ($successMsg): ?>
      <div style="background:var(--success-bg); border:1px solid var(--success); color:var(--success); padding:0.85rem; border-radius:10px; font-size:0.9rem; margin-bottom:1.5rem;">
        ✓ <?= e($successMsg) ?>
      </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
      <div style="background:var(--error-bg); border:1px solid var(--error); color:var(--error); padding:0.85rem; border-radius:10px; font-size:0.9rem; margin-bottom:1.5rem;">
        ⚠️ <?= e($errorMsg) ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <?= csrfField() ?>

      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required value="<?= e($userRecord['name'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Email Address (Read only)</label>
        <input type="email" class="form-control" value="<?= e($userRecord['email'] ?? '') ?>" disabled style="opacity:0.7;">
      </div>

      <div class="form-group">
        <label class="form-label">Mobile Phone</label>
        <input type="tel" name="mobile" class="form-control" value="<?= e($userRecord['mobile'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Change Password (Leave blank to keep current)</label>
        <input type="password" name="new_password" class="form-control" placeholder="New password">
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; margin-top:1rem; padding:0.8rem;">
        Save Changes
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
