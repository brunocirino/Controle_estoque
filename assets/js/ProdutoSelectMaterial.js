// Definir a variável materiaisCadastrados como global
let materiaisCadastrados = [];

// Função para fazer a requisição AJAX e buscar todos os materiais
function TrazerTodosMateriais() {
    $.ajax({
        url: '../controller/TrazerTodosMateriais.php',
        method: 'GET',
        success: function(response) {
            try {
                // Parse da resposta para JSON
                const materiais = JSON.parse(response);
                console.log('Requisição AJAX bem sucedida:', materiais);

                // Atualizar a lista de materiais cadastrados
                atualizarMateriaisCadastrados(materiais);
            } catch (e) {
                console.error('Erro ao processar a resposta:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição AJAX:', error);
        }
    });
}

// Função para atualizar a lista de materiais cadastrados com a resposta AJAX
function atualizarMateriaisCadastrados(materiais) {
    // Atualiza a lista global materiaisCadastrados
    materiaisCadastrados = materiais.map(material => {
        return { 
            id: material.codMat, 
            nome: material.nomeMat 
        };
    });

    // Chama a função para popular o select com os materiais
    preencherMateriais(materiaisCadastrados);
}

// Função para popular a lista de materiais no select
function preencherMateriais(materiaisCadastrados) {
    const materiaisSelect = document.getElementById("edit-materiais");
    materiaisSelect.innerHTML = ''; // Limpa as opções anteriores

    materiaisCadastrados.forEach(material => {
        const option = document.createElement("option");
        option.value = material.id;
        option.textContent = material.nome;
        materiaisSelect.appendChild(option);
    });
}

// Função para adicionar campos de quantidade ao selecionar materiais
document.getElementById('edit-materiais').addEventListener('change', function() {
    const selecionados = Array.from(this.selectedOptions).map(opt => opt.value);
    const container = document.getElementById('quantidade-container');
    container.innerHTML = ''; // Limpa os campos anteriores

    // Adiciona campos de quantidade para os materiais selecionados
    selecionados.forEach(materialId => {
        const material = materiaisCadastrados.find(m => m.id == materialId);
        if (material) {
            const div = document.createElement('div');
            div.innerHTML = `
                <label for="quantidade-${material.id}">Quantidade de ${material.nome}:</label>
                <input type="number" id="quantidade-${material.id}" name="quantidades[${material.id}]" min="0">
            `;
            container.appendChild(div);
        }
    });
});

// Chama a função para trazer os materiais quando o modal é aberto ou quando necessário
TrazerTodosMateriais();
