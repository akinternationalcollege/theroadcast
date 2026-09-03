<?php
require_once '../backend/config.php';
requireLogin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM enquiries WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: enquiries.php?msg=deleted");
    exit;
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $enq_id = $_POST['enq_id'];
    $stmt = $conn->prepare("UPDATE enquiries SET status = ? WHERE id = ?");
    $stmt->execute([$status, $enq_id]);
    header("Location: enquiries.php?action=view&id=$enq_id&msg=updated");
    exit;
}

// Fetch List
if ($action == 'list') {
    $enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC")->fetchAll();
}

// Fetch Single
if ($action == 'view' && $id) {
    $stmt = $conn->prepare("SELECT * FROM enquiries WHERE id = ?");
    $stmt->execute([$id]);
    $enquiry = $stmt->fetch();

    if (!$enquiry) {
        header("Location: enquiries.php");
        exit;
    }

    // Mark as Read if it's New
    if ($enquiry['status'] == 'New') {
        $conn->prepare("UPDATE enquiries SET status = 'Read' WHERE id = ?")->execute([$id]);
        $enquiry['status'] = 'Read';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Enquiries - THE ROADCAST Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .enquiry-details { background: var(--card-bg); padding: 30px; border-radius: 8px; border: 1px solid var(--border-color); }
        .detail-group { margin-bottom: 20px; }
        .detail-group label { color: var(--text-muted); font-size: 13px; text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 5px; }
        .detail-group p { font-size: 16px; color: #fff; }
        .message-box { background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); margin-top: 20px; }
        .btn-outline { border: 1px solid var(--border-color); padding: 8px 15px; border-radius: 4px; display: inline-block; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        form select { padding: 10px; background: var(--main-bg); color: #fff; border: 1px solid var(--border-color); border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; // We will create this next for reusability ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Enquiries</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-success" style="background: rgba(40,167,69,0.1); color: #28a745; padding: 15px; border: 1px solid #28a745; margin-bottom: 20px; border-radius: 4px;">Enquiry deleted successfully.</div>
            <?php endif; ?>
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                <div class="alert alert-success" style="background: rgba(40,167,69,0.1); color: #28a745; padding: 15px; border: 1px solid #28a745; margin-bottom: 20px; border-radius: 4px;">Status updated successfully.</div>
            <?php endif; ?>

            <?php if($action == 'list'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>All Enquiries</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($enquiries as $enq): ?>
                                <tr>
                                    <td><?php echo date('M d, Y H:i', strtotime($enq['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($enq['name']); ?></td>
                                    <td><?php echo htmlspecialchars($enq['email']); ?></td>
                                    <td><?php echo htmlspecialchars($enq['service_required'] ?: '-'); ?></td>
                                    <td><span class="status-badge <?php echo strtolower($enq['status']); ?>"><?php echo $enq['status']; ?></span></td>
                                    <td>
                                        <a href="enquiries.php?action=view&id=<?php echo $enq['id']; ?>" class="action-btn view"><i class="fas fa-eye"></i></a>
                                        <a href="enquiries.php?action=delete&id=<?php echo $enq['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this enquiry?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($action == 'view'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>Enquiry Details</h3>
                        <a href="enquiries.php" class="btn-outline"><i class="fas fa-arrow-left"></i> Back to List</a>
                    </div>
                    <div class="enquiry-details">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="detail-group">
                                <label>Name</label>
                                <p><?php echo htmlspecialchars($enquiry['name']); ?></p>
                            </div>
                            <div class="detail-group">
                                <label>Date Received</label>
                                <p><?php echo date('M d, Y H:i A', strtotime($enquiry['created_at'])); ?></p>
                            </div>
                            <div class="detail-group">
                                <label>Email</label>
                                <p><a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>" style="color: var(--accent);"><?php echo htmlspecialchars($enquiry['email']); ?></a></p>
                            </div>
                            <div class="detail-group">
                                <label>Phone</label>
                                <p><a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>" style="color: var(--accent);"><?php echo htmlspecialchars($enquiry['phone']); ?></a></p>
                            </div>
                            <div class="detail-group">
                                <label>Service Required</label>
                                <p><?php echo htmlspecialchars($enquiry['service_required'] ?: 'Not specified'); ?></p>
                            </div>
                            <div class="detail-group">
                                <label>Event Date & Location</label>
                                <p>
                                    <?php echo $enquiry['event_date'] ? date('M d, Y', strtotime($enquiry['event_date'])) : 'N/A'; ?>
                                    <?php echo $enquiry['location'] ? ' | ' . htmlspecialchars($enquiry['location']) : ''; ?>
                                </p>
                            </div>
                        </div>

                        <div class="detail-group message-box">
                            <label>Message / Notes</label>
                            <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($enquiry['message'] ?: 'No message provided.'); ?></p>
                        </div>

                        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 30px 0;">

                        <form action="enquiries.php" method="POST" style="display: flex; align-items: center; gap: 15px;">
                            <input type="hidden" name="enq_id" value="<?php echo $enquiry['id']; ?>">
                            <label style="color: var(--text-muted); font-weight: 600;">Update Status:</label>
                            <select name="status">
                                <option value="Read" <?php echo $enquiry['status'] == 'Read' ? 'selected' : ''; ?>>Read</option>
                                <option value="Replied" <?php echo $enquiry['status'] == 'Replied' ? 'selected' : ''; ?>>Replied</option>
                                <option value="Closed" <?php echo $enquiry['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-small">Update</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>

</body>
</html>