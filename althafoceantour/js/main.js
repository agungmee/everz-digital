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
    fetch(section.file)
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
  });

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

      // Open by default after 2 seconds to attract attention
      setTimeout(() => {
        waChatBox.classList.add("active");
      }, 2000);
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
    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      mainNav.classList.toggle("active");
      
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
        document.body.style.overflow = "auto";
      });
    });
  }

  if (header) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        header.classList.add("scrolled");
      } else {
        header.classList.remove("scrolled");
      }
    });
  }
});
