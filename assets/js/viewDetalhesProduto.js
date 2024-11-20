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
            url: '../controller/ConsultarProduto.php',
            method: 'POST',
            data: { Codigo: codigo },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                var detalhes = JSON.parse(response);
    
                // Preencha o modal com os detalhes recebidos
                document.getElementById('view-Codigo').value = detalhes[0].id_identificador; 
                document.getElementById('view-nome').value = detalhes[0].nomeProd;
                document.getElementById('view-qtd-prod').value = detalhes[0].qtdProd; 
                document.getElementById('view-preco').value = detalhes[0].preco;
                document.getElementById('view-estado').value = detalhes[0].estadoProd;
    
                // Preencher a tabela de materiais
                var tabelaMaterial = document.getElementById('materiais-table').getElementsByTagName('tbody')[0];
                tabelaMaterial.innerHTML = ''; 
    
                detalhes.forEach(function(Material, index) {
                    var novaLinha = tabelaMaterial.insertRow();
                    var celulaCodigo = novaLinha.insertCell(0); 
                    var celulaNmMaterial = novaLinha.insertCell(1); 
                    var celulaQtdMaterial = novaLinha.insertCell(2); 
    
                    celulaCodigo.textContent = Material.materiais_ids;
                    celulaNmMaterial.textContent = Material.materiais_nomes;
                    celulaQtdMaterial.textContent = Material.materiais_qtd; 
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
