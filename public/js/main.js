/**
 * main.js
 *
 * Handles client-side interactivity, confirmation prompts, and basic validations.
 *
 * @package BookingSystem\Public\Js
 * @author  Maseko S.
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', () => {
    // Confirm before cancelling a booking
    const cancelButtons = document.querySelectorAll('.btn-cancel');
    cancelButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to cancel this booking?')) {
                e.preventDefault();
            }
        });
    });

    // Optional: Dynamic minimum date enforcement if HTML5 min attribute isn't supported
    const dateInput = document.getElementById('booking_date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
});