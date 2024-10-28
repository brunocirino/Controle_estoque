// Definir a variável fornecedoresCadastrados como global
window.fornecedoresCadastrados = [];

// Função para fazer a requisição AJAX e buscar todos os fornecedores
export function TrazerTodosFornecedores() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../controller/TrazerTodosFornecedores.php', // Atualizado para o novo endpoint
            method: 'GET',
            success: function(response) {
                try {
                    // Parse da resposta para JSON
                    const fornecedores = JSON.parse(response);
                    console.log('Requisição AJAX bem sucedida:', fornecedores);

                    // Atualizar a lista de fornecedores cadastrados
                    atualizarFornecedoresCadastrados(fornecedores);
                    resolve(); // Resolve a Promise quando a requisição for bem-sucedida
                } catch (e) {
                    console.error('Erro ao processar a resposta:', e);
                    reject(e); // Rejeita a Promise em caso de erro
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
                reject(error); // Rejeita a Promise em caso de erro
            }
        });
    });
}

// Função para atualizar a lista de fornecedores cadastrados com a resposta AJAX
function atualizarFornecedoresCadastrados(fornecedores) {
    // Atualiza a lista global fornecedoresCadastrados
    fornecedoresCadastrados = fornecedores.map(fornecedor => {
        return { 
            id: fornecedor.id, 
            nome: fornecedor.Nome
        };
    });

    // Chama a função para popular o select com os fornecedores
    preencherFornecedores(fornecedoresCadastrados);
}

// Função para popular a lista de fornecedores no select
function preencherFornecedores(fornecedoresCadastrados) {
    const fornecedoresSelect = document.getElementById("edit-Fornecedor"); // Usando o ID correto
    fornecedoresSelect.innerHTML = ''; // Limpa as opções anteriores

    fornecedoresCadastrados.forEach(fornecedor => {
        const option = document.createElement("option");
        option.value = fornecedor.id;
        option.textContent = fornecedor.nome; // Exibe apenas o nome do fornecedor
        fornecedoresSelect.appendChild(option);
    });
}

// Função para adicionar campos de detalhes ao selecionar fornecedores (opcional)
document.getElementById('edit-Fornecedor').addEventListener('change', function() {
    const selecionados = Array.from(this.selectedOptions).map(opt => opt.value);
    const container = document.getElementById('detalhes-fornecedores-container');
    console.log(container)
    container.innerHTML = ''; // Limpa os campos anteriores

    // Adiciona campos de detalhes para os fornecedores selecionados (opcional)
    selecionados.forEach(fornecedorId => {
        const fornecedor = fornecedoresCadastrados.find(f => f.id == fornecedorId);
        if (fornecedor) {
            const div = document.createElement('div');
            div.innerHTML = `<p>Nome: ${fornecedor.nome}</p>`;
            container.appendChild(div);
        }
    });
});

// Chama a função para trazer os fornecedores quando o modal é aberto ou quando necessário
TrazerTodosFornecedores();
