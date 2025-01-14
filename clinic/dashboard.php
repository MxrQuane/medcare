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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clinic Admin Dashboard</title>
  <link rel="stylesheet" href="../style/clinic-admin.css">
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
        <li><a class="active">Dashboard</a></li>
        <li><a href="appointments.html">Appointments</a></li>
        <li><a href="#patients">Patients</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="../backend/logout.php" class="btn-logout">Logout</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <!-- Dashboard Section -->
    <section id="dashboard" class="section">
      <h1>Dashboard</h1>
      <div class="stats">
        <div class="stat-card">
          <h3>Total Appointments</h3>
          <p id="total-appointments">1350</p>
        </div>
        <div class="stat-card">
          <h3>Total Patients</h3>
          <p id="total-patients">10430</p>
        </div>
        <div class="stat-card">
          <h3>Total Doctors</h3>
          <?php 
          $stmt = $conn->prepare("SELECT COUNT(*) FROM Users WHERE role = 'Doctor'");
          $stmt->execute();
          $totalDoctors = $stmt->fetchColumn();
          echo "<p id='total-doctors'>$totalDoctors</p>";
          ?>
        </div>
      </div>
      <div class="upcoming-appointments">
        <h2>Upcoming Appointments</h2>
        <table id="upcoming-appointments-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tbody>
              <tr>
                <td>2025-01-10</td>
                <td>10:10</td>
                <td>Jane Smith</td>
                <td>Dr. John Doe</td>
                <td>Confirmed</td>
              </tr>
          </tbody>
          </tbody>
        </table>
      </div>
    </section>
  </main>
  <!-- Script -->
</body>
</html>