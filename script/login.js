// Toggle Between Signup and Login
function showSignup() {
      document.getElementById("signup-form").style.display = "flex";
      document.getElementById("doctor-login-form").style.display = "none";
      document.getElementById("login-form").style.display = "none";
      document.getElementById("signup-toggle").classList.add("active");
      document.getElementById("login-toggle").classList.remove("active");
    }
    
    function showLogin() {
      document.getElementById("login-form").style.display = "flex";
      document.getElementById("doctor-login-form").style.display = "none";
      document.getElementById("signup-form").style.display = "none";
      document.getElementById("login-toggle").classList.add("active");
      document.getElementById("signup-toggle").classList.remove("active");
    }

    function doctorLogin() {
      document.getElementById("doctor-login-form").style.display = "flex";
      document.getElementById("toggle-buttons-2").style.display = "flex";
      document.getElementById("toggle-buttons").style.display = "none";
      document.getElementById("doctor-btn").style.display = "none";
      document.getElementById("login-form").style.display = "none";
      document.getElementById("signup-form").style.display = "none";
      document.getElementById("login-toggle").classList.remove("active");
      document.getElementById("signup-toggle").classList.remove("active");
    }

    function showDoctor() {
      document.getElementById("doctor-login-form").style.display = "flex";
      document.getElementById("toggle-buttons-2").style.display = "flex";
      document.getElementById("clinic-login-form").style.display = "none";
      document.getElementById("toggle-buttons").style.display = "none";
      document.getElementById("doctor-login-toggle").classList.add("active");
      document.getElementById("clinic-login-toggle").classList.remove("active");
    }
    
    function showClinic() {
      document.getElementById("clinic-login-form").style.display = "flex";
      document.getElementById("doctor-login-form").style.display = "none";
      document.getElementById("toggle-buttons-2").style.display = "flex";
      document.getElementById("toggle-buttons").style.display = "none";
      document.getElementById("clinic-login-toggle").classList.add("active");
      document.getElementById("doctor-login-toggle").classList.remove("active");
    }

    // Go Back to Home
    function goToHome() {
      window.location.href = "../";
    }

    // window.onload = function() {
    //   showLogin();
    // }