/**
 * Leave Management System
 * Main JavaScript file
 */

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  
  // Initialize Bootstrap popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
  
  // Add active state to current nav item
  activateCurrentNavItem();
  
  // Animate elements with animate-slide-up class
  animateElements();
  
  // Add date validation to leave request form
  setupDateValidation();
});

/**
 * Activates the current navigation item based on the URL
 */
function activateCurrentNavItem() {
  // Get current path
  var path = window.location.pathname;
  var page = path.split("/").pop();
  
  // Find nav links
  var navLinks = document.querySelectorAll('.nav-link');
  
  // Loop through links and add active class to matching page
  navLinks.forEach(function(link) {
    var href = link.getAttribute('href');
    if (href === page) {
      link.classList.add('active');
    }
  });
}

/**
 * Animates elements with the animate-slide-up class
 */
function animateElements() {
  var animatedElements = document.querySelectorAll('.animate-slide-up');
  
  animatedElements.forEach(function(element, index) {
    // Add a delay to each element for staggered animation
    setTimeout(function() {
      element.style.opacity = '1';
      element.style.transform = 'translateY(0)';
    }, 100 * index);
  });
}

/**
 * Sets up date validation for leave request form
 */
function setupDateValidation() {
  var startDateInput = document.getElementById('start_date');
  var endDateInput = document.getElementById('end_date');
  
  if (startDateInput && endDateInput) {
    // Set min date to today for start date
    var today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today);
    
    // Update end date min value when start date changes
    startDateInput.addEventListener('change', function() {
      endDateInput.setAttribute('min', this.value);
      
      // If end date is before start date, update it
      if (endDateInput.value && endDateInput.value < this.value) {
        endDateInput.value = this.value;
      }
    });
  }
}

/**
 * Shows an alert message that automatically disappears
 * @param {string} message - The message to display
 * @param {string} type - The type of alert (success, danger, warning)
 */
function showAlert(message, type = 'success') {
  var alertContainer = document.getElementById('alert-container');
  
  if (!alertContainer) {
    alertContainer = document.createElement('div');
    alertContainer.id = 'alert-container';
    alertContainer.style.position = 'fixed';
    alertContainer.style.top = '20px';
    alertContainer.style.right = '20px';
    alertContainer.style.zIndex = '9999';
    document.body.appendChild(alertContainer);
  }
  
  var alert = document.createElement('div');
  alert.className = 'alert alert-' + type + ' alert-dismissible fade show';
  alert.innerHTML = message + 
    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
  
  alertContainer.appendChild(alert);
  
  // Auto-dismiss after 5 seconds
  setTimeout(function() {
    alert.classList.remove('show');
    setTimeout(function() {
      alertContainer.removeChild(alert);
    }, 150);
  }, 5000);
} 