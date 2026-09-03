<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background: url('assets/images/services-bg.jpg') center/cover no-repeat;">
    <div class="header-overlay"></div>
    <div class="container relative z-10 text-center">
        <h1 class="page-title">Our <span class="text-accent">Services</span></h1>
        <p class="page-subtitle">Photography, Videography, Media, and Event Production</p>
    </div>
</section>

<!-- Photography & Videography Services -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2><span class="text-accent">Camera & Shoot</span> Services</h2>
            <p>Professional photography and videography for all occasions</p>
        </div>

        <div class="services-grid">
            <!-- Camera -->
            <div class="service-box">
                <i class="fas fa-camera-retro service-icon"></i>
                <h3>Professional Camera Shoot</h3>
                <p>High-quality event photography and video shooting tailored to your needs.</p>
            </div>

            <!-- Wedding -->
            <div class="service-box">
                <i class="fas fa-rings-wedding service-icon"></i> <!-- Note: fallback to heart if rings-wedding not in free -->
                <i class="fas fa-heart service-icon"></i>
                <h3>Wedding & Pre-Wedding</h3>
                <p>Cinematic wedding videos, pre-wedding shoots, and beautiful storytelling.</p>
            </div>

            <!-- Drone -->
            <div class="service-box">
                <i class="fas fa-drone service-icon"></i> <!-- Note: fallback to helicopter/plane -->
                <i class="fas fa-helicopter service-icon"></i>
                <h3>Drone Photography</h3>
                <p>Breathtaking aerial shots and drone videography for events.</p>
            </div>

            <!-- Private Shoot -->
            <div class="service-box">
                <i class="fas fa-user-secret service-icon"></i>
                <h3>Private Shoot</h3>
                <p>Personalized photo sessions for special occasions and private events.</p>
            </div>
        </div>
    </div>
</section>

<!-- Event Services & Production -->
<section class="section-padding bg-alt">
    <div class="container">
        <div class="section-header">
            <h2><span class="text-accent">Event Management</span> & Production</h2>
            <p>End-to-end solutions for your events</p>
        </div>

        <div class="services-list-grid">
            <div class="service-list-item">
                <div class="list-icon"><i class="fas fa-magic"></i></div>
                <div class="list-content">
                    <h4>Event Decoration</h4>
                    <p>Creative and theme-based decoration for all types of events.</p>
                </div>
            </div>

            <div class="service-list-item">
                <div class="list-icon"><i class="fas fa-film"></i></div>
                <div class="list-content">
                    <h4>Video Editor</h4>
                    <p>Professional editing for podcasts, reels, and event coverage.</p>
                </div>
            </div>

            <div class="service-list-item">
                <div class="list-icon"><i class="fas fa-paint-brush"></i></div>
                <div class="list-content">
                    <h4>Graphic Designer</h4>
                    <p>Custom posters, social media posts, and promotional creatives.</p>
                </div>
            </div>

            <div class="service-list-item">
                <div class="list-icon"><i class="fas fa-images"></i></div>
                <div class="list-content">
                    <h4>Albums & Frames</h4>
                    <p>Trending album designs and custom photo frames to preserve memories.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5" style="margin-top: 50px;">
            <a href="contact.php" class="btn btn-primary">Book a Service</a>
        </div>
    </div>
</section>

<!-- Live Location Section -->
<section class="section-padding">
    <div class="container">
        <div class="live-location-banner text-center p-5 rounded" style="background: linear-gradient(45deg, rgba(255,59,48,0.1), rgba(15,15,17,1)); border: 1px solid var(--accent-color); padding: 50px; border-radius: 12px;">
            <h2 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 20px;">Live Location <span class="text-accent">Production</span></h2>
            <p style="font-size: 1.1rem; color: #ccc; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
                We bring our production to you! Available for on-location Live Shoots, Event Coverage, Podcasts, and Song recordings.
            </p>
            <div class="live-tags" style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; margin-bottom: 30px;">
                <span style="padding: 8px 15px; background: rgba(255,255,255,0.05); border-radius: 20px; font-size: 0.9rem;">Video Editing</span>
                <span style="padding: 8px 15px; background: rgba(255,255,255,0.05); border-radius: 20px; font-size: 0.9rem;">Reels</span>
                <span style="padding: 8px 15px; background: rgba(255,255,255,0.05); border-radius: 20px; font-size: 0.9rem;">Live Shoot</span>
            </div>
            <a href="contact.php?service=livelocation" class="btn btn-outline">Contact for On-Location Shoot</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>