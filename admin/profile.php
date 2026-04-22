<?php 
include 'header.php'; 

// 1. Fetch Admin Data
$admin_query = $conn->query("SELECT * FROM admin WHERE id = 1");
$admin = $admin_query->fetch_assoc();

$username = !empty($admin['username']) ? $admin['username'] : 'Admin User';
$email = !empty($admin['email']) ? $admin['email'] : 'admin@mytrip.com';

// 2. Fetch Stats from your 'packages' table
$count_query = $conn->query("SELECT COUNT(*) as total FROM packages");
$count_data = $count_query->fetch_assoc();
$total_packages = $count_data['total'];
?>

<style>
    .profile-container {
        max-width: 980px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .profile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        padding: 34px;
        display: flex;
        align-items: center;
        gap: 28px;
    }

    .avatar-circle {
        width: 124px;
        height: 124px;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #ffffff;
        font-weight: 800;
        flex-shrink: 0;
        box-shadow: 0 16px 30px rgba(1, 105, 111, 0.18);
    }

    .profile-details {
        flex: 1;
    }

    .admin-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 999px;
        background: var(--primary-soft);
        color: var(--primary);
        border: 1px solid rgba(1, 105, 111, 0.12);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .profile-details h1 {
        margin: 0 0 8px;
        font-size: 32px;
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
    }

    .profile-email {
        margin: 0 0 24px;
        color: var(--text-muted);
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-email i {
        color: var(--primary);
    }

    .btn-group {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-profile {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 20px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all var(--transition);
    }

    .btn-filled {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #ffffff;
        border: none;
        box-shadow: 0 12px 24px rgba(1, 105, 111, 0.14);
    }

    .btn-filled:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 28px rgba(1, 105, 111, 0.18);
    }

    .btn-outline {
        background: var(--surface-2);
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-outline:hover {
        background: var(--surface-soft);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }

    .stat-item {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 26px;
        transition: transform var(--transition), box-shadow var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary);
    }

    .stat-item:nth-child(2)::before {
        background: var(--success);
    }

    .stat-item:nth-child(3)::before {
        background: var(--warning);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 20px;
    }

    .stats-grid .stat-item:nth-child(1) .stat-icon {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .stats-grid .stat-item:nth-child(2) .stat-icon {
        background: var(--success-soft);
        color: var(--success);
    }

    .stats-grid .stat-item:nth-child(3) .stat-icon {
        background: var(--warning-soft);
        color: var(--warning);
    }

    .stat-item h3 {
        margin: 0 0 6px;
        font-size: 30px;
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--text);
    }

    .stat-item span {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    @media (max-width: 768px) {
        .profile-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 24px;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            font-size: 40px;
            border-radius: 24px;
        }

        .profile-details h1 {
            font-size: 28px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-container">

    <div class="profile-card">
        <div class="avatar-circle">
            <?php echo strtoupper(substr($username, 0, 1)); ?>
        </div>

        <div class="profile-details">
            <span class="admin-badge">Master Admin</span>
            <h1><?php echo $username; ?></h1>
            <p class="profile-email">
                <i class="far fa-envelope"></i>
                <?php echo $email; ?>
            </p>

            <div class="btn-group">
                <a href="settings.php" class="btn-profile btn-filled">
                    <i class="fas fa-user-edit"></i> Edit Branding
                </a>
                <a href="change_password.php" class="btn-profile btn-outline">
                    <i class="fas fa-shield-alt"></i> Security
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3><?php echo $total_packages; ?></h3>
            <span>Total Trips Listed</span>
        </div>

        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <h3>Active</h3>
            <span>Database Status</span>
        </div>

        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h3>Verified</h3>
            <span>Account Security</span>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>