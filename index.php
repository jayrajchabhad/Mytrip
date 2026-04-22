<?php 
include 'f_header.php'; 

// 1. GET USER FAVORITES 
$user_favs = [];
if(isset($_SESSION['user_id'])){
    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT package_id FROM favorites WHERE user_id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $fav_res = $stmt->get_result();
    while($f = $fav_res->fetch_assoc()){ 
        $user_favs[] = $f['package_id']; 
    }
}

// 2. HANDLE FILTERS
$where_clauses = [];
if(isset($_GET['cat'])){
    $cat = mysqli_real_escape_string($conn, $_GET['cat']);
    $where_clauses[] = "category = '$cat'";
}
if(isset($_GET['days'])){
    $days = mysqli_real_escape_string($conn, $_GET['days']);
    $where_clauses[] = "duration LIKE '%$days%'";
}

$where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";

// Handle Sorting
$order_sql = " ORDER BY id DESC"; // default
if(isset($_GET['sort'])){
    if($_GET['sort'] == 'low') $order_sql = " ORDER BY price ASC";
    if($_GET['sort'] == 'high') $order_sql = " ORDER BY price DESC";
}
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    :root { 
        --primary: #01696f; 
        --accent: #ff4757;
        --text-dark: #1a1a1a;
        --text-light: #717171;
        --bg-color: #f8fafb;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-color); }

    .main-content { padding: 40px 5%; max-width: 1400px; margin: 0 auto; }

    /* Filters */
    .filter-wrapper { margin-bottom: 40px; }
    .filter-container { display: flex; gap: 12px; justify-content: flex-start; overflow-x: auto; padding-bottom: 10px; }
    .filter-btn { 
        padding: 12px 24px; border-radius: 50px; background: white; 
        text-decoration: none; color: var(--text-dark); font-weight: 600; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; 
        white-space: nowrap; border: 1px solid transparent;
        display: flex; align-items: center; gap: 8px;
    }
    .filter-btn:hover { transform: translateY(-2px); border-color: var(--primary); }
    .filter-btn.active { background: var(--primary); color: white; }

    /* Secondary Filter Bar */
    .secondary-filters {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .filter-select {
        padding: 10px 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        font-family: inherit;
        font-weight: 600;
        color: var(--text-dark);
        outline: none;
        cursor: pointer;
        transition: 0.3s;
    }
    .filter-select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(1, 105, 111, 0.05); }

    .duration-pills { display: flex; gap: 10px; }
    .pill {
        padding: 8px 16px;
        border-radius: 10px;
        background: white;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 700;
        transition: 0.3s;
    }
    .pill:hover { border-color: var(--primary); color: var(--primary); }
    .pill.active { background: #eef7f8; color: var(--primary); border-color: var(--primary); }

    /* Header */
    .section-header { margin-bottom: 30px; }
    .section-header h2 { font-size: 32px; font-weight: 800; color: var(--text-dark); margin: 0; }
    .section-header p { color: var(--text-light); margin-top: 5px; }

    /* Trip Grid */
    .trip-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }

    /* Card Styling */
    .trip-card { 
        background: white; border-radius: 24px; overflow: hidden; 
        transition: 0.4s cubic-bezier(0.23, 1, 0.32, 1); position: relative; 
        box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;
    }
    .trip-card:hover { transform: translateY(-12px); box-shadow: 0 20px 60px rgba(0,0,0,0.1); }
    
    .card-image-box { position: relative; height: 240px; overflow: hidden; background: #eee; }
    .card-image-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .trip-card:hover .card-image-box img { transform: scale(1.1); }
    
    .category-tag {
        position: absolute; bottom: 15px; left: 15px;
        background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 8px;
        font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--primary);
    }

    .card-info { padding: 24px; }
    .card-info h3 { margin: 0 0 10px 0; font-size: 20px; color: var(--text-dark); font-weight: 700; }
    
    .meta-info { display: flex; gap: 15px; color: var(--text-light); font-size: 13px; margin-bottom: 20px; }
    .meta-info i { color: var(--primary); }

    .card-footer { 
        display: flex; justify-content: space-between; align-items: center; 
        border-top: 1px solid #f5f5f5; padding-top: 20px; 
    }
    
    .price-box small { display: block; font-size: 11px; color: var(--text-light); font-weight: 600; }
    .price { font-size: 24px; font-weight: 800; color: var(--primary); }

    .view-btn { 
        background: #eef7f8; color: var(--primary); padding: 12px 20px; 
        border-radius: 14px; text-decoration: none; font-weight: 700; 
        font-size: 14px; transition: 0.3s; 
    }
    .view-btn:hover { background: var(--primary); color: white; }

    /* Heart Button */
    .fav-container { position: absolute; top: 15px; right: 15px; z-index: 5; }
    .fav-btn {
        width: 42px; height: 42px; border: none;
        background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.3s; color: white; font-size: 18px;
        outline: none;
    }
    .fav-btn:hover { background: white; color: var(--accent); }
    .fav-btn.active { background: white; color: var(--accent); border-color: var(--accent); animation: pop 0.4s; }

    @keyframes pop {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }
</style>

<div class="main-content">
    <div class="filter-wrapper">
        <div class="filter-container">
            <a href="index.php" class="filter-btn <?php echo !isset($_GET['cat']) ? 'active' : ''; ?>">
                <i class="fas fa-globe-americas"></i> All Trips
            </a>
            <a href="index.php?cat=National" class="filter-btn <?php echo ($_GET['cat'] ?? '') == 'National' ? 'active' : ''; ?>">
                <i class="fas fa-map-marker-alt"></i> India Special
            </a>
            <a href="index.php?cat=International" class="filter-btn <?php echo ($_GET['cat'] ?? '') == 'International' ? 'active' : ''; ?>">
                <i class="fas fa-plane"></i> World Tour
            </a>
        </div>

        <div class="secondary-filters">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 13px; font-weight: 800; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Sort By:</span>
                <select class="filter-select" onchange="applyFilter('sort', this.value)">
                    <option value="">Newest First</option>
                    <option value="low" <?php echo ($_GET['sort'] ?? '') == 'low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="high" <?php echo ($_GET['sort'] ?? '') == 'high' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-left: auto;">
                <span style="font-size: 13px; font-weight: 800; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Duration:</span>
                <div class="duration-pills">
                    <a href="javascript:void(0)" onclick="applyFilter('days', '')" class="pill <?php echo !isset($_GET['days']) || $_GET['days'] == '' ? 'active' : ''; ?>">All</a>
                    <a href="javascript:void(0)" onclick="applyFilter('days', '3 Days')" class="pill <?php echo ($_GET['days'] ?? '') == '3 Days' ? 'active' : ''; ?>">3 Days</a>
                    <a href="javascript:void(0)" onclick="applyFilter('days', '5 Days')" class="pill <?php echo ($_GET['days'] ?? '') == '5 Days' ? 'active' : ''; ?>">5 Days</a>
                    <a href="javascript:void(0)" onclick="applyFilter('days', '7 Days')" class="pill <?php echo ($_GET['days'] ?? '') == '7 Days' ? 'active' : ''; ?>">7+ Days</a>
                </div>
            </div>
        </div>
    </div>

    <header class="section-header">
        <h2>Find Your Next Adventure</h2>
        <p>Explore handpicked packages for your dream vacation</p>
    </header>
    
    <div id="package_container" class="trip-grid">
        <?php
        $query = "SELECT * FROM packages $where_sql $order_sql";
        $res = $conn->query($query);
        if($res->num_rows > 0):
            while($row = $res->fetch_assoc()): 
                $is_liked = in_array($row['id'], $user_favs);
                
                $img_name = trim($row['image_url']);
                $image_src = "uploads/" . $img_name;
                
                // Fallback check
                if (!file_exists($image_src)) {
                    $image_src = "admin/uploads/" . $img_name;
                }
        ?>
            <div class="trip-card">
                <div class="card-image-box">
                    <div class="fav-container">
                        <button class="fav-btn <?php echo $is_liked ? 'active' : ''; ?>" onclick="toggleFavorite(this, <?php echo $row['id']; ?>)">
                            <i class="<?php echo $is_liked ? 'fas' : 'far'; ?> fa-heart"></i>
                        </button>
                    </div>
                    
                    <img src="<?php echo $image_src; ?>" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400';" 
                         alt="Trip Image">
                    
                    <div class="category-tag"><?php echo $row['category']; ?></div>
                </div>

                <div class="card-info">
                    <h3><?php echo $row['title']; ?></h3>
                    <div class="meta-info">
                        <span><i class="fas fa-map-pin"></i> <?php echo $row['location']; ?></span>
                        <span><i class="far fa-calendar-alt"></i> <?php echo $row['duration']; ?></span>
                        <span>
                            <?php 
                            $mode = strtolower($row['travel_mode']);
                            $icon = "fa-plane"; // default
                            if($mode == 'bus') $icon = "fa-bus";
                            if($mode == 'train') $icon = "fa-train";
                            ?>
                            <i class="fas <?php echo $icon; ?>"></i> <?php echo $row['travel_mode']; ?>
                        </span>
                    </div>
                    
                    <div class="card-footer">
                        <div class="price-box">
                            <small>Starts from</small>
                            <span class="price">₹<?php echo number_format($row['price']); ?></span>
                        </div>
                        <a href="package_details.php?id=<?php echo $row['id']; ?>" class="view-btn">
                            Details <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; 
        else: ?>
            <p style="text-align:center; grid-column: 1/-1; padding: 50px;">No trips found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function toggleFavorite(btn, packageId) {
    $.post("toggle_favorite.php", { package_id: packageId }, function(response) {
        let res = response.trim();
        
        if (res == "login_required") {
            alert("Please login to save favorites!");
            window.location.href = "login.php";
            return;
        }

        // Toggle the 'active' class on the button
        btn.classList.toggle('active');
        
        // Find the heart icon inside the button
        const icon = btn.querySelector('i');
        
        // Switch between solid (fas) and regular (far) heart icons
        if (btn.classList.contains('active')) {
            icon.classList.replace('far', 'fas');
        } else {
            icon.classList.replace('fas', 'far');
        }
    });
}

function applyFilter(key, value) {
    let url = new URL(window.location.href);
    if (value) {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    window.location.href = url.toString();
}
</script>

<?php include 'f_footer.php'; ?>