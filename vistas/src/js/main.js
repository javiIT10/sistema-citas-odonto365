document.addEventListener("DOMContentLoaded", function () {
  /* Variables del menu y modales */
  const menuToggle = document.getElementById("menuToggle");
  const menu = document.getElementById("menu");

  const menuIcon = menuToggle.querySelector(".menu-icon");

  let isOpen = false;

  /* ======================================================================
                            EVENT LISTENERS
====================================================================== */
  menuToggle.addEventListener("click", toggleMenu);

  function toggleMenu() {
    isOpen = !isOpen;
    menu.classList.toggle("active", isOpen);
    menuIcon.classList.toggle("menu-open", isOpen);
    document.body.classList.remove("modal-open");
  }
});
