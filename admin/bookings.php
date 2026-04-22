<?php 
include 'header.php'; 

// --- 1. STATUS UPDATE LOGIC ---
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $conn->query("UPDATE bookings SET status = '$status' WHERE id = $id");
    echo "<script>window.location='bookings.php';</script>";
}

// --- 2. DELETE BOOKING LOGIC ---
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM bookings WHERE id = $id");
    echo "<script>window.location='bookings.php';</script>";
}
?>

<style>
    .admin-header {
        margin-bottom: 28px;
    }

    .admin-header h2 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .admin-header p {
        margin: 0;
        color: var(--text-muted);
        font-size: 14px;
    }

    .table-container {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-sm);
        padding: 22px;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        border-bottom: 1px solid rgba(40, 37, 29, 0.08);
    }

    thead th {
        text-align: left;
        padding: 14px 18px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
    }

    tbody td {
        padding: 18px;
        font-size: 14px;
        color: var(--text);
        border-bottom: 1px solid rgba(40, 37, 29, 0.06);
        vertical-align: middle;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tbody tr:hover {
        background: var(--surface-2);
    }

    .customer-name,
    .trip-title {
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }

    .customer-phone,
    .trip-date {
        font-size: 12px;
        color: var(--text-muted);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border: 1px solid transparent;
    }

    .status-pill::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-pending {
        background: var(--warning-soft);
        color: #9a4d00;
        border-color: rgba(218, 113, 1, 0.12);
    }

    .status-pending::before {
        background: var(--warning);
    }

    .status-confirmed {
        background: var(--success-soft);
        color: var(--success);
        border-color: rgba(67, 122, 34, 0.12);
    }

    .status-confirmed::before {
        background: var(--success);
    }

    .status-cancelled {
        background: var(--danger-soft);
        color: var(--danger);
        border-color: rgba(161, 53, 68, 0.12);
    }

    .status-cancelled::before {
        background: var(--danger);
    }

    .action-group {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all var(--transition);
        box-shadow: 0 10px 20px rgba(1, 105, 111, 0.12);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 28px rgba(1, 105, 111, 0.18);
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .btn-manage {
        background: var(--surface-2);
        border: 1px solid var(--border);
        color: var(--text);
        padding: 10px 14px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 13px;
        transition: all var(--transition);
    }

    .btn-manage:hover {
        background: var(--surface-soft);
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        min-width: 190px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 8px;
        box-shadow: var(--shadow-md);
        z-index: 100;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content a {
        display: block;
        text-decoration: none;
        padding: 11px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        transition: background var(--transition), color var(--transition);
    }

    .dropdown-content a:hover {
        background: var(--surface-soft);
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(40, 37, 29, 0.08);
        margin: 6px 0;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
        font-size: 14px;
    }

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        background: rgba(40, 37, 29, 0.45);
        backdrop-filter: blur(6px);
    }

    .modal-content {
        width: calc(100% - 32px);
        max-width: 520px;
        margin: 7% auto;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: 28px;
        box-shadow: var(--shadow-md);
        padding: 32px;
        position: relative;
    }

    .modal-close {
        position: absolute;
        right: 20px;
        top: 18px;
        font-size: 24px;
        color: var(--text-muted);
        cursor: pointer;
        transition: color var(--transition);
    }

    .modal-close:hover {
        color: var(--text);
    }

    .modal-content h3 {
        margin: 0 0 8px;
        color: var(--primary);
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .modal-content p {
        margin: 0 0 24px;
        color: var(--text-muted);
        font-size: 13px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(40, 37, 29, 0.06);
    }

    .detail-row:last-of-type {
        border-bottom: none;
    }

    .detail-row span {
        color: var(--text-muted);
        font-size: 13px;
    }

    .detail-row b {
        color: var(--text);
        font-size: 14px;
        text-align: right;
    }

    .modal-btn {
        width: 100%;
        margin-top: 22px;
        padding: 14px;
    }

    @media (max-width: 768px) {
        .admin-header h2 {
            font-size: 26px;
        }

        .table-container {
            padding: 16px;
        }

        table {
            min-width: 760px;
        }

        .modal-content {
            margin: 18% auto;
            padding: 24px;
        }
    }
</style>

<div class="admin-header">
    <h2>Customer Bookings</h2>
    <p>Manage reservations and track client engagement across all packages.</p>
</div>

<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <h3>Booking Dossier</h3>
        <p>Detailed breakdown of the customer's selection.</p>

        <div class="detail-row"><span>Customer Name</span> <b id="m-name"></b></div>
        <div class="detail-row"><span>Contact Number</span> <b id="m-phone"></b></div>
        <div class="detail-row"><span>Selected Trip</span> <b id="m-package"></b></div>
        <div class="detail-row"><span>Preferred Date</span> <b id="m-date"></b></div>
        <div class="detail-row"><span>Current Status</span> <b id="m-status"></b></div>
        <div class="detail-row"><span>System Entry</span> <b id="m-created"></b></div>

        <button onclick="closeModal()" class="btn-primary modal-btn">Dismiss View</button>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Customer</th>
                <th>Trip Details</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT b.*, p.title FROM bookings b 
                    LEFT JOIN packages p ON b.package_id = p.id 
                    ORDER BY b.id DESC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $statusClass = 'status-pending';
                    if ($row['status'] == 'Confirmed') $statusClass = 'status-confirmed';
                    if ($row['status'] == 'Cancelled') $statusClass = 'status-cancelled';

                    $tripTitle = $row['title'] ? $row['title'] : "Package Deleted";
                    $jsData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    echo "<tr>
                        <td>
                            <div class='customer-name'>".$row['customer_name']."</div>
                            <div class='customer-phone'>".$row['customer_phone']."</div>
                        </td>
                        <td>
                            <div class='trip-title'>".$tripTitle."</div>
                            <div class='trip-date'>Date: ".$row['booking_date']."</div>
                        </td>
                        <td>
                            <span class='status-pill $statusClass'>".$row['status']."</span>
                        </td>
                        <td style='text-align: right;'>
                            <div class='action-group'>
                                <button onclick='showDetails($jsData)' class='btn-primary'>View</button>

                                <div class='dropdown'>
                                    <button class='btn-manage'>Manage</button>
                                    <div class='dropdown-content'>
                                        <a href='?id=".$row['id']."&status=Confirmed' style='color: var(--success);'>Mark Confirmed</a>
                                        <a href='?id=".$row['id']."&status=Cancelled' style='color: var(--warning);'>Mark Cancelled</a>
                                        <div class='dropdown-divider'></div>
                                        <a href='?delete_id=".$row['id']."' style='color: var(--danger);' onclick='return confirm(\"Delete permanently?\")'>Remove Inquiry</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='empty-state'>No booking records available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function showDetails(data) {
        document.getElementById('m-name').innerText = data.customer_name;
        document.getElementById('m-phone').innerText = data.customer_phone;
        document.getElementById('m-package').innerText = data.title || "Deleted Package";
        document.getElementById('m-date').innerText = data.booking_date;
        document.getElementById('m-status').innerText = data.status;
        document.getElementById('m-created').innerText = data.created_at;
        document.getElementById('bookingModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('bookingModal').style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('bookingModal')) closeModal();
    }
</script>

<?php include 'footer.php'; ?>