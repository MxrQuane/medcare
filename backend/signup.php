<?php
// Start the session
session_start();

require 'db.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $name = $_POST["name"];
  $email = $_POST["email"];
  $number = $_POST["number"];
  $password = $_POST["password"];

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insert into Users table
  try {
    // Start a transaction
    $conn->beginTransaction();

    // Insert into Users table
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (:name, :email, :password, 'Patient')");
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->execute();

    // Get the last inserted user_id
    $user_id = $conn->lastInsertId();

    // Insert into Patients table
    $stmt = $conn->prepare("INSERT INTO Patients (user_id, contact) VALUES (:user_id, :contact)");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->bindParam(":contact", $number);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Redirect to login page
    header("Location: ../login_signup/login.php");
    exit();
  } catch (PDOException $e) {
    // Rollback the transaction on error
    $conn->rollBack();
    die("Error: " . $e->getMessage());
  }
}
?>