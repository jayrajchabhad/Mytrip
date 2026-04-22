<?php 
include 'header.php'; 

// --- 1. USER REGISTRATION LOGIC ---
if (isset($_POST['add_user'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if email already exists
    $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
    
    if ($check_email->num_rows > 0) {
        echo "<script>alert('Error: Email already registered!');</script>";
    } else {
        $sql = "INSERT INTO users (full_name, email, phone, password, role) 
                VALUES ('$full_name', '$email', '$phone', '$password', '$role')";

        if ($conn->query($sql)) {
            echo "<script>alert('User Created Successfully!'); window.location='users.php';</script>";
        } else {
            echo "<script>alert('Error creating user. Please try again.');</script>";
        }
    }
}
?>

<style>
    .form-container {
        max-width: 800px;
        margin: 10px auto;
        background: var(--surface);
        padding: 40px;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .form-header {
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 26px;
        font-weight: 800;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.05em;
    }

    .form-control {
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: var(--surface-2);
        color: var(--text);
        font-size: 15px;
        transition: var(--transition);
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
    }

    .full-width {
        grid-column: span 2;
    }

    .btn-save {
        background: var(--primary);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        margin-top: 25px;
        transition: var(--transition);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .btn-save:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
    }

    .btn-save i {
        font-size: 18px;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .full-width {
            grid-column: span 1;
        }
        .form-container {
            padding: 25px;
        }
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h2><i class="fas fa-user-plus" style="color: var(--primary);"></i> Register New User</h2>
        <p style="color: var(--text-muted); font-size: 14px;">Create a new account for a customer or administrator.</p>
    </div>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group full-width">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="+91 00000 00000" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="role">User Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="Customer">Customer</option>
                    <option value="Admin">Administrator</option>
                </select>
            </div>
        </div>

        <button type="submit" name="add_user" class="btn-save">
            <i class="fas fa-check-circle"></i> Create Account
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>