document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        var linhas = document.querySelectorAll('tbody tr'); // Seleciona todas as linhas dentro do tbody

        if (linhas.length === 0) {
            console.warn('Nenhuma linha encontrada na tabela.');
        }

        linhas.forEach(function(linha) {
            linha.addEventListener('dblclick', function() {
                var codigo = this.cells[0].textContent; // Obtém o código da primeira célula da linha clicada
                abrirModalDetalhes(codigo);
            });
        });
    }, 100); // Atraso de 100ms

    function abrirModalDetalhes(codigo) {
        $.ajax({
            url: '../controller/ConsultarPoCompra.php',
            method: 'POST',
            data: { Codigo: codigo },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                var detalhes = JSON.parse(response);
    
                // Preencha o modal com os detalhes recebidos
                document.getElementById('view-Codigo').value = detalhes[0].id_identificador; 
                document.getElementById('view-titulo').value = detalhes[0].Titulo;
                document.getElementById('view-Fornecedor').value = detalhes[0].nomeFantasia; 
                document.getElementById('view-preco-total').value = detalhes[0].total_preco;
                document.getElementById('view-prioridade').value = detalhes[0].Prioridade;
                document.getElementById('view-status').value = detalhes[0].status;
                document.getElementById('view-nf').value = detalhes[0].NR_NF;
    
                // Preencher a tabela de materiais
                var tabelaMateriais = document.getElementById('materiais-table').getElementsByTagName('tbody')[0];
                tabelaMateriais.innerHTML = ''; // Limpa a tabela antes de preencher
    
                detalhes.forEach(function(material, index) {
                    var novaLinha = tabelaMateriais.insertRow(); // Insere nova linha na tabela
                    var celulaNome = novaLinha.insertCell(0); // Insere célula para o nome do material
                    var celulaQuantidade = novaLinha.insertCell(1); // Insere célula para a quantidade do material
                    var celulaPrecoUnitario = novaLinha.insertCell(2); // Insere célula para o preço unitário
                    var celulaPrecoTotal = novaLinha.insertCell(3); // Insere célula para o preço total
    
                    celulaNome.innerHTML = `<span id="nomeMat-${index}" data-id-material="${material.idMat}">${material.nomeMat}</span>`;
                    celulaQuantidade.innerHTML = `<input id="qtdMat-${index}" type="number" value="${material.qtdMat}" readonly>`;
                    celulaPrecoUnitario.textContent = material.preco_unit; // Preenche o preço unitário
                    celulaPrecoTotal.textContent = material.preco_total; // Preenche o preço total
                });
    
                // Mostre o modal
                var modalDetalhes = document.getElementById('viewModal');
                var btnSalvarEdit = document.getElementById('btn-salvaredit');
                btnSalvarEdit.style.display = 'none';
                modalDetalhes.style.display = 'block';
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
    

    // Código existente para fechar o modal
    var spanCloseDetalhes = document.getElementsByClassName('close')[1];
    spanCloseDetalhes.addEventListener('click', function() {
        var modalDetalhes = document.getElementById('viewModal');
        modalDetalhes.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        var modalDetalhes = document.getElementById('viewModal');
        if (event.target == modalDetalhes) {
            modalDetalhes.style.display = 'none'; // Correção: fechar o modal
        }
    });
});
