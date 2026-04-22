<?php 
include 'f_header.php'; 
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$u_phone = mysqli_real_escape_string($conn, $_SESSION['user_phone']); 

// Handle Cancel Action
if(isset($_POST['cancel_booking'])) {
    $b_id = intval($_POST['booking_id']);
    $conn->query("UPDATE bookings SET status='Cancelled' WHERE id=$b_id AND customer_phone='$u_phone'");
    echo "<script>window.location.href='user_dashboard.php?msg=Booking+Cancelled';</script>";
    exit;
}

// Handle Delete Action
if(isset($_POST['delete_booking'])) {
    $b_id = intval($_POST['booking_id']);
    $conn->query("DELETE FROM bookings WHERE id=$b_id AND customer_phone='$u_phone'");
    echo "<script>window.location.href='user_dashboard.php?msg=Booking+Deleted';</script>";
    exit;
}

$bookings = $conn->query("SELECT b.*, p.title, p.image_url, p.price FROM bookings b 
                         JOIN packages p ON b.package_id = p.id 
                         WHERE b.customer_phone = '$u_phone' ORDER BY b.id DESC");
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: white; }

    .dashboard-wrapper {
        padding: 60px 5%;
        max-width: 1400px;
        margin: 0 auto;
        min-height: 80vh;
    }

    .dashboard-header {
        margin-bottom: 60px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .welcome-text h2 { 
        font-size: 36px; 
        font-weight: 900; 
        color: var(--text-dark); 
        margin-bottom: 10px; 
        letter-spacing: -1.5px;
    }
    .welcome-text p { font-size: 17px; color: var(--text-light); font-weight: 600; }

    .booking-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .booking-card {
        background: white;
        border-radius: 40px;
        padding: 30px;
        display: grid;
        grid-template-columns: 140px 1.5fr 1fr 1fr 200px;
        align-items: center;
        gap: 40px;
        border: 1.5px solid #f1f5f9;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .booking-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.06);
        border-color: var(--primary);
    }

    .booking-img {
        width: 140px;
        height: 110px;
        border-radius: 24px;
        object-fit: cover;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .trip-info h4 {
        margin: 0 0 8px 0;
        font-size: 22px;
        font-weight: 900;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }
    .trip-info span {
        font-size: 14px;
        color: var(--text-light);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.7;
    }

    .meta-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .meta-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 15px;
    }
    .meta-item i { color: var(--primary); font-size: 16px; width: 20px; }

    .status-pill {
        padding: 10px 20px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .status-confirmed { background: #eef7f2; color: #1e5c37; }
    .status-pending { background: #fff9eb; color: #92400e; }
    .status-cancelled { background: #fef2f2; color: #991b1b; }

    .action-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .btn-ticket {
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white !important;
        text-decoration: none;
        padding: 14px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 800;
        text-align: center;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(1, 105, 111, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-ticket:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(1, 105, 111, 0.3); }

    .btn-secondary-group {
        display: flex;
        gap: 15px;
        justify-content: center;
    }
    .btn-ghost {
        background: none;
        border: none;
        padding: 5px;
        font-size: 13px;
        font-weight: 800;
        color: var(--text-light);
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }
    .btn-ghost:hover { color: var(--text-dark); text-decoration: underline; }
    .btn-delete:hover { color: #dc2626; }

    .empty-state {
        text-align: center;
        padding: 100px 40px;
        background: #f8fafc;
        border-radius: 40px;
        border: 2px dashed #e2e8f0;
    }

    @media (max-width: 1100px) {
        .booking-card {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 40px;
            gap: 25px;
        }
        .booking-img { margin: 0 auto; width: 200px; height: 140px; }
        .meta-item { justify-content: center; }
        .status-pill { margin: 0 auto; }
        .dashboard-header { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-header">
        <div class="welcome-text">
            <h2>Welcome, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?> 👋</h2>
            <p>Manage your upcoming travels and booking history.</p>
        </div>
        <?php if(isset($_GET['msg'])): ?>
            <div style="background: #eef7f2; color: #1e5c37; padding: 12px 25px; border-radius: 14px; font-weight: 800; font-size: 14px;">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="booking-grid">
        <?php if($bookings->num_rows > 0): ?>
            <?php while($row = $bookings->fetch_assoc()): ?>
                <div class="booking-card">
                    <?php 
                        $img_path = "uploads/" . $row['image_url'];
                        if(!file_exists($img_path)) { $img_path = "admin/uploads/" . $row['image_url']; }
                    ?>
                    <img src="<?php echo $img_path; ?>" class="booking-img" onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400';">

                    <div class="trip-info">
                        <span>Booking ID: #TRP-<?php echo $row['id']; ?></span>
                        <h4><?php echo $row['title']; ?></h4>
                    </div>

                    <div class="meta-box">
                        <div class="meta-item">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('d M Y', strtotime($row['booking_date'])); ?>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            ₹<?php echo number_format($row['price']); ?>
                        </div>
                    </div>

                    <div class="status-box">
                        <?php 
                        $status = $row['status'];
                        if($status == 'Confirmed') {
                            echo '<div class="status-pill status-confirmed"><i class="fas fa-check-circle"></i> Confirmed</div>';
                        } elseif($status == 'Pending') {
                            echo '<div class="status-pill status-pending"><i class="fas fa-clock"></i> Pending</div>';
                        } else {
                            echo '<div class="status-pill status-cancelled"><i class="fas fa-times-circle"></i> Cancelled</div>';
                        }
                        ?>
                    </div>

                    <div class="action-group">
                        <?php if($status == 'Confirmed'): ?>
                            <a href="ticket.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn-ticket">
                                <i class="fas fa-ticket-alt"></i> View Ticket
                            </a>
                        <?php elseif($status == 'Pending'): ?>
                            <div style="text-align: center; color: var(--text-light); font-weight: 800; font-size: 13px;">
                                <i class="fas fa-spinner fa-spin"></i> Processing...
                            </div>
                        <?php endif; ?>
                        
                        <div class="btn-secondary-group">
                            <?php if($status != 'Cancelled'): ?>
                            <form method="POST" onsubmit="return confirm('Cancel this trip?');">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="cancel_booking" class="btn-ghost">Cancel</button>
                            </form>
                            <?php endif; ?>

                            <form method="POST" onsubmit="return confirm('Delete this record permanently?');">
                                <input type="hidden" name="booking_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_booking" class="btn-ghost btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="120" style="opacity: 0.1; margin-bottom: 30px;">
                <h3 style="font-size: 28px; font-weight: 900; color: var(--text-dark); margin-bottom: 15px;">No adventures yet.</h3>
                <p style="color: var(--text-light); font-weight: 600; margin-bottom: 30px;">The world is waiting for you to explore its hidden gems.</p>
                <a href="index.php" class="btn-ticket" style="display: inline-flex; width: auto; padding: 18px 40px;">Explore Destinations</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'f_footer.php'; ?>