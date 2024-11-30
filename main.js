// navbar
document.addEventListener('DOMContentLoaded', function() {
    // Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navContent = document.querySelector('.nav-content');
  
    menuToggle?.addEventListener('click', function() {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !isExpanded);
      navContent.classList.toggle('active');
    });
  
    // Dropdown Toggles
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        
        const submenu = this.nextElementSibling;
        submenu.classList.toggle('active');
  
        // Close other dropdowns
        dropdownToggles.forEach(otherToggle => {
          if (otherToggle !== toggle) {
            otherToggle.setAttribute('aria-expanded', 'false');
            otherToggle.nextElementSibling.classList.remove('active');
          }
        });
      });
    });
  
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        dropdownToggles.forEach(toggle => {
          toggle.setAttribute('aria-expanded', 'false');
          toggle.nextElementSibling.classList.remove('active');
        });
      }
    });
  
    // Theme Toggle
    function toggleTheme() {
      document.body.classList.toggle('dark-mode');
      const themeSwitcher = document.querySelector('.theme-switcher');
      if (document.body.classList.contains('dark-mode')) {
          themeSwitcher.textContent = '🌙';
      } else {
          themeSwitcher.textContent = '🌞';
      }
  }
    // Expose toggleTheme to window for onclick handler
    window.toggleTheme = toggleTheme;
  });
  
  //tampilan image slider
let slideIndex = 0;
let slides = document.querySelectorAll('.slide');
let dots = document.querySelector('.dots');
let autoplayInterval;

// Create dot indicators
slides.forEach((_, index) => {
    let dot = document.createElement('span');
    dot.classList.add('dot');
    dot.onclick = () => currentSlide(index);
    dots.appendChild(dot);
});

function showSlides() {
    let slider = document.querySelector('.slider');
    slider.style.transform = `translateX(${-slideIndex * 100}%)`;

    let dotElements = document.querySelectorAll('.dot');
    dotElements.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

function changeSlide(n) {
    slideIndex += n;
    if (slideIndex >= slides.length) slideIndex = 0;
    if (slideIndex < 0) slideIndex = slides.length - 1;
    showSlides();
    resetAutoplay();
}

// Tambahkan fungsi alias untuk dipanggil melalui onclick
function plusSlides(n) {
    changeSlide(n);
}

function currentSlide(n) {
    slideIndex = n;
    showSlides();
    resetAutoplay();
}

function autoplay() {
    changeSlide(1);
}

function resetAutoplay() {
    clearInterval(autoplayInterval);
    autoplayInterval = setInterval(autoplay, 5000);
}

// Initialize slider
showSlides();
resetAutoplay();

  // menu kontak
  document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (validateForm()) {
        showLoading();
        
        // Simulate form submission
        setTimeout(() => {
            hideLoading();
            showSuccess();
            this.reset();
        }, 2000);
    }
  });
  
  function validateForm() {
    let isValid = true;
    
    // Name validation
    const name = document.getElementById('name');
    if (!name.value.trim()) {
        showError('nameError');
        isValid = false;
    } else {
        hideError('nameError');
    }
    
    // Email validation
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        showError('emailError');
        isValid = false;
    } else {
        hideError('emailError');
    }
    
    // Phone validation
    const phone = document.getElementById('phone');
    const phoneRegex = /^[0-9]{10,13}$/;
    if (!phoneRegex.test(phone.value)) {
        showError('phoneError');
        isValid = false;
    } else {
        hideError('phoneError');
    }
    
    // Message validation
    const message = document.getElementById('message');
    if (!message.value.trim()) {
        showError('messageError');
        isValid = false;
    } else {
        hideError('messageError');
    }
    
    return isValid;
  }
  
  function showError(errorId) {
    document.getElementById(errorId).style.display = 'block';
  }
  
  function hideError(errorId) {
    document.getElementById(errorId).style.display = 'none';
  }
  
  function showLoading() {
    document.getElementById('loading').style.display = 'block';
  }
  
  function hideLoading() {
    document.getElementById('loading').style.display = 'none';
  }
  
  function showSuccess() {
    alert('Pesan berhasil dikirim!');
  }
    //footer
    // language
  
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
          pageLanguage: 'id', // Changed from 'in' to 'id' for proper Indonesian language code
          autoDisplay: true,
          layout: google.translate.TranslateElement.InlineLayout.VERTICAL
      }, 'google_translate_element');
  }
  
  // Smooth scroll functionality dengan implementasi yang lebih baik
  document.querySelectorAll('a[data-scroll]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      if (targetId === '#top') {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      } else {
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });
  
  // Scroll to top button visibility dengan throttling
  const scrollButton = document.querySelector('.scroll-to-top');
  let lastScrollTime = 0;
  const throttleTime = 100; // ms
  
  function toggleScrollButton() {
    const now = Date.now();
    if (now - lastScrollTime >= throttleTime) {
      lastScrollTime = now;
      
      if (window.pageYOffset > 200) {
        scrollButton.classList.add('visible');
      } else {
        scrollButton.classList.remove('visible');
      }
    }
  }
  
  // Event listener untuk scroll dengan throttling
  window.addEventListener('scroll', toggleScrollButton, { passive: true });
  
  // Click handler untuk scroll button
  scrollButton.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  
  // Intersection Observer untuk animasi pada scroll (tetap sama seperti sebelumnya)
  const footerSections = document.querySelectorAll('.footer-section');
  
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);
  
  footerSections.forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'all 0.5s ease-out';
    observer.observe(section);
  });
  
  // Polyfill untuk smooth scroll di browser yang tidak mendukung
  if (!('scrollBehavior' in document.documentElement.style)) {
    const smoothScroll = (targetY) => {
      const startY = window.pageYOffset;
      const difference = targetY - startY;
      const duration = 750;
      let start;
  
      window.requestAnimationFrame(function step(timestamp) {
        if (!start) start = timestamp;
        const time = timestamp - start;
        const percent = Math.min(time / duration, 1);
        
        window.scrollTo(0, startY + difference * percent);
        
        if (time < duration) {
          window.requestAnimationFrame(step);
        }
      });
    };
  
    document.querySelectorAll('a[data-scroll]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#top') {
          smoothScroll(0);
        } else {
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            smoothScroll(targetElement.offsetTop);
          }
        }
      });
    });
  }
  
  function logout() {
      alert("Anda telah logout!"); // Pesan alert sebelum logout
      window.location.href = 'logout.php'; // Arahkan ke logout.php
  }