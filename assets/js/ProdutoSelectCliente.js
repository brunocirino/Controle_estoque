
window.ClientesCadastrados = [];

// Função para fazer a requisição AJAX e buscar todos os fornecedores
export function TrazerTodosClientes() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../controller/TrazerTodosClientes.php',
            method: 'GET',
            success: function(response) {
                try {
                    // Parse da resposta para JSON
                    const Clientes = JSON.parse(response);
                    console.log('Requisição AJAX bem sucedida:', Clientes);

                    // Atualizar a lista de fornecedores cadastrados
                    atualizarClientesCadastrados(Clientes);
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
function atualizarClientesCadastrados(Clientes) {
    // Atualiza a lista global fornecedoresCadastrados
    ClientesCadastrados = Clientes.map(cliente => {
        return { 
            id: cliente.codCli, 
            nome: cliente.nomeCli
        };
    });

    // Chama a função para popular o select com os fornecedores
    preencherClientes(ClientesCadastrados);
}

// Função para popular a lista de fornecedores no select
function preencherClientes(ClientesCadastrados) {
    const ClienteSelect = document.getElementById("edit-Cliente"); // Usando o ID correto
    ClienteSelect.innerHTML = ''; // Limpa as opções anteriores

    ClientesCadastrados.forEach(Cliente => {
        const option = document.createElement("option");
        option.value = Cliente.id;
        option.textContent = Cliente.nome; // Exibe apenas o nome do fornecedor
        ClienteSelect.appendChild(option);
    });
}

// Função para adicionar campos de detalhes ao selecionar fornecedores (opcional)
document.getElementById('edit-Cliente').addEventListener('change', function() {
    const selecionados = Array.from(this.selectedOptions).map(opt => opt.value);
    const container = document.getElementById('detalhes-Clientes-container');
    container.innerHTML = ''; // Limpa os campos anteriores

    // Adiciona campos de detalhes para os fornecedores selecionados (opcional)
    selecionados.forEach(ClienteID => {
        const Cliente = ClientesCadastrados.find(f => f.id == ClienteID);
        if (Cliente) {
            const div = document.createElement('div');
            div.innerHTML = `<p>Nome: ${Cliente.nome}</p>`;
            container.appendChild(div);
        }
    });
});

TrazerTodosClientes();
