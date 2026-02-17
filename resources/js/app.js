import './bootstrap';

// JS-1: Debounced auto-submit on public listing filters
let searchTimeout;

const qInput = document.querySelector('[name=q]');
const cityInput = document.querySelector('[name=city]');
const sortSelect = document.querySelector('[name=sort]');

if (qInput) {
    const form = qInput.form;
    
    // Debounced search on keyword input (400ms)
    qInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => form.submit(), 400);
    });

    // Immediate submit on city/sort changes
    if (cityInput) {
        cityInput.addEventListener('change', () => form.submit());
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', () => form.submit());
    }
}

// JS-2: Confirm dialogs for admin moderation actions
document.querySelectorAll('.approve-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('Approve this listing?')) {
            e.preventDefault();
        }
    });
});

document.querySelectorAll('.reject-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const reason = form.querySelector('input[name=rejection_reason]')?.value.trim();
        if (!reason) {
            e.preventDefault();
            alert('Please provide a rejection reason');
            return;
        }
        if (!confirm('Reject this listing with the provided reason?')) {
            e.preventDefault();
        }
    });
});

