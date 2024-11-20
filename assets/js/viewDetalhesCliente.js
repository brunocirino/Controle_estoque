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
            url: '../controller/ConsultarCliente.php',
            method: 'POST',
            data: { Codigo: codigo },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                var detalhes = JSON.parse(response);
    
                // Preencha o modal com os detalhes recebidos
                document.getElementById('view-Codigo').value = detalhes[0].codCli; 
                document.getElementById('view-CPF').value = detalhes[0].cpfCli;
                document.getElementById('view-nome').value = detalhes[0].nomeCli; 
                document.getElementById('view-email').value = detalhes[0].emailCli;
    
                // Preencher a tabela de materiais
                var tabelaEndereco = document.getElementById('endereco-table').getElementsByTagName('tbody')[0];
                tabelaEndereco.innerHTML = ''; // Limpa a tabela antes de preencher
    
                detalhes.forEach(function(Endereco, index) {
                    var novaLinha = tabelaEndereco.insertRow(); // Insere nova linha na tabela
                    var celulaBairro = novaLinha.insertCell(0); // Insere célula para o nome do material
                    var celulaUF = novaLinha.insertCell(1); // Insere célula para a quantidade do material
                    var celulaCEP = novaLinha.insertCell(2); // Insere célula para o preço unitário
    
                    celulaBairro.textContent = Endereco.bairro;
                    celulaUF.textContent = Endereco.uf; // Preenche o preço unitário
                    celulaCEP.textContent = Endereco.cep; // Preenche o preço total
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
