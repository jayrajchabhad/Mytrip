<?php
session_start();
include 'db.php';

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$pass', 'Customer')";
    if($conn->query($sql)){
        header("Location: login.php?msg=Account+Created!+Please+Login.");
        exit;
    }
}

include 'f_header.php'; 
?>
<style>
    body { padding-top: 0; }
    nav { position: absolute; width: 90%; background: transparent; backdrop-filter: none; box-shadow: none; border: none; }
    nav span { color: white !important; }
    
    .auth-page {
        display: flex;
        min-height: 100vh;
        background: white;
    }
    
    .auth-visual {
        flex: 1.2;
        position: relative;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .auth-visual img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.7;
    }
    .visual-content {
        position: relative;
        z-index: 2;
        padding: 60px;
        color: white;
    }
    .visual-content h1 { font-size: 56px; font-weight: 900; letter-spacing: -2px; line-height: 1.1; margin-bottom: 20px; }
    .visual-content p { font-size: 20px; opacity: 0.8; font-weight: 500; }

    .auth-form-side {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        background: #f8fafc;
    }
    .auth-form-card {
        width: 100%;
        max-width: 420px;
    }
    .auth-form-card h2 { font-size: 36px; font-weight: 900; color: #0f172a; margin-bottom: 10px; letter-spacing: -1.5px; }
    .auth-form-card p { color: #64748b; margin-bottom: 40px; font-weight: 600; }

    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-weight: 800; color: #0f172a; margin-bottom: 8px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .input-wrapper { position: relative; }
    .input-wrapper i { position: absolute; left: 20px; top: 18px; color: var(--primary); font-size: 18px; }
    
    .form-control {
        width: 100%;
        padding: 18px 20px 18px 55px;
        background: white;
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        transition: 0.3s;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(1, 105, 111, 0.05); }

    .auth-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white;
        padding: 20px;
        border: none;
        border-radius: 20px;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.2);
    }
    .auth-btn:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(1, 105, 111, 0.3); }

    .auth-footer {
        margin-top: 35px;
        text-align: center;
        color: #64748b;
        font-weight: 600;
        font-size: 15px;
    }
    .auth-footer a { color: var(--primary); font-weight: 800; text-decoration: none; }

    @media (max-width: 1000px) {
        .auth-visual { display: none; }
        nav span { color: #0f172a !important; }
        nav { background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    }
</style>

<div class="auth-page">
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1200" alt="Travel Background">
        <div class="visual-content">
            <h1>Start your story.</h1>
            <p>Unlock exclusive travel deals and personalized itineraries just for you.</p>
        </div>
    </div>
    
    <div class="auth-form-side">
        <div class="auth-form-card">
            <h2>Join MyTrip</h2>
            <p>Create your free account today.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <i class="far fa-user"></i>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="far fa-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone-alt"></i>
                        <input type="text" name="phone" class="form-control" placeholder="+1 234 567 8900" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" name="register" class="auth-btn">Create Account</button>
            </form>
            
            <div class="auth-footer">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>
</div>

<?php include 'f_footer.php'; ?>