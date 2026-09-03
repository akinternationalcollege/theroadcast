<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background: url('assets/images/portfolio-bg.jpg') center/cover no-repeat;">
    <div class="header-overlay"></div>
    <div class="container relative z-10 text-center">
        <h1 class="page-title">Our <span class="text-accent">Portfolio</span></h1>
        <p class="page-subtitle">A glimpse into our events, shoots, and productions</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <!-- Portfolio Filters -->
        <div class="portfolio-filters text-center">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="podcast">Podcast</button>
            <button class="filter-btn" data-filter="jamming">Jamming</button>
            <button class="filter-btn" data-filter="openmic">Open Mic</button>
            <button class="filter-btn" data-filter="wedding">Wedding</button>
            <button class="filter-btn" data-filter="photography">Photography</button>
        </div>

        <!-- Portfolio Grid (Masonry logic simulated via CSS Grid/Flex for now) -->
        <div class="portfolio-grid">

            <!-- Item 1 -->
            <div class="portfolio-item podcast">
                <img src="https://via.placeholder.com/600x600/1a1a1d/ffffff?text=Podcast+Episode+1" alt="Podcast">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Nitin Tanwar Interview</h4>
                        <p>Podcast</p>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="portfolio-item jamming">
                <img src="https://via.placeholder.com/600x800/1a1a1d/ffffff?text=Acoustic+Night" alt="Jamming">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Acoustic Night</h4>
                        <p>Jamming Session</p>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="portfolio-item openmic">
                <img src="https://via.placeholder.com/800x600/1a1a1d/ffffff?text=Standup+Comedy" alt="Open Mic">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Arjun Soni Live</h4>
                        <p>Open Mic</p>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="portfolio-item wedding">
                <img src="https://via.placeholder.com/600x400/1a1a1d/ffffff?text=Pre-Wedding+Shoot" alt="Wedding">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Desert Story</h4>
                        <p>Pre-Wedding Shoot</p>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="portfolio-item photography">
                <img src="https://via.placeholder.com/400x600/1a1a1d/ffffff?text=Drone+Shot" alt="Drone">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Fatehpur Fort</h4>
                        <p>Drone Photography</p>
                    </div>
                </div>
            </div>

            <!-- Item 6 -->
            <div class="portfolio-item jamming">
                <img src="https://via.placeholder.com/600x600/1a1a1d/ffffff?text=Live+Singer" alt="Jamming">
                <div class="portfolio-overlay">
                    <div class="portfolio-info">
                        <h4>Weekend Vibes</h4>
                        <p>Jamming Session</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>