<?php
session_start();
require 'db.php'; // Include the database connection file

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Decode the JSON data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Build the SQL query based on the search criteria
  $query = "
    SELECT a.appointment_id, u.name AS doctor_name, c.name AS clinic_name, d.specialty, a.appointment_date, a.appointment_time, a.price
    FROM Appointments a
    JOIN Users u ON a.doctor_id = u.user_id
    JOIN Doctors d ON a.doctor_id = d.doctor_id
    JOIN Clinics c ON a.clinic_id = c.clinic_id
    WHERE 1=1
  ";

  // Add filters based on the search criteria
  if (!empty($data['clinic'])) {
    $query .= " AND c.name = :clinic";
  }
  if (!empty($data['doctor'])) {
    $query .= " AND d.name = :doctor";
  }
  if (!empty($data['specialty'])) {
    $query .= " AND d.specialty = :specialty";
  }
  if (!empty($data['state'])) {
    $query .= " AND c.state = :state";
  }
  if (!empty($data['municipality'])) {
    $query .= " AND c.municipality = :municipality";
  }
  if (!empty($data['startDate'])) {
    $query .= " AND a.appointment_date >= :startDate";
  }
  if (!empty($data['endDate'])) {
    $query .= " AND a.appointment_date <= :endDate";
  }
  if (!empty($data['minPrice'])) {
    $query .= " AND a.price >= :minPrice";
  }
  if (!empty($data['maxPrice'])) {
    $query .= " AND a.price <= :maxPrice";
  }

  // Prepare and execute the query
  $stmt = $conn->prepare($query);
  if (!empty($data['clinic'])) {
    $stmt->bindParam(':clinic', $data['clinic']);
  }
  if (!empty($data['doctor'])) {
    $stmt->bindParam(':doctor', $data['doctor']);
  }
  if (!empty($data['specialty'])) {
    $stmt->bindParam(':specialty', $data['specialty']);
  }
  if (!empty($data['state'])) {
    $stmt->bindParam(':state', $data['state']);
  }
  if (!empty($data['municipality'])) {
    $stmt->bindParam(':municipality', $data['municipality']);
  }
  if (!empty($data['startDate'])) {
    $stmt->bindParam(':startDate', $data['startDate']);
  }
  if (!empty($data['endDate'])) {
    $stmt->bindParam(':endDate', $data['endDate']);
  }
  if (!empty($data['minPrice'])) {
    $stmt->bindParam(':minPrice', $data['minPrice']);
  }
  if (!empty($data['maxPrice'])) {
    $stmt->bindParam(':maxPrice', $data['maxPrice']);
  }

  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($results) {
    echo json_encode(['success' => true, 'results' => $results]);
  } else {
    echo json_encode(['success' => false, 'message' => 'No appointments found.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>