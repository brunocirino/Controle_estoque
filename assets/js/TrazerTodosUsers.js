function TrazerTodosUsers() {
    $.ajax({
        url: '../controller/TrazerTodosUsuarios.php',
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
    var usuarios = JSON.parse(data);

    // Selecionar o corpo da tabela onde os dados serão inseridos
    var tbody = document.querySelector('tbody');

    // Limpar o corpo da tabela antes de adicionar os novos dados
    tbody.innerHTML = '';

    // Iterar sobre os produtos e adicionar linhas à tabela
    usuarios.forEach(function(usuario, index) {
        var newRow = document.createElement('tr');
        newRow.classList.add('linha' + (index + 1));

        // Criar células para cada propriedade do usuario e preencher com os dados
        var keys = ['codigo', 'nome', 'login', 'senha', 'Status', 'setor'];
        keys.forEach(function(key) {
            var newCell = document.createElement('td');
            newCell.textContent = usuario[key];
            newRow.appendChild(newCell);
        });

        // Adicionar a nova linha à tabela
        tbody.appendChild(newRow);
    });
}


document.addEventListener('DOMContentLoaded', function() {
    TrazerTodosUsers();
});