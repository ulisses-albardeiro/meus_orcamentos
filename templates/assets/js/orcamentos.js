// Função para adicionar novos itens ao orçamento
function adicionarItemDetalhado() {
    let container = document.getElementById('itens-container');
    let index = document.querySelectorAll('.item').length;
    let item = document.createElement('div');
    item.classList.add('row', 'g-2', 'mb-2', 'item', 'mt-2');
    item.innerHTML = `
            <div class="col-6">
                <input required type="text" class="form-control form-control-sm" name="itens[${index}][nome]" placeholder="Nome do Serviço">
            </div>
            <div class="col-2">
                <input required type="number" class="form-control form-control-sm" name="itens[${index}][qtd]" placeholder="Qtd*">
            </div>
            <div class="col-3">
                <input required type="text" class="form-control form-control-sm valor-unitario" name="itens[${index}][valor]" placeholder="Valor unitário">
            </div>
            <div class="col-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removerItem(this)">X</button>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="itens[${index}][descricao]" placeholder="Descrição"></textarea>
            </div>
        `;
    container.appendChild(item);

    // Aplica a máscara monetária ao novo campo criado
    aplicarMascaraMonetaria(item.querySelector('.valor-unitario'));
}

// Função para adicionar novos itens simples
function adicionarItemSimples() {
    let container = document.getElementById('itens-container');
    let index = document.querySelectorAll('.item').length;
    let item = document.createElement('div');
    item.classList.add('row', 'g-2', 'mb-2', 'item', 'mt-2');
    item.innerHTML = `
            <div class="col-9">
                <input required type="text" class="form-control form-control-sm" name="itens[${index}][nome]" placeholder="Nome do Serviço">
            </div>
            <div class="col-2">
                <input required type="number" class="form-control form-control-sm" name="itens[${index}][qtd]" placeholder="Qtd*">
            </div>
            <div class="col-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removerItem(this)">X</button>
            </div>
            <div class="col-12">
                <textarea class="form-control" name="itens[${index}][descricao]" placeholder="Descrição"></textarea>
            </div>
        `;
    container.appendChild(item);

}

// Função para remover itens do orçamento
function removerItem(button) {
    button.closest('.item').remove();
}

// Função para aplicar máscara monetária a um campo específico
function aplicarMascaraMonetaria(campo) {
    if (!campo) return;

    campo.addEventListener('input', function (e) {
        // Remove todos os caracteres não numéricos
        let valor = this.value.replace(/[^\d]/g, '');

        // Se estiver vazio, define como vazio
        if (valor === '') {
            this.value = '';
            return;
        }

        // Converte para número com centavos
        valor = (parseInt(valor, 10) / 100);

        // Formata com 2 casas decimais e vírgula
        this.value = valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).replace('.', '~').replace(',', '.').replace('~', ',');
    });

    campo.addEventListener('blur', function () {
        let valor = this.value.replace(/[^\d]/g, '');

        if (!valor) {
            this.value = '';
            return;
        }

        valor = parseInt(valor, 10) / 100;

        // Formata como moeda brasileira completa
        this.value = valor.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    });

    // Formata inicialmente se houver valor
    if (campo.value) {
        campo.dispatchEvent(new Event('blur'));
    }
}

// Função para aplicar máscara de CNPJ a um campo específico
function aplicarMascaraCNPJ(campo) {
    if (!campo) return;

    campo.addEventListener('input', function (e) {
        // Remove todos os caracteres não numéricos
        let value = this.value.replace(/\D/g, '');

        // Aplica a máscara conforme o usuário digita
        if (value.length > 2) {
            value = value.substring(0, 2) + '.' + value.substring(2);
        }
        if (value.length > 6) {
            value = value.substring(0, 6) + '.' + value.substring(6);
        }
        if (value.length > 10) {
            value = value.substring(0, 10) + '/' + value.substring(10);
        }
        if (value.length > 15) {
            value = value.substring(0, 15) + '-' + value.substring(15);
        }

        // Limita o tamanho máximo
        this.value = value.substring(0, 18);
    });
}

// Quando o DOM estiver completamente carregado
document.addEventListener('DOMContentLoaded', function () {
    // Aplica máscara CNPJ aos campos existentes
    aplicarMascaraCNPJ(document.getElementById('cnpj-input'));
    aplicarMascaraCNPJ(document.getElementById('cnpj-input-cliente'));

    // Aplica máscara monetária ao campo inicial
    const camposValor = document.querySelectorAll('.valor-unitario');
    camposValor.forEach(campo => {
        aplicarMascaraMonetaria(campo);
    });

    // Adiciona evento para aplicar máscara a novos itens adicionados dinamicamente
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('valor-unitario')) {
            aplicarMascaraMonetaria(e.target);
        }
    });
});

function dropdownClientes(clientes) {
    // Seletores do DOM
    const nomeInput = document.getElementById('nome_cliente');
    const dropdown = document.getElementById('autocomplete-dropdown');
    const formFields = {
        nome: document.querySelector('[name="nome_cliente"]'),
        telefone: document.querySelector('[name="telefone_cliente"]'),
        celular: document.querySelector('[name="celular_cliente"]'),
        cpf_cnpj: document.querySelector('[name="cpf_cnpj_cliente"]'),
        email: document.querySelector('[name="email_cliente"]'),
        cep: document.querySelector('[name="cep_cliente"]'),
        rua: document.querySelector('[name="rua_cliente"]'),
        n_casa: document.querySelector('[name="n_casa_cliente"]'),
        bairro: document.querySelector('[name="bairro_cliente"]'),
        cidade: document.querySelector('[name="cidade_cliente"]'),
        uf: document.querySelector('[name="uf_cliente"]')
    };

    // Função para renderizar as opções no dropdown
    function renderDropdown(filteredClients) {
        dropdown.innerHTML = '';
        if (filteredClients.length > 0) {
            filteredClients.forEach(cliente => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.href = '#';
                item.textContent = cliente.nome;
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    fillForm(cliente);
                    dropdown.classList.remove('show');
                });
                dropdown.appendChild(item);
            });
            dropdown.classList.add('show');
        } else {
            const noResults = document.createElement('span');
            noResults.className = 'dropdown-item disabled text-muted';
            noResults.textContent = 'Nenhum cliente encontrado em seus registros.';
            dropdown.appendChild(noResults);
            dropdown.classList.add('show');
        }
    }

    // Função para preencher todos os campos do formulário
    function fillForm(cliente) {
        formFields.nome.value = cliente.nome || '';
        formFields.telefone.value = cliente.telefone || '';
        formFields.celular.value = cliente.celular || '';
        formFields.cpf_cnpj.value = cliente.cpf_cnpj || '';
        formFields.email.value = cliente.email || '';
        formFields.cep.value = cliente.cep || '';
        formFields.rua.value = cliente.rua || '';
        formFields.n_casa.value = cliente.n_casa || '';
        formFields.bairro.value = cliente.bairro || '';
        formFields.cidade.value = cliente.cidade || '';
        formFields.uf.value = (cliente.uf || '').toUpperCase();
    }

    // Evento de 'input' para o campo de nome
    nomeInput.addEventListener('input', () => {
        const query = nomeInput.value.toLowerCase();

        if (query.length > 0) {
            const filteredClients = clientes.filter(cliente =>
                cliente.nome.toLowerCase().includes(query)
            );
            renderDropdown(filteredClients);
        } else {
            dropdown.classList.remove('show');
        }
    });

    // Esconde o dropdown se o usuário clicar fora
    document.addEventListener('click', (e) => {
        if (!nomeInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
}