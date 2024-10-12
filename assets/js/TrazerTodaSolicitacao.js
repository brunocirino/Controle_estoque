

// Função para fazer a requisição AJAX e preencher a tabela com os dados retornados
function trazerTodaSolicitacao() {
    $.ajax({
        url: '../controller/TrazerSolicitacaoCompra.php',
        method: 'GET',
        success: function(response) {
            console.log('Requisição AJAX bem sucedida:', response);
            // Preencher a tabela com os dados retornados
            preencherTabela(response);
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });
}

// Função para preencher a tabela com os dados retornados da requisição AJAX
function preencherTabela(data) {
    // Converter a resposta JSON em um array de objetos
    var produtos = JSON.parse(data);

    // Selecionar o corpo da tabela onde os dados serão inseridos
    var tbody = document.querySelector('tbody');

    // Limpar o corpo da tabela antes de adicionar os novos dados
    tbody.innerHTML = '';

    // Iterar sobre os produtos e adicionar linhas à tabela
    produtos.forEach(function(produto, index) {
        var newRow = document.createElement('tr');
        newRow.classList.add('linha' + (index + 1));


        // Criar células para cada propriedade do produto
        var keys = ['id_identificador', 'Titulo', 'nomeFantasia', 'total_preco', 'Prioridade', 'status'];
        keys.forEach(function(key) {
            var newCell = document.createElement('td');
            newCell.textContent = key === 'total_preco' ? 'R$ ' + produto[key] : produto[key];
            newRow.appendChild(newCell);
        });

        // Adicionar a nova linha à tabela
        tbody.appendChild(newRow);
    });
}



// Chamando as funções quando a página é carregada
document.addEventListener('DOMContentLoaded', function() {
    trazerTodaSolicitacao();
});
