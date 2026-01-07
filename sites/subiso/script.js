// Navbar Scroll Effect
window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Slider Functionality
let slideIndex = 0;
let slideInterval;

function showSlides() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    
    // Remove active class from all slides and dots
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    // Increment slide index
    slideIndex++;
    if (slideIndex >= slides.length) {
        slideIndex = 0;
    }
    
    // Add active class to current slide and dot
    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
}

// Auto slide every 5 seconds
function startSlider() {
    slideInterval = setInterval(showSlides, 2000);
}

// Manual slide control
function currentSlide(n) {
    clearInterval(slideInterval);
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    slideIndex = n;
    slides[slideIndex].classList.add('active');
    dots[slideIndex].classList.add('active');
    
    startSlider();
}

// Mobile Menu Toggle
function toggleMenu() {
    const navLinks = document.getElementById('navLinks');
    const toggleButton = document.querySelector('.menu-toggle');
    navLinks.classList.toggle('active');
    toggleButton.classList.toggle('active');
    document.body.classList.toggle('nav-open');
}

// Close mobile menu when clicking on a link
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        const navLinks = document.getElementById('navLinks');
        navLinks.classList.remove('active');
        document.querySelector('.menu-toggle').classList.remove('active');
        document.body.classList.remove('nav-open');
    });
});

// Initialize slider when page loads
window.addEventListener('load', () => {
    startSlider();
});

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Reveal on scroll (replay when scrolling up/down)
document.addEventListener('DOMContentLoaded', () => {
  const revealItems = document.querySelectorAll('.reveal-item');
  if (!revealItems.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
      } else {
        entry.target.classList.remove('is-visible');
      }
    });
  }, { threshold: 0.2 });

  revealItems.forEach(item => observer.observe(item));
});

// OFFSET Image Slider
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.offset-slide');
    if (!slides.length) return;

    let index = 0;
    slides[0].classList.add('active');

    setInterval(() => {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
    }, 4000);
});

// SERVICES IMAGE SLIDER
document.addEventListener('DOMContentLoaded', () => {
  const serviceSlides = document.querySelectorAll('.services-slide');
  if (!serviceSlides.length) return;

  let current = 0;
  serviceSlides[current].classList.add('active');

  setInterval(() => {
    serviceSlides[current].classList.remove('active');
    current = (current + 1) % serviceSlides.length;
    serviceSlides[current].classList.add('active');
  }, 3500);
});

// COMPONENT SERVICE SLIDER
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.component-card');
  let index = 0;

  if (!cards.length) return;

  setInterval(() => {
    cards[index].classList.remove('active');
    index = (index + 1) % cards.length;
    cards[index].classList.add('active');
  }, 3500);
});

// MACHINE SERVICE SELECTOR - INTERACTIVE SELECTION
document.addEventListener('DOMContentLoaded', () => {
  const machineItems = document.querySelectorAll('.machine-item');
  const machineImage = document.getElementById('machineImage');
  const waButton = document.getElementById('waButton');

  // Check if elements exist
  if (!machineItems.length || !machineImage || !waButton) return;

  machineItems.forEach(item => {
    item.addEventListener('click', () => {
      // Remove active class from all items
      machineItems.forEach(i => i.classList.remove('active'));
      
      // Add active class to clicked item
      item.classList.add('active');
      
      // Get data from clicked item
      const serviceName = item.getAttribute('data-service');
      const servicePrice = item.getAttribute('data-price');
      const serviceImage = item.getAttribute('data-image');
      
      // Change image with fade effect
      machineImage.style.opacity = '0';
      setTimeout(() => {
        machineImage.src = serviceImage;
        machineImage.style.opacity = '1';
      }, 300);
      
      // Update WhatsApp link with selected service
      const waMessage = `Halo SUBISO, saya tertarik dengan paket ${serviceName} (Mulai dari ${servicePrice})`;
      const encodedMessage = encodeURIComponent(waMessage);
      waButton.href = `https://wa.me/6285183137230?text=${encodedMessage}`;
    });
  });
});
