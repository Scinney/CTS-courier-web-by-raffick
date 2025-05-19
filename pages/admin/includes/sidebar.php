<?php
$current_section = $_GET['section'] ?? 'dashboard';
?>

<div class="sidebar">
    <div class="profile">
        <h3><?php echo htmlspecialchars($_SESSION['first_name']); ?></h3>
        <small><?php echo ucfirst($_SESSION['role']); ?></small>
    </div>

    <a href="index.php?section=dashboard" class="<?= $current_section === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
    <a href="index.php?section=users" class="<?= $current_section === 'users' ? 'active' : '' ?>">👤 User Management</a>
    <a href="index.php?section=parcels" class="<?= $current_section === 'parcels' ? 'active' : '' ?>">📦 Parcel Section</a>
    <a href="index.php?section=branches" class="<?= $current_section === 'branches' ? 'active' : '' ?>">🏢 Branches</a>
    <a href="index.php?section=delivery" class="<?= $current_section === 'delivery' ? 'active' : '' ?>">🚚 Delivery Management</a>
    <a href="index.php?section=reports" class="<?= $current_section === 'reports' ? 'active' : '' ?>">📈 Reports</a>
    <a href="index.php?section=settings" class="<?= $current_section === 'settings' ? 'active' : '' ?>">⚙️ Settings</a>
    <a href="../../../auth/logout.php">🚪 Logout</a>

    <div class="watermark">
        © CTS Courier Malawi
    </div>
</div>
