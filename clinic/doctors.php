<?php
session_start();
require '../backend/db.php';
$isLoggedIn = isset($_SESSION["user_id"]);

if (isset($_SESSION["user_id"])) {
  if ($_SESSION["role"] == "Patient") {
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

 // Step 1: Fetch clinic_id based on the user_id from the session
 $stmt = $conn->prepare("SELECT clinic_id FROM admins WHERE user_id = :user_id");
 $stmt->bindParam(":user_id", $_SESSION["user_id"]);
 $stmt->execute();
 $clinic_id = $stmt->fetchColumn();
 
 if ($clinic_id === false) {
     // Handle the case where no clinic_id is found for the user
     echo "No clinic found for this user.";
     exit;
 }
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctors - Clinic Admin</title>
  <link rel="stylesheet" href="../style/doctors.css">
  <link rel="stylesheet" href="../style/modal.css">
  <link rel="stylesheet" href="../style/popup.css">
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <?php 
      $stmt = $conn->prepare("
          SELECT c.name 
          FROM clinics c
          JOIN admins a ON c.clinic_id = a.clinic_id
          WHERE a.user_id = :user_id
      ");
      $stmt->bindParam(":user_id", $_SESSION["user_id"]);
      $stmt->execute();
      $name = $stmt->fetchColumn();
      echo "<div class='logo'>$name</div>";
      $stmt = $conn->prepare("SELECT name FROM Users WHERE user_id = :user_id");
      $stmt->bindParam(":user_id", $_SESSION["user_id"]);
      $stmt->execute();
      $name = $stmt->fetchColumn();
      echo "<h4>$name</h2>";
    ?>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="appointments.php">Appointments</a></li>
        <li><a class="active">Doctors</a></li>
        <li><a href="../backend/logout.php" class="btn-logout">Logout</a></li>
      </ul>
    </nav>
  </aside>
  <!-- Main Content -->
  <main class="main-content">
    <!-- Doctors Section -->
    <section id="doctors" class="section">
      <h1>Doctors</h1>
      <button class="btn-action" onclick="openAddDoctorModal()">Add Doctor</button>
      <table id="doctors-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Specialty</th>
            <th>Contact</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          // Step 2: Fetch all doctor details and their names in a single query using JOIN
          $stmt = $conn->prepare("
            SELECT d.doctor_id, d.specialty, d.contact, d.experience, d.bio, u.name 
            FROM inclinic ic
            JOIN Doctors d ON ic.doctor_id = d.doctor_id
            JOIN Users u ON d.user_id = u.user_id
            WHERE ic.clinic_id = :clinic_id
          ");
          $stmt->bindParam(":clinic_id", $clinic_id);
          $stmt->execute();
          $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
          // Step 3: Display the doctors' information in a table
          foreach ($doctors as $doctor) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($doctor["name"]) . "</td>";
              echo "<td>" . htmlspecialchars($doctor["specialty"]) . "</td>";
              echo "<td>" . htmlspecialchars($doctor["contact"]) . "</td>";
              echo "<td>";
              echo "<button class='btn-action' onclick='openDoctorPopup(" . htmlspecialchars(json_encode($doctor)) . ")'>View</button>";
              echo "<button class='btn-action' onclick='openEditDoctorModal(" . htmlspecialchars(json_encode($doctor)) . ")'>Edit</button>";
              echo "</td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </section>
  </main>

  <!-- Doctor Details Popup -->
  <div id="doctor-popup" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeDoctorPopup()">&times;</span>
      <h2 id="doctor-name"></h2>
      <p id="doctor-specialty"></p>
      <p id="doctor-experience"></p>
      <p id="doctor-contact"></p>
      <p id="doctor-bio"></p>
      <button class="btn-action" onclick="openEditDoctorModal()">Edit</button>
    </div>
  </div>

  <!-- Add Doctor Modal -->
  <div id="add-doctor-modal" class="modal" style="display:none;">
    <div class="modal-content-add" id="modal-content-add">
      <span class="close" onclick="closeAddDoctorModal()">&times;</span>
      <h2>Add Doctor</h2>
      <button id="exist" onclick = "doctorExists()">user already exists</button>
      <button id="not-exist" onclick = "newDoctor()">user doesn't exist</button>
    </div>
  
      <!-- Add Existing Doctor Modal -->
      <div class="modal-content-exist" id="modal-content-exist" style="display:none;">
          <span class="close" onclick="closeAddDoctorModal()">&times;</span>
          <h2>Add Doctor</h2>
          <div class="search-container">
            <form id="search-doctor-form" onsubmit="searchDoctors(event)">
              <input type="text" id="search-doctor-name" placeholder="Search by doctor's name" required>
              <button type="submit" class="btn-action">Search</button>
            </form>
          </div>
          <!-- Doctors Table -->
          <table id="doctors-table">
            <thead>
            <tr>
              <th>Name</th>
              <th>Specialty</th>
              <th>Contact</th>
            </tr>
            </thead>
            <tbody id="doctors-table-body">

            </tbody>
          </table>
      </div>

        <!-- Add Non Existing Doctor Modal -->
        <div class="modal-content-new" id="modal-content-new" style="display:none;">
          <span class="close" onclick="closeAddDoctorModal()">&times;</span>
          <h2>Add Doctor</h2>
          <form id="add-doctor-form" method="POST" action="../backend/add_doctor.php">
            <label for="doctor-email">Email:</label>
            <input type="text" id="doctor-email" name="email" required>
            <label for="doctor-password">Password:</label>
            <input type="password" id="doctor-password" name="password" required>
            <label for="doctor-name">Name:</label>
            <input type="text" id="doctor-name" name="name" required>
            <label for="doctor-specialty">Specialty:</label>
            <input type="text" id="doctor-specialty" name="specialty" required>
            <label for="doctor-experience">Experience:</label>
            <input type="text" id="doctor-experience" name="experience" required>
            <label for="doctor-contact">Contact:</label>
            <input type="text" id="doctor-contact" name="contact" required>
            <label for="doctor-bio">Bio:</label>
            <textarea id="doctor-bio" name="bio" rows="4" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Doctor</button>
          </form>
        </div>
  
    <!-- <div id="add-doctor-modal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeAddDoctorModal()">&times;</span>
        <h2>Add Doctor</h2>
        <form id="add-doctor-form">
          <label for="doctor-name">Name:</label>
          <input type="text" id="doctor-name" required>
          <label for="doctor-specialty">Specialty:</label>
          <input type="text" id="doctor-specialty" required>
          <label for="doctor-experience">Experience:</label>
          <input type="text" id="doctor-experience" required>
          <label for="doctor-contact">Contact:</label>
          <input type="text" id="doctor-contact" required>
          <label for="doctor-bio">Bio:</label>
          <textarea id="doctor-bio" rows="4" required></textarea>
          <button type="submit">Add Doctor</button>
        </form>
      </div>
    </div> -->
  </div>
    <!-- Edit Doctor Modal -->
    <div id="edit-doctor-modal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeEditDoctorModal()">&times;</span>
        <h2>Edit Doctor</h2>
        <form id="edit-doctor-form" method="POST" action="../backend/add_doctor.php">
          <input type="hidden" id="edit-doctor-id">
          <label for="edit-doctor-name">Name:</label>
          <input type="text" id="edit-doctor-name" required>
          <label for="edit-doctor-specialty">Specialty:</label>
          <input type="text" id="edit-doctor-specialty" required>
          <label for="edit-doctor-experience">Experience:</label>
          <input type="text" id="edit-doctor-experience" required>
          <label for="edit-doctor-contact">Contact:</label>
          <input type="text" id="edit-doctor-contact" required>
          <label for="edit-doctor-bio">Bio:</label>
          <textarea id="edit-doctor-bio" rows="4" required></textarea>
          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

  <?php 
  if (isset($_GET["success"])): ?>
    <div id="popup" class="popup">
      <p><?php echo $_GET["success"] ?></p>
    </div>
  <?php else: if(isset($_GET["error"])): ?>
    <div id="popup" class="popup" style = "background-color: red;">
      <p><?php echo $_GET["error"] ?></p>
    </div>
  <?php endif; endif; ?>
  

  <!-- Script -->
  <script src="../script/doctors.js"></script>
  <script src="../script/popup.js"></script>
</body>
</html>