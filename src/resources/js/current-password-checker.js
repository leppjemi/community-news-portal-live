/**
 * Current Password Checker
 * Provides real-time validation for the current password field
 */

export function initCurrentPasswordChecker() {
    const currentPasswordInput = document.getElementById('current-password-input');

    // Exit if current password input doesn't exist on this page
    if (!currentPasswordInput) return;

    const feedbackContainer = document.getElementById('current-password-feedback');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let debounceTimer;

    currentPasswordInput.addEventListener('input', function () {
        const password = this.value;

        // Clear previous timer
        clearTimeout(debounceTimer);

        // Reset feedback if empty
        if (password.length === 0) {
            hideFeedback();
            return;
        }

        // Show loading state
        showLoading();

        // Debounce API call
        debounceTimer = setTimeout(() => {
            checkPassword(password);
        }, 500);
    });

    function checkPassword(password) {
        fetch('/api/check-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.valid) {
                    showSuccess();
                } else {
                    showError();
                }
            })
            .catch(error => {
                console.error('Error checking password:', error);
                hideFeedback();
            });
    }

    function showLoading() {
        if (!feedbackContainer) return;
        feedbackContainer.innerHTML = '<span class="loading loading-spinner loading-xs text-primary"></span>';
        feedbackContainer.classList.remove('hidden');
    }

    function showSuccess() {
        if (!feedbackContainer) return;
        feedbackContainer.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-xs text-success ml-1">Password correct</span>
        `;
        feedbackContainer.classList.remove('hidden');
        currentPasswordInput.classList.remove('input-error');
        currentPasswordInput.classList.add('input-success');
    }

    function showError() {
        if (!feedbackContainer) return;
        feedbackContainer.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="text-xs text-error ml-1">Password incorrect</span>
        `;
        feedbackContainer.classList.remove('hidden');
        currentPasswordInput.classList.remove('input-success');
        currentPasswordInput.classList.add('input-error');
    }

    function hideFeedback() {
        if (!feedbackContainer) return;
        feedbackContainer.classList.add('hidden');
        feedbackContainer.innerHTML = '';
        currentPasswordInput.classList.remove('input-success', 'input-error');
    }
}
