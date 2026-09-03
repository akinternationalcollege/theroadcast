<?php
// admin/sidebar.php (Extracting sidebar for reuse)
$current_page = basename($_SERVER['PHP_SELF']);

// Only run query if $conn exists (which it should since config is included before this)
if(isset($conn)) {
    $new_enquiries_count = $conn->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn();
} else {
    $new_enquiries_count = 0;
}
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>ROADCAST</h2>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="enquiries.php" class="<?php echo $current_page == 'enquiries.php' ? 'active' : ''; ?>"><i class="fas fa-envelope"></i> Enquiries
            <?php if($new_enquiries_count > 0): ?>
                <span class="badge"><?php echo $new_enquiries_count; ?></span>
            <?php endif; ?>
        </a></li>
        <li><a href="portfolio.php" class="<?php echo $current_page == 'portfolio.php' ? 'active' : ''; ?>"><i class="fas fa-images"></i> Portfolio</a></li>
        <li><a href="events.php" class="<?php echo $current_page == 'events.php' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Events</a></li>
        <li><a href="podcasts.php" class="<?php echo $current_page == 'podcasts.php' ? 'active' : ''; ?>"><i class="fas fa-microphone"></i> Podcasts</a></li>
        <li><a href="news.php" class="<?php echo $current_page == 'news.php' ? 'active' : ''; ?>"><i class="fas fa-newspaper"></i> News</a></li>
        <li class="logout"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>