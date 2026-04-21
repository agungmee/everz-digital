document.addEventListener("DOMContentLoaded", () => {
  const sections = [
    { id: "hero-placeholder", file: "sections/hero.html" },
    { id: "destinations-placeholder", file: "sections/destinations.html" },
    { id: "about-placeholder", file: "sections/about.html" },
    { id: "packages-placeholder", file: "sections/packages.html" },
    { id: "jetski-placeholder", file: "sections/jetski.html" },
    { id: "features-placeholder", file: "sections/features.html" },
    { id: "gallery-placeholder", file: "sections/gallery.html" },
    { id: "testimonials-placeholder", file: "sections/testimonials.html" },
    { id: "blog-placeholder", file: "sections/blog.html" },
    { id: "maps-placeholder", file: "sections/maps.html" },
    { id: "footer-placeholder", file: "sections/footer.html" }
  ];

  // Load all sections
  Promise.all(sections.map(section => 
    fetch(`${section.file}?v=${new Date().getTime()}`)
      .then(response => {
        if (!response.ok) throw new Error(`Failed to load ${section.file}`);
        return response.text();
      })
      .then(html => {
        const el = document.getElementById(section.id);
        if (el) el.innerHTML = html;
      })
      .catch(err => console.error(err))
  )).then(() => {
    console.log("All sections loaded.");
    initializeWhatsApp();
    initializeJetski();
    initializeDestSlider();
    initializeGallerySlider();
  });

  // Gallery Slider Logic
  function initializeGallerySlider() {
    new Swiper(".gallery-slider", {
      slidesPerView: 1.2,
      spaceBetween: 15,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        480: {
          slidesPerView: 2,
          spaceBetween: 15,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
      },
    });
  }

  // Destinations Slider Logic
  function initializeDestSlider() {
    new Swiper(".dest-slider", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      centeredSlides: false,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },
    });
  }

  // Jetski Selection Logic
  function initializeJetski() {
    const jetskiItems = document.querySelectorAll(".jetski-item");
    const pesanBtn = document.getElementById("btn-pesan-jetski");

    if (jetskiItems.length > 0 && pesanBtn) {
      jetskiItems.forEach(item => {
        item.addEventListener("click", () => {
          // Remove selected class from all items
          jetskiItems.forEach(i => i.classList.remove("selected"));
          
          // Add selected class to clicked item
          item.classList.add("selected");

          // Update WhatsApp link
          const duration = item.getAttribute("data-duration");
          const baseUrl = "https://wa.me/6287718031430";
          const message = `Halo Althaf Ocean Tour, saya ingin pesan paket Jetski dengan durasi *${duration}*.`;
          pesanBtn.href = `${baseUrl}?text=${encodeURIComponent(message)}`;
          
          // Smooth scroll to button (optional but helpful on mobile)
          if (window.innerWidth < 768) {
              pesanBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        });
      });
    }
  }

  // WhatsApp Widget Logic
  function initializeWhatsApp() {
    const waToggle = document.getElementById("wa-toggle-btn");
    const waChatBox = document.getElementById("wa-chat-box");
    const waClose = document.getElementById("wa-close-chat");
    const waSend = document.getElementById("wa-send-btn");
    const waInput = document.getElementById("wa-message-input");

    if (waToggle && waChatBox) {
      waToggle.addEventListener("click", () => {
        waChatBox.classList.toggle("active");
      });
    }

    if (waClose) {
      waClose.addEventListener("click", () => {
        waChatBox.classList.remove("active");
      });
    }

    if (waSend && waInput) {
      const sendMessage = () => {
        const message = waInput.value.trim();
        if (message) {
          const encodedMessage = encodeURIComponent(message);
          window.open(`https://wa.me/6287718031430?text=${encodedMessage}`, "_blank");
          waInput.value = "";
          waChatBox.classList.remove("active");
        }
      };

      waSend.addEventListener("click", sendMessage);
      waInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
          e.preventDefault();
          sendMessage();
        }
      });
    }
  }

  // Sticky Navbar Logic
  const header = document.querySelector(".draft-header");
  const menuToggle = document.getElementById("menu-toggle");
  const mainNav = document.getElementById("main-nav");

  if (menuToggle && mainNav) {
    const waWidget = document.querySelector(".wa-widget-container");
    
    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      mainNav.classList.toggle("active");
      
      // Hide WhatsApp widget when menu is open
      if (waWidget) {
        waWidget.classList.toggle("hide-on-menu");
      }
      
      // Prevent scrolling when menu is open
      if (mainNav.classList.contains("active")) {
        document.body.style.overflow = "hidden";
      } else {
        document.body.style.overflow = "auto";
      }
    });

    // Close menu when a link is clicked
    const navLinks = mainNav.querySelectorAll("a");
    navLinks.forEach(link => {
      link.addEventListener("click", () => {
        menuToggle.classList.remove("active");
        mainNav.classList.remove("active");
        if (waWidget) waWidget.classList.remove("hide-on-menu");
        document.body.style.overflow = "auto";
      });
    });
  }

  if (header && !header.classList.contains("header-solid")) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }
    });
  }
});
