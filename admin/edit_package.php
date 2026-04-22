<?php 
include 'header.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM packages WHERE id = $id");
    $pkg = $res->fetch_assoc();
}

if (isset($_POST['update_trip'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = $_POST['category'];
    $travel_mode = $_POST['travel_mode'];
    $price = $_POST['price'];
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Check if new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        // Delete old image
        @unlink("uploads/" . $pkg['image_url']);
    } else {
        $image = $pkg['image_url']; // Keep old image
    }

    $sql = "UPDATE packages SET 
            title='$title', location='$location', category='$category', 
            travel_mode='$travel_mode', price='$price', duration='$duration', 
            description='$description', image_url='$image' 
            WHERE id = $id";

    if ($conn->query($sql)) {
        echo "<script>alert('Package Updated Successfully!'); window.location='manage_packages.php';</script>";
    }
}
?>

<style>
    .edit-card {
        max-width: 800px;
        margin: 20px auto;
        background: white;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: #64748b; margin-bottom: 8px; text-transform: uppercase; }
    .form-control {
        width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0;
        background: #f8fafc; outline: none; transition: 0.3s;
    }
    .form-control:focus { border-color: var(--primary); background: #fff; }
    .btn-update {
        width: 100%; padding: 15px; background: var(--primary); color: white; border: none;
        border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;
    }
    .btn-update:hover { background: #4f46e5; transform: translateY(-2px); }
    .current-img-preview { width: 100px; height: 60px; object-fit: cover; border-radius: 8px; margin-top: 10px; border: 2px solid #e2e8f0; }
</style>

<div class="edit-card">
    <h2 style="margin-bottom: 30px; font-weight: 800;"><i class="fas fa-edit" style="color: var(--primary);"></i> Edit Package</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Trip Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo $pkg['title']; ?>" required>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo $pkg['location']; ?>" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" id="category" class="form-control" onchange="updateTravelModes()" required>
                    <option value="National" <?php if($pkg['category'] == 'National') echo 'selected'; ?>>National Trip</option>
                    <option value="International" <?php if($pkg['category'] == 'International') echo 'selected'; ?>>International Trip</option>
                </select>
            </div>
            <div class="form-group">
                <label>Travel Mode</label>
                <select name="travel_mode" id="travel_mode" class="form-control" required>
                    <option value="<?php echo $pkg['travel_mode']; ?>" selected><?php echo $pkg['travel_mode']; ?></option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (₹)</label>
                <input type="number" name="price" class="form-control" value="<?php echo $pkg['price']; ?>" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control" value="<?php echo $pkg['duration']; ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5" required><?php echo $pkg['description']; ?></textarea>
        </div>

        <div class="form-group">
            <label>Change Image (Leave blank to keep current)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <img src="uploads/<?php echo $pkg['image_url']; ?>" class="current-img-preview">
        </div>

        <button type="submit" name="update_trip" class="btn-update">Save Changes</button>
    </form>
</div>

<script>
function updateTravelModes() {
    const category = document.getElementById('category').value;
    const travelSelect = document.getElementById('travel_mode');
    const currentMode = "<?php echo $pkg['travel_mode']; ?>";
    travelSelect.innerHTML = '';

    if (category === 'International') {
        travelSelect.add(new Option('Flight', 'Flight'));
    } else {
        const modes = ['Flight', 'Train', 'Bus'];
        modes.forEach(m => {
            let opt = new Option(m, m);
            if(m === currentMode) opt.selected = true;
            travelSelect.add(opt);
        });
    }
}
// Run on load to populate travel modes correctly
window.onload = updateTravelModes;
</script>

<?php include 'footer.php'; ?>