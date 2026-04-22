<?php 
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$u_id = $_SESSION['user_id'];

if(isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    $conn->query("UPDATE users SET full_name='$name', phone='$phone' WHERE id=$u_id");
    $_SESSION['user_name'] = $name; 
    header("Location: profile.php?msg=Profile+Updated!");
    exit;
}

if(isset($_POST['change_password'])){
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $user_data = $conn->query("SELECT password FROM users WHERE id = $u_id")->fetch_assoc();

    if(!password_verify($current_pass, $user_data['password'])){
        header("Location: profile.php?error=Current+password+is+incorrect");
        exit;
    }

    if($new_pass !== $confirm_pass){
        header("Location: profile.php?error=New+passwords+do+not+match");
        exit;
    }

    $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
    $conn->query("UPDATE users SET password='$hashed_pass' WHERE id=$u_id");
    header("Location: profile.php?msg=Password+Updated+Successfully!");
    exit;
}

include 'f_header.php'; 

$user = $conn->query("SELECT * FROM users WHERE id = $u_id")->fetch_assoc();
?>

<?php if(isset($_GET['error'])): ?>
    <div style="max-width: 600px; margin: 20px auto; background: #fee2e2; color: #991b1b; padding: 15px 25px; border-radius: 16px; font-weight: 800; border: 1.5px solid #fecaca; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_GET['msg'])): ?>
    <script>alert('<?php echo htmlspecialchars($_GET['msg']); ?>');</script>
<?php endif; ?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: white; }
    
    .profile-hero {
        padding: 80px 5% 120px;
        text-align: center;
        background: linear-gradient(rgba(248, 250, 252, 0.9), rgba(248, 250, 252, 0.9)), url('https://images.unsplash.com/photo-1473116763249-2faaef81ccda?q=80&w=1200');
        background-size: cover;
        background-position: center;
    }
    .profile-hero h1 {
        font-size: 48px;
        font-weight: 900;
        color: var(--text-dark);
        letter-spacing: -2px;
        margin: 0;
    }
    .profile-hero p {
        color: var(--text-light);
        font-weight: 700;
        font-size: 17px;
        margin-top: 10px;
    }

    .profile-container {
        display: flex;
        justify-content: center;
        margin-top: -60px;
        padding: 0 5% 100px;
    }
    
    .profile-card {
        background: white;
        padding: 60px;
        border-radius: 40px;
        box-shadow: 0 40px 80px rgba(0,0,0,0.06);
        border: 1.5px solid #f1f5f9;
        width: 100%;
        max-width: 600px;
        position: relative;
    }
    
    .avatar-wrapper {
        width: 100px;
        height: 100px;
        background: var(--primary);
        border-radius: 35px;
        margin: -110px auto 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 40px rgba(1, 105, 111, 0.2);
        border: 8px solid white;
    }
    .avatar-wrapper i { font-size: 40px; color: white; }

    .form-grid { display: grid; grid-template-columns: 1fr; gap: 25px; }
    
    .field-label { display: block; font-weight: 800; color: var(--text-dark); margin-bottom: 12px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .field-input {
        width: 100%;
        padding: 18px 22px;
        background: #f8fafc;
        border: 2px solid #f8fafc;
        border-radius: 20px;
        font-family: inherit;
        font-weight: 700;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .field-input:focus { outline: none; border-color: var(--primary); background: white; }
    .field-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
    
    .save-changes-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white;
        padding: 22px;
        border: none;
        border-radius: 24px;
        font-size: 17px;
        font-weight: 900;
        cursor: pointer;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        margin-top: 20px;
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.25);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .save-changes-btn:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(1, 105, 111, 0.35); }

    .msg-success {
        background: #eef7f2;
        color: #1e5c37;
        padding: 18px 25px;
        border-radius: 20px;
        margin-bottom: 40px;
        font-weight: 800;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1.5px solid #d4ede0;
    }
</style>

<div class="profile-hero">
    <h1>Account Settings</h1>
    <p>Manage your personal information and travel preferences.</p>
</div>

<div class="profile-container">
    <div class="profile-card">
        <div class="avatar-wrapper">
            <i class="fas fa-user"></i>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="msg-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-grid">
                <div class="field-group">
                    <label class="field-label">Full Name</label>
                    <input type="text" name="full_name" class="field-input" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Email Address <span style="font-size:10px; opacity:0.5;">(Private)</span></label>
                    <input type="text" class="field-input" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                
                <div class="field-group">
                    <label class="field-label">Phone Number</label>
                    <input type="text" name="phone" class="field-input" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="e.g. +91 98765 43210">
                </div>
            </div>
            
            <button type="submit" name="update_profile" class="save-changes-btn" style="margin-bottom: 40px;">Update Profile</button>
        </form>

        <div style="margin-top: 40px; padding-top: 50px; border-top: 1.5px solid #f1f5f9;">
            <h3 style="font-size: 22px; font-weight: 900; color: var(--text-dark); margin-bottom: 30px; letter-spacing: -0.5px;">
                <i class="fas fa-shield-alt" style="color: var(--primary); margin-right: 10px;"></i> Security & Password
            </h3>
            
            <form method="POST">
                <div class="form-grid">
                    <div class="field-group">
                        <label class="field-label">Current Password</label>
                        <input type="password" name="current_password" class="field-input" placeholder="••••••••" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field-group">
                            <label class="field-label">New Password</label>
                            <input type="password" name="new_password" class="field-input" placeholder="New Password" required>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="field-input" placeholder="Confirm New Password" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="change_password" class="save-changes-btn" style="background: #0f172a;">Change Password</button>
            </form>
        </div>
        
        <div style="margin-top: 40px; padding-top: 30px; border-top: 1.5px solid #f1f5f9; text-align: center;">
            <p style="font-size: 13px; color: var(--text-light); font-weight: 700;">Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?></p>
        </div>
    </div>
</div>

<?php include 'f_footer.php'; ?>