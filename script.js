document.addEventListener('DOMContentLoaded', () => {
    // Responsive Navigation Menu
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        menuToggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                navLinks.classList.toggle('active');
            }
        });
    }

    // Search Bar Functionality
    const searchInput = document.querySelector('.search-bar input');
    const searchButton = document.querySelector('.search-bar button');

    if (searchButton && searchInput && propertyCards.length > 0) {
        searchButton.addEventListener('click', () => {
            const searchQuery = searchInput.value.toLowerCase();
            propertyCards.forEach(card => {
                const location = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
                if (location.includes(searchQuery)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Back to Top Button (Optional)
    const backToTopButton = document.createElement('button');
    backToTopButton.textContent = '⬆ Back to Top';
    backToTopButton.classList.add('back-to-top');
    document.body.appendChild(backToTopButton);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.style.display = 'none';
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
