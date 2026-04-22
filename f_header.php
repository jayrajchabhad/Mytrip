<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'db.php';

// Fetch Dynamic Branding
$settings = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$site_logo = $settings['logo'] ?? 'logo.png';
$site_name = $settings['site_name'] ?? 'MyTrip';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $site_name; ?> | Premium Travels</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    :root { 
        --primary: #01696f; 
        --accent: #ff7e5f; 
        --bg: #f8fafc; 
        --nav-height: 80px;
        --glass: rgba(255, 255, 255, 0.75);
    }
    
    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background: var(--bg); 
        margin: 0; 
        padding-top: 15px;
        color: #0f172a;
    }

    /* Floating Island Nav */
    nav { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 0 45px; 
        height: 75px;
        background: rgba(255, 255, 255, 0.7); 
        backdrop-filter: blur(30px) saturate(200%); 
        -webkit-backdrop-filter: blur(30px) saturate(200%);
        position: sticky; 
        top: 20px; 
        z-index: 1000;
        margin: 0 5%;
        border-radius: 32px;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        box-shadow: 
            0 20px 40px rgba(0,0,0,0.04),
            0 1px 1px rgba(255,255,255,1) inset;
    }

    /* Animated Search Box */
    .search-box { 
        position: relative; 
        background: #f1f5f9; 
        border-radius: 18px; 
        padding: 2px 18px; 
        display: flex; 
        align-items: center; 
        transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        border: 1.5px solid transparent;
        width: 260px;
    }
    .search-box:focus-within {
        width: 380px;
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.08);
    }
    .search-box input { 
        border: none; 
        background: transparent; 
        padding: 12px; 
        outline: none; 
        width: 100%; 
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }

    /* Navigation Link Styling */
    .nav-links { display: flex; align-items: center; gap: 32px; }
    .nav-links a { 
        text-decoration: none; 
        font-weight: 700; 
        font-size: 13px; 
        color: #64748b; 
        transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .nav-links a:hover { color: var(--primary); transform: translateY(-2px); }
    .nav-links i { margin-right: 8px; font-size: 16px; opacity: 0.7; }

    /* Action Buttons */
    .btn-prime { 
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white !important; 
        padding: 14px 30px !important; 
        border-radius: 18px; 
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.2);
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1) !important;
        border: none;
    }
    .btn-prime:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 20px 40px rgba(1, 105, 111, 0.3);
    }

    .user-pill { 
        background: #eef7f8; 
        color: var(--primary) !important;
        padding: 10px 22px !important; 
        border-radius: 18px; 
        font-weight: 800 !important; 
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid #e2f1f2;
    }
    .user-pill:hover { background: #e2f1f2; }

    /* Logo Animation */
    .brand-logo { transition: 0.4s; text-decoration: none; display: flex; align-items: center; gap: 12px; }
    .brand-logo:hover { transform: scale(1.05); }

    @media (max-width: 1100px) {
        nav { margin: 0 10px; padding: 0 20px; border-radius: 20px; }
        .search-box { display: none; }
        .nav-links span { display: none; }
    }
</style>
</head>
<body>

<nav>
    <a href="index.php" class="brand-logo">
        <div style="background: var(--primary); width: 42px; height: 42px; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(1, 105, 111, 0.2);">
            <i class="fas fa-paper-plane" style="color: white; font-size: 20px;"></i>
        </div>
        <span style="font-weight:900; color:#0f172a; font-size:24px; letter-spacing:-1.5px;"><?php echo $site_name; ?></span>
    </a>

    <div class="search-box">
        <i class="fas fa-search" style="color:#888;"></i>
        <input type="text" id="live_search" placeholder="Where to next?">
    </div>

    <div class="nav-links">
        <a href="index.php"><i class="fas fa-compass"></i> Explore</a>
        <a href="budget_tool.php"><i class="fas fa-calculator"></i> Planner</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="blog.php"><i class="fas fa-book-open"></i> Guides</a>
        <a href="support.php"><i class="fas fa-headset"></i> Support</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="favorites.php"><i class="fas fa-heart" style="color: #ff5f5f;"></i> Wishlist</a>
            <a href="user_dashboard.php"><i class="fas fa-ticket-alt"></i> My Trips</a>
            <a href="profile.php" class="user-pill"><i class="fas fa-user-circle"></i> Hi, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></a>
            <a href="logout.php" style="color: #888; font-size: 12px;">Logout</a>
        <?php else: ?>
            <a href="login.php">Sign In</a>
            <a href="register.php" class="btn-prime">Join Now</a>
        <?php endif; ?>
    </div>
</nav>



<script>
$(document).ready(function(){
    $("#live_search").keyup(function(){
        let input = $(this).val();
        
        // Target the container on index.php
        let container = $("#package_container");

        if(input != ""){
            $.ajax({
                url: "ajax_search.php", 
                method: "POST",
                data: {search: input}, 
                success: function(data){
                    // Only update if we are on a page that has the container
                    if(container.length > 0) {
                        container.html(data);
                    }
                }
            });
        } else {
            // Only reload if the search was actually used to filter
            if(input == "" && container.length > 0) {
                location.reload(); 
            }
        }
    });
});
</script>