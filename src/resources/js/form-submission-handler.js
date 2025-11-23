/**
 * Form Submission Handler
 * Shows loading state during form submission
 */

export function initFormSubmissionHandler() {
    const registerForm = document.querySelector('form[action*="register"]');

    // Exit if registration form doesn't exist on this page
    if (!registerForm) return;

    const submitBtn = document.getElementById('register-submit-btn');
    const registerIcon = document.getElementById('register-icon');
    const registerSpinner = document.getElementById('register-spinner');
    const btnText = document.getElementById('register-btn-text');

    registerForm.addEventListener('submit', function (e) {
        // Show loading state
        if (submitBtn && registerIcon && registerSpinner && btnText) {
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            // Hide normal icon, show spinner
            registerIcon.classList.add('hidden');
            registerSpinner.classList.remove('hidden');

            // Update button text
            btnText.textContent = 'Creating Account...';
        }

        // Form will submit normally, loading state persists until page redirect
    });
}
