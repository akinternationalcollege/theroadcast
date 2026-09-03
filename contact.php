<?php
// Form Submission Logic (Simple Mockup for now, to be connected to DB)
$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $service = htmlspecialchars($_POST['service'] ?? '');
    $event_date = htmlspecialchars($_POST['event_date'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Basic validation
    if(empty($name) || empty($email) || empty($phone)) {
        $error_msg = "Please fill in all required fields.";
    } else {
        // Here you would insert into the database
        // e.g. mysqli_query($conn, "INSERT INTO enquiries (name, email...) VALUES (...)");

        $success_msg = "Thank you, $name! Your enquiry for $service has been received. We will contact you shortly.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<section class="page-header" style="background: url('assets/images/contact-bg.jpg') center/cover no-repeat;">
    <div class="header-overlay"></div>
    <div class="container relative z-10 text-center">
        <h1 class="page-title">Get in <span class="text-accent">Touch</span></h1>
        <p class="page-subtitle">Book our services, events, or just say hello</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">

        <div class="contact-wrapper" style="display: grid; grid-template-columns: 1fr 2fr; gap: 50px;">

            <!-- Contact Info -->
            <div class="contact-info">
                <div class="section-header" style="text-align: left;">
                    <h2>Contact <span class="text-accent">Info</span></h2>
                    <p>Reach out to us directly using the information below.</p>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email Us</h4>
                        <p><a href="mailto:theroadcastt@gmail.com">theroadcastt@gmail.com</a></p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Location</h4>
                        <p>Sikar, Rajasthan, India<br>(Serving Fatehpur, Churu, Jhunjhunu, Salasar, Nawalgarh)</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-share-alt"></i>
                    <div>
                        <h4>Follow Us</h4>
                        <div class="social-links" style="margin-top: 10px;">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-container">
                <div class="form-box">
                    <h3 style="font-family: var(--font-heading); font-size: 1.8rem; margin-bottom: 25px;">Send an <span class="text-accent">Enquiry</span></h3>

                    <?php if($success_msg): ?>
                        <div class="alert alert-success"><?php echo $success_msg; ?></div>
                    <?php endif; ?>

                    <?php if($error_msg): ?>
                        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST" class="contact-form">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone">Mobile Number *</label>
                                <input type="tel" id="phone" name="phone" required class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="service">Service Required</label>
                                <select id="service" name="service" class="form-control">
                                    <option value="">Select a Service</option>
                                    <option value="Podcast">Podcast Booking</option>
                                    <option value="Jamming">Jamming Session / Open Mic</option>
                                    <option value="Photography">Photography & Videography</option>
                                    <option value="Promotion">Paid Promotion</option>
                                    <option value="LiveLocation">Live Location Shoot</option>
                                    <option value="Other">Other Query</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date">Event Date (Optional)</label>
                                <input type="date" id="event_date" name="event_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="location">Event Location (Optional)</label>
                                <input type="text" id="location" name="location" class="form-control">
                            </div>
                        </div>

                        <div class="form-group" style="width: 100%;">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" rows="5" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; margin-top: 20px;">Submit Enquiry</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>