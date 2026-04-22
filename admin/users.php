<?php 
include 'header.php'; 

// --- DELETE USER LOGIC ---
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    
    $delete_query = "DELETE FROM users WHERE id = '$user_id'";
    
    if ($conn->query($delete_query)) {
        echo "<script>alert('User deleted successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
    }
}

// Fetch the total number of registered users
$total_users_query = $conn->query("SELECT COUNT(*) as count FROM users");
$total_users = $total_users_query->fetch_assoc()['count'];
?>

<style>
    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .user-header h1 {
        margin: 0 0 6px;
        color: var(--text);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .user-header p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .stats-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 12px 24px rgba(1, 105, 111, 0.16);
        white-space: nowrap;
    }

    .user-list-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        padding: 22px;
        overflow-x: auto;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
    }

    .user-table thead tr {
        border-bottom: 1px solid rgba(40, 37, 29, 0.08);
    }

    .user-table thead th {
        text-align: left;
        padding: 14px 18px;
        color: var(--text-muted);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
    }

    .user-table tbody td {
        padding: 18px;
        color: var(--text);
        font-size: 14px;
        border-bottom: 1px solid rgba(40, 37, 29, 0.06);
        vertical-align: middle;
    }

    .user-table tbody tr:last-child td {
        border-bottom: none;
    }

    .user-table tbody tr:hover {
        background: var(--surface-2);
    }

    .user-id {
        color: var(--text-muted);
        font-weight: 700;
    }

    .user-name {
        font-weight: 700;
        color: var(--text);
        display: block;
        margin-bottom: 4px;
    }

    .user-email {
        font-size: 13px;
        color: var(--text-muted);
    }

    .user-phone {
        font-weight: 600;
        color: var(--text);
    }

    .joined-date {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: var(--surface-soft);
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .action-cell {
        text-align: center;
    }

    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--danger);
        background: var(--danger-soft);
        border: 1px solid rgba(161, 53, 68, 0.12);
        padding: 10px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
        transition: all var(--transition);
    }

    .btn-delete:hover {
        background: var(--danger);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(161, 53, 68, 0.16);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px !important;
        color: var(--text-muted);
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .user-header h1 {
            font-size: 26px;
        }

        .user-list-section {
            padding: 16px;
        }

        .user-table {
            min-width: 850px;
        }
    }
</style>

<div class="user-header">
    <div>
        <h1>User Management</h1>
        <p>View and manage all registered customers on the platform.</p>
    </div>

    <div class="stats-badge">
        <i class="fas fa-users"></i>
        Total Users: <?php echo $total_users; ?>
    </div>
</div>

<div class="user-list-section">
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Details</th>
                <th>Phone Number</th>
                <th>Joined Date</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
            if ($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    echo "<tr>
                        <td><span class='user-id'>#".$row['id']."</span></td>
                        <td>
                            <span class='user-name'>".$row['full_name']."</span>
                            <span class='user-email'>".$row['email']."</span>
                        </td>
                        <td><span class='user-phone'>".$row['phone']."</span></td>
                        <td><span class='joined-date'>".date('d M Y', strtotime($row['created_at']))."</span></td>
                        <td class='action-cell'>
                            <a href='users.php?delete_user=".$row['id']."' 
                               class='btn-delete'
                               onclick='return confirm(\"Are you sure you want to delete this user permanently?\")'>
                               <i class='fas fa-trash-alt'></i> Delete User
                            </a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='empty-state'>No registered users found in the system.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>