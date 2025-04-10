//Menu hanburger Sidebar
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

//DataTable
/* $(document).ready(function () {
  $('#myTable').DataTable({
      language: {
          url: 'cdn.datatables.net/plug-ins/1.13.6/i18n/Portuguese-Brasil.json'
      },
      pageLength: 10, // Define o número de linhas por página
      lengthMenu: [5, 10, 25, 50, 100], // Opções de linhas por página
      ordering: true, // Habilita ordenação
      responsive: true // Torna a tabela responsiva
  });
}); */

//Preview de imagem no input file
function mostarImagem() {

  if (this.files && this.files[0]) {
    var file = new FileReader();
    file.onload = function (e) {
      document.getElementById("preview").src = e.target.result;
    };
    file.readAsDataURL(this.files[0]);
  }
}
document.getElementById("thumb").addEventListener("change", mostarImagem, false);



