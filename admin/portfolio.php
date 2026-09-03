<?php
require_once '../backend/config.php';
requireLogin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = $_GET['msg'] ?? '';

// Handle Delete
if ($action == 'delete' && $id) {
    // In a real app, also delete the image file from server using unlink()
    $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: portfolio.php?msg=deleted");
    exit;
}

// Handle Form Submission (Add/Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_portfolio'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $port_id = $_POST['id'];

    // Simple image handling (Mockup - normally handle $_FILES)
    $image_path = $_POST['image_path_text'];
    if(empty($image_path)) {
        $image_path = "https://via.placeholder.com/600x600/1a1a1d/ffffff?text=".urlencode($title);
    }

    if ($port_id) {
        // Update
        $stmt = $conn->prepare("UPDATE portfolio SET title=?, category=?, image_path=?, status=? WHERE id=?");
        $stmt->execute([$title, $category, $image_path, $status, $port_id]);
        header("Location: portfolio.php?msg=updated");
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO portfolio (title, category, image_path, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $category, $image_path, $status]);
        header("Location: portfolio.php?msg=added");
    }
    exit;
}

// Fetch List
if ($action == 'list') {
    $items = $conn->query("SELECT * FROM portfolio ORDER BY created_at DESC")->fetchAll();
}

// Fetch Single for Edit
$edit_item = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Portfolio - THE ROADCAST Admin</title>
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
        .img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Portfolio Management</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($msg == 'deleted'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Item deleted successfully.</div> <?php endif; ?>
            <?php if($msg == 'updated'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Item updated successfully.</div> <?php endif; ?>
            <?php if($msg == 'added'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Item added successfully.</div> <?php endif; ?>

            <?php if($action == 'list'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>All Portfolio Items</h3>
                        <a href="portfolio.php?action=add" class="btn-small"><i class="fas fa-plus"></i> Add New</a>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($items) > 0): ?>
                                    <?php foreach($items as $item): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="img-preview"></td>
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td>
                                            <?php if($item['status']): ?>
                                                <span class="status-badge replied">Visible</span>
                                            <?php else: ?>
                                                <span class="status-badge closed">Hidden</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="portfolio.php?action=edit&id=<?php echo $item['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                            <a href="portfolio.php?action=delete&id=<?php echo $item['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">No portfolio items found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($action == 'add' || $action == 'edit'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3><?php echo $action == 'edit' ? 'Edit Item' : 'Add New Item'; ?></h3>
                        <a href="portfolio.php" class="btn-small" style="background:transparent; border:1px solid var(--border-color);">Back</a>
                    </div>
                    <div class="form-box">
                        <form action="portfolio.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_item ? $edit_item['id'] : ''; ?>">

                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <?php
                                    $cats = ['Podcast', 'Jamming', 'OpenMic', 'Wedding', 'Photography', 'Drone', 'GraphicDesign'];
                                    foreach($cats as $c):
                                        $selected = ($edit_item && $edit_item['category'] == $c) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $c; ?>" <?php echo $selected; ?>><?php echo $c; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Image URL (Mockup - In reality, use File Upload)</label>
                                <input type="text" name="image_path_text" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['image_path']) : ''; ?>" placeholder="Leave blank to auto-generate placeholder">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo ($edit_item && $edit_item['status'] == 1) ? 'selected' : ''; ?>>Visible</option>
                                    <option value="0" <?php echo ($edit_item && $edit_item['status'] == 0) ? 'selected' : ''; ?>>Hidden</option>
                                </select>
                            </div>

                            <button type="submit" name="save_portfolio" class="btn-submit">Save Item</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>