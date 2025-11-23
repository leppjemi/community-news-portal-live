/**
 * Email Availability Checker
 * Checks if email is already registered in the database
 */

let emailCheckTimeout = null;

export function initEmailAvailabilityChecker() {
    const emailInput = document.getElementById('email-input');

    // Exit if email input doesn't exist on this page
    if (!emailInput) return;

    const availabilityIndicator = document.getElementById('email-availability-indicator');

    // Email availability checker with debouncing
    emailInput.addEventListener('input', function () {
        const email = this.value.trim();

        // Clear previous timeout
        if (emailCheckTimeout) {
            clearTimeout(emailCheckTimeout);
        }

        // Hide indicator if email is empty or invalid format
        if (email.length === 0 || !isValidEmail(email)) {
            availabilityIndicator.style.display = 'none';
            return;
        }

        // Show loading state
        showLoadingState();

        // Debounce the API call (wait 500ms after user stops typing)
        emailCheckTimeout = setTimeout(() => {
            checkEmailAvailability(email);
        }, 500);
    });

    function isValidEmail(email) {
        // Basic email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showLoadingState() {
        availabilityIndicator.style.display = 'block';
        const svg = availabilityIndicator.querySelector('svg');
        const span = availabilityIndicator.querySelector('span');

        // Show loading spinner
        svg.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
        svg.classList.remove('text-success', 'text-error');
        svg.classList.add('text-base-content/50', 'animate-spin');
        span.textContent = 'Checking availability...';
        span.classList.remove('text-success', 'text-error');
        span.classList.add('text-base-content/50');
    }

    async function checkEmailAvailability(email) {
        try {
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch('/api/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            const svg = availabilityIndicator.querySelector('svg');
            const span = availabilityIndicator.querySelector('span');

            // Remove loading state
            svg.classList.remove('animate-spin', 'text-base-content/50');

            if (data.available) {
                // Email is available
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                svg.classList.remove('text-error');
                svg.classList.add('text-success');
                span.textContent = 'Email is available';
                span.classList.remove('text-error');
                span.classList.add('text-success');
            } else {
                // Email is already taken
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                svg.classList.remove('text-success');
                svg.classList.add('text-error');
                span.textContent = 'Email is already taken';
                span.classList.remove('text-success');
                span.classList.add('text-error');
            }
        } catch (error) {
            console.error('Error checking email availability:', error);

            // Hide indicator on error
            availabilityIndicator.style.display = 'none';
        }
    }
}
