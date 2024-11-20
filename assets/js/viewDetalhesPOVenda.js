document.addEventListener('DOMContentLoaded', function() {
    
    var tabela = document.querySelector('tbody');

    tabela.addEventListener('dblclick', function(event) {
        var linha = event.target.closest('tr');
        if (linha) {
            var codigo = linha.cells[0].textContent.trim(); 
            abrirModalDetalhes(codigo);
        }
    });

    function abrirModalDetalhes(codigo) {
        $.ajax({
            url: '../controller/ConsultarPoVenda.php',
            method: 'POST',
            data: { Codigo: codigo },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                var detalhes = JSON.parse(response);
    
                // Preencha o modal com os detalhes recebidos
                document.getElementById('view-Codigo').value = detalhes[0].id_identificador; 
                document.getElementById('view-titulo').value = detalhes[0].Titulo;
                document.getElementById('view-cliente').value = detalhes[0].nomeCliente; 
                document.getElementById('view-cpf-cliente').value = detalhes[0].cpfCliente;
                document.getElementById('view-preco-total').value = detalhes[0].preco_total_PO;
                document.getElementById('view-status').value = detalhes[0].status;
                document.getElementById('view-nf').value = detalhes[0].NR_NF;
    
                // Preencher a tabela de materiais
                var tabelaProdutos = document.getElementById('produtos-table').getElementsByTagName('tbody')[0];
                tabelaProdutos.innerHTML = ''; // Limpa a tabela antes de preencher
    
                detalhes.forEach(function(Produto, index) {
                    var novaLinha = tabelaProdutos.insertRow(); // Insere nova linha na tabela
                    var celulaNome = novaLinha.insertCell(0); // Insere célula para o nome do material
                    var celulaQuantidade = novaLinha.insertCell(1); // Insere célula para a quantidade do material
                    var celulaPrecoUnitario = novaLinha.insertCell(2); // Insere célula para o preço unitário
                    var celulaPrecoTotal = novaLinha.insertCell(3); // Insere célula para o preço total
    
                    celulaNome.textContent = Produto.nomeProd;
                    celulaQuantidade.innerHTML = `<input id="qtdProd-${index}" type="number" value="${Produto.qtdProd}" readonly>`;
                    celulaPrecoUnitario.textContent = Produto.prcUnitProd; // Preenche o preço unitário
                    celulaPrecoTotal.textContent = Produto.preco_total; // Preenche o preço total
                });
    
                // Mostre o modal
                var modalDetalhes = document.getElementById('viewModal');
                var btnSalvar =  document.getElementById('btn-salvaredit');
                btnSalvar.style.display = 'none'
                modalDetalhes.style.display = 'block';
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }
    

    // Código existente para fechar o modal
    var spanCloseDetalhes = document.getElementsByClassName('close')[1]; // Correção: pegar o segundo elemento 'close'
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
