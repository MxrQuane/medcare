<?php
// Start the session
session_start();

require 'db.php';
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $email = $_POST["email"];
  $password = $_POST["password"];
  $role = $_POST["role"];

  // Fetch user from the database
  try {
      $stmt = $conn->prepare("SELECT user_id, name, password, role FROM Users WHERE email = :email AND role = :role");
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":role", $role);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if ($user) {
        // Verify the password
        if (password_verify($password, $user["password"])) {
          // Password is correct, log the user in
          $_SESSION["user_id"] = $user["user_id"];
          $_SESSION["name"] = $user["name"];
          $_SESSION["role"] = $user["role"];
            header("Location: ../");
          exit();
        } else {
            // Invalid password
            $error = "Invalid password.";
          }
        } else {
          // User not found
          $error = "Invalid email.";
        }
      } catch (PDOException $e) {
        $error = "An error occurred. Please try again later.";
      }
    
      if (isset($error)) {
        $redirectUrl = "../login_signup/login.php?error=" . urlencode($error);
        header("Location: $redirectUrl");
        exit();
      }
    }
?>