<?php
require_once '../backend/config.php';
requireLogin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = $_GET['msg'] ?? '';

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: events.php?msg=deleted");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_event'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $event_id = $_POST['id'];

    if ($event_id) {
        $stmt = $conn->prepare("UPDATE events SET title=?, type=?, event_date=?, location=?, description=?, status=? WHERE id=?");
        $stmt->execute([$title, $type, $event_date, $location, $description, $status, $event_id]);
        header("Location: events.php?msg=updated");
    } else {
        $stmt = $conn->prepare("INSERT INTO events (title, type, event_date, location, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $type, $event_date, $location, $description, $status]);
        header("Location: events.php?msg=added");
    }
    exit;
}

// Fetch List
if ($action == 'list') {
    $items = $conn->query("SELECT * FROM events ORDER BY event_date DESC")->fetchAll();
}

// Fetch Single for Edit
$edit_item = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events - THE ROADCAST Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .form-box { background: var(--card-bg); padding: 30px; border-radius: 8px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px; }
        .form-control { width: 100%; padding: 12px; background: rgba(15,15,17,0.8); border: 1px solid var(--border-color); color: #fff; border-radius: 4px; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: var(--accent); }
        .btn-submit { background: var(--accent); color: #fff; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
        input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Event Management</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($msg == 'deleted'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Event deleted.</div> <?php endif; ?>
            <?php if($msg == 'updated'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Event updated.</div> <?php endif; ?>
            <?php if($msg == 'added'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Event added.</div> <?php endif; ?>

            <?php if($action == 'list'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>All Events</h3>
                        <a href="events.php?action=add" class="btn-small"><i class="fas fa-plus"></i> Add Event</a>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($items) > 0): ?>
                                    <?php foreach($items as $item): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y H:i', strtotime($item['event_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['type']); ?></td>
                                        <td><?php echo htmlspecialchars($item['location']); ?></td>
                                        <td>
                                            <?php
                                            $c = 'new';
                                            if($item['status'] == 'Ongoing') $c = 'replied';
                                            if($item['status'] == 'Completed') $c = 'closed';
                                            if($item['status'] == 'Cancelled') $c = 'delete';
                                            ?>
                                            <span class="status-badge <?php echo $c; ?>"><?php echo $item['status']; ?></span>
                                        </td>
                                        <td>
                                            <a href="events.php?action=edit&id=<?php echo $item['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                            <a href="events.php?action=delete&id=<?php echo $item['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No events found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($action == 'add' || $action == 'edit'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3><?php echo $action == 'edit' ? 'Edit Event' : 'Add New Event'; ?></h3>
                        <a href="events.php" class="btn-small" style="background:transparent; border:1px solid var(--border-color);">Back</a>
                    </div>
                    <div class="form-box">
                        <form action="events.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_item ? $edit_item['id'] : ''; ?>">

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>Event Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="Jamming" <?php echo ($edit_item && $edit_item['type'] == 'Jamming') ? 'selected' : ''; ?>>Jamming Session</option>
                                        <option value="OpenMic" <?php echo ($edit_item && $edit_item['type'] == 'OpenMic') ? 'selected' : ''; ?>>Open Mic</option>
                                        <option value="Podcast" <?php echo ($edit_item && $edit_item['type'] == 'Podcast') ? 'selected' : ''; ?>>Live Podcast</option>
                                        <option value="Other" <?php echo ($edit_item && $edit_item['type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>Date & Time</label>
                                    <input type="datetime-local" name="event_date" class="form-control" required value="<?php echo $edit_item ? date('Y-m-d\TH:i', strtotime($edit_item['event_date'])) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="location" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['location']) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control"><?php echo $edit_item ? htmlspecialchars($edit_item['description']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="Upcoming" <?php echo ($edit_item && $edit_item['status'] == 'Upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                                    <option value="Ongoing" <?php echo ($edit_item && $edit_item['status'] == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                    <option value="Completed" <?php echo ($edit_item && $edit_item['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="Cancelled" <?php echo ($edit_item && $edit_item['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>

                            <button type="submit" name="save_event" class="btn-submit">Save Event</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>