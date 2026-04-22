<?php 
include 'header.php'; 

// --- UPDATE ADMIN PASSWORD LOGIC ---
if (isset($_POST['update_password'])) {
    $new_user = mysqli_real_escape_string($conn, $_POST['admin_user']);
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $update_query = "UPDATE admin SET username='$new_user', password='$new_pass' WHERE id=1";
        
        if ($conn->query($update_query)) {
            echo "<script>alert('Credentials updated successfully!'); window.location='change_password.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}
?>

<style>
    .center-box {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 72vh;
        width: 100%;
    }

    .security-card {
        width: 100%;
        max-width: 560px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        padding: 36px;
    }

    .card-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .security-icon {
        width: 74px;
        height: 74px;
        margin: 0 auto 16px;
        border-radius: 22px;
        background: var(--success-soft);
        color: var(--success);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 0 10px 24px rgba(67, 122, 34, 0.12);
    }

    .card-header h2 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .card-header p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-size: 12px;
        font-weight: 800;
        color: var(--text);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px;
        border-radius: 14px;
        border: 1px solid var(--border);
        background: var(--surface-2);
        color: var(--text);
        font-size: 14px;
        transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
    }

    .form-input:focus {
        outline: none;
        border-color: rgba(1, 105, 111, 0.35);
        box-shadow: 0 0 0 4px rgba(1, 105, 111, 0.10);
        background: #ffffff;
    }

    .input-note {
        margin-top: 8px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .btn-lock {
        width: 100%;
        margin-top: 10px;
        padding: 14px 18px;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: all var(--transition);
        box-shadow: 0 12px 24px rgba(1, 105, 111, 0.15);
    }

    .btn-lock:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 28px rgba(1, 105, 111, 0.18);
    }

    @media (max-width: 768px) {
        .center-box {
            min-height: auto;
            align-items: flex-start;
        }

        .security-card {
            padding: 24px;
        }

        .card-header h2 {
            font-size: 26px;
        }
    }
</style>

<div class="center-box">
    <div class="security-card">
        <div class="card-header">
            <div class="security-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2>Admin Security</h2>
            <p>Update your login credentials below.</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label>New Username</label>
                <input type="text" name="admin_user" class="form-input" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_pass" class="form-input" placeholder="••••••••" required>
                <div class="input-note">Use a strong password for better account protection.</div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_pass" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" name="update_password" class="btn-lock">
                <i class="fas fa-key"></i> Update Login Info
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>