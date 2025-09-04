// Menu hamburger do Sidebar
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

//Seta do menu usuário
document.addEventListener("DOMContentLoaded", function () {
  const dropdownToggles = document.querySelectorAll('.nav-item.dropdown .nav-link');

  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function () {
      const icon = this.querySelector('i');
      icon.classList.toggle('bi-chevron-down');
      icon.classList.toggle('bi-chevron-up');
    });
  });
});
