import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.querySelector('[data-listings-filter-form]');
    const qInput = document.querySelector('[data-filter-q]');
    const cityInput = document.querySelector('[data-filter-city]');
    const sortSelect = document.querySelector('[data-filter-sort]');

    if (filterForm && qInput) {
        let searchTimeout;

        qInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => filterForm.submit(), 400);
        });

        if (cityInput) {
            cityInput.addEventListener('change', () => filterForm.submit());
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', () => filterForm.submit());
        }
    }

    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Approve this listing?')) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const reason = form.querySelector('input[name=rejection_reason]')?.value.trim();
            if (!reason) {
                return;
            }

            if (!confirm('Reject this listing with this reason?')) {
                e.preventDefault();
            }
        });
    });
});

