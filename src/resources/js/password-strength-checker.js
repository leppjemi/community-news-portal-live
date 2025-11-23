/**
 * Password Strength Checker
 * Provides real-time password strength validation and visual feedback
 */

export function initPasswordStrengthChecker() {
    const passwordInput = document.getElementById('password-input');

    // Exit if password input doesn't exist on this page
    if (!passwordInput) return;

    const strengthContainer = document.getElementById('password-strength-container');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const togglePassword = document.getElementById('toggle-password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    // Password confirmation elements
    const confirmPasswordInput = document.getElementById('password-confirmation-input');
    const matchIndicator = document.getElementById('password-match-indicator');

    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');

    // Toggle password visibility
    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    });

    // Password strength checker
    passwordInput.addEventListener('input', function () {
        const password = this.value;

        if (password.length === 0) {
            strengthContainer.style.display = 'none';
            resetRequirements();
            checkPasswordMatch(); // Also check match when password changes
            return;
        }

        strengthContainer.style.display = 'block';

        // Check requirements
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

        // Update requirement indicators
        updateRequirement(reqLength, hasLength);
        updateRequirement(reqUppercase, hasUppercase);
        updateRequirement(reqLowercase, hasLowercase);
        updateRequirement(reqNumber, hasNumber);
        updateRequirement(reqSpecial, hasSpecial);

        // Calculate strength
        let strength = 0;
        if (hasLength) strength += 20;
        if (hasUppercase) strength += 20;
        if (hasLowercase) strength += 20;
        if (hasNumber) strength += 20;
        if (hasSpecial) strength += 20;

        // Update strength bar
        strengthBar.style.width = strength + '%';

        // Update color and text based on strength
        if (strength <= 40) {
            strengthBar.className = 'h-full transition-all duration-300 bg-error';
            strengthText.textContent = 'Weak';
            strengthText.className = 'text-xs font-medium text-error';
        } else if (strength <= 80) {
            strengthBar.className = 'h-full transition-all duration-300 bg-warning';
            strengthText.textContent = 'Medium';
            strengthText.className = 'text-xs font-medium text-warning';
        } else {
            strengthBar.className = 'h-full transition-all duration-300 bg-success';
            strengthText.textContent = 'Strong';
            strengthText.className = 'text-xs font-medium text-success';
        }

        // Check password match when password changes
        checkPasswordMatch();
    });

    // Password confirmation checker
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    function checkPasswordMatch() {
        if (!confirmPasswordInput || !matchIndicator) return;

        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        // Don't show indicator if confirm password is empty
        if (confirmPassword.length === 0) {
            matchIndicator.style.display = 'none';
            return;
        }

        matchIndicator.style.display = 'block';
        const svg = matchIndicator.querySelector('svg');
        const span = matchIndicator.querySelector('span');

        if (password === confirmPassword && password.length > 0) {
            // Passwords match
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            svg.classList.remove('text-error');
            svg.classList.add('text-success');
            span.textContent = 'Passwords match';
            span.classList.remove('text-error');
            span.classList.add('text-success');
        } else {
            // Passwords don't match
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            svg.classList.remove('text-success');
            svg.classList.add('text-error');
            span.textContent = 'Passwords do not match';
            span.classList.remove('text-success');
            span.classList.add('text-error');
        }
    }

    function updateRequirement(element, isMet) {
        const svg = element.querySelector('svg');
        const span = element.querySelector('span');

        if (isMet) {
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            svg.classList.remove('text-base-content/30');
            svg.classList.add('text-success');
            span.classList.remove('text-base-content/50');
            span.classList.add('text-success');
        } else {
            svg.innerHTML = '<circle cx="12" cy="12" r="10" stroke-width="2"/>';
            svg.classList.remove('text-success');
            svg.classList.add('text-base-content/30');
            span.classList.remove('text-success');
            span.classList.add('text-base-content/50');
        }
    }

    function resetRequirements() {
        updateRequirement(reqLength, false);
        updateRequirement(reqUppercase, false);
        updateRequirement(reqLowercase, false);
        updateRequirement(reqNumber, false);
        updateRequirement(reqSpecial, false);
    }
}
