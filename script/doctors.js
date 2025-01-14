// Function to open the doctor popup
function openDoctorPopup(doctor) {
  // Update the popup content with the selected doctor's details
  document.getElementById('doctor-name').textContent = doctor.name;
  document.getElementById('doctor-specialty').innerHTML = '<strong>Specialty:</strong> ' + doctor.specialty || 'Not specified';
  document.getElementById('doctor-experience').innerHTML = '<strong>Experience:</strong> ' + doctor.experience || 'Not specified';
  document.getElementById('doctor-contact').innerHTML = '<strong>Contact:</strong> ' + doctor.contact || 'Not specified';
  document.getElementById('doctor-bio').innerHTML = '<strong>Bio:</strong> ' + doctor.bio || 'Not specified';

  // Display the popup
  document.getElementById('doctor-popup').style.display = 'block';
}

// Function to close the doctor popup
function closeDoctorPopup() {
  document.getElementById('doctor-popup').style.display = 'none';
}

// Function to open the edit doctor modal
function openEditDoctorModal(doctor) {
  // Populate the edit modal with the doctor's details
  document.getElementById('edit-doctor-id').value = doctor.doctor_id;
  document.getElementById('edit-doctor-name').value = doctor.name;
  document.getElementById('edit-doctor-specialty').value = doctor.specialty;
  document.getElementById('edit-doctor-experience').value = doctor.experience || '';
  document.getElementById('edit-doctor-contact').value = doctor.contact;
  document.getElementById('edit-doctor-bio').value = doctor.bio || '';

  // Display the edit modal
  document.getElementById('edit-doctor-modal').style.display = 'block';
}

// Function to close the edit doctor modal
function closeEditDoctorModal() {
  document.getElementById('edit-doctor-modal').style.display = 'none';
}

// Function to open the add doctor modal
function openAddDoctorModal() {
  document.getElementById('add-doctor-modal').style.display = 'block';
  document.getElementById('modal-content-add').style.display = 'block';
  document.getElementById('modal-content-new').style.display = 'none';
  document.getElementById('modal-content-exist').style.display = 'none';
}

// Function to close the add doctor modal
function closeAddDoctorModal() {
  document.getElementById('add-doctor-modal').style.display = 'none';
}

function doctorExists() {
  document.getElementById('modal-content-exist').style.display = 'block';
  document.getElementById('modal-content-add').style.display = 'none';
  document.getElementById('modal-content-new').style.display = 'none';
}

function newDoctor() {
  document.getElementById('modal-content-new').style.display = 'block';
  document.getElementById('modal-content-add').style.display = 'none';
  document.getElementById('modal-content-exist').style.display = 'none';
}
// Close modals when clicking outside the modal content
window.onclick = function (event) {
  const doctorPopup = document.getElementById('doctor-popup');
  const addDoctorModal = document.getElementById('add-doctor-modal');
  const editDoctorModal = document.getElementById('edit-doctor-modal');

  if (event.target === doctorPopup) {
    closeDoctorPopup();
  }
  if (event.target === addDoctorModal) {
    closeAddDoctorModal();
  }
  if (event.target === editDoctorModal) {
    closeEditDoctorModal();
  }
};