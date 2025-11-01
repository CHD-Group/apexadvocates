document.addEventListener('DOMContentLoaded', function () {
  // ===== COUNTER ANIMATION =====
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = +counter.getAttribute('data-target');
        const suffix = counter.getAttribute('data-suffix') || '';
        const duration = 2000;
        const startTime = performance.now();

        const updateCounter = (currentTime) => {
          const elapsedTime = currentTime - startTime;
          const progress = Math.min(elapsedTime / duration, 1);
          const value = Math.floor(progress * target);

          // Special handling for million counter
          if (counter.textContent.includes('million')) {
            counter.textContent = value + ' million';
          } else {
            counter.textContent = value + (progress === 1 ? suffix : '');
          }

          if (progress < 1) {
            requestAnimationFrame(updateCounter);
          }
        };

        requestAnimationFrame(updateCounter);
        counterObserver.unobserve(counter);
      }
    });
  }, { threshold: 0.5 });

  // Observe all counters
  document.querySelectorAll('.counter').forEach(counter => {
    counterObserver.observe(counter);
  });

  const navToggle = document.getElementById('navToggle');
  const navMenu = document.getElementById('navMenu');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

  // Toggle mobile menu
  navToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    navMenu.classList.toggle('active');

    const icon = navToggle.querySelector('i');
    icon.style.transition = 'transform 0.3s ease';

    if (navMenu.classList.contains('active')) {
      icon.classList.replace('fa-bars', 'fa-times');
      icon.style.transform = 'rotate(180deg)';
    } else {
      icon.classList.replace('fa-times', 'fa-bars');
      icon.style.transform = 'rotate(0deg)';
      document.querySelectorAll('.dropdown-content').forEach(menu => {
        menu.classList.remove('active');
      });
    }
  });

  // Toggle dropdowns — FIXED HERE
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function (e) {
      const dropdown = this.nextElementSibling;
      const isMobile = window.innerWidth <= 992;

      if (dropdown && dropdown.classList.contains('dropdown-content')) {
        e.preventDefault();
        e.stopPropagation();

        // Close others on desktop
        if (!isMobile) {
          document.querySelectorAll('.dropdown-content').forEach(menu => {
            if (menu !== dropdown) menu.classList.remove('active');
          });
          document.querySelectorAll('.dropdown-toggle').forEach(t => {
            if (t !== this) t.classList.remove('active');
          });
        }

        dropdown.classList.toggle('active');
        this.classList.toggle('active');
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.dropdown')) {
      document.querySelectorAll('.dropdown-content').forEach(menu => {
        menu.classList.remove('active');
      });
      document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.classList.remove('active');
      });
    }

    if (window.innerWidth <= 992 && !e.target.closest('.navbar')) {
      closeMobileMenu();
    }
  });

  function closeMobileMenu() {
    navMenu.classList.remove('active');
    const icon = navToggle.querySelector('i');
    icon.classList.replace('fa-times', 'fa-bars');
    icon.style.transform = 'rotate(0deg)';
    document.querySelectorAll('.dropdown-content').forEach(menu => {
      menu.classList.remove('active');
    });
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
      toggle.classList.remove('active');
    });
  }
});
