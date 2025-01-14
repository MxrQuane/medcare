function changeInputValue(id,value) {
      document.getElementById(id).value = value;
}

// Function to filter dropdown options
function filterDropdown(id,num) {
      const input = document.getElementById(id);
      const filter = input.value.toUpperCase(); // Get the input value and convert to uppercase
      const dropdownOptions = document.getElementById('dropdown-options'+ " "+num);
      const options = dropdownOptions.getElementsByClassName('search-dropdown');
    
      // Loop through all dropdown options
      for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const text = option.textContent || option.innerText;
        // Check if the option text contains the input value
        if (text.toUpperCase().indexOf(filter) > -1 && filter.length > 0 && filter.trim() !== '') {
          option.style.display = ''; // Show the option
        } else {
          option.style.display = 'none'; // Hide the option
        }
      }
      //     Show the dropdown when the input is focused
    document.getElementById(id).addEventListener('focus', function () {
      document.getElementById('dropdown-options' + " " + num).style.display = 'block';
    });
        document.addEventListener('click', function (event) {
      const dropdown = document.getElementById('dropdown-options' + " " + num);
      const input = document.getElementById(id);
      if (event.target !== input && event.target !== dropdown) {
        dropdown.style.display = 'none';
      }
      });
    }


    
    // Function to set the input value when an option is clicked
    function changeInputValue(id, value) {
      document.getElementById(id).value = value;
      document.getElementById('dropdown-options').style.display = 'none'; // Hide the dropdown after selection
    }
    

