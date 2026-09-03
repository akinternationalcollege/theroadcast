<?php
require_once '../backend/config.php';
requireLogin();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$msg = $_GET['msg'] ?? '';

// Handle Delete
if ($action == 'delete' && $id) {
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: news.php?msg=deleted");
    exit;
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_news'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $news_id = $_POST['id'];

    $image_path = $_POST['image_path_text'];
    if(empty($image_path)) {
        $image_path = "https://via.placeholder.com/800x500/1a1a1d/ffffff?text=".urlencode($category." News");
    }

    if ($news_id) {
        $stmt = $conn->prepare("UPDATE news SET title=?, category=?, content=?, image_path=?, status=? WHERE id=?");
        $stmt->execute([$title, $category, $content, $image_path, $status, $news_id]);
        header("Location: news.php?msg=updated");
    } else {
        $stmt = $conn->prepare("INSERT INTO news (title, category, content, image_path, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $content, $image_path, $status]);
        header("Location: news.php?msg=added");
    }
    exit;
}

// Fetch List
if ($action == 'list') {
    $items = $conn->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
}

// Fetch Single for Edit
$edit_item = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage News - THE ROADCAST Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .form-box { background: var(--card-bg); padding: 30px; border-radius: 8px; border: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: var(--text-muted); font-size: 14px; }
        .form-control { width: 100%; padding: 12px; background: rgba(15,15,17,0.8); border: 1px solid var(--border-color); color: #fff; border-radius: 4px; box-sizing: border-box; font-family:inherit;}
        .form-control:focus { outline: none; border-color: var(--accent); }
        .btn-submit { background: var(--accent); color: #fff; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
        .img-preview { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <header class="topbar">
            <h2>Local News Coverage</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if($msg == 'deleted'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">News deleted.</div> <?php endif; ?>
            <?php if($msg == 'updated'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">News updated.</div> <?php endif; ?>
            <?php if($msg == 'added'): ?> <div class="alert alert-success" style="padding:15px; background:rgba(40,167,69,0.1); color:#28a745; border:1px solid #28a745; margin-bottom:20px;">News added.</div> <?php endif; ?>

            <?php if($action == 'list'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3>All News Articles</h3>
                        <a href="news.php?action=add" class="btn-small"><i class="fas fa-plus"></i> Add News</a>
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($items) > 0): ?>
                                    <?php foreach($items as $item): ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="img-preview"></td>
                                        <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars(strlen($item['title']) > 40 ? substr($item['title'], 0, 40).'...' : $item['title']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td>
                                            <?php if($item['status']): ?>
                                                <span class="status-badge replied">Published</span>
                                            <?php else: ?>
                                                <span class="status-badge closed">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="news.php?action=edit&id=<?php echo $item['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                                            <a href="news.php?action=delete&id=<?php echo $item['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No news articles found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif($action == 'add' || $action == 'edit'): ?>
                <div class="panel">
                    <div class="panel-header">
                        <h3><?php echo $action == 'edit' ? 'Edit News' : 'Add News'; ?></h3>
                        <a href="news.php" class="btn-small" style="background:transparent; border:1px solid var(--border-color);">Back</a>
                    </div>
                    <div class="form-box">
                        <form action="news.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $edit_item ? $edit_item['id'] : ''; ?>">

                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                                <div class="form-group">
                                    <label>Headline / Title</label>
                                    <input type="text" name="title" class="form-control" required value="<?php echo $edit_item ? htmlspecialchars($edit_item['title']) : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Location (Category)</label>
                                    <select name="category" class="form-control" required>
                                        <?php
                                        $locs = ['Sikar', 'Fatehpur', 'Churu', 'Jhunjhunu', 'Salasar', 'Nawalgarh'];
                                        foreach($locs as $l):
                                            $selected = ($edit_item && $edit_item['category'] == $l) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $l; ?>" <?php echo $selected; ?>><?php echo $l; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Image URL (Mockup)</label>
                                <input type="text" name="image_path_text" class="form-control" value="<?php echo $edit_item ? htmlspecialchars($edit_item['image_path']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label>Article Content</label>
                                <textarea name="content" rows="10" class="form-control" required><?php echo $edit_item ? htmlspecialchars($edit_item['content']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo ($edit_item && $edit_item['status'] == 1) ? 'selected' : ''; ?>>Published</option>
                                    <option value="0" <?php echo ($edit_item && $edit_item['status'] == 0) ? 'selected' : ''; ?>>Draft</option>
                                </select>
                            </div>

                            <button type="submit" name="save_news" class="btn-submit">Save Article</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
</html>