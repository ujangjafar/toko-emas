import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Mobile menu toggle with Alpine (opsional, bisa juga pakai vanilla JS)
document.addEventListener('DOMContentLoaded', function() {
    // Close mobile menu on orientation change
    window.addEventListener('orientationchange', function() {
        if (window.innerWidth >= 768) {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.add('hidden');
        }
    });

    // Prevent body scroll when mobile menu is open
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', function() {
            const isOpen = !mobileMenu.classList.contains('hidden');
            document.body.style.overflow = isOpen ? '' : 'hidden';
        });

        // Close menu when clicking on a link
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    }
});