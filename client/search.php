<?php
  session_start();
  require '../backend/db.php';
  $isLoggedIn = isset($_SESSION["user_id"]);

  if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] == "Admin") {
      header("Location: ../");
      exit();
    } elseif ($_SESSION["role"] == "Doctor") {
      header("Location: ../");
      exit();
    }
  }else{
    header("Location: ../login_signup/login.php");
    exit();
  }


  $stmt = $conn->prepare("SELECT DISTINCT specialty FROM doctors");
  $stmt->execute();
  $specialties = $stmt->fetchAll();
  
  $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'Doctor'");
  $stmt->execute();
  $doctors = $stmt->fetchAll();
  
  $stmt = $conn->prepare("SELECT * FROM clinics");
  $stmt->execute();
  $clinics = $stmt->fetchAll();

  $stmt = $conn->prepare("SELECT * FROM inclinic");
  $stmt->execute();
  $inclinic = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Appointments</title>
  <link rel="stylesheet" href="../style/home.css">
  <link rel="stylesheet" href="../style/search.css">
</head>
<body>
  <!-- Header Section (Same as Homepage) -->
  <header>
    <div class="container">
      <div class="logo">MedCare</div>
      <nav>
        <ul>
          <li><a href="../">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Clinics & Doctors</a></li>
          <li><a href="#">Contact Us</a></li>
          <?php if ($isLoggedIn): ?>
            <!-- Display Logout Button if Logged In -->
            <li><a href="../backend/logout.php" class="btn-login">Logout</a></li>
          <?php else: ?>
            <!-- Display Signup/Login Buttons if Not Logged In -->
            <li><a href="../login_signup/login.php" class="btn-login">Login/Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Search Section -->
  <section class="search">
    <div class="container">
      <h1>Find Your Appointment</h1>
      <form class="search-form" id="search-form" method="GET" action="../backend/search_appointments.php"> 
        <!-- Clinic -->
        <div class="form-group">
          <label for="clinic">Clinic</label>
          <input type="text" id="clinic" name="clinic" placeholder="Enter clinic" oninput="filterDropdown('clinic','3')">
          <div id="dropdown-options 3">
          <?php
            foreach ($clinics as $clinic) {
              echo "<div class='search-dropdown' onclick='changeInputValue(\"clinic\",\"$clinic[name]\")' style='display:none'> $clinic[name]</div>";
            }
          ?>
          </div>
        </div>

        <!-- Doctor -->
        <div class="form-group">
          <label for="doctor">Doctor</label>
          <input type="text" id="doctor" name="doctor" placeholder="Enter doctor" oninput="filterDropdown('doctor','2')">
          <div id="dropdown-options 2">
          <?php
            foreach ($doctors as $doctor) {
              echo "<div class='search-dropdown' onclick='changeInputValue(\"doctor\",\"$doctor[name]\")' style='display:none'> $doctor[name]</div>";
            }
          ?>
          </div>
        </div>

        <!-- Specialty -->
        <div class="form-group">
          <label for="specialty">Specialty</label>
          <input type="text" id="specialty" name="specialty" placeholder="Enter Specialty" oninput="filterDropdown('specialty','1')">
          <div id="dropdown-options 1">
          <?php
            foreach ($specialties as $specialty) {
              echo "<div class='search-dropdown' onclick='changeInputValue(\"specialty\",\"$specialty[specialty]\")' style='display:none'> $specialty[specialty]</div>";
            }
          ?>
          </div>
        </div>

        <!-- Location -->
        <div class="form-group">
          <label for="state">State</label>
          <select id="state" name="state">
            <option value="">Select State</option>
            <option value="state1">State 1</option>
            <option value="state2">State 2</option>
          </select>
        </div>
        <div class="form-group">
          <label for="municipality">Municipality</label>
          <select id="municipality" name="municipality">
            <option value="">Select Municipality</option>
            <option value="municipality1">Municipality 1</option>
            <option value="municipality2">Municipality 2</option>
          </select>
        </div>

        <!-- Price -->
        <!-- <div class="form-group">
          <label for="price">Price Range</label>
          <input type="number" id="min-price" placeholder="Min Price" name="minPrice">
          <input type="number" id="max-price" placeholder="Max Price" name="maxPrice">
        </div> -->

        <!-- Search Button -->
        <button type="submit" class="btn-search">Search</button>
      </form>
    </div>
  </section>

  <!-- Results Section -->
  <section class="results">
    <div class="container">
      <h2>Search Results</h2>
      <div class="result-cards">
        <div class="result-card">
          <img src="../assets/image/doctor-pfp.webp" alt="">
          <div class="text">
            <h3 class="name">adem haine</h3>
            <p class="bio">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure temporibus officiis rem minima dolore debitis, culpa tempore eaque quis voluptatum reiciendis ut! Consequuntur voluptates maiores voluptatem tempora laudantium ab veritatis.</p>
          </div>
          </div>
        <div class="result-card">
          <img src="../assets/image/doctor-pfp.webp" alt="">
          <div class="text">
            <h3 class="name">adem haine</h3>
            <p class="bio">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure temporibus officiis rem minima dolore debitis, culpa tempore eaque quis voluptatum reiciendis ut! Consequuntur voluptates maiores voluptatem tempora laudantium ab veritatis.</p>
          </div>
          </div>
        <div class="result-card">
          <img src="../assets/image/doctor-pfp.webp" alt="">
          <div class="text">
            <h3 class="name">adem haine</h3>
            <p class="bio">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure temporibus officiis rem minima dolore debitis, culpa tempore eaque quis voluptatum reiciendis ut! Consequuntur voluptates maiores voluptatem tempora laudantium ab veritatis.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Section (Same as Homepage) -->
  <footer>
    <div class="container">
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Clinics & Doctors</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h3>Contact Us</h3>
        <p>Phone: +123 456 7890</p>
        <p>Email: info@medcare.com</p>
        <p>Address: 123 Health St, City, Country</p>
      </div>
      <div class="footer-social">
        <h3>Follow Us</h3>
        <a href="#"><img src="../assets/image/facebook-icon.png" alt="Facebook"></a>
        <a href="#"><img src="../assets/image/twitter-icon.png" alt="Twitter"></a>
        <a href="#"><img src="../assets/image/instagram-icon.png" alt="Instagram"></a>
      </div>
    </div>
  </footer>

  
   <!-- Scroll to Top Button -->
   <button id="scroll-to-top" title="Go to top">↑</button>
   <script src="../script/scrolltop.js"></script>
   <script src="../script/search.js"></script>
</body>
</html>