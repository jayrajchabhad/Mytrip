<?php 
include 'header.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM packages WHERE id = $id");
    $pkg = $res->fetch_assoc();

    if (!$pkg) {
        echo "<script>alert('Package not found!'); window.location='manage_packages.php';</script>";
        exit;
    }
} else {
    header("Location: manage_packages.php");
    exit;
}
?>

<style>
    .view-container {
        max-width: 980px;
        margin: 0 auto;
        padding: 24px 0;
    }

    .view-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .view-image {
        width: 100%;
        height: 320px;
        background: linear-gradient(135deg, var(--primary-soft), var(--success-soft));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .view-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition);
    }

    .view-card:hover .view-image img {
        transform: scale(1.02);
    }

    .view-content {
        padding: 32px;
    }

    .badge-row {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border: 1px solid transparent;
    }

    .badge-category {
        background: var(--primary-soft);
        color: var(--primary);
        border-color: rgba(1, 105, 111, 0.12);
    }

    .badge-travel {
        background: var(--warning-soft);
        color: var(--warning);
        border-color: rgba(218, 113, 1, 0.12);
    }

    .package-title {
        margin: 0 0 12px;
        font-size: 32px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.03em;
        color: var(--text);
    }

    .package-location {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px;
        color: var(--primary);
        font-size: 18px;
        font-weight: 600;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 28px 0;
        padding: 24px 0;
        border-top: 1px solid rgba(40, 37, 29, 0.08);
        border-bottom: 1px solid rgba(40, 37, 29, 0.08);
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-item label {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }

    .detail-item span {
        font-weight: 700;
        color: var(--text);
        font-size: 16px;
    }

    .price-highlight {
        color: var(--success) !important;
        font-size: 20px;
    }

    .description-label {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 12px;
    }

    .package-description {
        color: var(--text);
        line-height: 1.7;
        font-size: 15px;
        margin-bottom: 32px;
    }

    .action-buttons {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: var(--surface-2);
        color: var(--text);
        text-decoration: none;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        border: 1px solid var(--border);
        transition: all var(--transition);
    }

    .btn-back:hover {
        background: var(--surface-soft);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    @media (min-width: 769px) {
        .view-card {
            flex-direction: row;
        }

        .view-image {
            width: 40%;
            height: 420px;
        }

        .view-content {
            width: 60%;
        }
    }

    @media (max-width: 768px) {
        .view-container {
            padding: 16px;
        }

        .view-content {
            padding: 24px;
        }

        .package-title {
            font-size: 26px;
        }

        .package-location {
            font-size: 16px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="view-container">
    <div class="view-card">
        <div class="view-image">
            <img src="uploads/<?php echo $pkg['image_url']; ?>" alt="Package Image">
        </div>
        
        <div class="view-content">
            <div class="badge-row">
                <span class="badge badge-category">
                    <i class="fas fa-globe"></i>
                    <?php echo $pkg['category']; ?>
                </span>
                <span class="badge badge-travel">
                    <i class="fas <?php echo ($pkg['travel_mode'] == 'Flight') ? 'fa-plane' : (($pkg['travel_mode'] == 'Train') ? 'fa-train' : 'fa-bus'); ?>"></i>
                    <?php echo $pkg['travel_mode']; ?>
                </span>
            </div>

            <h1 class="package-title"><?php echo $pkg['title']; ?></h1>
            
            <p class="package-location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo $pkg['location']; ?>
            </p>

            <div class="detail-grid">
                <div class="detail-item">
                    <label>Price</label>
                    <span class="price-highlight">₹<?php echo number_format($pkg['price']); ?></span>
                </div>
                <div class="detail-item">
                    <label>Duration</label>
                    <span><?php echo $pkg['duration']; ?></span>
                </div>
            </div>

            <label class="description-label">Description</label>
            <div class="package-description">
                <?php echo nl2br($pkg['description']); ?>
            </div>

            <div class="action-buttons">
                <a href="manage_packages.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>