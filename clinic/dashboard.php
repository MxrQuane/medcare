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
} else {
    header("Location: ../login_signup/login.php");
    exit();
}

// Fetch total appointments
$stmt = $conn->query("SELECT COUNT(*) AS total_appointments FROM appointments");
$totalAppointments = $stmt->fetchColumn();

// Fetch total patients
$stmt = $conn->query("SELECT COUNT(*) AS total_patients FROM patients");
$totalPatients = $stmt->fetchColumn();

// Fetch total doctors
$stmt = $conn->query("SELECT COUNT(*) AS total_doctors FROM users WHERE role = 'Doctor'");
$totalDoctors = $stmt->fetchColumn();

// Fetch upcoming appointments
$query = "
    SELECT 
        a.date, 
        a.time, 
        up.name AS patient_name, 
        ud.name AS doctor_name, 
        a.status
    FROM appointments a
    INNER JOIN patients p ON a.patient_id = p.patient_id
    INNER JOIN users up ON p.user_id = up.user_id -- Join for patient name
    INNER JOIN doctors d ON a.doctor_id = d.doctor_id
    INNER JOIN users ud ON d.user_id = ud.user_id -- Join for doctor name
    WHERE a.date >= CURDATE() -- Only fetch upcoming appointments
    ORDER BY a.date, a.time
";

$stmt = $conn->prepare($query);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <li><a href="appointments.php">Appointments</a></li>
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
                    <p id="total-appointments"><?= $totalAppointments ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Patients</h3>
                    <p id="total-patients"><?= $totalPatients ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Doctors</h3>
                    <p id="total-doctors"><?= $totalDoctors ?></p>
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
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($appointment['date']) ?></td>
                                    <td><?= htmlspecialchars($appointment['time']) ?></td>
                                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No upcoming appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>