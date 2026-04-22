<?php 
include 'header.php'; 

// --- UPDATE SETTINGS LOGIC ---
if (isset($_POST['update_settings'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['site_name']);
    
    if (!empty($_FILES['logo']['name'])) {
        $file_name = $_FILES['logo']['name'];
        $temp_name = $_FILES['logo']['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        
        $new_logo_name = "logo_" . time() . "." . $file_ext;
        $upload_path = "uploads/" . $new_logo_name;

        if (move_uploaded_file($temp_name, $upload_path)) {
            $conn->query("UPDATE settings SET site_name='$new_name', logo='$new_logo_name' WHERE id=1");
            echo "<script>alert('Settings and Logo updated!'); window.location='settings.php';</script>";
        } else {
            echo "<script>alert('Failed to upload image.');</script>";
        }
    } else {
        $conn->query("UPDATE settings SET site_name='$new_name' WHERE id=1");
        echo "<script>alert('Site name updated!'); window.location='settings.php';</script>";
    }
}
?>

<style>
    .centering-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 150px);
        width: 100%;
    }

    .settings-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .settings-header h1 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .settings-header p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .settings-wrapper {
        width: 100%;
        max-width: 640px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        padding: 34px;
    }

    .form-group {
        margin-bottom: 24px;
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

    .help-text {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .logo-preview-box {
        display: flex;
        align-items: center;
        gap: 18px;
        background: var(--surface-soft);
        border: 1px dashed rgba(40, 37, 29, 0.14);
        border-radius: 18px;
        padding: 18px;
    }

    .logo-preview-box img {
        width: 64px;
        height: 64px;
        object-fit: contain;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 8px;
        box-shadow: var(--shadow-sm);
    }

    .preview-title {
        margin: 0 0 4px;
        color: var(--text);
        font-size: 14px;
        font-weight: 700;
    }

    .preview-subtitle {
        margin: 0;
        color: var(--text-muted);
        font-size: 12px;
    }

    .form-divider {
        border: 0;
        border-top: 1px solid rgba(40, 37, 29, 0.08);
        margin: 28px 0;
    }

    .btn-update {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #ffffff;
        padding: 14px 20px;
        border-radius: 14px;
        border: none;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all var(--transition);
        box-shadow: 0 12px 24px rgba(1, 105, 111, 0.15);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 28px rgba(1, 105, 111, 0.18);
    }

    @media (max-width: 768px) {
        .centering-container {
            min-height: auto;
            justify-content: flex-start;
        }

        .settings-header h1 {
            font-size: 28px;
        }

        .settings-wrapper {
            padding: 24px;
        }

        .logo-preview-box {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="centering-container">
    <div class="settings-header">
        <h1>Branding Settings</h1>
        <p>Manage your website name and dynamic logo.</p>
    </div>

    <div class="settings-wrapper">
        <form method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Website Title</label>
                <input type="text" name="site_name" class="form-input" value="<?php echo $site_name; ?>" required>
                <span class="help-text">Updates the sidebar and browser tab name.</span>
            </div>

            <div class="form-group">
                <label>Active Logo</label>
                <div class="logo-preview-box">
                    <img src="uploads/<?php echo $site_logo; ?>" alt="Current Logo">
                    <div class="preview-info">
                        <p class="preview-title">Current Brand Image</p>
                        <p class="preview-subtitle">Stored in /uploads/</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Upload New Logo</label>
                <input type="file" name="logo" class="form-input" accept="image/*">
                <span class="help-text">Recommended: Square PNG or SVG.</span>
            </div>

            <hr class="form-divider">

            <button type="submit" name="update_settings" class="btn-update">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>