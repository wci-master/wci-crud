// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-out effect to alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '1';
                let fadeEffect = setInterval(function() {
                    if (alert.style.opacity > 0) {
                        alert.style.opacity -= 0.1;
                    } else {
                        clearInterval(fadeEffect);
                        alert.style.display = 'none';
                    }
                }, 50);
            }, 5000);
        });
    }
    
    // Add active class to current navigation item
    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll('nav ul li a');
    
    navLinks.forEach(function(link) {
        const linkPath = link.getAttribute('href');
        
        // Check if the current path includes the link path
        // This handles both exact matches and sub-pages
        if (currentLocation === linkPath || 
            (linkPath !== '/wci-crud/index.php' && currentLocation.includes(linkPath))) {
            link.parentElement.classList.add('active');
        }
    });
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            let hasError = false;
            const requiredInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            
            requiredInputs.forEach(function(input) {
                if (!input.value.trim()) {
                    hasError = true;
                    input.classList.add('error-input');
                    
                    // Create error message if it doesn't exist
                    let errorSpan = input.nextElementSibling;
                    if (!errorSpan || !errorSpan.classList.contains('help-block')) {
                        errorSpan = document.createElement('span');
                        errorSpan.classList.add('help-block');
                        errorSpan.textContent = 'This field is required';
                        input.parentNode.insertBefore(errorSpan, input.nextSibling);
                    }
                } else {
                    input.classList.remove('error-input');
                    
                    // Remove error message if it exists
                    const errorSpan = input.nextElementSibling;
                    if (errorSpan && errorSpan.classList.contains('help-block') && errorSpan.textContent === 'This field is required') {
                        errorSpan.remove();
                    }
                }
            });
            
            if (hasError) {
                event.preventDefault();
            }
        });
    });
    
    // Task status filter functionality
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const taskRows = document.querySelectorAll('.task-table tbody tr');
            
            taskRows.forEach(function(row) {
                const statusCell = row.querySelector('td:nth-child(2)');
                const statusText = statusCell.textContent.trim().toLowerCase();
                
                if (selectedStatus === 'all' || statusText === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Password strength indicator
    const passwordInputs = document.querySelectorAll('input[type="password"][name="password"], input[type="password"][name="new_password"]');
    
    passwordInputs.forEach(function(input) {
        // Create strength indicator
        const strengthIndicator = document.createElement('div');
        strengthIndicator.classList.add('password-strength');
        input.parentNode.insertBefore(strengthIndicator, input.nextSibling);
        
        input.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/\d/)) strength += 1;
            if (password.match(/[^a-zA-Z\d]/)) strength += 1;
            
            strengthIndicator.className = 'password-strength';
            
            switch (strength) {
                case 0:
                    strengthIndicator.classList.add('very-weak');
                    feedback = 'Very weak';
                    break;
                case 1:
                    strengthIndicator.classList.add('weak');
                    feedback = 'Weak';
                    break;
                case 2:
                    strengthIndicator.classList.add('medium');
                    feedback = 'Medium';
                    break;
                case 3:
                    strengthIndicator.classList.add('strong');
                    feedback = 'Strong';
                    break;
                case 4:
                    strengthIndicator.classList.add('very-strong');
                    feedback = 'Very strong';
                    break;
            }
            
            strengthIndicator.textContent = feedback;
        });
    });
});