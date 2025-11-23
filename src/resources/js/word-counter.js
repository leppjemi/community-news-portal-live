/**
 * Word Counter for Content Textarea
 * Displays real-time word and character count
 */

export function initWordCounter() {
    const textarea = document.getElementById('content-textarea');
    const wordCountEl = document.getElementById('word-count');
    const charCountEl = document.getElementById('char-count');

    // Exit if elements don't exist on this page
    if (!textarea || !wordCountEl || !charCountEl) return;

    function updateCounts() {
        const text = textarea.value.trim();

        // Count characters
        const charCount = textarea.value.length;

        // Count words - split by whitespace and filter empty strings
        const wordCount = text.length === 0 ? 0 : text.split(/\s+/).filter(word => word.length > 0).length;

        // Update display
        wordCountEl.textContent = wordCount;
        charCountEl.textContent = charCount;
    }

    // Update on input
    textarea.addEventListener('input', updateCounts);

    // Initial count (for edit mode where content may be pre-filled)
    updateCounts();
}
