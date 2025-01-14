// Function to show the popup
function showPopup() {
      const popup = document.getElementById('popup');
      popup.style.display = 'block'; // Show the popup
      popup.style.animation = 'slideIn 0.5s ease-out'; // Add slide-in animation
    
      // Hide the popup after 3 seconds
      setTimeout(() => {
        popup.style.animation = 'slideOut 0.5s ease-out'; // Add slide-out animation
        setTimeout(() => {
          popup.style.display = 'none'; // Hide the popup after animation
        }, 500); // Wait for the slide-out animation to finish
      }, 3000); // 3 seconds delay
    }
    
    // Example: Show a popup when the page loads
    window.onload = () => {
      showPopup();
    };