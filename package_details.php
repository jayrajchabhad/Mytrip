<?php 
session_start();
include 'db.php';

// 1. DATA FETCHING
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $conn->query("SELECT * FROM packages WHERE id = $id");

if($res->num_rows == 0) {
    include 'f_header.php';
    echo "<div style='padding:100px; text-align:center;'><h2>Trip not found!</h2><a href='index.php'>Go Back</a></div>";
    include 'f_footer.php';
    exit;
}
$pkg = $res->fetch_assoc();

// Calculate dynamic rating stats from the reviews table
$rating_query = $conn->query("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM reviews WHERE package_id = $id");
$rating_data = $rating_query->fetch_assoc();

$average_score = $rating_data['avg_rating'] ? round($rating_data['avg_rating'], 1) : 0;
$total_reviews = $rating_data['review_count'];

// 2. BOOKING LOGIC MOVED TO CHECKOUT.PHP

// 3. REVIEW LOGIC - REDIRECT FIXES THE REFRESH ISSUE
if(isset($_POST['submit_review'])){
    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit;
    } else {
        $p_id = $id;
        $u_id = $_SESSION['user_id'];
        $u_name = $_SESSION['user_name'];
        $rating = intval($_POST['rating']);
        $comment = $conn->real_escape_string($_POST['comment']);

        if($rating > 0) {
            $sql = "INSERT INTO reviews (package_id, user_id, user_name, rating, comment) 
                    VALUES ('$p_id', '$u_id', '$u_name', '$rating', '$comment')";
            
            if($conn->query($sql)){
                // This redirect "clears" the POST data so refreshing does nothing
                header("Location: package_details.php?id=$id&review_status=posted");
                exit; 
            }
        }
    }
}

include 'f_header.php'; 
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: white; color: var(--text-dark); }

    .package-hero {
        position: relative;
        height: 70vh;
        min-height: 600px;
        margin: 20px 5% 0;
        border-radius: 40px 40px 0 0;
        overflow: hidden;
    }
    .package-hero img { width: 100%; height: 100%; object-fit: cover; }
    
    .package-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 60px 5%;
        display: grid;
        grid-template-columns: 1.8fr 1fr;
        gap: 80px;
    }

    .trip-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #e0f2f1;
        color: var(--primary);
        border-radius: 12px;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }

    .trip-title {
        font-size: clamp(40px, 6vw, 72px);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -3px;
        margin-bottom: 30px;
    }

    .trip-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin: 50px 0;
    }
    .meta-card {
        padding: 30px;
        background: #f8fafc;
        border-radius: 30px;
        border: 1.5px solid #f1f5f9;
        transition: 0.3s;
    }
    .meta-card:hover { transform: translateY(-5px); border-color: var(--primary); background: white; }
    .meta-card i { font-size: 24px; color: var(--primary); margin-bottom: 15px; display: block; }
    .meta-card label { font-size: 12px; font-weight: 800; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px; }
    .meta-card value { font-size: 17px; font-weight: 900; color: var(--text-dark); display: block; }

    .description-box {
        font-size: 19px;
        line-height: 1.8;
        color: var(--text-light);
        margin-bottom: 60px;
    }

    /* Sticky Booking Card */
    .booking-sidebar {
        position: sticky;
        top: 100px;
        background: white;
        padding: 50px;
        border-radius: 40px;
        border: 1.5px solid #f1f5f9;
        box-shadow: 0 40px 80px rgba(0,0,0,0.06);
    }
    .sidebar-price { font-size: 48px; font-weight: 900; color: var(--text-dark); letter-spacing: -2px; margin-bottom: 5px; }
    .sidebar-price-label { color: var(--text-light); font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 35px; }

    .field-group { margin-bottom: 25px; }
    .field-label { display: block; font-weight: 800; color: var(--text-dark); margin-bottom: 12px; font-size: 13px; text-transform: uppercase; }
    .field-input {
        width: 100%;
        padding: 18px 22px;
        background: #f8fafc;
        border: 2px solid #f8fafc;
        border-radius: 20px;
        font-family: inherit;
        font-weight: 700;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .field-input:focus { outline: none; border-color: var(--primary); background: white; }

    .book-now-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white;
        padding: 22px;
        border-radius: 24px;
        font-size: 18px;
        font-weight: 900;
        border: none;
        cursor: pointer;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.25);
    }
    .book-now-btn:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(1, 105, 111, 0.35); }

    /* Reviews Section */
    .reviews-section { margin-top: 100px; padding-top: 80px; border-top: 1.5px solid #f1f5f9; }
    .review-card {
        padding: 40px;
        background: #f8fafc;
        border-radius: 32px;
        margin-bottom: 30px;
        border: 1.5px solid #f1f5f9;
        transition: 0.3s;
    }
    .review-card:hover { border-color: var(--primary); background: white; box-shadow: 0 20px 40px rgba(0,0,0,0.04); }
    .review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .reviewer-name { font-weight: 900; font-size: 18px; color: var(--text-dark); }
    .review-stars { color: #f59e0b; font-size: 14px; display: flex; gap: 4px; }
    .review-text { color: var(--text-light); line-height: 1.8; font-size: 16px; font-weight: 500; }

    @media (max-width: 1100px) {
        .package-container { grid-template-columns: 1fr; gap: 60px; }
        .package-hero { height: 50vh; border-radius: 0; margin: 0; }
        .trip-title { font-size: 48px; letter-spacing: -2px; }
    }
</style>

<div class="package-hero">
    <?php 
        $img_path = "uploads/" . $pkg['image_url'];
        if(!file_exists($img_path)) { $img_path = "admin/uploads/" . $pkg['image_url']; }
    ?>
    <img src="<?php echo $img_path; ?>" onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=1200';">
</div>

<div class="package-container">
    <div class="main-content">
        <div class="trip-badge"><?php echo $pkg['category']; ?></div>
        <h1 class="trip-title"><?php echo $pkg['title']; ?></h1>

        <div class="trip-meta-grid">
            <div class="meta-card">
                <i class="fas fa-map-marker-alt"></i>
                <label>Location</label>
                <value><?php echo $pkg['location']; ?></value>
            </div>
            <div class="meta-card">
                <i class="fas fa-clock"></i>
                <label>Duration</label>
                <value><?php echo $pkg['duration']; ?></value>
            </div>
            <div class="meta-card">
                <?php 
                    $mode = strtolower($pkg['travel_mode']);
                    $icon = "fa-plane";
                    if($mode == 'bus') $icon = "fa-bus";
                    if($mode == 'train') $icon = "fa-train";
                ?>
                <i class="fas <?php echo $icon; ?>"></i>
                <label>Travel Mode</label>
                <value><?php echo $pkg['travel_mode']; ?></value>
            </div>
            <div class="meta-card">
                <i class="fas fa-star" style="color:#f59e0b"></i>
                <label>Rating</label>
                <value><?php echo $average_score; ?> <small style="font-weight:600; opacity:0.5">(<?php echo $total_reviews; ?>)</small></value>
            </div>
        </div>

        <div class="description-box">
            <h3 style="font-size: 32px; font-weight: 900; color: var(--text-dark); margin-bottom: 25px; letter-spacing: -1px;">The Experience</h3>
            <p><?php echo nl2br($pkg['description']); ?></p>
        </div>

        <div class="reviews-section" id="reviews">
            <h3 style="font-size: 32px; font-weight: 900; color: var(--text-dark); margin-bottom: 40px; letter-spacing: -1px;">Guest Experiences</h3>
            
            <?php if(isset($_GET['review_status']) && $_GET['review_status'] == 'posted'): ?>
                <div style="background: #eef7f2; color: #1e5c37; padding: 15px 25px; border-radius: 16px; font-weight: 800; margin-bottom: 30px;">
                    <i class="fas fa-check-circle"></i> Review shared successfully!
                </div>
            <?php endif; ?>

            <div class="existing-reviews">
                <?php
                $review_res = $conn->query("SELECT * FROM reviews WHERE package_id = $id ORDER BY created_at DESC");
                if($review_res->num_rows > 0):
                    while($rev = $review_res->fetch_assoc()):
                ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-name"><?php echo htmlspecialchars($rev['user_name']); ?></div>
                            <div class="review-stars">
                                <?php for($i=1; $i<=5; $i++) echo ($i <= $rev['rating']) ? '★' : '☆'; ?>
                            </div>
                        </div>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                        <div style="margin-top: 15px; font-size: 12px; font-weight: 700; color: #94a3b8;">
                            <?php echo date('M d, Y', strtotime($rev['created_at'])); ?>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <p style="color: var(--text-light); font-weight: 600; font-style: italic;">No reviews yet. Be the first to share your experience!</p>
                <?php endif; ?>
            </div>

            <div style="margin-top: 60px; padding: 40px; background: #f8fafc; border-radius: 32px;">
                <h4 style="font-size: 24px; font-weight: 900; margin-bottom: 25px;">Write a Review</h4>
                <form action="" method="POST">
                    <div class="field-group">
                        <label class="field-label">Rating</label>
                        <select name="rating" class="field-input" required>
                            <option value="5">5 Stars - Amazing</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Okay</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label class="field-label">Your Experience</label>
                        <textarea class="field-input" name="comment" rows="4" placeholder="Tell us about the trip..." required></textarea>
                    </div>
                    <button type="submit" name="submit_review" class="book-now-btn" style="width: auto; padding: 18px 40px; font-size: 15px;">Post Review</button>
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <div class="booking-sidebar">
            <div class="sidebar-price">₹<?php echo number_format($pkg['price']); ?></div>
            <div class="sidebar-price-label">Price per guest</div>
            
            <form method="GET" action="checkout.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="field-group">
                    <label class="field-label">Travel Date</label>
                    <input type="date" name="travel_date" class="field-input" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="field-group">
                    <label class="field-label">Guests</label>
                    <select name="guests" class="field-input">
                        <option value="2 Adults (Recommended)">2 Adults (Recommended)</option>
                        <option value="1 Adult">1 Adult</option>
                        <option value="Group of 4+">Group of 4+</option>
                    </select>
                </div>

                <button type="submit" class="book-now-btn">Proceed to Checkout</button>
            </form>
            
            <div style="margin-top: 30px; text-align: center; color: var(--text-light); font-size: 13px; font-weight: 700;">
                <i class="fas fa-shield-alt" style="color: var(--primary);"></i> 100% Secure Checkout Guarantee
            </div>
        </div>
    </div>
</div>

<section class="escapes-section">
    <div class="escapes-header">
        <div class="header-text">
            <h2>Other Dream Escapes</h2>
            <p>Curated journeys tailored to your unique travel style.</p>
        </div>
        <a href="index.php" class="view-all-link">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="escapes-grid">
        <?php
        $others = $conn->query("SELECT * FROM packages WHERE id != $id LIMIT 3");
        while($op = $others->fetch_assoc()):
            $o_img = trim($op['image_url']);
            $image_src = "uploads/" . $o_img;
            if (!file_exists($image_src)) { $image_src = "admin/uploads/" . $o_img; }
        ?>
            <div class="escape-card">
                <div class="escape-img">
                    <img src="<?php echo $image_src; ?>" onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400';" alt="Trip">
                    <div class="escape-badge"><?php echo $op['category']; ?></div>
                </div>
                <div class="escape-info">
                    <h3><?php echo $op['title']; ?></h3>
                    <div class="escape-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo $op['location']; ?></span>
                        <span><i class="far fa-clock"></i> <?php echo $op['duration']; ?></span>
                    </div>
                    <div class="escape-footer">
                        <div class="price-stack">
                            <label>Starts from</label>
                            <value>₹<?php echo number_format($op['price']); ?></value>
                        </div>
                        <a href="package_details.php?id=<?php echo $op['id']; ?>" class="escape-btn">Explore</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<style>
    .escapes-section {
        max-width: 1400px;
        margin: 100px auto;
        padding: 80px 5%;
        background: #f8fafc;
        border-radius: 60px;
        border: 1.5px solid #f1f5f9;
    }
    .escapes-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 50px;
    }
    .header-text h2 { font-size: 36px; font-weight: 900; color: var(--text-dark); margin-bottom: 10px; letter-spacing: -1.5px; }
    .header-text p { font-size: 17px; color: var(--text-light); font-weight: 600; }
    
    .view-all-link {
        color: var(--primary);
        font-weight: 800;
        text-decoration: none;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: 0.3s;
    }
    .view-all-link:hover { gap: 15px; opacity: 0.8; }

    .escapes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
    }

    .escape-card {
        background: white;
        border-radius: 40px;
        overflow: hidden;
        border: 1.5px solid #f1f5f9;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        display: flex;
        flex-direction: column;
    }
    .escape-card:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(0,0,0,0.06); border-color: var(--primary); }
    
    .escape-img { height: 260px; position: relative; overflow: hidden; }
    .escape-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .escape-card:hover .escape-img img { transform: scale(1.1); }
    
    .escape-badge {
        position: absolute;
        top: 25px; left: 25px;
        background: rgba(255,255,255,0.95);
        padding: 8px 15px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--primary);
        letter-spacing: 1px;
        backdrop-filter: blur(10px);
    }

    .escape-info { padding: 35px; flex: 1; display: flex; flex-direction: column; }
    .escape-info h3 { font-size: 24px; font-weight: 900; color: var(--text-dark); margin-bottom: 12px; letter-spacing: -0.5px; }
    .escape-meta { display: flex; gap: 20px; color: var(--text-light); font-size: 14px; font-weight: 700; margin-bottom: 30px; }
    .escape-meta i { color: var(--primary); }

    .escape-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 25px;
        border-top: 1.5px solid #f8fafc;
    }
    .price-stack label { display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; }
    .price-stack value { font-size: 24px; font-weight: 900; color: var(--primary); letter-spacing: -1px; }

    .escape-btn {
        padding: 12px 25px;
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
        border-radius: 16px;
        font-weight: 800;
        font-size: 13px;
        transition: 0.3s;
        text-transform: uppercase;
    }
    .escape-btn:hover { background: var(--primary); color: white; }

    @media (max-width: 900px) {
        .escapes-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
</style>

<?php include 'f_footer.php'; ?>