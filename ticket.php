<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$u_phone = mysqli_real_escape_string($conn, $_SESSION['user_phone']);
$b_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT b.*, p.title, p.location, p.duration, p.image_url, p.price 
          FROM bookings b 
          JOIN packages p ON b.package_id = p.id 
          WHERE b.id = $b_id AND b.customer_phone = '$u_phone'";

$res = $conn->query($query);

if ($res->num_rows == 0) {
    die("Ticket not found or unauthorized access.");
}

$booking = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ticket - #TRP-<?php echo $booking['id']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #01696f;
            --secondary: #0f172a;
            --text-gray: #64748b;
            --bg-color: #f8fafc;
            --ticket-bg: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 40px 20px;
            color: var(--secondary);
        }

        .ticket-wrapper {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .ticket-card {
            background: var(--ticket-bg);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
        }

        .ticket-header {
            background: var(--primary);
            color: white;
            padding: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .brand span {
            font-size: 14px;
            font-weight: 600;
            opacity: 0.8;
            display: block;
            margin-top: 5px;
        }

        .ticket-id {
            text-align: right;
        }

        .ticket-id h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }

        .ticket-id span {
            font-size: 14px;
            opacity: 0.8;
        }

        .ticket-body {
            padding: 40px;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }

        .trip-details h2 {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 10px 0;
            color: var(--secondary);
        }

        .trip-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            color: var(--text-gray);
            font-weight: 600;
            font-size: 14px;
        }

        .trip-meta i {
            color: var(--primary);
            margin-right: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .info-box {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 16px;
        }

        .info-box label {
            display: block;
            font-size: 12px;
            color: var(--text-gray);
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .info-box p {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--secondary);
        }

        .ticket-sidebar {
            border-left: 2px dashed #e2e8f0;
            padding-left: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .qr-placeholder {
            width: 100%;
            aspect-ratio: 1;
            background: #f1f5f9;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .qr-placeholder img {
            width: 80%;
            height: 80%;
            opacity: 0.8;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            background: #dcfce7;
            color: #166534;
            font-weight: 800;
            border-radius: 12px;
            text-align: center;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }

        .ticket-footer {
            background: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            font-size: 13px;
            color: var(--text-gray);
            border-top: 1px solid #e2e8f0;
            font-weight: 600;
        }

        .action-bar {
            margin-top: 30px;
            text-align: center;
        }

        .btn-print {
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print:hover {
            box-shadow: 0 10px 20px rgba(1, 105, 111, 0.2);
            transform: translateY(-2px);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .ticket-wrapper {
                max-width: 100%;
            }
            .ticket-card {
                box-shadow: none;
                border: 2px solid #e2e8f0;
            }
            .action-bar {
                display: none;
            }
            .ticket-sidebar {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .ticket-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .info-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="ticket-wrapper">
    <div class="ticket-card" id="ticket-content">
        <div class="ticket-header">
            <div class="brand">
                <h1>Mytrip</h1>
                <span>Your Dream Escape Awaits</span>
            </div>
            <div class="ticket-id">
                <h2>#TRP-<?php echo $booking['id']; ?></h2>
                <span>Booking Reference</span>
            </div>
        </div>

        <div class="ticket-body">
            <div class="trip-main">
                <div class="trip-details">
                    <h2><?php echo htmlspecialchars($booking['title']); ?></h2>
                    <div class="trip-meta">
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($booking['location']); ?></span>
                        <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($booking['duration']); ?></span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <label>Travel Date</label>
                        <p><?php echo date('F d, Y', strtotime($booking['booking_date'])); ?></p>
                    </div>
                    <div class="info-box">
                        <label>Guest Name</label>
                        <p><?php echo htmlspecialchars($booking['customer_name']); ?></p>
                    </div>
                    <div class="info-box">
                        <label>Contact Phone</label>
                        <p><?php echo htmlspecialchars($booking['customer_phone']); ?></p>
                    </div>
                    <div class="info-box" style="grid-column: span 2;">
                        <label>Payment ID (Razorpay)</label>
                        <p style="font-size: 14px; font-family: monospace;"><?php echo $booking['payment_id']; ?></p>
                    </div>
                </div>
            </div>

            <div class="ticket-sidebar">
                <div>
                    <div class="qr-placeholder">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TRP-<?php echo $booking['id']; ?>" alt="QR Code">
                    </div>
                    <div class="status-badge">
                        <i class="fas fa-check-circle"></i> Confirmed
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <span style="font-size: 12px; color: var(--text-gray); font-weight: 700; text-transform: uppercase;">Amount Paid</span>
                    <div style="font-size: 24px; font-weight: 900; color: var(--secondary);">
                        ₹<?php echo number_format($booking['total_price']); ?>
                    </div>
                    <small style="font-size: 10px; color: #94a3b8;">Total incl. taxes & fees</small>
                </div>
            </div>
        </div>

        <div class="ticket-footer">
            Please present this ticket (digital or printed) along with a valid ID at the time of departure. 
            For support, contact hello@mytrip.com.
        </div>
    </div>

    <div class="action-bar">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print / Save as PDF
        </button>
    </div>
</div>

<script>
    // Automatically trigger print dialog to save as PDF
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
