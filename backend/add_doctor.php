<?php
session_start();
require 'db.php'; // Include the database connection file

// Step 1: Fetch clinic_id based on the user_id from the session
$stmt = $conn->prepare("SELECT clinic_id FROM admins WHERE user_id = :user_id");
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$clinic_id = $stmt->fetchColumn();

if ($clinic_id === false) {
    // Handle the case where no clinic_id is found for the user
    echo "No clinic found for this user.";
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the form data
    if (
        empty($_POST['email']) ||
        empty($_POST['password']) ||
        empty($_POST['name']) ||
        empty($_POST['specialty']) ||
        empty($_POST['contact'])
    ) {
        $error = "All fields are required.";
    } else {
        // Extract the form data
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $name = $_POST['name'];
        $specialty = $_POST['specialty'];
        $experience = $_POST['experience'];
        $contact = $_POST['contact'];
        $bio = $_POST['bio'];

        // Insert the doctor into the Users table
        try {
            $conn->beginTransaction();

            // Verify if email exists
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user_id = $stmt->fetchColumn();
            if ($user_id) {
                $error = "Email already exists";
            } else {
                // Insert into users table
                $stmt = $conn->prepare("INSERT INTO users (email, password, name, role) VALUES (:email, :password, :name, 'Doctor')");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':name', $name);
                $stmt->execute();
                $user_id = $conn->lastInsertId(); // Get the last inserted user_id

                if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
                  // Read the image file
                  $image = file_get_contents($_FILES["image"]["tmp_name"]);
                  $stmt = $conn->prepare("INSERT INTO doctors (user_id, specialty, contact, experience, bio, pfp) VALUES (:user_id, :specialty, :contact, :experience, :bio, :pfp)");
                } else {
                // Insert into doctors table
                $stmt = $conn->prepare("INSERT INTO doctors (user_id, specialty, contact, experience, bio) VALUES (:user_id, :specialty, :contact, :experience, :bio)");
                }
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':specialty', $specialty);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':experience', $experience);
                $stmt->bindParam(':bio', $bio);
                $stmt->execute();
                $doctor_id = $conn->lastInsertId(); // Get the last inserted doctor_id

                // Step 3: Insert into inclinic table
                $stmt = $conn->prepare("INSERT INTO inclinic (clinic_id, doctor_id) VALUES (:clinic_id, :doctor_id)");
                $stmt->bindParam(':clinic_id', $clinic_id);
                $stmt->bindParam(':doctor_id', $doctor_id);
                $stmt->execute();

                $conn->commit(); // Commit the transaction
                header('Location: ../clinic/doctors.php?success=new doctor added successfully');
                exit;
            }
        } catch (PDOException $e) {
            $conn->rollBack(); // Rollback the transaction on error
            $error = "An error occurred. Please try again later.";
        }
    }
} else {
    $error = "An error occurred with POST. Please try again later.";
}

// Redirect the user back to the doctors page with an error message
if (isset($error)) {
    $redirectUrl = "../clinic/doctors.php?error=" . urlencode($error);
    header("Location: $redirectUrl");
    exit();
}
?>