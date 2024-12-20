function TrazerTodosUsers() {
    $.ajax({
        url: '../controller/TrazerTodosFornecedores.php',
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

// Função para aplicar máscara de CNPJ
function formatarCNPJ(cnpj) {
    
    cnpj = cnpj.replace(/\D/g, '');
    
    return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
}

// Função para aplicar máscara de telefone
function formatarTelefone(telefone) {
    
    telefone = telefone.replace(/\D/g, '');
    
    return telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1)$2-$3');
}

function preencherTabela(data) {
    // Converter a resposta JSON em um array de objetos
    var fornecedores = JSON.parse(data);

    // Selecionar o corpo da tabela onde os dados serão inseridos
    var tbody = document.querySelector('tbody');

    // Limpar o corpo da tabela antes de adicionar os novos dados
    tbody.innerHTML = '';

    // Iterar sobre os produtos e adicionar linhas à tabela
    fornecedores.forEach(function(fornecedor, index) {
        var newRow = document.createElement('tr');
        newRow.classList.add('linha' + (index + 1));

        // Aplicar a máscara de CNPJ e Telefone antes de exibir
        fornecedor.CNPJ = formatarCNPJ(fornecedor.CNPJ);
        fornecedor.Telefone = formatarTelefone(fornecedor.Telefone);

        // Criar células para cada propriedade do usuario e preencher com os dados
        var keys = ['id', 'Nome', 'nomeFantasia', 'CNPJ', 'Telefone', 'Contactante', 'endFaturamento', 'endEntrega', 'endCobranca'];
        keys.forEach(function(key) {
            var newCell = document.createElement('td');
            newCell.textContent = fornecedor[key];
            newRow.appendChild(newCell);
        });

        // Adicionar a nova linha à tabela
        tbody.appendChild(newRow);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    TrazerTodosUsers();
});
