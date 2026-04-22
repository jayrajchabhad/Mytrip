<?php 
include 'header.php'; 

// --- 1. DYNAMIC STATS FETCHING ---
// Get Total Packages
$total_res = $conn->query("SELECT COUNT(*) as total FROM packages");
$total_trips = $total_res->fetch_assoc()['total'];

// Get International Packages
$intl_res = $conn->query("SELECT COUNT(*) as total FROM packages WHERE category='International'");
$intl_trips = $intl_res->fetch_assoc()['total'];

// Get National Packages
$nat_res = $conn->query("SELECT COUNT(*) as total FROM packages WHERE category='National'");
$nat_trips = $nat_res->fetch_assoc()['total'];

// Get Total Revenue/Value
$price_res = $conn->query("SELECT SUM(price) as total_val FROM packages");
$total_value = $price_res->fetch_assoc()['total_val'];
?>

<style>
    :root {
        --bg: #f7f6f2;
        --surface: #f9f8f5;
        --surface-2: #ffffff;
        --surface-soft: #f1ede7;
        --border: rgba(40, 37, 29, 0.10);

        --text: #28251d;
        --text-muted: #7a7974;
        --text-light: #bab9b4;

        --primary: #01696f;
        --primary-hover: #0c4e54;
        --primary-soft: #d8e8e7;

        --success: #437a22;
        --success-soft: #dbe8d2;

        --warning: #da7101;
        --warning-soft: #f5e2cd;

        --danger: #a13544;
        --danger-soft: #efd9dd;

        --shadow-sm: 0 8px 24px rgba(40, 37, 29, 0.05);
        --shadow-md: 0 16px 40px rgba(40, 37, 29, 0.08);

        --radius-lg: 20px;
        --radius-xl: 28px;
        --transition: 0.3s ease;
    }

    body {
        background: var(--bg);
        color: var(--text);
    }

    .welcome-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
        padding: 38px;
        border-radius: var(--radius-xl);
        color: #f9f8f4;
        margin-bottom: 35px;
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(255,255,255,0.08);
    }

    .welcome-card h1 {
        font-size: 30px;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 10px;
        letter-spacing: -0.02em;
    }

    .welcome-card p {
        margin: 0;
        font-size: 15px;
        color: rgba(249, 248, 244, 0.88);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 36px;
    }

    .stat-card {
        background: var(--surface);
        padding: 28px;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .stat-card.total::before {
        background: var(--primary);
    }

    .stat-card.international::before {
        background: var(--success);
    }

    .stat-card.national::before {
        background: var(--warning);
    }

    .stat-card.value::before {
        background: var(--danger);
    }

    .stat-card i.bg-icon {
        position: absolute;
        right: -15px;
        bottom: -12px;
        font-size: 92px;
        opacity: 0.05;
        color: var(--text);
    }

    .stat-label {
        display: block;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.03em;
        color: var(--text);
    }

    .activity-section {
        background: var(--surface);
        padding: 28px;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .activity-header h3 {
        font-weight: 800;
        font-size: 22px;
        color: var(--text);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .activity-header a {
        color: var(--primary);
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
        transition: color var(--transition);
    }

    .activity-header a:hover {
        color: var(--primary-hover);
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        text-align: left;
        border-bottom: 1px solid rgba(40, 37, 29, 0.08);
    }

    thead th {
        padding: 14px 0;
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    tbody tr {
        border-bottom: 1px solid rgba(40, 37, 29, 0.06);
        transition: background var(--transition);
    }

    tbody tr:hover {
        background: var(--surface-2);
    }

    tbody td {
        padding: 16px 0;
        vertical-align: middle;
    }

    .package-title {
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .package-location {
        font-size: 13px;
        color: var(--text-muted);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border: 1px solid transparent;
    }

    .badge-intl {
        background: var(--primary-soft);
        color: var(--primary);
        border-color: rgba(1, 105, 111, 0.10);
    }

    .badge-nat {
        background: var(--warning-soft);
        color: #9a4d00;
        border-color: rgba(218, 113, 1, 0.10);
    }

    .travel-mode {
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .travel-mode i {
        color: var(--primary);
    }

    .price-text {
        font-weight: 800;
        color: var(--primary);
    }

    @media (max-width: 768px) {
        .welcome-card {
            padding: 26px;
        }

        .welcome-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 18px;
        }

        .dashboard-grid {
            gap: 18px;
        }

        .stat-card {
            padding: 22px;
        }

        .stat-number {
            font-size: 28px;
        }

        .activity-section {
            padding: 20px;
        }

        table {
            min-width: 720px;
        }
    }
</style>

<div class="welcome-card">
    <div class="welcome-flex" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Control Center Dashboard</h1>
            <p>Manage your <b>National</b> and <b>International</b> trip offerings with ease.</p>
        </div>
        <i class="fas fa-chart-line" style="font-size: 50px; opacity: 0.22;"></i>
    </div>
</div>

<div class="dashboard-grid">
    <div class="stat-card total">
        <i class="fas fa-suitcase-rolling bg-icon"></i>
        <span class="stat-label">Total Listings</span>
        <div class="stat-number"><?php echo $total_trips; ?></div>
    </div>

    <div class="stat-card international">
        <i class="fas fa-globe-americas bg-icon"></i>
        <span class="stat-label">International</span>
        <div class="stat-number"><?php echo $intl_trips; ?></div>
    </div>

    <div class="stat-card national">
        <i class="fas fa-map-marked-alt bg-icon"></i>
        <span class="stat-label">National</span>
        <div class="stat-number"><?php echo $nat_trips; ?></div>
    </div>

    <div class="stat-card value">
        <i class="fas fa-wallet bg-icon"></i>
        <span class="stat-label">Inventory Value</span>
        <div class="stat-number">₹<?php echo number_format($total_value / 1000, 1); ?>K</div>
    </div>
</div>

<div class="activity-section">
    <div class="activity-header">
        <h3>Latest Packages Added</h3>
        <a href="manage_packages.php">View Management <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Type</th>
                    <th>Travel Via</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM packages ORDER BY id DESC LIMIT 5";
                $recent = $conn->query($query);
                
                while($row = $recent->fetch_assoc()):
                    $mode = $row['travel_mode'];
                    $mode_icon = ($mode == 'Flight') ? 'fa-plane' : (($mode == 'Train') ? 'fa-train' : 'fa-bus');
                ?>
                <tr>
                    <td>
                        <div class="package-title"><?php echo $row['title']; ?></div>
                        <div class="package-location"><?php echo $row['location']; ?></div>
                    </td>
                    <td>
                        <span class="badge <?php echo ($row['category'] == 'International') ? 'badge-intl' : 'badge-nat'; ?>">
                            <?php echo $row['category']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="travel-mode">
                            <i class="fas <?php echo $mode_icon; ?>"></i>
                            <?php echo $mode; ?>
                        </div>
                    </td>
                    <td>
                        <div class="price-text">₹<?php echo number_format($row['price']); ?></div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>