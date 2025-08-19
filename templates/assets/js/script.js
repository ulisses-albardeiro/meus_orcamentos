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

/**
 * Exibe uma notificação toast na tela
 * 
 * @param {string} mensagem - Texto da notificação a ser exibido
 * @param {string} [tipo='success'] - Tipo da notificação (define a cor)
 *        Valores possíveis: 'success', 'danger', 'warning', 'info', etc.
 * 
 * @example
 * mostrarNotificacao('Operação realizada com sucesso!');
 * mostrarNotificacao('Erro ao salvar dados', 'danger');
 */
function mostrarNotificacao(mensagem, tipo = 'success') {
  const toast = $(`
                <div class="toast align-items-center text-white bg-${tipo} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${mensagem}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `);

  $('#notificacoes').append(toast);
  new bootstrap.Toast(toast[0]).show();

  // Remove o toast após alguns segundos
  setTimeout(() => toast.remove(), 3000);
}

// Cria o container de notificações se não existir
if ($('#notificacoes').length === 0) {
  $('body').append('<div id="notificacoes" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
}



function copiarLinkDaPagina(classe){
  // Encontra o botão de compartilhamento pelo texto ou classe
    const shareButton = document.querySelector(classe);

    // Verifica se o botão existe antes de adicionar o evento
    if (shareButton) {
      shareButton.addEventListener('click', function (event) {
        // Previne a ação padrão do link (que é recarregar a página ou ir para o #)
        event.preventDefault();

        // Pega o URL da página atual
        const url = window.location.href;

        // Usa a API Clipboard para copiar o URL para a área de transferência
        navigator.clipboard.writeText(url)
          .then(() => {
            // Mensagem de sucesso (opcional)
            mostrarNotificacao("Link copiado com sucesso!");
          })
          .catch(err => {
            // Mensagem de erro, caso a cópia falhe
            console.error('Erro ao copiar o link: ', err);
            mostrarNotificacao("Hove um erro ao copiar o link", "danger");
          });
      });
    }
}