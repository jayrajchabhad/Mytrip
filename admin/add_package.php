<?php 
include 'header.php'; 

if (isset($_POST['add_trip'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = $_POST['category'];
    $travel_mode = $_POST['travel_mode'];
    $price = $_POST['price'];
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image = time() . "_" . $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    $sql = "INSERT INTO packages (title, location, category, travel_mode, price, duration, description, image_url) 
            VALUES ('$title', '$location', '$category', '$travel_mode', '$price', '$duration', '$description', '$image')";

    if ($conn->query($sql)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        echo "<script>alert('Trip Added Successfully!'); window.location='manage_packages.php';</script>";
    }
}
?>

<style>
    .form-card {
        max-width: 850px;
        margin: 20px auto;
        background: var(--surface);
        padding: 40px;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }

    .form-header h2 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-header i { color: var(--primary); }

    .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.05em;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--surface-2);
        color: var(--text);
        font-size: 15px;
        transition: var(--transition);
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .full-width { grid-column: span 2; }

    .btn-submit {
        background: var(--primary);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: var(--transition);
    }

    .btn-submit:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h2><i class="fas fa-plus-circle"></i> Add New Trip Package</h2>
    </div>
    <form method="POST" enctype="multipart/form-data">
        <div class="input-grid">
            <div class="form-group">
                <label>Trip Title</label>
                <input type="text" name="title" class="form-control" placeholder="Manali Special" required>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" class="form-control" placeholder="Himachal Pradesh" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" id="category" class="form-control" onchange="updateTravelModes()" required>
                    <option value="National">National Trip</option>
                    <option value="International">International Trip</option>
                </select>
            </div>
            <div class="form-group">
                <label>Travel Mode</label>
                <select name="travel_mode" id="travel_mode" class="form-control" required>
                    <option value="Flight">Flight</option>
                    <option value="Train">Train</option>
                    <option value="Bus">Bus</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price (₹)</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Duration</label>
                <input type="text" name="duration" class="form-control" placeholder="3 Days, 2 Nights" required>
            </div>
            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group full-width">
                <label>Trip Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
        </div>
        <button type="submit" name="add_trip" class="btn-submit">Publish Package</button>
    </form>
</div>

<script>
function updateTravelModes() {
    const category = document.getElementById('category').value;
    const travelSelect = document.getElementById('travel_mode');
    travelSelect.innerHTML = '';
    if (category === 'International') {
        travelSelect.add(new Option('Flight', 'Flight'));
    } else {
        ['Flight', 'Train', 'Bus'].forEach(m => travelSelect.add(new Option(m, m)));
    }
}
</script>