document.addEventListener('DOMContentLoaded', () => {
    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.add('scrolled'); // wait, let's keep it glassy always or remove class
            navbar.classList.remove('scrolled');
        }
    });

    // Run once on load just in case page is loaded not at top
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    }

    // Mobile Navigation Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    const mobileLinks = mobileNav.querySelectorAll('a');

    mobileMenuBtn.addEventListener('click', () => {
        mobileNav.classList.add('active');
    });

    closeMenuBtn.addEventListener('click', () => {
        mobileNav.classList.remove('active');
    });

    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileNav.classList.remove('active');
        });
    });

    // Intersection Observer for Reveal Animations
    const revealElements = document.querySelectorAll('.reveal');

    const revealOptions = {
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealOnScroll = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                return;
            } else {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, revealOptions);

    revealElements.forEach(el => {
        revealOnScroll.observe(el);
    });

    // Custom Slider Engine
    function setupCarousel(trackId, prevBtnId, nextBtnId) {
        const track = document.getElementById(trackId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);
        if (!track) return;

        const items = Array.from(track.querySelectorAll('.carousel-item'));
        
        const updateCenterItem = () => {
            const trackCenter = track.getBoundingClientRect().left + track.clientWidth / 2;
            let closestItem = null;
            let minDistance = Infinity;

            items.forEach(item => {
                const itemCenter = item.getBoundingClientRect().left + item.clientWidth / 2;
                const distance = Math.abs(trackCenter - itemCenter);
                
                if (distance < minDistance) {
                    minDistance = distance;
                    closestItem = item;
                }
                item.classList.remove('active');
            });

            if (closestItem) {
                closestItem.classList.add('active');
            }
        };

        track.addEventListener('scroll', () => {
            requestAnimationFrame(updateCenterItem);
        });
        
        // Timeout to ensure layout is calculated before initial run
        setTimeout(updateCenterItem, 300);
        window.addEventListener('resize', () => requestAnimationFrame(updateCenterItem));

        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                const itemWidth = items[0].clientWidth + 32; // Assuming gap is 2rem = 32px
                track.scrollBy({ left: -itemWidth, behavior: 'smooth' });
            });

            nextBtn.addEventListener('click', () => {
                const itemWidth = items[0].clientWidth + 32;
                track.scrollBy({ left: itemWidth, behavior: 'smooth' });
            });
        }
    }

    setupCarousel('galleryTrack', 'galPrev', 'galNext');
    setupCarousel('testimonialTrack', 'testiPrev', 'testiNext');
});
