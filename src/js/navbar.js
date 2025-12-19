document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.querySelector("#menu-btn");
  const mobileMenu = document.querySelector("#mobile-menu");
  const navLinks = document.querySelectorAll("a[href^='#']");
  let isOpen = false;

  // ❗ Guard (WAJIB)
  if (!menuBtn || !mobileMenu) return;

  // 🔸 Toggle Hamburger Menu
  menuBtn.addEventListener("click", () => {
    isOpen = !isOpen;

    mobileMenu.classList.toggle("hidden");
    mobileMenu.classList.toggle("flex");

    // animasi optional
    mobileMenu.classList.add("animate-fade-slide");

    const bars = menuBtn.querySelectorAll("span");

    if (isOpen) {
      bars[0].classList.add("rotate-45", "translate-y-1.5");
      bars[1].classList.add("opacity-0");
      bars[2].classList.add("-rotate-45", "-translate-y-1.5");
    } else {
      bars[0].classList.remove("rotate-45", "translate-y-1.5");
      bars[1].classList.remove("opacity-0");
      bars[2].classList.remove("-rotate-45", "-translate-y-1.5");
    }
  });

  // 🔸 Smooth Scroll + Auto Close
  navLinks.forEach(link => {
    link.addEventListener("click", e => {
      const href = link.getAttribute("href");

      if (!href.startsWith("#")) return;

      e.preventDefault();

      const target = document.getElementById(href.substring(1));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 70,
          behavior: "smooth"
        });
      }

      // tutup menu mobile
      if (isOpen) {
        mobileMenu.classList.add("hidden");
        mobileMenu.classList.remove("flex");
        isOpen = false;

        const bars = menuBtn.querySelectorAll("span");
        bars[0].classList.remove("rotate-45", "translate-y-1.5");
        bars[1].classList.remove("opacity-0");
        bars[2].classList.remove("-rotate-45", "-translate-y-1.5");
      }
    });
  });
});
// navbar.js — simpan di src/js/navbar.js