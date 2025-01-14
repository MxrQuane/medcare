<?php
// Start the session
session_start();

// Redirect logged-in users
if (isset($_SESSION["user_id"])) {
  header("Location: ../");  
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup/Login - Clinic Admin</title>
  <link rel="stylesheet" href="../style/login.css">
</head>
<body>
  <!-- doctor Button -->
  <button class="doctor-btn" id="doctor-btn" onclick="doctorLogin()">Login as a Doctor</button>

  <!-- Signup/Login Container -->
  <div class="auth-container">
    <!-- Toggle Between Signup and Login -->
    <div class="toggle-buttons" id="toggle-buttons">
      <button id="signup-toggle" onclick="showSignup()">Sign Up</button>
      <button id="login-toggle" class="active" onclick="showLogin()">Log In</button>
    </div>

    <div class="toggle-buttons-2" id="toggle-buttons-2" style="display: none;">
      <button id="doctor-login-toggle" class="active" onclick="showDoctor()">Doctor</button>
      <button id="clinic-login-toggle" onclick="showClinic()">Clinic</button>
    </div>

    <!-- Signup Form -->
    <form id="signup-form" class="auth-form" style="display: none;" method="POST" action="../backend/signup.php">
      <h2>Sign Up</h2>
      <label for="signup-name">Name:</label>
      <input type="text" id="signup-name" name="name" required>
      <label for="signup-email">Email:</label>
      <input type="email" id="signup-email" name="email" required>
      <label for="signup-email">Number:</label>
      <input type="text" id="signup-number" name="number" required>
      <label for="signup-password">Password:</label>
      <input type="password" id="signup-password" name="password" required>
      <button type="submit">Sign Up</button>
    </form>
    
    <!-- Login Form -->
    <form id="login-form" class="auth-form" method="POST" action="../backend/login.php">
      <h2>Log In</h2>
      <!-- Error Message -->
      <?php if (isset($_GET["error"])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET["error"]); ?></div>
      <?php endif; ?>
      <label for="login-email">Email:</label>
      <input type="email" id="login-email" name="email" required>
      <label for="login-password">Password:</label>
      <input type="password" id="login-password" name="password" required>
      <input type="password" name="role" value="Patient" style="display: none;">
      <button type="submit">Log In</button>
    </form>
    
    
    <form id="doctor-login-form" class="auth-form" style="display: none;" method="POST" action="../backend/login.php">
      <h2>Log In As A Doctor</h2>
       <!-- Error Message -->
      <?php if (isset($_GET["error"])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET["error"]); ?></div>
      <?php endif; ?>
      <label for="login-email">Email:</label>
      <input type="email" id="login-email" name="email" required>
      <label for="login-password">Password:</label>
      <input type="password" id="login-password" name="password" required>
      <input type="password" name="role" value="Doctor" style="display: none;">
      <button type="submit">Log In</button>
    </form>
    
    <form id="clinic-login-form" class="auth-form" style="display: none;" method="POST" action="../backend/login.php">
      <h2>Log In As A Clinic</h2>
       <!-- Error Message -->
      <?php if (isset($_GET["error"])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_GET["error"]); ?></div>
      <?php endif; ?>
      <label for="login-email">Email:</label>
      <input type="email" id="login-email" name="email" required>
      <label for="login-password">Password:</label>
      <input type="password" id="login-password" name="password" required>
      <input type="password" name="role" value="Admin" style="display: none;">
      <button type="submit">Log In</button>
    </form>

    <!-- Go Back to Home Button -->
    <a class="btn-home" onclick="goToHome()">Go Back to Home</a>
  </div>
  <script src="../script/login.js"></script>
</body>
</html>