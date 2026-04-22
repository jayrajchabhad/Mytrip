<?php 
session_start();
include 'db.php';

if(!isset($_SESSION['otp_code'])){
    header("Location: forgot_password.php");
    exit;
}

if(isset($_POST['submit_otp'])){
    $user_otp = $_POST['otp'];
    if($user_otp == $_SESSION['otp_code']){
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
        exit;
    } else {
        header("Location: verify_otp.php?error=Incorrect+OTP+Code");
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
        background: linear-gradient(rgba(1, 105, 111, 0.4), rgba(1, 105, 111, 0.4)), url('https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=1200');
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

    .otp-input {
        width: 100%;
        padding: 22px;
        background: #f8fafc;
        border: 2px solid #f8fafc;
        border-radius: 20px;
        font-family: inherit;
        font-weight: 900;
        font-size: 32px;
        text-align: center;
        letter-spacing: 15px;
        transition: 0.3s;
        box-sizing: border-box;
        color: var(--primary);
    }
    .otp-input:focus { outline: none; border-color: #01696f; background: white; }

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
        margin-top: 30px;
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

    .demo-alert { background: #e0f2f1; color: #00796b; padding: 15px; border-radius: 15px; font-size: 12px; font-weight: 700; margin-bottom: 30px; text-align: center; border: 1px dashed #00796b; }
</style>

<div class="auth-page">
    <div class="auth-visual">
        <div class="visual-content">
            <h1>Verify Identity.</h1>
            <p>We've sent a unique code to your mobile device. Please enter it to continue.</p>
        </div>
    </div>

    <div class="auth-form-container">
        <div class="auth-card">
            <h2>Enter OTP</h2>
            <p>Verification code sent to <strong style="color:var(--primary)"><?php echo htmlspecialchars($_SESSION['reset_phone']); ?></strong></p>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'demo'): ?>
                <div style="background: #e0f2f1; color: #00796b; padding: 15px; border-radius: 15px; font-size: 12px; font-weight: 700; margin-bottom: 30px; text-align: center; border: 1px dashed #00796b;">
                    <i class="fas fa-mobile-alt"></i> DEMO MODE: Your OTP is <span style="font-size: 18px; color: #01696f;"><?php echo $_SESSION['otp_code']; ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="error-banner">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="otp" class="otp-input" placeholder="0000" maxlength="4" required autofocus>
                <button type="submit" name="submit_otp" class="auth-btn">Verify & Continue</button>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="forgot_password.php" style="color: #64748b; font-weight: 700; text-decoration: none; font-size: 13px;">Resend OTP Code</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'f_footer.php'; ?>
