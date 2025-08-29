/**
 * Exibe um preview da imagem selecionada em um input file
 * 
 * @param {string} id_thumb - ID do elemento input file de imagem
 * 
 * @example
 * // HTML necessário:
 * // <input type="file" id="imagem-input">
 * // <img id="preview" src="placeholder.jpg">
 * 
 * // Uso:
 * previewImagem('imagem-input');
 */
function previewImagem(id_thumb) {
  document.getElementById(id_thumb).addEventListener("change", function () {
    if (this.files && this.files[0]) {
      var file = new FileReader();
      file.onload = function (e) {
        document.getElementById("preview").src = e.target.result;
      };
      file.readAsDataURL(this.files[0]);
    }
  }, false);
}

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



function copiarLinkDaPagina(classe) {
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

// -------------------- CELULAR --------------------
function mascaraCelularInput(celular) {
    function formatar(valor) {
        let v = valor.replace(/\D/g, "").substring(0, 11);
        let a = v.split(""); 
        let f = "";
        if (a.length > 0) f += `(${a.slice(0, 2).join("")})`;
        if (a.length > 2) f += `${a.slice(2, 7).join("")}`;
        if (a.length > 7) f += `-${a.slice(7, 11).join("")}`;
        return f;
    }

    // Aplica máscara no valor já preenchido
    celular.value = formatar(celular.value);

    // Aplica máscara enquanto digita
    celular.addEventListener("input", function () {
        celular.value = formatar(celular.value);
    });
}

// -------------------- TELEFONE --------------------
function mascaraTelefoneInput(tel) {
    function formatar(valor) {
        let v = valor.replace(/\D/g, "").substring(0, 10);
        let a = v.split("");
        let f = "";
        if (a.length > 0) f += `(${a.slice(0, 2).join("")})`;
        if (a.length > 2) f += `${a.slice(2, 6).join("")}`;
        if (a.length > 6) f += `-${a.slice(6, 10).join("")}`;
        return f;
    }

    tel.value = formatar(tel.value);

    tel.addEventListener("input", function () {
        tel.value = formatar(tel.value);
    });
}

// -------------------- CPF/CNPJ --------------------
function mascaraCpfCnpjInput(doc) {
    function formatar(valor) {
        let v = valor.replace(/\D/g, "").substring(0, 14);
        let a = v.split(""); 
        let f = "";

        if (a.length <= 11) { // CPF
            if (a.length > 0) f += `${a.slice(0, 3).join("")}`;
            if (a.length > 3) f += `.${a.slice(3, 6).join("")}`;
            if (a.length > 6) f += `.${a.slice(6, 9).join("")}`;
            if (a.length > 9) f += `-${a.slice(9, 11).join("")}`;
        } else { // CNPJ
            f += `${a.slice(0, 2).join("")}.${a.slice(2, 5).join("")}.${a.slice(5, 8).join("")}/${a.slice(8, 12).join("")}-${a.slice(12, 14).join("")}`;
        }

        return f;
    }

    doc.value = formatar(doc.value);

    doc.addEventListener("input", function () {
        doc.value = formatar(doc.value);
    });
}

// -------------------- CEP --------------------
function mascaraCepInput(cep) {
    function formatar(valor) {
        let v = valor.replace(/\D/g, "").substring(0, 8);
        let a = v.split("");
        let f = "";
        if (a.length > 0) f += `${a.slice(0, 5).join("")}`;
        if (a.length > 5) f += `-${a.slice(5, 8).join("")}`;
        return f;
    }

    cep.value = formatar(cep.value);

    cep.addEventListener("input", function () {
        cep.value = formatar(cep.value);
    });
}