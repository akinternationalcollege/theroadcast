<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background: url('assets/images/events-bg.jpg') center/cover no-repeat;">
    <div class="header-overlay"></div>
    <div class="container relative z-10 text-center">
        <h1 class="page-title">Live <span class="text-accent">Events</span></h1>
        <p class="page-subtitle">Jamming Sessions, Open Mics, and Live Podcasts</p>
    </div>
</section>

<!-- Jamming Session -->
<section id="jamming" class="section-padding bg-alt">
    <div class="container">
        <div class="section-header">
            <h2><span class="text-accent">Jamming</span> Sessions</h2>
            <p>Live music, singer performances, and acoustic vibes.</p>
        </div>
        <div class="events-grid">
            <div class="event-card">
                <div class="event-img">
                    <img src="https://via.placeholder.com/600x400/1a1a1d/ffffff?text=Jamming+Session+1" alt="Jamming Session">
                    <span class="event-badge">Upcoming</span>
                </div>
                <div class="event-info">
                    <h3>Weekend Acoustic Jam</h3>
                    <p class="event-meta"><i class="fas fa-calendar-alt"></i> Oct 15, 2026 | <i class="fas fa-map-marker-alt"></i> Sikar</p>
                    <p>Join us for an evening of soulful music with local talented singers.</p>
                    <a href="contact.php?service=jamming" class="btn btn-outline mt-3">Book Tickets</a>
                </div>
            </div>
            <div class="event-card">
                <div class="event-img">
                    <img src="https://via.placeholder.com/600x400/1a1a1d/ffffff?text=Jamming+Session+2" alt="Jamming Session">
                    <span class="event-badge bg-gray">Past Event</span>
                </div>
                <div class="event-info">
                    <h3>Unplugged Night</h3>
                    <p class="event-meta"><i class="fas fa-calendar-alt"></i> Sep 20, 2026 | <i class="fas fa-map-marker-alt"></i> Fatehpur</p>
                    <p>A beautiful night of unplugged covers and original music.</p>
                    <a href="portfolio.php#jamming" class="btn btn-outline mt-3">View Gallery</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Open Mic -->
<section id="openmic" class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2><span class="text-accent">Open Mic</span> Events</h2>
            <p>Showcase your hidden talents in Stand-up, Poetry, and Singing.</p>
        </div>
        <div class="events-grid">
            <div class="event-card">
                <div class="event-img">
                    <img src="https://via.placeholder.com/600x400/1a1a1d/ffffff?text=Open+Mic+Stage" alt="Open Mic">
                </div>
                <div class="event-info">
                    <h3>The Stage is Yours</h3>
                    <p class="event-meta"><i class="fas fa-calendar-alt"></i> Nov 05, 2026 | <i class="fas fa-map-marker-alt"></i> Churu</p>
                    <p>Open for all artists: Poets, Comedians, Singers, and Storytellers.</p>
                    <a href="contact.php?service=openmic" class="btn btn-primary mt-3">Register to Perform</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="section-padding bg-alt border-top">
    <div class="container">
        <div class="section-header">
            <h2>Event <span class="text-accent">Experience</span></h2>
            <p>Join our premium entertainment events at an affordable price.</p>
        </div>

        <div class="pricing-container">
            <div class="pricing-card">
                <div class="price-header">
                    <h3>Standard Pass</h3>
                    <div class="price"><span>₹</span>400</div>
                </div>
                <div class="price-body">
                    <ul>
                        <li><i class="fas fa-check"></i> Entry to Open Mic/Jamming</li>
                        <li><i class="fas fa-check"></i> Basic Seating</li>
                        <li><i class="fas fa-check"></i> Photography included</li>
                    </ul>
                    <a href="contact.php" class="btn btn-outline">Book Now</a>
                </div>
            </div>

            <div class="pricing-card featured">
                <div class="price-header">
                    <h3>Premium Pass</h3>
                    <div class="price"><span>₹</span>600</div>
                </div>
                <div class="price-body">
                    <ul>
                        <li><i class="fas fa-check"></i> Priority Front Row Seating</li>
                        <li><i class="fas fa-check"></i> Meet & Greet with Artists</li>
                        <li><i class="fas fa-check"></i> Complimentary Beverage</li>
                        <li><i class="fas fa-check"></i> Feature in Event After-movie</li>
                    </ul>
                    <a href="contact.php" class="btn btn-primary">Book Premium</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>