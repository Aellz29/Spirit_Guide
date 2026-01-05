document.addEventListener("DOMContentLoaded", () => {
  const menuBtn = document.querySelector("#menu-btn");
  const mobileMenu = document.querySelector("#mobile-menu");
  let isOpen = false;

  if (!menuBtn || !mobileMenu) return;

  menuBtn.addEventListener("click", () => {
    isOpen = !isOpen;

    // Slide Animation
    if (isOpen) {
      mobileMenu.classList.remove("translate-x-full");
    } else {
      mobileMenu.classList.add("translate-x-full");
    }

    // Hamburger Morph (X)
    const bars = menuBtn.querySelectorAll("span");
    if (isOpen) {
      bars[0].classList.add("rotate-45", "translate-y-2");
      bars[1].classList.add("opacity-0");
      bars[2].classList.add("-rotate-45", "-translate-y-2");
    } else {
      bars[0].classList.remove("rotate-45", "translate-y-2");
      bars[1].classList.remove("opacity-0");
      bars[2].classList.remove("-rotate-45", "-translate-y-2");
    }
  });

  // Klik di luar menu untuk menutup
  document.addEventListener("click", (e) => {
    if (isOpen && !mobileMenu.contains(e.target) && !menuBtn.contains(e.target)) {
      menuBtn.click();
    }
  });
});