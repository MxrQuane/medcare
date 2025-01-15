<?php
session_start();
require 'db.php'; // Include your database connection file

// Check if doctor_id and date are provided
if (!isset($_GET['doctor_id']) || !isset($_GET['date'])) {
  echo json_encode([]);
  exit();
}

$doctor_id = $_GET['doctor_id'];
$date = $_GET['date'];

// Get the day of the week for the selected date
$day_of_week = date('l', strtotime($date)); // e.g., "Monday"

// Fetch working hours for the doctor on the selected day
$stmt = $conn->prepare("
  SELECT start_time, end_time 
  FROM doctor_working_hours 
  WHERE doctor_id = :doctor_id AND day_of_week = :day_of_week
");
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->bindParam(':day_of_week', $day_of_week);
$stmt->execute();
$working_hours = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$working_hours) {
  echo json_encode([]);
  exit();
}

// Generate time slots
$start_time = strtotime($working_hours['start_time']);
$end_time = strtotime($working_hours['end_time']);
$time_slots = [];

while ($start_time < $end_time) {
  $time_slots[] = date('H:i:s', $start_time); // Format as "08:00", "08:30", etc.
  $start_time += 1800; // Add 30 minutes (1800 seconds)
}

// Fetch booked appointments for the selected date
$stmt = $conn->prepare("
  SELECT time 
  FROM appointments 
  WHERE doctor_id = :doctor_id AND date = :date
");
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->bindParam(':date', $date);
$stmt->execute();
$booked_times = $stmt->fetchAll(PDO::FETCH_COLUMN);
// Filter out booked times

$available_times = array_diff($time_slots, $booked_times);

$available_times = array_map(function($time) {
      return substr($time, 0, 5); // Convert "08:00:00" to "08:00"
    }, $available_times);

echo json_encode(array_values($available_times));
?>