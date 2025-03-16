document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    const html = document.documentElement;
    
    if (savedTheme) {
        html.className = savedTheme;
    }
    
    // Theme toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    const themeToggleMobile = document.getElementById('theme-toggle-mobile');
    
    function toggleTheme() {
        if (html.classList.contains('light-mode')) {
            html.classList.remove('light-mode');
            html.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark-mode');
        } else {
            html.classList.remove('dark-mode');
            html.classList.add('light-mode');
            localStorage.setItem('theme', 'light-mode');
        }
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    if (themeToggleMobile) {
        themeToggleMobile.addEventListener('click', toggleTheme);
    }
    
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('nav');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }
    
    // Close menu when clicking on a link
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            nav.classList.remove('active');
        });
    });
    
    // Blog Post Modal
    const modal = document.getElementById('blogModal');
    const modalContent = document.getElementById('modalContent');
    const closeBtn = document.querySelector('.close');
    const readMoreBtns = document.querySelectorAll('.read-more');
    
    if (modal && closeBtn) {
        // Close modal when clicking the X
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Open modal with blog post content
        readMoreBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.getAttribute('data-id');
                
                // Fetch blog post content using AJAX
                fetch(`get_post.php?id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalContent.innerHTML = `
                                <h2>${data.title}</h2>
                                <p class="post-date">${data.date}</p>
                                <div class="post-content">${data.content}</div>
                            `;
                            modal.style.display = 'block';
                        } else {
                            alert('Error loading post');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading post');
                    });
            });
        });
    }
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.getAttribute('href').length > 1) { // Ignore empty # links
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // Add active class to nav links based on scroll position
    const sections = document.querySelectorAll('section');
    
    function highlightNavLink() {
        const scrollPosition = window.scrollY;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                document.querySelector(`nav ul li a[href="#${sectionId}"]`)?.classList.add('active');
            } else {
                document.querySelector(`nav ul li a[href="#${sectionId}"]`)?.classList.remove('active');
            }
        });
    }
    
    window.addEventListener('scroll', highlightNavLink);
});