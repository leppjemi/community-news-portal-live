import './bootstrap';
import { initPasswordStrengthChecker } from './password-strength-checker';
import { initEmailAvailabilityChecker } from './email-availability-checker';
import { initFormSubmissionHandler } from './form-submission-handler';
import { initWordCounter } from './word-counter';
import { initCurrentPasswordChecker } from './current-password-checker';

// Initialize password strength checker on page load
document.addEventListener('DOMContentLoaded', () => {
    initPasswordStrengthChecker();
    initEmailAvailabilityChecker();
    initFormSubmissionHandler();
    initWordCounter();
    initCurrentPasswordChecker();
});
