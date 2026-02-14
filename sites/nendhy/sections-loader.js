// Load sections dynamically
async function loadSections() {
    const sections = [
      { id: 'hero-placeholder', file: 'sections/hero.html' },
      { id: 'motorbike-placeholder', file: 'sections/motorbike.html' },
      { id: 'tour-activity-placeholder', file: 'sections/tour-activity.html' },
      { id: 'services-placeholder', file: 'sections/services.html' },
      { id: 'about-placeholder', file: 'sections/about.html' },
      { id: 'why-placeholder', file: 'sections/why-choose.html' },
      { id: 'contact-placeholder', file: 'sections/contact.html' },
      { id: 'testimonials-placeholder', file: 'sections/testimonials.html' },
      { id: 'footer-placeholder', file: 'sections/footer.html' },
      { id: 'modals-placeholder', file: 'sections/modals.html' }
    ];

    for (const section of sections) {
      const placeholder = document.getElementById(section.id);
      if (!placeholder) continue;

      try {
        const response = await fetch(section.file);
        if (!response.ok) throw new Error(`Failed to load ${section.file}`);
        const html = await response.text();
        placeholder.outerHTML = html;
      } catch (error) {
        console.error(`Error loading ${section.file}:`, error);
        placeholder.innerHTML = `<p style="color: red;">Error loading section</p>`;
      }
    }

    // Run scripts after sections are loaded
    initializeScripts();
  }

  // Initialize all functionality after sections are loaded
  function initializeScripts() {
    // Hero slider
    (function(){
      const slides = Array.from(document.querySelectorAll('.aha-slide'));
      const dots = Array.from(document.querySelectorAll('.aha-dot'));
      if(slides.length <= 1) return;

      let idx = 0;
      const INTERVAL = 5200;

      function render(){
        slides.forEach((slide,i)=> slide.classList.toggle('active', i===idx));
        dots.forEach((dot,i)=> dot.classList.toggle('active', i===idx));
      }

      setInterval(function(){
        idx = (idx + 1) % slides.length;
        render();
      }, INTERVAL);
    })();

    // About slider
    (function(){
      const aboutSlides = Array.from(document.querySelectorAll('.aha-about-slide'));
      if(aboutSlides.length <= 1) return;

      let idx = 0;
      const INTERVAL = 4200;

      setInterval(function(){
        aboutSlides[idx].classList.remove('active');
        idx = (idx + 1) % aboutSlides.length;
        aboutSlides[idx].classList.add('active');
      }, INTERVAL);
    })();

    // Navigation scroll effect
    (function(){
      const nav = document.querySelector('.aha-nav');
      if(!nav) return;

      function getScrollTop(){
        return Math.max(
          window.pageYOffset || 0,
          document.documentElement.scrollTop || 0,
          document.body.scrollTop || 0
        );
      }

      function applyState(isScrolled){
        nav.classList.toggle('is-scrolled', isScrolled);
        nav.style.background = isScrolled ? "#ffffff" : "rgba(10,18,28,.02)";
        nav.style.boxShadow = isScrolled ? "0 18px 40px rgba(15,23,42,.12)" : "none";
      }

      let last = -1;
      function tick(){
        const current = getScrollTop();
        if(current !== last){
          applyState(current > 2);
          last = current;
        }
        requestAnimationFrame(tick);
      }
      applyState(getScrollTop() > 2);
      requestAnimationFrame(tick);
    })();

    function initTourActivityCardSlides(scope = document){
      const mediaEls = Array.from(scope.querySelectorAll('.tour-activity-media[data-autoslide="true"]'));
      mediaEls.forEach((media)=>{
        if(media.dataset.sliderReady === '1') return;
        const slides = Array.from(media.querySelectorAll('img'));
        if(slides.length <= 1){
          media.dataset.sliderReady = '1';
          return;
        }

        let idx = slides.findIndex((slide)=> slide.classList.contains('is-active'));
        if(idx < 0) idx = 0;
        slides.forEach((slide, i)=> slide.classList.toggle('is-active', i === idx));

        const configured = Number.parseInt(media.dataset.interval || '', 10);
        const delay = Number.isFinite(configured) && configured > 0 ? configured : 3000;
        media.dataset.sliderReady = '1';

        setInterval(()=>{
          slides[idx].classList.remove('is-active');
          idx = (idx + 1) % slides.length;
          slides[idx].classList.add('is-active');
        }, delay);
      });
    }

    // Bike carousel (infinite loop) - clones + native swipe
    (function(){
      const carousel = document.getElementById('bikesCarousel');
      if(!carousel) return;
      const track = carousel.querySelector('.bikes-track');
      if(!track) return;

      // build clones for infinite loop
      const originals = Array.from(track.children);
      if(!originals.length) return;
      const baseCount = originals.length;

      // avoid double-initializing
      if(track.getAttribute('data-cloned') === '1') return;

      originals.forEach(node => {
        const c = node.cloneNode(true);
        c.classList.add('clone');
        track.appendChild(c);
      });
      // prepend clones (clone in reverse to preserve order)
      for(let i = originals.length - 1; i >= 0; i--){
        const c = originals[i].cloneNode(true);
        c.classList.add('clone');
        track.insertBefore(c, track.firstChild);
      }
      track.setAttribute('data-cloned', '1');

      function cardWidth(){
        const card = track.querySelector('.bike-card');
        if(!card) return 280;
        const gap = parseFloat(getComputedStyle(track).gap || 20);
        return card.getBoundingClientRect().width + gap;
      }

      function setInitial(){
        const cw = cardWidth();
        if(!cw) return;
        // position at the first original item
        track.scrollLeft = cw * baseCount;
      }

      let isJumping = false;
      let scrollTimer = null;

      function checkLoop(){
        if(isJumping) return;
        const cw = cardWidth();
        if(!cw) return;
        const left = track.scrollLeft;
        const max = track.scrollWidth - track.clientWidth;

        if(left <= cw * 0.5){
          isJumping = true;
          track.scrollLeft = left + cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        } else if(left >= max - cw * 0.5){
          isJumping = true;
          track.scrollLeft = left - cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        }
      }

      // native scroll (swipe) handling - debounce then check loop
      track.addEventListener('scroll', ()=>{
        if(scrollTimer) clearTimeout(scrollTimer);
        scrollTimer = setTimeout(checkLoop, 80);
      }, {passive:true});

      // prev/next buttons scroll by one card
      const prev = carousel.querySelector('.bike-prev');
      const next = carousel.querySelector('.bike-next');
      function scrollBy(n){
        const cw = cardWidth();
        if(!cw) return;
        track.scrollTo({ left: track.scrollLeft + n * cw, behavior: 'smooth' });
      }
      if(prev) prev.addEventListener('click', ()=> scrollBy(-1));
      if(next) next.addEventListener('click', ()=> scrollBy(1));

      // set initial after small delay (allow layout)
      setTimeout(setInitial, 60);
      window.addEventListener('resize', setInitial);
    })();

    // Tour activity carousel (infinite loop) - clones + native swipe
    (function(){
      const carousel = document.getElementById('tourActivityCarousel');
      if(!carousel) return;
      const track = carousel.querySelector('.tour-activity-track');
      if(!track) return;

      const originals = Array.from(track.children);
      if(!originals.length) return;
      const baseCount = originals.length;

      if(track.getAttribute('data-cloned') === '1') return;

      originals.forEach((node) => {
        const c = node.cloneNode(true);
        c.classList.add('clone');
        track.appendChild(c);
      });
      for(let i = originals.length - 1; i >= 0; i--){
        const c = originals[i].cloneNode(true);
        c.classList.add('clone');
        track.insertBefore(c, track.firstChild);
      }
      track.setAttribute('data-cloned', '1');

      function cardWidth(){
        const card = track.querySelector('.tour-activity-card');
        if(!card) return 280;
        const gap = parseFloat(getComputedStyle(track).gap || 18);
        return card.getBoundingClientRect().width + gap;
      }

      function setInitial(){
        const cw = cardWidth();
        if(!cw) return;
        track.scrollLeft = cw * baseCount;
      }

      let isJumping = false;
      let scrollTimer = null;

      function checkLoop(){
        if(isJumping) return;
        const cw = cardWidth();
        if(!cw) return;
        const left = track.scrollLeft;
        const max = track.scrollWidth - track.clientWidth;

        if(left <= cw * 0.5){
          isJumping = true;
          track.scrollLeft = left + cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        } else if(left >= max - cw * 0.5){
          isJumping = true;
          track.scrollLeft = left - cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        }
      }

      track.addEventListener('scroll', ()=>{
        if(scrollTimer) clearTimeout(scrollTimer);
        scrollTimer = setTimeout(checkLoop, 80);
      }, {passive:true});

      const prev = carousel.querySelector('.tour-activity-prev');
      const next = carousel.querySelector('.tour-activity-next');
      function scrollBy(n){
        const cw = cardWidth();
        if(!cw) return;
        track.scrollTo({ left: track.scrollLeft + n * cw, behavior: 'smooth' });
      }
      if(prev) prev.addEventListener('click', ()=> scrollBy(-1));
      if(next) next.addEventListener('click', ()=> scrollBy(1));

      setTimeout(setInitial, 60);
      window.addEventListener('resize', setInitial);

      initTourActivityCardSlides(track);
    })();

    // Services carousel (infinite loop) - clones + native swipe
    (function(){
      const carousel = document.getElementById('servicesCarousel');
      if(!carousel) return;
      const track = carousel.querySelector('.aha-services-track');
      if(!track) return;

      const originals = Array.from(track.children);
      if(!originals.length) return;
      const baseCount = originals.length;

      if(track.getAttribute('data-cloned') === '1') return;

      originals.forEach((node) => {
        const c = node.cloneNode(true);
        c.classList.add('clone');
        track.appendChild(c);
      });
      for(let i = originals.length - 1; i >= 0; i--){
        const c = originals[i].cloneNode(true);
        c.classList.add('clone');
        track.insertBefore(c, track.firstChild);
      }
      track.setAttribute('data-cloned', '1');

      function cardWidth(){
        const card = track.querySelector('.aha-service-card');
        if(!card) return 280;
        const gap = parseFloat(getComputedStyle(track).gap || 18);
        return card.getBoundingClientRect().width + gap;
      }

      function setInitial(){
        const cw = cardWidth();
        if(!cw) return;
        track.scrollLeft = cw * baseCount;
      }

      let isJumping = false;
      let scrollTimer = null;

      function checkLoop(){
        if(isJumping) return;
        const cw = cardWidth();
        if(!cw) return;
        const left = track.scrollLeft;
        const max = track.scrollWidth - track.clientWidth;

        if(left <= cw * 0.5){
          isJumping = true;
          track.scrollLeft = left + cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        } else if(left >= max - cw * 0.5){
          isJumping = true;
          track.scrollLeft = left - cw * baseCount;
          setTimeout(()=> isJumping = false, 60);
        }
      }

      track.addEventListener('scroll', ()=>{
        if(scrollTimer) clearTimeout(scrollTimer);
        scrollTimer = setTimeout(checkLoop, 80);
      }, {passive:true});

      const prev = carousel.querySelector('.aha-services-prev');
      const next = carousel.querySelector('.aha-services-next');
      function scrollBy(n){
        const cw = cardWidth();
        if(!cw) return;
        track.scrollTo({ left: track.scrollLeft + n * cw, behavior: 'smooth' });
      }
      if(prev) prev.addEventListener('click', ()=> scrollBy(-1));
      if(next) next.addEventListener('click', ()=> scrollBy(1));

      setTimeout(setInitial, 60);
      window.addEventListener('resize', setInitial);
    })();

    // Testimonial carousel
    (function(){
      const track = document.getElementById('ahaTestimonialTrack');
      const dotsWrap = document.getElementById('ahaTestimonialDots');
      if(!track || !dotsWrap) return;

      const cards = Array.from(track.querySelectorAll('.aha-testimonial-card'));
      const dots = Array.from(dotsWrap.querySelectorAll('.aha-testimonial-dot'));
      if(!cards.length || !dots.length) return;

      let idx = 0;
      let timer = null;

      function setActive(index){
        dots.forEach((dot, i)=> dot.classList.toggle('active', i === index));
      }

      function updateActive(){
        if(track.scrollWidth <= track.clientWidth) return;
        const trackRect = track.getBoundingClientRect();
        const trackCenter = trackRect.left + trackRect.width / 2;
        let closest = 0;
        let minDistance = Infinity;

        cards.forEach((card, i)=>{
          const rect = card.getBoundingClientRect();
          const cardCenter = rect.left + rect.width / 2;
          const distance = Math.abs(trackCenter - cardCenter);
          if(distance < minDistance){
            minDistance = distance;
            closest = i;
          }
        });
        idx = closest;
        setActive(closest);
      }

      function scrollToIndex(index){
        if(track.scrollWidth <= track.clientWidth) return;
        const card = cards[index];
        const left = card.offsetLeft - (track.clientWidth - card.clientWidth) / 2;
        track.scrollTo({left, behavior:'smooth'});
      }

      function startAuto(){
        if(track.scrollWidth <= track.clientWidth) return;
        if(timer) clearInterval(timer);
        timer = setInterval(()=>{
          idx = (idx + 1) % cards.length;
          scrollToIndex(idx);
          setActive(idx);
        }, 4200);
      }

      let rafId = null;
      track.addEventListener('scroll', ()=>{
        if(rafId) cancelAnimationFrame(rafId);
        rafId = requestAnimationFrame(updateActive);
      }, {passive:true});

      window.addEventListener('resize', ()=>{
        updateActive();
        startAuto();
      });

      updateActive();
      startAuto();
    })();

    // Mobile menu toggle
    (function(){
      const burger = document.querySelector('.aha-burger');
      const mobile = document.getElementById('ahaMobileMenu');
      if(!burger || !mobile) return;

      function openMobile(){
        mobile.classList.add('is-open');
        burger.classList.add('is-open');
        burger.setAttribute('aria-expanded', 'true');
        mobile.setAttribute('aria-hidden', 'false');
      }
      function closeMobile(){
        mobile.classList.remove('is-open');
        burger.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        mobile.setAttribute('aria-hidden', 'true');
      }

      burger.addEventListener('click', ()=>{
        if(mobile.classList.contains('is-open')){
          closeMobile();
        }else{
          openMobile();
        }
      });

      mobile.addEventListener('click', (e)=>{
        if(e.target.dataset.close){ closeMobile(); }
      });

      mobile.querySelectorAll('a').forEach((link)=>{
        link.addEventListener('click', closeMobile);
      });
    })();

    // Modal handlers
    (function(){
      const WA_NUMBER = "6283850102934";
      const modal = document.getElementById('ahaTransferModal');
      const form = document.getElementById('ahaTransferForm');
      const vehicleInput = document.getElementById('ahaTfVehicle');
      const fromInput = document.getElementById('ahaTfFrom');
      const toInput = document.getElementById('ahaTfTo');
      const dailyPackageInput = document.getElementById('ahaTfDailyPackage');
      const dateInput = document.getElementById('ahaTfDate');
      const timeInput = document.getElementById('ahaTfTime');
      const priceInput = document.getElementById('ahaTfPrice');
      const tabs = Array.from(document.querySelectorAll('.aha-book-tabs .aha-tab'));

      if(!modal || !form) return;

      function setMode(mode){
        modal.dataset.mode = mode;
        modal.classList.toggle('is-daily', mode === "daily");
        tabs.forEach((tab)=>{
          tab.classList.toggle('is-active', tab.dataset.mode === mode);
          tab.setAttribute('aria-selected', String(tab.dataset.mode === mode));
        });
      }

      function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }

      modal.addEventListener('click', (e)=>{
        if(e.target.dataset.close){ closeModal(); }
      });

      tabs.forEach((tab)=>{
        tab.addEventListener('click', ()=>{
          setMode(tab.dataset.mode || "transfer");
        });
      });

      form.addEventListener('submit', (e)=>{
        e.preventDefault();
        const name = document.getElementById('ahaTfName').value.trim();
        const phone = document.getElementById('ahaTfPhone').value.trim();
        const email = document.getElementById('ahaTfEmail').value.trim();
        const vehicle = vehicleInput.value.trim();
        const mode = modal.dataset.mode || "transfer";
        const from = fromInput.value.trim();
        const to = toInput.value.trim();
        const dailyPackage = dailyPackageInput ? dailyPackageInput.value : "";
        const date = dateInput ? dateInput.value : "";
        const time = timeInput ? timeInput.value : "";
        const price = priceInput.value.trim();
        const note = document.getElementById('ahaTfNote').value.trim();

        const header = mode === "daily"
          ? "Hello Nendhy Holiday Lombok Transport, I want to book a daily tour vehicle."
          : "Hello Nendhy Holiday Lombok Transport, I want to book an airport transfer.";
        const routeLines = mode === "daily"
          ? `Daily tour package: ${dailyPackage || "-"}`
          : `From: ${from || "-"}\nTo: ${to || "-"}`;
        const msg =
`${header}

Name: ${name}
Phone: ${phone}
Email: ${email}
Vehicle: ${vehicle || "-"}
${routeLines}
Booking date: ${date || "-"}
Estimated time: ${time || "-"}
Price: ${price}
Notes: ${note || "-"}
`;
        const url = "https://wa.me/" + WA_NUMBER + "?text=" + encodeURIComponent(msg);
        window.open(url, "_blank", "noopener,noreferrer");
        closeModal();
      });
    })();

    // Ensure booking buttons show the requested label
    (function(){
      // run after a short delay to allow sections HTML to be inserted
      setTimeout(()=>{
        document.querySelectorAll('.motor-book').forEach(el=> el.textContent = 'Book This Bike');
        document.querySelectorAll('.tour-activity-book').forEach(el=> el.textContent = 'Book This Tour');
        document.querySelectorAll('.aha-service-book').forEach(el=> el.textContent = 'More Info');
      }, 60);
    })();

    // Tour/Service inquiry modal before redirecting to WhatsApp
    (function(){
      const WA_NUMBER = "6283850102934";
      const modal = document.getElementById('ahaInquiryModal');
      const form = document.getElementById('ahaInquiryForm');
      if(!modal || !form) return;

      const titleEl = document.getElementById('ahaInquiryTitle');
      const categoryInput = document.getElementById('ahaInquiryCategory');
      const itemInput = document.getElementById('ahaInquiryItem');
      const nameInput = document.getElementById('ahaInquiryName');
      const phoneInput = document.getElementById('ahaInquiryPhone');
      const dateInput = document.getElementById('ahaInquiryDate');
      const paxInput = document.getElementById('ahaInquiryPax');
      const noteInput = document.getElementById('ahaInquiryNote');

      let currentKind = "tour";
      let currentItem = "";

      function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }

      function openModal(kind, itemName){
        currentKind = kind;
        currentItem = itemName;

        const isTour = kind === "tour";
        if(titleEl) titleEl.textContent = isTour ? "Book This Tour" : "Service Information Request";
        if(categoryInput) categoryInput.value = isTour ? "Tour" : "Service";
        if(itemInput) itemInput.value = itemName;

        form.reset();
        if(categoryInput) categoryInput.value = isTour ? "Tour" : "Service";
        if(itemInput) itemInput.value = itemName;
        if(paxInput) paxInput.value = "1";

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
      }

      document.addEventListener('click', (e)=>{
        const trigger = e.target.closest('.tour-activity-book, .aha-service-book');
        if(!trigger) return;

        e.preventDefault();

        const card = trigger.closest('.tour-activity-card, .aha-service-card');
        const title = card ? card.querySelector('h3') : null;
        const itemName = title ? title.textContent.trim() : "Selected item";
        const kind = trigger.classList.contains('tour-activity-book') ? "tour" : "service";

        openModal(kind, itemName);
      });

      modal.addEventListener('click', (e)=>{
        if(e.target.dataset.close){ closeModal(); }
      });

      form.addEventListener('submit', (e)=>{
        e.preventDefault();

        const isTour = currentKind === "tour";
        const name = nameInput ? nameInput.value.trim() : "";
        const phone = phoneInput ? phoneInput.value.trim() : "";
        const date = dateInput ? dateInput.value.trim() : "";
        const pax = paxInput ? paxInput.value.trim() : "";
        const note = noteInput ? noteInput.value.trim() : "";

        const header = isTour
          ? "Hello Nendhy Holiday Lombok Transport, I want to book this tour."
          : "Hello Nendhy Holiday Lombok Transport, I want more information about this service.";
        const msg =
`${header}

Category: ${isTour ? "Tour" : "Service"}
Item: ${currentItem || "-"}
Name: ${name || "-"}
WhatsApp: ${phone || "-"}
Preferred date: ${date || "-"}
Guests: ${pax || "-"}
Notes: ${note || "-"}
`;

        const url = "https://wa.me/" + WA_NUMBER + "?text=" + encodeURIComponent(msg);
        window.open(url, "_blank", "noopener,noreferrer");
        closeModal();
      });
    })();

    // Bike booking modal before redirecting to WhatsApp
    (function(){
      const WA_NUMBER = "6283850102934";
      const modal = document.getElementById('ahaBikeBookingModal');
      const form = document.getElementById('ahaBikeBookingForm');
      if(!modal || !form) return;

      const bikeModelInput = document.getElementById('ahaBikeModel');
      const nameInput = document.getElementById('ahaBikeName');
      const phoneInput = document.getElementById('ahaBikePhone');
      const startDateInput = document.getElementById('ahaBikeStartDate');
      const durationInput = document.getElementById('ahaBikeDuration');
      const pickupTimeInput = document.getElementById('ahaBikePickupTime');
      const pickupLocationInput = document.getElementById('ahaBikePickupLocation');
      const noteInput = document.getElementById('ahaBikeNote');

      let currentBike = "";

      function closeModal(){
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
      }

      function openModal(bikeName){
        currentBike = bikeName || "Selected bike";
        form.reset();
        if(bikeModelInput) bikeModelInput.value = currentBike;
        if(durationInput) durationInput.value = "1";
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
      }

      document.addEventListener('click', (e)=>{
        const trigger = e.target.closest('.motor-book');
        if(!trigger) return;

        e.preventDefault();
        const card = trigger.closest('.motor-card');
        const title = card ? card.querySelector('h3') : null;
        openModal(title ? title.textContent.trim() : "Selected bike");
      });

      modal.addEventListener('click', (e)=>{
        if(e.target.dataset.close){ closeModal(); }
      });

      form.addEventListener('submit', (e)=>{
        e.preventDefault();

        const name = nameInput ? nameInput.value.trim() : "";
        const phone = phoneInput ? phoneInput.value.trim() : "";
        const startDate = startDateInput ? startDateInput.value.trim() : "";
        const duration = durationInput ? durationInput.value.trim() : "";
        const pickupTime = pickupTimeInput ? pickupTimeInput.value.trim() : "";
        const pickupLocation = pickupLocationInput ? pickupLocationInput.value.trim() : "";
        const note = noteInput ? noteInput.value.trim() : "";

        const msg =
`Hello Nendhy Holiday Lombok Transport, I want to book a motorbike rental.

Bike model: ${currentBike || "-"}
Name: ${name || "-"}
WhatsApp: ${phone || "-"}
Rental start date: ${startDate || "-"}
Rental duration: ${duration || "-"} day(s)
Pickup time: ${pickupTime || "-"}
Pickup/Drop-off location: ${pickupLocation || "-"}
Notes: ${note || "-"}
`;

        const url = "https://wa.me/" + WA_NUMBER + "?text=" + encodeURIComponent(msg);
        window.open(url, "_blank", "noopener,noreferrer");
        closeModal();
      });
    })();
  }

  // Load sections when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadSections);
  } else {
    loadSections();
  }
