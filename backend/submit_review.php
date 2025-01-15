<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate input
  if (
    empty($_POST['doctor_id']) ||
    empty($_POST['rating'])
  ) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
  }

  $doctor_id = $_POST['doctor_id'];
  $rating = intval($_POST['rating']);

  // Validate rating
  if ($rating < 1 || $rating > 5) {
    echo json_encode(['error' => 'Rating must be between 1 and 5.']);
    exit;
  }

  // Fetch the current rating and review count
  $stmt = $conn->prepare("SELECT rating, review_count FROM doctors WHERE doctor_id = :doctor_id");
  $stmt->bindParam(':doctor_id', $doctor_id);
  $stmt->execute();
  $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$doctor) {
    echo json_encode(['error' => 'Doctor not found.']);
    exit;
  }

  // Calculate the new average rating
  $currentRating = floatval($doctor['rating']);
  $reviewCount = intval($doctor['review_count']);
  $newRating = (($currentRating * $reviewCount) + $rating) / ($reviewCount + 1);

  // Update the doctor's rating and review count
  $stmt = $conn->prepare("
    UPDATE doctors 
    SET rating = :rating, review_count = review_count + 1 
    WHERE doctor_id = :doctor_id
  ");
  $stmt->bindParam(':rating', $newRating);
  $stmt->bindParam(':doctor_id', $doctor_id);
  $stmt->execute();
  
  $stmt = $conn->prepare("INSERT INTO reviewed (patient_id, doctor_id) VALUES (:patient_id, :doctor_id)");
  $stmt->bindParam(':patient_id', $_SESSION["user_id"]);
  $stmt->bindParam(':doctor_id', $doctor_id);
  $stmt->execute();
  
  echo json_encode(['success' => 'Review submitted successfully.', 'newRating' => $newRating]);
} else {
  echo json_encode(['error' => 'Invalid request method.']);
}
?>