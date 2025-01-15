<?php
session_start();
$isLoggedIn = isset($_SESSION["user_id"]);

if (isset($_SESSION["user_id"])) {
  if ($_SESSION["role"] == "Admin") {
    header("Location: clinic/dashboard.php");
    exit();
  } elseif ($_SESSION["role"] == "Doctor") {
    header("Location: doctor/dashboard.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medical Website - Home</title>
  <link rel="stylesheet" href="style/home.css">
</head>
<body>
  <!-- Header Section -->
  <header>
    <div class="container">
      <div class="logo">MedCare</div>
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#contact-us">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
            <!-- Display Logout Button if Logged In -->
            <li><a href="backend/logout.php" class="btn-login">Logout</a></li>
          <?php else: ?>
            <!-- Display Signup/Login Buttons if Not Logged In -->
            <li><a href="login_signup/login.php" class="btn-login">Login/Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Your Health, Our Priority – Book an Appointment Today!</h1>
      <p>Find the best doctors and clinics near you. Reserve your slot in just a few clicks.</p>
      <div class="appointment-widget">
        <button class="btn-book" onclick="window.location.href='client/search.php'">Book Now</button>
      </div>
    </div>
  </section>

  <!-- Key Features Section -->
  <section class="features">
    <div class="container">
      <h2>Why Choose Us?</h2>
      <div class="feature-cards">
        <div class="card">
          <img src="assets/image/clinic.jpg" alt="Clinic Icon">
          <h3>Find a Clinic</h3>
          <p>Search for clinics near you.</p>
        </div>
        <div class="card">
          <img src="assets/image/doctors.webp" alt="Doctor Icon">
          <h3>Choose a Doctor</h3>
          <p>Select from experienced specialists.</p>
        </div>
        <div class="card">
          <img src="assets/image/booking.webp" alt="Booking Icon">
          <h3>Book Instantly</h3>
          <p>Reserve your appointment online.</p>
        </div>
        <div class="card">
          <img src="assets/image/notification.avif" alt="Reminder Icon">
          <h3>Get Reminders</h3>
          <p>Receive SMS/email reminders for your appointment.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Section -->
  <footer>
    <div class="container">
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Clinics & Doctors</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h3 id="contact-us" >Contact Us</h3>
        <p>Phone: +123 456 7890</p>
        <p>Email: info@medcare.com</p>
        <p>Address: 123 Health St, City, Country</p>
      </div>
      <div class="footer-social">
        <h3>Follow Us</h3>
        <a href="#"><img src="assets/image/facebook-icon.png" alt="Facebook"></a>
        <a href="#"><img src="assets/image/twitter-icon.png" alt="Twitter"></a>
        <a href="#"><img src="assets/image/instagram-icon.png" alt="Instagram"></a>
      </div>
    </div>
  </footer>
  
   <!-- Scroll to Top Button -->
   <button id="scroll-to-top" title="Go to top">↑</button>
   <script src="script/scrolltop.js"></script>
</body>
</html>