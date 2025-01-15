<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login_signup/login.php");
    exit();
}

// Get search parameters from the form
$clinic = $_GET['clinic'] ?? '';
$doctor = $_GET['doctor'] ?? '';
$specialty = $_GET['specialty'] ?? '';
$state = $_GET['state'] ?? '';

// Build the SQL query based on the search parameters
$sql = "SELECT 
          users.name AS doctor_name, 
          clinics.name AS clinic_name, 
          doctors.doctor_id,
          doctors.experience, 
          doctors.bio,
          doctors.specialty,
          doctors.rating,
          doctors.review_count
        FROM inclinic
        INNER JOIN doctors ON inclinic.doctor_id = doctors.doctor_id
        INNER JOIN users ON doctors.user_id = users.user_id
        INNER JOIN clinics ON inclinic.clinic_id = clinics.clinic_id
        WHERE 1=1";

if (!empty($clinic)) {
    $sql .= " AND inclinic.clinic_id IN (SELECT clinics.clinic_id FROM clinics WHERE clinics.name LIKE :clinic)";
}

if (!empty($doctor)) {
  $sql .= " AND inclinic.doctor_id IN (SELECT doctors.doctor_id FROM doctors INNER JOIN users ON doctors.user_id = users.user_id WHERE users.name LIKE :doctor AND users.role = 'Doctor')";
}

if (!empty($specialty)) {
    $sql .= " AND inclinic.doctor_id IN (SELECT doctors.doctor_id FROM doctors WHERE doctors.specialty LIKE :specialty)";
}

if (!empty($state)) {
    $sql .= " AND inclinic.clinic_id IN (SELECT clinics.clinic_id FROM clinics WHERE clinics.state = :state)";
}

// Prepare and execute the query
$stmt = $conn->prepare($sql);

if (!empty($clinic)) {
    $stmt->bindValue(':clinic', "%$clinic%");
}

if (!empty($doctor)) {
    $stmt->bindValue(':doctor', "%$doctor%");
}

if (!empty($specialty)) {
    $stmt->bindValue(':specialty', "%$specialty%");
}

if (!empty($state)) {
    $stmt->bindValue(':state', $state);
}


$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Redirect back to the search page with the results
$_SESSION['search_results'] = $results;
header("Location: ../client/search.php");
exit();
?>