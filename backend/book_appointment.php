<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "error" => "You must be logged in to book an appointment."]);
    exit();
}

// Get form data
$doctor_id = $_POST['doctor_id'] ?? null;
$clinic_id = $_POST['clinic_id'] ?? null;
$date = $_POST['date'] ?? null;
$time = $_POST['time'] ?? null;
$patient_id = $_SESSION["user_id"]; // Use the logged-in user's ID as the patient ID

// Validate input
if (!$doctor_id || !$clinic_id || !$date || !$time) {
    echo json_encode(["success" => false, "error" => "Please fill out all fields."]);
    exit();
}

// Check if the selected time slot is available
$stmt = $conn->prepare("
    SELECT appointment_id 
    FROM appointments 
    WHERE doctor_id = :doctor_id 
      AND clinic_id = :clinic_id 
      AND date = :date 
      AND time = :time 
      AND status = 'booked'
");
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->bindParam(':clinic_id', $clinic_id);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':time', $time);
$stmt->execute();

if ($stmt->fetch()) {
    echo json_encode(["success" => false, "error" => "The selected time slot is already booked."]);
    exit();
}

// Insert the appointment into the database
$stmt = $conn->prepare("
    INSERT INTO appointments (patient_id, doctor_id, clinic_id, date, time, status)
    VALUES (:patient_id, :doctor_id, :clinic_id, :date, :time, 'booked')
");
$stmt->bindParam(':patient_id', $patient_id);
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->bindParam(':clinic_id', $clinic_id);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':time', $time);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to book the appointment. Please try again."]);
}
?>