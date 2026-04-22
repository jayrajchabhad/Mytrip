<?php 
include 'f_header.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$u_id = $_SESSION['user_id'];

// Remove from favorites logic
if(isset($_GET['remove'])){
    $fav_id = intval($_GET['remove']);
    $conn->query("DELETE FROM favorites WHERE id=$fav_id AND user_id=$u_id");
}

$favs = $conn->query("SELECT f.id as fav_id, p.* FROM favorites f JOIN packages p ON f.package_id = p.id WHERE f.user_id = $u_id");
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: white; }

    .wishlist-container {
        padding: 60px 5%;
        max-width: 1400px;
        margin: 0 auto;
        min-height: 80vh;
    }

    .wishlist-header {
        margin-bottom: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .wishlist-header h2 { 
        font-size: 36px; 
        font-weight: 900; 
        color: var(--text-dark); 
        letter-spacing: -1.5px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .wishlist-header h2 i { color: #ff4757; }

    .count-pill {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 20px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 40px;
    }

    .wish-card {
        background: white;
        border-radius: 40px;
        overflow: hidden;
        position: relative;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        border: 1.5px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    .wish-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.06);
        border-color: var(--primary);
    }

    .image-wrapper {
        position: relative;
        height: 280px;
        overflow: hidden;
    }
    .image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.6s;
    }
    .wish-card:hover .image-wrapper img { transform: scale(1.1); }

    .remove-trigger {
        position: absolute;
        top: 25px;
        right: 25px;
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        z-index: 10;
        font-size: 14px;
    }
    .remove-trigger:hover {
        background: #ff4757;
        color: white;
        transform: rotate(90deg) scale(1.1);
    }

    .wish-content {
        padding: 40px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .wish-content h3 {
        margin: 0 0 10px 0;
        font-size: 24px;
        font-weight: 900;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }
    .wish-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 25px;
        border-top: 1.5px solid #f8fafc;
    }
    .price-tag {
        font-size: 24px;
        font-weight: 900;
        color: var(--primary);
        letter-spacing: -1px;
    }

    .btn-explore {
        padding: 12px 25px;
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
        border-radius: 16px;
        font-weight: 800;
        font-size: 13px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-explore:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(1, 105, 111, 0.15);
    }

    .empty-state {
        text-align: center;
        padding: 120px 40px;
        grid-column: 1/-1;
        background: #f8fafc;
        border-radius: 40px;
        border: 2px dashed #e2e8f0;
    }
    .empty-state i { font-size: 80px; color: #cbd5e1; margin-bottom: 30px; opacity: 0.5; }
    .empty-state h3 { font-size: 28px; font-weight: 900; color: var(--text-dark); margin-bottom: 15px; }

    @media (max-width: 900px) {
        .wishlist-grid { grid-template-columns: 1fr; }
        .wishlist-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
</style>

<div class="wishlist-container">
    <div class="wishlist-header">
        <h2><i class="fas fa-heart"></i> Saved Destinations</h2>
        <span class="count-pill"><?php echo $favs->num_rows; ?> Destinations</span>
    </div>

    <div class="wishlist-grid">
        <?php if($favs->num_rows > 0): ?>
            <?php while($row = $favs->fetch_assoc()): ?>
                <div class="wish-card">
                    <a href="?remove=<?php echo $row['fav_id']; ?>" class="remove-trigger" title="Remove from wishlist">
                        <i class="fas fa-times"></i>
                    </a>

                    <div class="image-wrapper">
                        <?php 
                            $img_path = "admin/uploads/" . $row['image_url'];
                            if(!file_exists($img_path)) { $img_path = "uploads/" . $row['image_url']; }
                        ?>
                        <img src="<?php echo $img_path; ?>" alt="<?php echo $row['title']; ?>" onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400';">
                    </div>

                    <div class="wish-content">
                        <h3><?php echo $row['title']; ?></h3>
                        
                        <div class="wish-meta">
                            <div class="price-tag">₹<?php echo number_format($row['price']); ?></div>
                            <a href="package_details.php?id=<?php echo $row['id']; ?>" class="btn-explore">
                                Explore
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-heart"></i>
                <h3>Your wishlist is quiet.</h3>
                <p style="color: var(--text-light); font-weight: 600; margin-bottom: 30px;">Save the destinations that inspire you and plan your perfect getaway later.</p>
                <a href="index.php" class="btn-explore" style="display: inline-flex; background: var(--primary); color: white; padding: 18px 40px;">Discover Destinations</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'f_footer.php'; ?>