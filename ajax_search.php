<?php
include 'db.php';

if(isset($_POST['search'])){
    $input = mysqli_real_escape_string($conn, $_POST['search']);
    
    // Query to find matching packages
    $query = "SELECT * FROM packages WHERE title LIKE '%$input%' OR location LIKE '%$input%' OR category LIKE '%$input%' ORDER BY id DESC";
    $res = $conn->query($query);

    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            // --- IMAGE LOGIC (Matching your index.php) ---
            $img_name = trim($row['image_url']);
            $image_src = "uploads/" . $img_name;
            
            // Check if file exists in root, otherwise try admin folder
            if (!file_exists($image_src)) {
                $image_src = "admin/uploads/" . $img_name;
            }
            
            // Generate the Card HTML
            echo '
            <div class="trip-card">
                <div class="card-image-box" style="position: relative; height: 200px; overflow: hidden;">
                    <img src="'.$image_src.'" 
                         onerror="this.src=\'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400\'" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                    <div class="category-tag" style="position: absolute; bottom: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; color: #01696f;">
                        '.$row['category'].'
                    </div>
                </div>
                
                <div class="card-info" style="padding: 20px;">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #1a1a1a;">'.$row['title'].'</h3>
                    <div style="display: flex; gap: 10px; color: #717171; font-size: 11px; margin-bottom: 15px; flex-wrap: wrap;">
                        <span><i class="fas fa-map-pin"></i> '.$row['location'].'</span>
                        <span><i class="far fa-clock"></i> '.$row['duration'].'</span>
                        <span>
                            <i class="fas '.($row['travel_mode'] == 'Bus' ? 'fa-bus' : ($row['travel_mode'] == 'Train' ? 'fa-train' : 'fa-plane')).'"></i> 
                            '.$row['travel_mode'].'
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f5f5f5; padding-top: 15px;">
                        <span style="font-size: 20px; font-weight: 800; color: #01696f;">₹'.number_format($row['price']).'</span>
                        <a href="package_details.php?id='.$row['id'].'" 
                           style="background: #eef7f8; color: #01696f; padding: 8px 16px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 13px;">
                           Details
                        </a>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo "<div style='grid-column: 1/-1; text-align: center; padding: 100px 20px;'>
                <i class='fas fa-search' style='font-size: 40px; color: #ddd; margin-bottom: 20px;'></i>
                <h3 style='color: #717171;'>No trips found for '$input'</h3>
                <p style='color: #999;'>Try searching for a different destination or category.</p>
              </div>";
    }
}
?>