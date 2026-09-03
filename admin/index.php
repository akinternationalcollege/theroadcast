<?php
require_once '../backend/config.php';
requireLogin();

// Fetch counts for dashboard stats
$stats = [
    'enquiries' => $conn->query("SELECT COUNT(*) FROM enquiries")->fetchColumn(),
    'new_enquiries' => $conn->query("SELECT COUNT(*) FROM enquiries WHERE status = 'New'")->fetchColumn(),
    'portfolio' => $conn->query("SELECT COUNT(*) FROM portfolio")->fetchColumn(),
    'events' => $conn->query("SELECT COUNT(*) FROM events")->fetchColumn(),
    'podcasts' => $conn->query("SELECT COUNT(*) FROM podcasts")->fetchColumn()
];

// Fetch 5 most recent enquiries
$recent_enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - THE ROADCAST Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <header class="topbar">
            <h2>Dashboard</h2>
            <div class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #4CAF50;"><i class="fas fa-envelope"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $stats['enquiries']; ?></h3>
                        <p>Total Enquiries</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #2196F3;"><i class="fas fa-images"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $stats['portfolio']; ?></h3>
                        <p>Portfolio Items</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #9C27B0;"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $stats['events']; ?></h3>
                        <p>Events</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #ff3b30;"><i class="fas fa-podcast"></i></div>
                    <div class="stat-details">
                        <h3><?php echo $stats['podcasts']; ?></h3>
                        <p>Podcasts</p>
                    </div>
                </div>
            </div>

            <!-- Recent Enquiries Table -->
            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Enquiries</h3>
                    <a href="enquiries.php" class="btn-small">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($recent_enquiries) > 0): ?>
                                <?php foreach($recent_enquiries as $enq): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($enq['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($enq['name']); ?></td>
                                    <td><?php echo htmlspecialchars($enq['service_required'] ?: 'General'); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo strtolower($enq['status']); ?>">
                                            <?php echo $enq['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="enquiries.php?view=<?php echo $enq['id']; ?>" class="action-btn view"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No recent enquiries.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>