document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var Titulo = document.getElementById('modal-title');
    var isEditMode = false;  // Variável para rastrear o modo atual


    var campoTitulo = document.getElementById('view-titulo');
    var campoFornecedor = document.getElementById('view-Fornecedor');
    var campoPrecoTotal = document.getElementById('view-preco-total');
    var campoPrioridade = document.getElementById('view-prioridade');
    var campoStatus = document.getElementById('view-status');

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar material";

        document.getElementById('edit-titulo').value = '';
        document.getElementById('edit-Fornecedor').value = '';
        document.getElementById('edit-materiais').value = '';
        document.getElementById('edit-prioridade').value = '';
    });

    btnEditar.addEventListener('click', function() {
        isEditMode = true;  // Definir modo de edição
        var CodigoMat = document.querySelector('.product-id').value;
        if (CodigoMat) {
            $.ajax({
                url: '../controller/ConsultarPoCompra.php',
                method: 'POST',
                data: { Codigo: CodigoMat }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var detalhes = JSON.parse(response);
    
                    // Preencha o modal com os detalhes recebidos
                    document.getElementById('view-Codigo').value = detalhes[0].id_identificador;
                    campoTitulo.value = detalhes[0].Titulo;
                    campoFornecedor.value = detalhes[0].nomeFantasia;
                    campoPrecoTotal.value = detalhes[0].total_preco;
                    campoPrioridade.value = detalhes[0].Prioridade;
                    campoStatus.value = detalhes[0].status;
    
                    // Remover o atributo 'readonly' para permitir edição
                    campoTitulo.removeAttribute('readonly');
                    campoFornecedor.removeAttribute('readonly');
                    campoPrecoTotal.removeAttribute('readonly');
                    campoPrioridade.removeAttribute('readonly');
                    campoStatus.removeAttribute('readonly');
    
                    // Preencher a tabela de materiais
                    var tabelaMateriais = document.getElementById('materiais-table').getElementsByTagName('tbody')[0];
                    tabelaMateriais.innerHTML = ''; // Limpa a tabela antes de preencher
    
                    detalhes.forEach(function(material) {
                        var novaLinha = tabelaMateriais.insertRow(); // Insere nova linha na tabela
                        var celulaNome = novaLinha.insertCell(0);
                        var celulaQuantidade = novaLinha.insertCell(1);
                        var celulaPrecoUnitario = novaLinha.insertCell(2);
                        var celulaPrecoTotal = novaLinha.insertCell(3);
    
                        celulaNome.innerHTML = `<input id="nomeMat-${index}" type="text" value="${material.nomeMat}" >`;
                        celulaQuantidade.innerHTML = `<input id="qtdMat-${index}" type="number" value="${material.qtdMat}" >`;
                        celulaPrecoUnitario.textContent = material.preco_unit; // Preenche o preço unitário
                        celulaPrecoTotal.textContent = material.preco_total;
                    });
    
                    // Verifica se o botão "Salvar" já existe
                    if (!document.getElementById('btn-salvaredit')) {
                        var btnSalvaredit = document.createElement('button');
                        btnSalvaredit.id = 'btn-salvaredit';
                        btnSalvaredit.textContent = 'Salvar';
                        btnSalvaredit.classList.add('btn', 'btn-primary'); // Adiciona classes CSS, se necessário
    
                        // Adicionar o botão logo após a tabela
                        var tabelaContainer = document.getElementById('materiais-table');
                        tabelaContainer.parentNode.insertBefore(btnSalvaredit, tabelaContainer.nextSibling); // Insere após a tabela
                    }
    
                    // Exibir o modal
                    var modalDetalhes = document.getElementById('viewModal');
                    modalDetalhes.style.display = 'block';
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        } else {
            alert('Por favor, digite o código do material.');
        }
    });
    

    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
        console.log(isEditMode)
    
        // Captura os valores dos campos do modal
        let CodSolicitacao = document.getElementById('edit-codigo').value;
        let tituloSolicitacao = campoTitulo.value;
        let prioridadeSolicitacao = campoPrioridade.value;
    
        // Captura os fornecedores selecionados
        let fornecedores = [];
        let selectFornecedores = campoFornecedor;
        for (let i = 0; i < selectFornecedores.selectedOptions.length; i++) {
            let id_fornecedor = selectFornecedores.selectedOptions[i].value;
            fornecedores.push({
                'id_fornecedor':id_fornecedor});
        }
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let materiais = [];
        let linhas = document.querySelectorAll('#materiais-body tr'); // Seleciona todas as linhas da tabela

        linhas.forEach((linha, index) => {
            let nomeMaterial = linha.querySelector(`#nomeMat-${index}`).value; // Captura o valor do nome do material
            let qtdMaterial = linha.querySelector(`#qtdMat-${index}`).value; // Captura o valor da quantidade

            // Aqui você deve ter uma forma de identificar o id_material
            // Se você não tem um id_material específico, você pode precisar armazená-lo em algum lugar

            materiais.push({
                'nome_material': nomeMaterial,
                'qtd_material': qtdMaterial
            });
        });

    
        // Verifica se estamos em modo de edição ou adição
        if (isEditMode) {
            // Editar solicitação
            $.ajax({
                url: '../controller/AlterarPoCompra.php',
                method: 'POST',
                data: {
                    Codigo: CodSolicitacao,
                    Titulo: tituloSolicitacao,
                    Prioridade: prioridadeSolicitacao,
                    Fornecedores: JSON.stringify(fornecedores),
                    Materiais: JSON.stringify(materiais)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    window.location.href = "../view/SolicitacaoCompra.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        } else {
            // Adicionar nova solicitação
            $.ajax({
                url: '../controller/AddPoCompra.php',
                method: 'POST',
                data: {
                    Titulo: tituloSolicitacao,
                    Prioridade: prioridadeSolicitacao,
                    Fornecedores: JSON.stringify(fornecedores),
                    Materiais: JSON.stringify(materiais)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert("Solicitação de compra adicionada com sucesso!");
                    window.location.href = "../view/SolicitacaoCompra.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        }
    });
    

    btnExcluir.addEventListener('click', function() {
        var CodigoPO = document.querySelector('.product-id').value;
        if (CodigoPO) {
            if (confirm("Tem certeza que deseja excluir esta solicitação de compra?")) {
                $.ajax({
                    url: '../controller/ExcluirPoCompra.php',
                    method: 'POST',
                    data: { id_identificador: CodigoPO },
                    success: function(response) {
                        console.log('Material excluído com sucesso:', response);
                        alert("Excluído ou inativado com sucesso!");
                        window.location.href = "../view/SolicitacaoCompra.php";
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
        } else {
            alert('Por favor, digite o código do material.');
        }  
    });

    spanClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    var productIdInput = document.querySelector('.product-id');
    productIdInput.addEventListener('input', function(event) {
        var valorDigitado = event.target.value;
        console.log('Valor digitado:', valorDigitado);
    });
});
