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
          <li><a href="#contact-us">Contact Us</a></li>
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
            <option value="Adrar">Adrar</option>
            <option value="Chlef">Chlef</option>
            <option value="Laghouat">Laghouat</option>
            <option value="Oum El Bouaghi">Oum El Bouaghi</option>
            <option value="Batna">Batna</option>
            <option value="Béjaïa">Béjaïa</option>
            <option value="Biskra">Biskra</option>
            <option value="Béchar">Béchar</option>
            <option value="Blida">Blida</option>
            <option value="Bouira">Bouira</option>
            <option value="Tamanrasset">Tamanrasset</option>
            <option value="Tébessa">Tébessa</option>
            <option value="Tlemcen">Tlemcen</option>
            <option value="Tiaret">Tiaret</option>
            <option value="Tizi Ouzou">Tizi Ouzou</option>
            <option value="Algiers">Algiers</option>
            <option value="Djelfa">Djelfa</option>
            <option value="Jijel">Jijel</option>
            <option value="Sétif">Sétif</option>
            <option value="Saïda">Saïda</option>
            <option value="Skikda">Skikda</option>
            <option value="Sidi Bel Abbès">Sidi Bel Abbès</option>
            <option value="Annaba">Annaba</option>
            <option value="Guelma">Guelma</option>
            <option value="Constantine">Constantine</option>
            <option value="Médéa">Médéa</option>
            <option value="Mostaganem">Mostaganem</option>
            <option value="M'Sila">M'Sila</option>
            <option value="Mascara">Mascara</option>
            <option value="Ouargla">Ouargla</option>
            <option value="Oran">Oran</option>
            <option value="El Bayadh">El Bayadh</option>
            <option value="Illizi">Illizi</option>
            <option value="Bordj Bou Arréridj">Bordj Bou Arréridj</option>
            <option value="Boumerdès">Boumerdès</option>
            <option value="El Tarf">El Tarf</option>
            <option value="Tindouf">Tindouf</option>
            <option value="Tissemsilt">Tissemsilt</option>
            <option value="El Oued">El Oued</option>
            <option value="Khenchela">Khenchela</option>
            <option value="Souk Ahras">Souk Ahras</option>
            <option value="Tipaza">Tipaza</option>
            <option value="Mila">Mila</option>
            <option value="Aïn Defla">Aïn Defla</option>
            <option value="Naâma">Naâma</option>
            <option value="Aïn Témouchent">Aïn Témouchent</option>
            <option value="Ghardaïa">Ghardaïa</option>
            <option value="Relizane">Relizane</option>
            <option value="Timimoun">Timimoun</option>
            <option value="Bordj Badji Mokhtar">Bordj Badji Mokhtar</option>
            <option value="Ouled Djellal">Ouled Djellal</option>
            <option value="Béni Abbès">Béni Abbès</option>
            <option value="In Salah">In Salah</option>
            <option value="In Guezzam">In Guezzam</option>
            <option value="Touggourt">Touggourt</option>
            <option value="Djanet">Djanet</option>
            <option value="El M'Ghair">El M'Ghair</option>
            <option value="El Menia">El Menia</option>
          </select>
        </div>

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
      <?php if (isset($_SESSION['search_results']) && !empty($_SESSION['search_results'])): ?>
        <?php foreach ($_SESSION['search_results'] as $result): ?>
          <div class="result-card">
            <img src="../assets/image/doctor-pfp.webp" alt="">
            <div class="text">
              <h3 class="name"><?php echo htmlspecialchars($result['doctor_name']); ?></h3>
              <h4 class="clinic"><?php echo htmlspecialchars($result['clinic_name']); ?></h4>
              <h5 class="exp"><?php echo htmlspecialchars($result['experience']); ?></h5>
              <p class="bio"><?php echo htmlspecialchars($result['bio']); ?></p>
            </div>
            <div class="actions">
              <div class="reviews">
                <p><?php echo htmlspecialchars(round($result['rating'], 1)); ?></p>
                <div class="txt">
                  <div class="stars">
                    <?php for ($i = 0; $i < floor($result['rating']); $i++): ?>
                      <div class="star active"></div>
                    <?php endfor; ?>
                    <?php if ($result['rating'] - floor($result['rating']) >= 0.5): ?>
                      <div class="star half"></div>
                    <?php endif; ?>
                  </div>
                  <p><?php echo htmlspecialchars($result['review_count']); ?> reviews</p>
                </div>
              </div>
              <button class="btn-book" onclick="window.location.href='doctor-details.php?id=<?php echo htmlspecialchars($result['doctor_id']); ?>'">Book Appointment</button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No results found.</p>
      <?php endif; ?>
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
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h3 id="contact-us">Contact Us</h3>
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