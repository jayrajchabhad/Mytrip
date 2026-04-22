<?php 
session_start();
include 'db.php';

if(isset($_POST['submit_forgot'])){
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $res = $conn->query("SELECT * FROM users WHERE phone='$phone'");
    
    if($res->num_rows > 0){
        $otp = rand(1000, 9999);
        $_SESSION['otp_code'] = $otp;
        $_SESSION['reset_phone'] = $phone;
        
        // Demo Mode: Direct redirect without sending real SMS
        header("Location: verify_otp.php?msg=demo");
        exit;
    } else {
        header("Location: forgot_password.php?error=Phone+number+not+registered");
        exit;
    }
}

include 'f_header.php';
?>

<style>
    body { padding-top: 0; }
    nav { position: absolute; width: 90%; background: transparent; backdrop-filter: none; box-shadow: none; border: none; }
    
    .auth-page {
        display: flex;
        min-height: 100vh;
        background: white;
    }
    
    .auth-visual {
        flex: 1.2;
        background: linear-gradient(rgba(1, 105, 111, 0.4), rgba(1, 105, 111, 0.4)), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        color: white;
    }
    
    .visual-content { max-width: 500px; text-align: center; }
    .visual-content h1 { font-size: 56px; font-weight: 900; margin-bottom: 20px; letter-spacing: -2px; line-height: 1.1; }
    .visual-content p { font-size: 18px; opacity: 0.9; font-weight: 600; }

    .auth-form-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        background: #f8fafc;
    }

    .auth-card {
        width: 100%;
        max-width: 450px;
        background: white;
        padding: 50px;
        border-radius: 40px;
        box-shadow: 0 40px 80px rgba(0,0,0,0.06);
        border: 1.5px solid #f1f5f9;
    }

    .auth-card h2 { font-size: 32px; font-weight: 900; color: #0f172a; margin-bottom: 10px; letter-spacing: -1.5px; }
    .auth-card p { color: #64748b; font-weight: 600; margin-bottom: 40px; font-size: 15px; }

    .form-group { margin-bottom: 25px; }
    .form-label { display: block; font-weight: 800; color: #0f172a; margin-bottom: 12px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .input-wrapper { position: relative; }
    .input-wrapper i { position: absolute; left: 20px; top: 18px; color: #01696f; font-size: 18px; opacity: 0.6; }
    
    .form-control {
        width: 100%;
        padding: 18px 20px 18px 55px;
        background: #f8fafc;
        border: 2px solid #f8fafc;
        border-radius: 20px;
        font-family: inherit;
        font-weight: 700;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: #01696f; background: white; }

    .auth-btn {
        width: 100%;
        background: linear-gradient(135deg, #01696f 0%, #014f54 100%);
        color: white;
        padding: 20px;
        border: none;
        border-radius: 22px;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        margin-top: 10px;
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.25);
    }
    .auth-btn:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(1, 105, 111, 0.35); }

    .error-banner {
        background: #fee2e2;
        color: #991b1b;
        padding: 15px;
        border-radius: 16px;
        margin-bottom: 30px;
        font-weight: 800;
        font-size: 13px;
        text-align: center;
        border: 1.5px solid #fecaca;
    }

    @media (max-width: 1000px) {
        .auth-visual { display: none; }
        nav span { color: #0f172a !important; }
    }
</style>

<div class="auth-page">
    <div class="auth-visual">
        <div class="visual-content">
            <h1>Recover Your Account.</h1>
            <p>Don't worry, even the best travelers lose their way sometimes. We'll help you get back on track.</p>
        </div>
    </div>

    <div class="auth-form-container">
        <div class="auth-card">
            <h2>Forgot Password?</h2>
            <p>Enter your registered mobile number to receive a 4-digit verification code.</p>

            <?php if(isset($_GET['error'])): ?>
                <div class="error-banner">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone-alt"></i>
                        <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                    </div>
                </div>

                <button type="submit" name="submit_forgot" class="auth-btn">Send OTP</button>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="login.php" style="color: #01696f; font-weight: 800; text-decoration: none; font-size: 14px;">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'f_footer.php'; ?>
