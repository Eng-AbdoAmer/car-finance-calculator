// public/js/navbar.js

// Navbar scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    
    if (navbar) {
        // Scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 80;
                    
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile navbar after click
                    const navbarCollapse = document.getElementById('navbarMain');
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        new bootstrap.Collapse(navbarCollapse).toggle();
                    }
                }
            });
        });
        
        // Update active nav link based on current page
        function updateActiveNav() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('#navbarMain .nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                // إذا كان الرابط يشبه المسار الحالي
                if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }
        
        updateActiveNav();
    }
});