function TrazerTodosClientes() {
    $.ajax({
        url: '../controller/TrazerTodosClientes.php',
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

function preencherTabela(data) {
    // Converter a resposta JSON em um array de objetos
    var Clientes = JSON.parse(data);

    // Selecionar o corpo da tabela onde os dados serão inseridos
    var tbody = document.querySelector('tbody');

    // Limpar o corpo da tabela antes de adicionar os novos dados
    tbody.innerHTML = '';

    // Iterar sobre os produtos e adicionar linhas à tabela
    Clientes.forEach(function(Cliente, index) {
        var newRow = document.createElement('tr');
        newRow.classList.add('linha' + (index + 1));

        newRow.setAttribute('data-id-end', Cliente['id_end']);
        newRow.setAttribute('data-cod-cli', Cliente['codCli']);

        // Criar células para cada propriedade do usuario e preencher com os dados
        var keys = ['codCli', 'cpfCli', 'nomeCli', 'emailCli', 'fone', 'id_end'];
        keys.forEach(function(key) {
            var newCell = document.createElement('td');
            newCell.textContent = Cliente[key];
            newRow.appendChild(newCell);
        });

        // Adicionar a nova linha à tabela
        tbody.appendChild(newRow);
    });
}


document.addEventListener('DOMContentLoaded', function() {
    TrazerTodosClientes();
});