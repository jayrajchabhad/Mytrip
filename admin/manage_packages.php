<?php 
include 'header.php'; 

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image_url FROM packages WHERE id = $id");
    if($row = $res->fetch_assoc()) { @unlink("uploads/".$row['image_url']); }
    $conn->query("DELETE FROM packages WHERE id = $id");
    echo "<script>window.location='manage_packages.php';</script>";
}
?>

<style>
    .manage-section {
        background: var(--surface);
        padding: 30px;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .page-header {
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 30px;
    }

    .btn-add {
        background: var(--primary);
        color: white !important;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        transition: var(--transition);
    }

    .btn-add:hover { background: var(--primary-hover); transform: translateY(-2px); }

    .pkg-table { width: 100%; border-collapse: collapse; }
    .pkg-table th { 
        text-align: left; padding: 15px; 
        color: var(--text-muted); font-size: 12px; 
        text-transform: uppercase; border-bottom: 1px solid var(--border);
    }
    .pkg-table td { padding: 20px 15px; border-bottom: 1px solid var(--surface-soft); }

    /* Action Buttons Styling */
    .action-link {
        padding: 10px;
        border-radius: 10px;
        margin: 0 2px;
        transition: var(--transition);
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--surface-soft);
    }
    
    .view-link:hover { color: #007bff; background: #e7f1ff; }
    .edit-link:hover { color: var(--primary); background: var(--primary-soft); }
    .delete-link:hover { color: var(--danger); background: var(--danger-soft); }

    .badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }
    .badge-intl { background: var(--warning-soft); color: var(--warning); }
    .badge-nat { background: var(--success-soft); color: var(--success); }
    .price-text { font-weight: 700; color: var(--text); }
</style>

<div class="page-header">
    <h2 style="font-weight: 800; color: var(--text);">Manage Packages</h2>
    <a href="add_package.php" class="btn-add"><i class="fas fa-plus-circle"></i> Add New Trip</a>
</div>

<div class="manage-section">
    <table class="pkg-table">
        <thead>
            <tr>
                <th>Trip Details</th>
                <th>Category</th>
                <th>Price</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM packages ORDER BY id DESC");
            while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <img src="uploads/<?php echo $row['image_url']; ?>" style="width: 65px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border);">
                        <div>
                            <div style="font-weight: 700; color: var(--text);"><?php echo $row['title']; ?></div>
                            <div style="font-size: 12px; color: var(--text-muted);"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']; ?></div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge <?php echo ($row['category'] == 'International') ? 'badge-intl' : 'badge-nat'; ?>">
                        <?php echo $row['category']; ?>
                    </span>
                </td>
                <td class="price-text">₹<?php echo number_format($row['price']); ?></td>
                <td style="text-align: right;">
                    <a href="view_package.php?id=<?php echo $row['id']; ?>" class="action-link view-link" title="View Details">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <a href="edit_package.php?id=<?php echo $row['id']; ?>" class="action-link edit-link" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <a href="?delete=<?php echo $row['id']; ?>" class="action-link delete-link" onclick="return confirm('Are you sure you want to delete this package?')" title="Delete">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>