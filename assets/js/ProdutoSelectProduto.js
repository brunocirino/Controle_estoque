// Definir a variável materiaisCadastrados como global
window.ProdutoCadastrados = [];

// Função para fazer a requisição AJAX e buscar todos os materiais
export function TrazerTodosProduto() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../controller/TrazerTodosProdutos.php',
            method: 'GET',
            success: function(response) {
                try {
                    // Parse da resposta para JSON
                    const Produtos = JSON.parse(response);
                    console.log('Requisição AJAX bem sucedida:', Produtos);

                    // Atualizar a lista de materiais cadastrados
                    atualizarProdutoCadastrados(Produtos);
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

// Função para atualizar a lista de materiais cadastrados com a resposta AJAX
function atualizarProdutoCadastrados(Produtos) {
    // Atualiza a lista global materiaisCadastrados
    ProdutoCadastrados = Produtos.map(produto => {
        return { 
            id: produto.id_identificador, 
            nome: produto.nomeProd 
        };
    });

    // Chama a função para popular o select com os materiais
    preencherProdutos(ProdutoCadastrados);
}

// Função para popular a lista de materiais no select
function preencherProdutos(ProdutoCadastrados) {
    const ProdutoSelect = document.getElementById("edit-Produtos");
    ProdutoSelect.innerHTML = ''; // Limpa as opções anteriores

    ProdutoCadastrados.forEach(produto => {
        const option = document.createElement("option");
        option.value = produto.id;
        option.textContent = produto.nome;
        ProdutoSelect.appendChild(option);
    });
}

document.getElementById('edit-Produtos').addEventListener('change', function() {
    const selecionados = Array.from(this.selectedOptions).map(opt => opt.value);
    const container = document.getElementById('quantidade-container');
    container.innerHTML = ''; // Limpa os campos anteriores

    // Adiciona campos de quantidade para os materiais selecionados
    selecionados.forEach(ProdutoID => {
        const Produto = ProdutoCadastrados.find(m => m.id == ProdutoID);
        if (Produto) {
            const div = document.createElement('div');
            div.innerHTML = `
                <label for="quantidade-${Produto.id}">Quantidade de ${Produto.nome}:</label>
                <input type="number" id="quantidade-${Produto.id}" name="quantidades[${Produto.id}]" min="0">
            `;
            container.appendChild(div);
        }
    });
});

// Chama a função para trazer os materiais quando o modal é aberto ou quando necessário
TrazerTodosProduto();
