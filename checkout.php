<?php
session_start();
include 'db.php';
include 'config_razorpay.php';


// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])){
    // Save the referring URL to come back after login if possible, but for simplicity, just redirect to login.
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$travel_date = isset($_GET['travel_date']) ? mysqli_real_escape_string($conn, $_GET['travel_date']) : '';
$guests = isset($_GET['guests']) ? mysqli_real_escape_string($conn, $_GET['guests']) : '2 Adults (Recommended)';

// Handle Payment Submission
if (isset($_POST['process_payment'])) {
    $u_name = mysqli_real_escape_string($conn, $_SESSION['user_name']);
    $u_phone = mysqli_real_escape_string($conn, $_SESSION['user_phone']);
    $p_id = intval($_POST['package_id']);
    $b_date = mysqli_real_escape_string($conn, $_POST['travel_date']);
    $payment_id = mysqli_real_escape_string($conn, $_POST['razorpay_payment_id']);
    
    // Calculate total price dynamically based on guests
    $guests_str = isset($_POST['guests_count']) ? mysqli_real_escape_string($conn, $_POST['guests_count']) : '2 Adults';
    $res_p = $conn->query("SELECT price FROM packages WHERE id = $p_id");
    $pkg_p = $res_p->fetch_assoc();
    
    $multiplier = 2; // default
    if(strpos($guests_str, '1 Adult') !== false) $multiplier = 1;
    if(strpos($guests_str, '4+') !== false) $multiplier = 4;
    
    $total_p = $pkg_p['price'] * $multiplier;
    $g_total = $total_p + ($total_p * 0.18);

    // Insert into bookings table
    $sql = "INSERT INTO bookings (customer_name, customer_phone, package_id, booking_date, status, payment_id, total_price) 
            VALUES ('$u_name', '$u_phone', '$p_id', '$b_date', 'Confirmed', '$payment_id', '$g_total')";
    
    if($conn->query($sql)){
        header("Location: user_dashboard.php?booking=success");
        exit;
    } else {
        $error = "Booking failed. Please contact support. Error: " . $conn->error;
    }
}

// Fetch package details
$res = $conn->query("SELECT * FROM packages WHERE id = $id");
if($res->num_rows == 0) {
    include 'f_header.php';
    echo "<div style='padding:100px; text-align:center;'><h2>Package not found!</h2><a href='index.php'>Go Back</a></div>";
    include 'f_footer.php';
    exit;
}
$pkg = $res->fetch_assoc();

// Calculate total
// If guests is '1 Adult', multiplier is 1. If '2 Adults...', multiplier is 2. 'Group of 4+', multiplier is 4.
$multiplier = 2; // default
if(strpos($guests, '1 Adult') !== false) $multiplier = 1;
if(strpos($guests, '4+') !== false) $multiplier = 4;
$total_price = $pkg['price'] * $multiplier;
$taxes = $total_price * 0.18; // 18% tax
$grand_total = $total_price + $taxes;

include 'f_header.php';
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: var(--soft-bg); }

    .checkout-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 5%;
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 40px;
    }

    /* Form Section */
    .checkout-form-section {
        background: white;
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }
    .checkout-form-section h2 { font-size: 28px; font-weight: 800; color: var(--text-dark); margin-bottom: 30px; }
    
    .payment-methods {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    .pay-method {
        flex: 1;
        padding: 15px;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        text-align: center;
        font-weight: 700;
        color: var(--text-light);
        cursor: pointer;
        transition: 0.3s;
    }
    .pay-method.active {
        border-color: var(--primary);
        color: var(--primary);
        background: #f0fdfa;
    }
    .pay-method i { font-size: 24px; display: block; margin-bottom: 5px; }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 700; color: var(--text-light); margin-bottom: 8px; font-size: 14px; }
    .form-control {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        font-family: inherit;
        font-size: 15px;
        background: #fafbfc;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: var(--primary); background: white; }
    
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    /* Summary Section */
    .summary-section {
        background: white;
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        position: sticky;
        top: 100px;
    }
    .summary-img { width: 100%; height: 200px; border-radius: 20px; object-fit: cover; margin-bottom: 20px; }
    .summary-title { font-size: 22px; font-weight: 800; color: var(--text-dark); margin-bottom: 10px; }
    .summary-meta { color: var(--text-light); font-size: 14px; margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
    .summary-meta i { width: 20px; color: var(--primary); }
    
    .price-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: var(--text-light); font-weight: 600; }
    .price-row.total { font-size: 22px; font-weight: 900; color: var(--text-dark); border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 10px; }
    
    .pay-btn {
        width: 100%;
        background: var(--primary);
        color: white;
        padding: 20px;
        border: none;
        border-radius: 16px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 20px;
    }
    .pay-btn:hover { background: #01585d; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(1, 105, 111, 0.2); }

    @media (max-width: 900px) {
        .checkout-container { grid-template-columns: 1fr; }
        .summary-section { position: static; order: -1; }
    }
</style>

<div class="checkout-container">
    <div class="checkout-form-section">
        <h2>Payment Details</h2>
        
        <?php if(isset($error)): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="payment-methods">
            <div class="pay-method active" id="method-razorpay"><i class="fas fa-bolt"></i> Online Razorpay</div>
            <div class="pay-method" id="method-card"><i class="far fa-credit-card"></i> Credit/Debit Card</div>
        </div>

        <div id="razorpay-info" style="text-align: center; padding: 30px; background: #f0fdfa; border-radius: 24px; margin-bottom: 30px; border: 1px solid var(--primary);">
            <i class="fas fa-shield-alt" style="font-size: 40px; color: var(--primary); margin-bottom: 15px;"></i>
            <h4 style="margin: 0 0 10px 0; color: var(--text-dark);">Razorpay Secure Payment</h4>
            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">Supports UPI, Google Pay, PhonePe, Netbanking, and all major Wallets.</p>
            <button type="button" class="pay-btn trigger-razorpay" style="margin-top: 0;">Pay ₹<?php echo number_format($grand_total); ?> via Razorpay</button>
        </div>

        <div id="card-info" style="display:none; text-align: center; padding: 30px; background: #f8fafc; border-radius: 24px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            <i class="far fa-credit-card" style="font-size: 40px; color: var(--primary); margin-bottom: 15px;"></i>
            <h4 style="margin: 0 0 10px 0; color: var(--text-dark);">Pay via Credit/Debit Card</h4>
            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">Safe & Secure checkout using your Visa, Mastercard, RuPay, or Amex card.</p>
            <button type="button" class="pay-btn trigger-razorpay" data-method="card" style="margin-top: 0;">Pay ₹<?php echo number_format($grand_total); ?> via Card</button>
        </div>

        <form action="checkout.php" method="POST" id="payment-form">
            <input type="hidden" name="package_id" value="<?php echo $id; ?>">
            <input type="hidden" name="travel_date" value="<?php echo htmlspecialchars($travel_date); ?>">
            <input type="hidden" name="process_payment" value="1">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="guests_count" value="<?php echo htmlspecialchars($guests); ?>">
            
            <div class="form-group" style="margin-top: 20px; text-align: center;">
                <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer; color: var(--text-light); font-size: 13px; font-weight: 600;">
                    <input type="checkbox" required checked> I agree to the terms and conditions.
                </label>
            </div>
        </form>
    </div>

    <div class="summary-section">
        <?php
        $img_src = "uploads/" . trim($pkg['image_url']);
        if (!file_exists($img_src)) $img_src = "admin/uploads/" . trim($pkg['image_url']);
        ?>
        <img src="<?php echo $img_src; ?>" class="summary-img" onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=400';">
        <h3 class="summary-title"><?php echo htmlspecialchars($pkg['title']); ?></h3>
        
        <div class="summary-meta">
            <div><i class="far fa-calendar-alt"></i> Date: <?php echo date('M d, Y', strtotime($travel_date)); ?></div>
            <div><i class="fas fa-users"></i> Guests: <?php echo htmlspecialchars($guests); ?></div>
            <div><i class="fas fa-map-marker-alt"></i> Location: <?php echo htmlspecialchars($pkg['location']); ?></div>
        </div>
        
        <div style="border-top: 1px dashed #e2e8f0; margin: 20px 0;"></div>
        
        <div class="price-row">
            <span>Base Price (x<?php echo $multiplier; ?>)</span>
            <span>₹<?php echo number_format($total_price); ?></span>
        </div>
        <div class="price-row">
            <span>Taxes & Fees (18%)</span>
            <span>₹<?php echo number_format($taxes); ?></span>
        </div>
        
        <div class="price-row total">
            <span>Total</span>
            <span style="color: var(--primary);">₹<?php echo number_format($grand_total); ?></span>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Simple UI toggle for payment methods
    $('.pay-method').click(function(){
        $('.pay-method').removeClass('active');
        $(this).addClass('active');
        
        if($(this).attr('id') == 'method-card') {
            $('#card-info').show();
            $('#razorpay-info').hide();
        } else {
            $('#card-info').hide();
            $('#razorpay-info').show();
        }
    });

    // Razorpay Integration
    var options = {
        "key": "<?php echo $keyId; ?>",
        "amount": "<?php echo $grand_total * 100; ?>", // Amount in paise
        "currency": "INR",
        "name": "Mytrip",
        "description": "Booking for <?php echo $pkg['title']; ?>",
        "image": "https://cdn-icons-png.flaticon.com/512/201/201623.png",
        "handler": function (response){
            // On success, set the payment ID and submit the form
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('payment-form').submit();
        },
        "prefill": {
            "name": "<?php echo $_SESSION['user_name']; ?>",
            "email": "<?php echo $_SESSION['user_email'] ?? ''; ?>",
            "contact": "<?php echo $_SESSION['user_phone']; ?>"
        },
        "theme": {
            "color": "#01696f"
        }
    };
    var rzp1;

    $('.trigger-razorpay').click(function(e){
        var method = $(this).data('method');
        
        // Clone original options to avoid persistent changes
        var currentOptions = JSON.parse(JSON.stringify(options));
        
        if(method === 'card') {
            // Force only Card payment for the Card tab
            currentOptions.config = {
                display: {
                    blocks: {
                        card_block: {
                            name: 'Pay using Card',
                            instruments: [{ method: 'card' }]
                        }
                    },
                    sequence: ['block.card_block'],
                    preferences: { show_default_blocks: false }
                }
            };
        } else {
            // For Online Payment, show everything except Card to make it distinct
            currentOptions.config = {
                display: {
                    blocks: {
                        online_block: {
                            name: 'Digital Payments',
                            instruments: [
                                { method: 'upi' },
                                { method: 'netbanking' },
                                { method: 'wallet' }
                            ]
                        }
                    },
                    sequence: ['block.online_block'],
                    preferences: { show_default_blocks: false }
                }
            };
        }

        // Add handler back (it gets lost in JSON stringify)
        currentOptions.handler = options.handler;

        rzp1 = new Razorpay(currentOptions);
        rzp1.open();
        e.preventDefault();
    });
</script>

<?php include 'f_footer.php'; ?>
