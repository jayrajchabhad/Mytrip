<?php 
include 'header.php'; 

// --- DELETE REVIEW LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM reviews WHERE id = $id");
    echo "<script>window.location='reviews.php';</script>";
}
?>

<style>
    /* 1. Theme Variables */
    :root {
        --accent: #6366f1;
        --accent-hover: #4f46e5;
        --danger: #ef4444;
        --bg-glass: rgba(255, 255, 255, 0.9);
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #f1f5f9;
        --star-gold: #f59e0b;
    }

    /* 2. Header Style */
    .admin-header {
        margin-bottom: 30px;
    }

    .admin-header h2 {
        color: var(--text-dark);
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .admin-header p {
        color: var(--text-muted);
        font-size: 14px;
        margin-top: 5px;
    }

    /* 3. Glassmorphism Review Card */
    .review-card {
        background: var(--bg-glass);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 18px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        border: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.08);
    }

    /* 4. Review Content Styling */
    .reviewer-info b {
        font-size: 16px;
        color: var(--text-dark);
        display: block;
    }

    .trip-tag {
        font-size: 12px;
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: inline-block;
    }

    .star-active { color: var(--star-gold); font-size: 16px; margin-right: 2px; }
    .star-inactive { color: #e2e8f0; font-size: 16px; margin-right: 2px; }

    .review-comment {
        color: #475569;
        font-size: 14px;
        line-height: 1.6;
        margin: 12px 0;
        font-style: italic;
    }

    .review-date {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* 5. Delete Button */
    .btn-delete-review {
        background: #fef2f2;
        color: var(--danger);
        padding: 10px 18px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    .btn-delete-review:hover {
        background: var(--danger);
        color: white;
        box-shadow: 0 8px 15px rgba(239, 68, 68, 0.2);
    }

    /* 6. Empty State */
    .no-reviews {
        text-align: center;
        padding: 80px;
        background: var(--bg-glass);
        border-radius: 24px;
        color: var(--text-muted);
        border: 2px dashed var(--border-color);
    }
</style>

<div class="admin-header">
    <h2>Package Reviews</h2>
    <p>Monitor customer satisfaction and moderate feedback for your travel packages.</p>
</div>

<div class="reviews-list">
    <?php
    // Fetching reviews with package titles
    $sql = "SELECT r.*, p.title FROM reviews r 
            LEFT JOIN packages p ON r.package_id = p.id 
            ORDER BY r.created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tripName = $row['title'] ? $row['title'] : "Deleted Package";
            ?>
            
            <div class="review-card">
                <div style="flex: 1; padding-right: 20px;">
                    <span class="trip-tag"><?php echo $tripName; ?></span>
                    
                    <div class="reviewer-info">
                        <b><?php echo $row['customer_name']; ?></b>
                    </div>

                    <div style="margin: 8px 0;">
                        <?php 
                        for($i=1; $i<=5; $i++) {
                            echo ($i <= $row['rating']) ? "<span class='star-active'>★</span>" : "<span class='star-inactive'>★</span>";
                        }
                        ?>
                    </div>

                    <p class="review-comment">"<?php echo htmlspecialchars($row['comment']); ?>"</p>
                    
                    <div class="review-date">
                        <i class="far fa-calendar-alt" style="margin-right: 5px;"></i>
                        <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                    </div>
                </div>

                <div>
                    <a href="?delete_id=<?php echo $row['id']; ?>" 
                       class="btn-delete-review"
                       onclick="return confirm('Permanently remove this customer review?')">
                       <i class="fas fa-trash-alt" style="margin-right: 5px;"></i> Delete
                    </a>
                </div>
            </div>

            <?php
        }
    } else {
        echo "<div class='no-reviews'>
                <i class='fas fa-comment-slash' style='font-size: 40px; margin-bottom: 15px; color: #cbd5e1;'></i>
                <p>No customer reviews have been submitted yet.</p>
              </div>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>