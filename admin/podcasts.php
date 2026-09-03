<?php
require_once '../backend/config.php';
requireLogin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = $_GET['msg'] ?? '';

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM podcasts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: podcasts.php?msg=deleted");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_podcast'])) {
    $title = $_POST['title'];
    $episode_number = $_POST['episode_number'];
    $duration = $_POST['duration'];
    $host_name = $_POST['host_name'];
    $youtube_link = $_POST['youtube_link'];
    $spotify_link = $_POST['spotify_link'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $pod_id = $_POST['id'];

    $image_path = $_POST['image_path_text'];
    if(empty($image_path)) {
        $image_path = "https://via.placeholder.com/600x600/1a1a1d/ffffff?text=".urlencode("Ep $episode_number");
    }

    if ($pod_id) {
        $stmt = $conn->prepare("UPDATE podcasts SET title=?, episode_number=?, duration=?, host_name=?, youtube_link=?, spotify_link=?, description=?, image_path=?, status=? WHERE id=?");
        $stmt->execute([$title, $episode_number, $duration, $host_name, $youtube_link, $spotify_link, $description, $image_path, $status, $pod_id]);
        header("Location: podcasts.php?msg=updated");
    } else {
        $stmt = $conn->prepare("INSERT INTO podcasts (title, episode_number, duration, host_name, youtube_link, spotify_link, description, image_path, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $episode_number, $duration, $host_name, $youtube_link, $spotify_link, $description, $image_path, $status]);
        header("Location: podcasts.php?msg=added");
    }
    exit;
}

// Fetch List
if ($action == 'list') {
    $items = $conn->query("SELECT * FROM podcasts ORDER BY created_at DESC")->fetchAll();
}

// Fetch Single for Edit
$edit_item = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM podcasts WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Podcasts - THE ROADCAST Admin</title>
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
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Podcast Management</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($msg == 'deleted'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Podcast deleted.</div> <?php endif; ?>
            <?php if($msg == 'updated'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Podcast updated.</div> <?php endif; ?>
            <?php if($msg == 'added'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">Podcast added.</div> <?php endif; ?>

            <?php if($action == 'list'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>All Episodes</h3>
                        <a href="podcasts.php?action=add" class="btn-small"><i class="fas fa-plus"></i> Add Episode</a>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Ep.</th>
                                    <th>Title</th>
                                    <th>Host</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($items) > 0): ?>
                                    <?php foreach($items as $item): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="img-preview"></td>
                                        <td><?php echo htmlspecialchars($item['episode_number']); ?></td>
                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['host_name']); ?></td>
                                        <td>
                                            <?php if($item['status']): ?>
                                                <span class="status-badge replied">Published</span>
                                            <?php else: ?>
                                                <span class="status-badge closed">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="podcasts.php?action=edit&id=<?php echo $item['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                            <a href="podcasts.php?action=delete&id=<?php echo $item['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No podcasts found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($action == 'add' || $action == 'edit'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3><?php echo $action == 'edit' ? 'Edit Episode' : 'Add New Episode'; ?></h3>
                        <a href="podcasts.php" class="btn-small" style="background:transparent; border:1px solid var(--border-color);">Back</a>
                    </div>
                    <div class="form-box">
                        <form action="podcasts.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_item ? $edit_item['id'] : ''; ?>">

                            <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>Episode Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Episode #</label>
                                    <input type="text" name="episode_number" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['episode_number']) : ''; ?>">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>Host Name</label>
                                    <input type="text" name="host_name" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['host_name']) : 'Nitin Tanwar'; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Duration (e.g. 45 mins)</label>
                                    <input type="text" name="duration" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['duration']) : ''; ?>">
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>YouTube Link</label>
                                    <input type="url" name="youtube_link" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['youtube_link']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Spotify Link</label>
                                    <input type="url" name="spotify_link" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['spotify_link']) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Image URL (Mockup)</label>
                                <input type="text" name="image_path_text" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['image_path']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="4" class="form-control"><?php echo $edit_item ? htmlspecialchars($edit_item['description']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo ($edit_item && $edit_item['status'] == 1) ? 'selected' : ''; ?>>Published</option>
                                    <option value="0" <?php echo ($edit_item && $edit_item['status'] == 0) ? 'selected' : ''; ?>>Draft</option>
                                </select>
                            </div>

                            <button type="submit" name="save_podcast" class="btn-submit">Save Episode</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>