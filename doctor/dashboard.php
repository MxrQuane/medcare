<?php
session_start();
require '../backend/db.php';
$isLoggedIn = isset($_SESSION["user_id"]);

// Redirect if not logged in or not a doctor
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "Doctor") {
    header("Location: ../login_signup/login.php");
    exit();
}

// Fetch doctor's name from the database
$stmt = $conn->prepare("SELECT doctor_id FROM doctors WHERE user_id = :u_id");
$stmt->bindParam(':u_id', $_SESSION["user_id"]);
$stmt->execute();
$doctor_id = $stmt->fetch(PDO::FETCH_ASSOC);

// $doctor_id = $_SESSION["user_id"];
$stmt = $conn->prepare("
    SELECT name 
    FROM users 
    WHERE user_id IN (SELECT user_id FROM doctors WHERE doctor_id = :doctor_id)
");
$stmt->bindParam(':doctor_id', $doctor_id["doctor_id"]);
$stmt->execute();
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    header("Location: ../login_signup/login.php");
    exit();
}

// Fetch upcoming appointments
$stmt = $conn->prepare("
    SELECT a.appointment_id, a.date, a.time, u.name AS patient_name, c.name AS clinic_name
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.patient_id
    INNER JOIN users u ON p.user_id = u.user_id
    INNER JOIN clinics c ON a.clinic_id = c.clinic_id
    WHERE a.doctor_id = :doctor_id AND a.date >= CURDATE() and a.status = 'booked'
    ORDER BY a.date, a.time
");
$stmt->bindParam(':doctor_id', $doctor_id["doctor_id"]);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch clinics where the doctor works
$stmt = $conn->prepare("
    SELECT c.name, c.address, c.phone, c.email
    FROM inclinic ic
    INNER JOIN clinics c ON ic.clinic_id = c.clinic_id
    WHERE ic.doctor_id = :doctor_id
");
$stmt->bindParam(':doctor_id', $doctor_id["doctor_id"]);
$stmt->execute();
$clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Dashboard - MedCare</title>
  <link rel="stylesheet" href="../style/doctor-dashboard.css">
</head>
<body>
  <!-- More Button (Hamburger Icon) -->
  <div class="more-button" id="moreButton" onclick="toggleSidebar()">☰</div>

  <!-- Overlay for clicking outside the sidebar -->
  <div class="overlay" id="overlay" onclick="hideSidebar()"></div>

  <!-- Sidebar Section -->
  <aside class="sidebar" id="sidebar">
    <div class="logo">MedCare</div>
    <nav>
      <ul>
        <li><a href="#" class="active">Dashboard</a></li>
        <li><a href="../client/doctor-details.php?id=<?php echo $doctor_id["doctor_id"] ?>">Profile</a></li>
        <li><a href="../backend/logout.php" class="btn-logout">Logout</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content Section -->
  <main class="main-content">
    <div class="container">
      <!-- Welcome Message -->
      <div class="welcome-message">
        <h1>Welcome, Dr. <?php echo htmlspecialchars($doctor['name']); ?></h1>
        <p>Here's an overview of your appointments and clinics.</p>
      </div>

      <!-- Quick Actions -->
      <div class="quick-actions">
        <button class="btn-action" onclick="viewPatientHistory()">View Patient History</button>
      </div>

      <!-- Upcoming Appointments -->
      <div class="upcoming-appointments">
        <h2>Upcoming Appointments</h2>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Patient Name</th>
              <th>Clinic</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $appointment): ?>
              <tr>
                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($appointment['clinic_name']); ?></td>
                <td>
                  <button class="btn-view" onclick="viewAppointmentDetails(<?php echo $appointment['appointment_id']; ?>)">
                    View Details
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Clinics Section -->
      <div class="clinics">
        <h2>Clinics You Work In</h2>
        <div class="clinic-cards">
          <?php foreach ($clinics as $clinic): ?>
            <div class="clinic-card">
              <h3><?php echo htmlspecialchars($clinic['name']); ?></h3>
              <p><strong>Address:</strong> <?php echo htmlspecialchars($clinic['address']); ?></p>
              <p><strong>Phone:</strong> <?php echo htmlspecialchars($clinic['phone']); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($clinic['email']); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </main>

  <!-- JavaScript -->
  <script>
    
    // Function to handle "View Details" button click
    function viewAppointmentDetails(appointmentId) {
      window.location.href = `appointment-details.php?id=${appointmentId}`;
    }

    // Function to simulate viewing patient history
    function viewPatientHistory() {
      alert('Redirecting to patient history page...');
      // window.location.href = 'patient-history.html';
    }
  </script>
</body>
</html>