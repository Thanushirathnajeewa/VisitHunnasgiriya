
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const nav = document.querySelector('[data-nav]');
    if (menuToggle && nav) {
        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', nav.classList.contains('open'));
        });
    }
    
function showHidePassword(inputId, buttonId) {

    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);

    if (input.type === "password") {
        input.type = "text";
        button.innerHTML = "🙈";
    } else {
        input.type = "password";
        button.innerHTML = "👁️";
    }
}


    const searchInput = document.querySelector('#attractionSearch');
    const categoryFilter = document.querySelector('#categoryFilter');
    const cards = Array.from(document.querySelectorAll('[data-attraction-card]'));
    const noResults = document.querySelector('#noResults');

    function filterAttractions() {
        if (!cards.length) return;
        const term = (searchInput?.value || '').trim().toLowerCase();
        const category = (categoryFilter?.value || '').toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const cardCategory = (card.dataset.category || '').toLowerCase();
            const matchesTerm = !term || name.includes(term) ||
                card.textContent.toLowerCase().includes(term);
            const matchesCategory = !category || cardCategory === category;
            const show = matchesTerm && matchesCategory;
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResults) noResults.hidden = visible !== 0;
    }

    searchInput?.addEventListener('input', filterAttractions);
    categoryFilter?.addEventListener('change', filterAttractions);

    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', function (event) {
            let valid = true;
            form.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('input-error');
                    valid = false;
                } else {
                    field.classList.remove('input-error');
                }
            });

            if (!valid) {
                event.preventDefault();
                form.querySelector('.input-error')?.focus();
                alert('Please complete all required fields.');
            }
        });
    });

    document.querySelectorAll('[data-confirm]').forEach(link => {
        link.addEventListener('click', function (event) {
            if (!window.confirm(this.dataset.confirm || 'Are you sure?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-gallery-thumb]').forEach(thumb => {
        thumb.addEventListener('click', function () {
            const main = document.querySelector('#mainGalleryImage');
            if (main && this.dataset.image) {
                main.src = this.dataset.image;
                main.alt = this.alt || 'Attraction image';
            }
        });
    });
});
