<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Trip - Discover Your Next Adventure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #00695c; --bg: #f8f9fa; --dark: #333; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: var(--bg); color: var(--dark); }
        
        /* Navbar */
        nav { background: white; padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { font-weight: bold; color: var(--primary); font-size: 1.5rem; }
        
        /* Hero Section */
        .hero { background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1506461883276-594a12b11cf3?q=80&w=2070') center/cover; height: 400px; display: flex; align-items: center; justify-content: center; color: white; text-align: center; }

        /* Package Grid */
        .container { padding: 50px 5%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .card { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; position: relative; }
        .card:hover { transform: translateY(-10px); }
        .card img { width: 100%; height: 220px; object-fit: cover; }
        .card-body { padding: 20px; }
        .category-tag { position: absolute; top: 15px; left: 15px; background: var(--primary); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; }
        .price { color: var(--primary); font-size: 1.4rem; font-weight: bold; }
        .btn { display: inline-block; padding: 10px 20px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px; margin-top: 15px; width: 100%; text-align: center; }
    </style>
</head>
<body>

<nav>
    <div class="logo"><i class="fa-solid fa-earth-americas"></i> My Trip</div>
    <div class="menu">
        <a href="index.php" style="margin-right:20px; text-decoration:none; color:var(--dark);">Home</a>
        <a href="admin/dashboard.php" style="text-decoration:none; color:var(--primary);">Admin Login</a>
    </div>
</nav>

<section class="hero">
    <div>
        <h1>Explore Beautiful India</h1>
        <p>Book Manali, Rajasthan, Kerala & more at best prices</p>
    </div>
</section>

<div class="container">
    <h2 style="margin-bottom:30px;">Featured Packages</h2>
    <div class="grid">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM packages");
        while($row = mysqli_fetch_assoc($query)) {
        ?>
        <div class="card">
            <span class="category-tag"><?php echo $row['category']; // Matches your Admin Category field ?></span>
            <img src="uploads/<?php echo $row['p_image']; ?>" alt="Trip">
            <div class="card-body">
                <h3><?php echo $row['p_title']; ?></h3>
                <p style="color:#666;"><i class="fa-solid fa-location-dot"></i> <?php echo $row['p_location']; ?></p>
                <p><i class="fa-solid fa-plane"></i> Travel via: <?php echo $row['travel_mode']; ?></p>
                <div class="price">₹<?php echo number_format($row['p_price']); ?></div>
                <a href="details.php?id=<?php echo $row['id']; ?>" class="btn">View Trip Details</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

</body>
</html>