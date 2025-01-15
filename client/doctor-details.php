<?php
session_start();
require '../backend/db.php'; // Include your database connection file
$isLoggedIn = isset($_SESSION["user_id"]);

// Check if the doctor ID is provided in the URL
if (!isset($_GET['id'])) {
  header("Location: ../client/search.php");
  exit();
}

$doctor_id = $_GET['id'];

// Fetch doctor details from the database
$stmt = $conn->prepare("
  SELECT 
    users.name AS doctor_name, 
    users.email AS doctor_email, 
    doctors.specialty, 
    doctors.experience, 
    doctors.bio, 
    doctors.rating, 
    doctors.review_count, 
    clinics.name AS clinic_name, 
    clinics.address, 
    clinics.phone AS clinic_phone, 
    clinics.email AS clinic_email,
    clinics.clinic_id
  FROM doctors
  INNER JOIN users ON doctors.user_id = users.user_id
  INNER JOIN inclinic ON doctors.doctor_id = inclinic.doctor_id
  INNER JOIN clinics ON inclinic.clinic_id = clinics.clinic_id
  WHERE doctors.doctor_id = :doctor_id
");
$stmt->bindParam(':doctor_id', $doctor_id);
$stmt->execute();
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
  header("Location: ../client/search.php");
  exit();
}

// Check if the logged-in patient has a completed appointment with this doctor
$canReview = false;
if ($isLoggedIn && $_SESSION["role"] == "Patient") {
  $patient_id = $_SESSION["user_id"];
  $stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM appointments 
    WHERE patient_id = :patient_id 
      AND doctor_id = :doctor_id 
      AND status = 'done'
  ");
  $stmt->bindParam(':patient_id', $patient_id);
  $stmt->bindParam(':doctor_id', $doctor_id);
  $stmt->execute();
  $canReview = $stmt->fetchColumn() > 0;
}

if($canReview) {
  $stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM reviewed 
    WHERE patient_id = :patient_id 
      AND doctor_id = :doctor_id 
  ");
  $stmt->bindParam(':patient_id', $patient_id);
  $stmt->bindParam(':doctor_id', $doctor_id);
  $stmt->execute();
  $canReview = $stmt->fetchColumn() == 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Details - MedCare</title>
  <link rel="stylesheet" href="../style/home.css">
  <link rel="stylesheet" href="../style/doctor-details.css">
</head>
<body>
  <!-- Header Section (Same as Homepage) -->
  <header>
    <div class="container">
      <div class="logo">MedCare</div>
      <nav>
        <ul>
          <li><a href="../">Home</a></li>
          <?php 
            if (isset($_SESSION["user_id"])) :
            if ($_SESSION["role"] == "Patient") :
          ?>
          <li><a href="#contact-us">Contact Us</a></li>
          <?php endif; endif; ?>
          <?php if ($isLoggedIn): ?>
            <!-- Display Logout Button if Logged In -->
            <li><a href="../backend/logout.php" class="btn-login">Logout</a></li>
          <?php else: ?>
            <!-- Display Signup/Login Buttons if Not Logged In -->
            <li><a href="../login_signup/login.php" class="btn-login">Login/Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Doctor Details Section -->
  <section class="doctor-details">
    <div class="container">
      <div class="doctor-info">
        <!-- Doctor's Image -->
        <div class="doctor-image">
          <img src="../assets/image/doctor-pfp.webp" alt="<?php echo htmlspecialchars($doctor['doctor_name']); ?>">
        </div>

        <!-- Doctor's Information -->
        <div class="doctor-text">
          <h1><?php echo htmlspecialchars($doctor['doctor_name']); ?></h1>
          <div class="stars">
            <?php for ($i = 0; $i < floor($doctor['rating']); $i++): ?>
              <div class="star active"></div>
            <?php endfor; ?>
            <?php if ($doctor['rating'] - floor($doctor['rating']) >= 0.5): ?>
              <div class="star half"></div>
            <?php endif; ?>
          </div>
          <p class="rating">(<?php echo htmlspecialchars($doctor['review_count']); ?> reviews)</p>
          <p class="specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></p>
          <p class="experience"><?php echo htmlspecialchars($doctor['experience']); ?></p>
          <p class="bio"><?php echo htmlspecialchars($doctor['bio']); ?></p>
        </div>
      </div>

      <!-- Clinic Information -->
      <div class="clinic-info">
        <h2>Clinic Details</h2>
        <div class="clinic-details">
          <p><strong>Clinic Name:</strong> <?php echo htmlspecialchars($doctor['clinic_name']); ?></p>
          <p><strong>Address:</strong> <?php echo htmlspecialchars($doctor['address']); ?></p>
          <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['clinic_phone']); ?></p>
          <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['clinic_email']); ?></p>
          <p><strong>Opening Hours:</strong> <?php echo "8am-16pm Monday-Friday"; ?></p>
        </div>
      </div>

      <?php 
        if (isset($_SESSION["user_id"])) :
          if ($_SESSION["role"] == "Patient") :
        ?>
      <!-- Available Times Section -->
      <?php if ($canReview): ?>
  <!-- Review Section -->
      <div class="review-section">
        <h2>Leave a Review</h2>
        <form id="review-form">
          <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">

          <div class="form-group">
            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>
          </div>
          <button type="submit" class="btn-submit">Submit Review</button>
        </form>
      </div>
      <?php endif; ?>
      <div class="available-times">
        <h2>Available Times</h2>
        <p>Select a date to view available time slots:</p>
        <div class="time-slots" id="time-slots">
          <!-- Time slots will be dynamically populated here -->
        </div>
      </div>

      <!-- Appointment Booking Section -->
      <div class="appointment-booking">
        <h2>Book an Appointment</h2>
        <form class="booking-form" method="POST" action="../backend/book_appointment.php" onsubmit="handleBooking(event)">
          <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
          <input type="hidden" name="clinic_id" value="<?php echo $doctor['clinic_id']; ?>">
                  
          <div class="form-group">
            <label for="date">Select Date</label>
            <input type="date" id="date" name="date" required onchange="updateAvailableTimes()">
          </div>
          <div class="form-group">
            <label for="time">Select Time</label>
            <select id="time" name="time" required>
              <option value="">Select a time</option>
              <!-- Time options will be dynamically populated here -->
            </select>
          </div>
          <button type="submit" class="btn-book">Confirm Booking</button>
        </form>
      </div>
    </div>
  </section>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_SESSION['success']); ?>
      <?php unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-error">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
      </div>
      <?php endif; ?>
      
      <!-- Payment Success Modal -->
      <div id="payment-success-modal" class="modal">
        <div class="modal-content">
          <span class="close-modal">&times;</span>
      <h2>Payment Successful!</h2>
      <p>Your appointment has been booked successfully.</p>
      <button id="close-modal-btn">Close</button>
    </div>
  </div>
  
  <!-- Footer Section (Same as Homepage) -->
  <footer>
    <div class="container">
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Services</a></li>
          <li><a href="#">Clinics & Doctors</a></li>
          <li><a href="#contact-us">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h3 id="contact-us">Contact Us</h3>
        <p>Phone: +123 456 7890</p>
        <p>Email: info@medcare.com</p>
        <p>Address: 123 Health St, City, Country</p>
      </div>
      <div class="footer-social">
        <h3>Follow Us</h3>
        <a href="#"><img src="../assets/image/facebook-icon.png" alt="Facebook"></a>
        <a href="#"><img src="../assets/image/twitter-icon.png" alt="Twitter"></a>
        <a href="#"><img src="../assets/image/instagram-icon.png" alt="Instagram"></a>
      </div>
    </div>
  </footer>
  <?php endif; endif;?>

  <!-- JavaScript -->
  <script>
// Function to fetch available times from the backend
async function fetchAvailableTimes(date) {
  const response = await fetch(`../backend/get_available_times.php?doctor_id=<?php echo $doctor_id; ?>&date=${date}`);
  const data = await response.json();
  return data;
}

// Function to update available time slots based on selected date
async function updateAvailableTimes() {
  const dateInput = document.getElementById('date');
  const timeSelect = document.getElementById('time');
  const timeSlotsContainer = document.getElementById('time-slots');
  const selectedDate = dateInput.value;

  // Clear previous options
  timeSelect.innerHTML = '<option value="">Select a time</option>';
  timeSlotsContainer.innerHTML = '';

  if (selectedDate) {
    const availableTimes = await fetchAvailableTimes(selectedDate);
    if (availableTimes.length > 0) {
      availableTimes.forEach(time => {
        // Add time to the dropdown
        const option = document.createElement('option');
        option.value = time;
        option.textContent = time;
        timeSelect.appendChild(option);

        // Display time slots as buttons
        const timeSlot = document.createElement('button');
        timeSlot.textContent = time;
        timeSlot.classList.add('time-slot');
        timeSlot.onclick = () => {
          timeSelect.value = time; // Set the selected time in the dropdown
        };
        timeSlotsContainer.appendChild(timeSlot);
      });
    } else {
      timeSlotsContainer.innerHTML = '<p>No available times for this date.</p>';
    }
  }
}

// Function to handle booking submission
async function handleBooking(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.success) {
      // Show the payment success modal
      showPaymentSuccessModal();
    } else {
      alert("Failed to book the appointment: " + data.error);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred. Please try again.");
  }
}

// Function to show the payment success modal
function showPaymentSuccessModal() {
  const modal = document.getElementById('payment-success-modal');
  modal.style.display = 'block';

  // Close the modal when the close button is clicked
  const closeModalBtn = document.getElementById('close-modal-btn');
  closeModalBtn.onclick = () => {
    modal.style.display = 'none';
  };

  // Close the modal when the user clicks outside the modal
  window.onclick = (event) => {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  };

  // Close the modal when the "x" button is clicked
  const closeModalX = document.querySelector('.close-modal');
  closeModalX.onclick = () => {
    modal.style.display = 'none';
  };
}

// Handle review form submission
document.getElementById('review-form')?.addEventListener('submit', async (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  try {
    const response = await fetch('../backend/submit_review.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.success) {
      alert('Review submitted successfully!');
      
      // Update the doctor's rating and review count dynamically
      const ratingElement = document.querySelector('.rating');
      const starsElement = document.querySelector('.stars');
      const reviewCount = parseInt(ratingElement.textContent.match(/\d+/)[0]) + 1;
      const newRating = data.newRating;

      // Update the review count
      ratingElement.textContent = `(${reviewCount} reviews)`;

      // Update the stars
      starsElement.innerHTML = '';
      for (let i = 0; i < Math.floor(newRating); i++) {
        const star = document.createElement('div');
        star.classList.add('star', 'active');
        starsElement.appendChild(star);
      }
      if (newRating - Math.floor(newRating) >= 0.5) {
        const star = document.createElement('div');
        star.classList.add('star', 'half');
        starsElement.appendChild(star);
      }
    } else {
      alert('Failed to submit review: ' + data.error);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('An error occurred. Please try again.');
  }
});
  </script>
</body>
</html>