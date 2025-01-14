// Scroll to Top Button
const scrollToTopButton = document.getElementById("scroll-to-top");

// Show the button when the user scrolls down 200px
window.addEventListener("scroll", function () {
  if (window.scrollY > 200) {
    scrollToTopButton.style.display = "block";
  } else {
    scrollToTopButton.style.display = "none";
  }
});

// Scroll to the top when the button is clicked
scrollToTopButton.addEventListener("click", function () {
  window.scrollTo({
    top: 0,
    behavior: "smooth", // Smooth scrolling
  });
});