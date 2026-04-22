<?php 
// 1. Core System Start
session_start();
include 'db.php'; 

// --- FETCH DYNAMIC LOGO & SETTINGS ---
$settings_query = $conn->query("SELECT * FROM settings WHERE id = 1");
$settings = $settings_query->fetch_assoc();

// Fallback values if database is empty
$site_logo = (!empty($settings['logo'])) ? $settings['logo'] : 'logo.png';
$site_name = (!empty($settings['site_name'])) ? $settings['site_name'] : 'Travel Admin';

// 2. Helper function for Active Sidebar Links
function isActive($page) {
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?> | Admin Panel</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #f7f6f2;
            --surface: #f9f8f5;
            --surface-2: #ffffff;
            --surface-soft: #f1ede7;
            --border: rgba(40, 37, 29, 0.10);

            --text: #28251d;
            --text-muted: #7a7974;

            --primary: #01696f;
            --primary-hover: #0c4e54;
            --primary-soft: #d8e8e7;

            --radius-xl: 28px;
            --transition: 0.3s ease;

            /* Sidebar specific */
            --sidebar-width: 280px;
            --sidebar-bg: #1a1a1a;
            --sidebar-text-muted: #797876;
            --sidebar-border: rgba(255, 255, 255, 0.08);
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        /* --- GLOBAL LINK FIX (Removes Underlines) --- */
        a { 
            text-decoration: none !important; 
            color: inherit;
            outline: none;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            border-right: 1px solid var(--border);
        }

        .sidebar-brand {
            padding: 32px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            padding: 6px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
        }

        .sidebar-menu {
            flex: 1;
            list-style: none;
            padding: 24px 0;
            overflow-y: auto;
        }

        .sidebar-menu li { margin-bottom: 4px; }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: var(--sidebar-text-muted);
            border-radius: 0 20px 20px 0;
            font-weight: 500;
            font-size: 14px;
            transition: all var(--transition);
            margin-right: 12px;
        }

        .sidebar-menu a i {
            width: 22px;
            font-size: 18px;
            margin-right: 14px;
        }

        .sidebar-menu a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
        }

        .sidebar-divider-label {
            padding: 10px 24px;
            font-size: 11px;
            color: var(--sidebar-text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .logout-section {
            padding: 20px 24px;
            border-top: 1px solid var(--sidebar-border);
        }

        .logout-link {
            display: flex;
            align-items: center;
            color: #f8b4b4 !important;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all var(--transition);
        }

        .logout-link:hover {
            background: rgba(248, 180, 180, 0.12) !important;
        }

        /* --- MAIN CONTENT --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100vw - var(--sidebar-width));
            min-height: 100vh;
            padding: 36px;
        }

        .top-navbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .user-profile-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--surface);
            transition: var(--transition);
        }

        .user-profile-nav:hover {
            background: var(--surface-2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .nav-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body>

<nav class="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <img src="uploads/<?php echo $site_logo; ?>" alt="Logo" class="brand-logo">
        <span class="brand-name"><?php echo $site_name; ?></span>
    </a>

    <ul class="sidebar-menu">
        <li class="sidebar-divider-label">Overview</li>
        <li>
            <a href="dashboard.php" class="<?php echo isActive('dashboard.php'); ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        
        <li class="sidebar-divider-label">Packages</li>
        <li>
            <a href="add_package.php" class="<?php echo isActive('add_package.php'); ?>">
                <i class="fas fa-plus-circle"></i> Add Trip
            </a>
        </li>
        <li>
            <a href="manage_packages.php" class="<?php echo isActive('manage_packages.php'); ?>">
                <i class="fas fa-map-marked-alt"></i> Manage Trips
            </a>
        </li>
        <li>
            <a href="bookings.php" class="<?php echo isActive('bookings.php'); ?>">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
        </li>

        <li class="sidebar-divider-label">User Management</li>
        <li>
            <a href="users.php" class="<?php echo isActive('users.php'); ?>">
                <i class="fas fa-users"></i> All Users
            </a>
        </li>
        <li>
            <a href="add_user.php" class="<?php echo isActive('add_user.php'); ?>">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </li>
        
        <li class="sidebar-divider-label">System</li>
        <li>
            <a href="settings.php" class="<?php echo isActive('settings.php'); ?>">
                <i class="fas fa-paint-brush"></i> Branding
            </a>
        </li>
        <li>
            <a href="change_password.php" class="<?php echo isActive('change_password.php'); ?>">
                <i class="fas fa-lock"></i> Security
            </a>
        </li>
    </ul>

    <div class="logout-section">
        <a href="logout.php" class="logout-link">
            <i class="fas fa-power-off" style="width: 22px; margin-right: 14px;"></i> Logout
        </a>
    </div>
</nav>

<main class="main-wrapper">
    <div class="top-navbar">
        <a href="profile.php" class="user-profile-nav">
            <span style="font-size: 14px; font-weight: 600;">Admin</span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=01696f&color=fff&size=40&bold=true" class="nav-avatar">
        </a>
    </div>