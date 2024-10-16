document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnSalvarEdit = document.getElementById('btn-salvaredit');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var btnEntregue = document.getElementById('btn-entregue');
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

    btnEntregue.addEventListener('click', function() {
        var CodigoPO = document.querySelector('.product-id').value;
        let status = "Entregue";
        let materiais = [];
    
        // Primeira requisição: ConsultarPoCompra.php
        $.ajax({
            url: '../controller/ConsultarPoCompra.php',
            method: 'POST',
            data: {
                Codigo: CodigoPO
            },
            success: function(responseMat) {
                console.log("AQui", responseMat);
                let dados = typeof responseMat === "string" ? JSON.parse(responseMat) : responseMat;
                
                // Processa os dados retornados da consulta
                dados.forEach(function(item) {
                    let idMat = item.id_mat;
                    let qtdMat = item.qtdMat;
    
                    console.log("ID do material:", idMat);
                    console.log("Quantidade do material:", qtdMat);
    
                    // Adiciona ao array materiais
                    materiais.push({
                        'id_mat': idMat,
                        'qtdMat': qtdMat 
                    });
                });
    
                // Segunda requisição: ConcluirPoCompra.php (apenas após processar os materiais)
                console.log("Materiais prontos para envio:", materiais);
                if (CodigoPO) {
                    $.ajax({
                        url: '../controller/ConcluirPoCompra.php',
                        method: 'POST',
                        data: {
                            CodigoPO: CodigoPO,
                            Materiais: JSON.stringify(materiais),  // Passa os materiais corretamente aqui
                            Status: status
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
                } else {
                    alert('Por favor, digite o código do material.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
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
                    campoFornecedor.setAttribute('data-id-fornecedor', detalhes[0].id_forn);
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
    
                    detalhes.forEach(function(material, index) {
                        var novaLinha = tabelaMateriais.insertRow(); // Insere nova linha na tabela
                        var celulaNome = novaLinha.insertCell(0);
                        var celulaQuantidade = novaLinha.insertCell(1);
                        var celulaPrecoUnitario = novaLinha.insertCell(2);
                        var celulaPrecoTotal = novaLinha.insertCell(3);
    
                        celulaNome.innerHTML = `<input id="nomeMat-${index}" type="text" value="${material.nomeMat}" data-id-material="${material.id_mat}" >`;
                        celulaQuantidade.innerHTML = `<input id="qtdMat-${index}" type="number" value="${material.qtdMat}" >`;
                        celulaPrecoUnitario.textContent = material.preco_unit; // Preenche o preço unitário
                        celulaPrecoTotal.textContent = material.preco_total;
                    });
    
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

    btnSalvarEdit.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão

        var CodigoPO = document.querySelector('.product-id').value;

        let tituloSolicitacao = campoTitulo.value;
        let prioridadeSolicitacao = campoPrioridade.value;
        let status = campoStatus.value;
        let idForn = campoFornecedor.getAttribute('data-id-fornecedor'); 
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let materiais = [];
        let linhas = document.querySelectorAll('#materiais-body tr'); // Seleciona todas as linhas da tabela

        linhas.forEach((linha, index) => {
            let nomeMaterial = linha.querySelector(`#nomeMat-${index}`).value; // Captura o valor do nome do material
            let qtdMaterial = linha.querySelector(`#qtdMat-${index}`).value; // Captura o valor da quantidade
            let precoUnit = linha.querySelector('td:nth-child(3)').textContent; // Captura o valor unitário
            let precoTotal = linha.querySelector('td:nth-child(4)').textContent; // Captura o valor total
            let idMat = linha.querySelector(`#nomeMat-${index}`).getAttribute('data-id-material');

            // Aqui você deve ter uma forma de identificar o id_material
            // Se você não tem um id_material específico, você pode precisar armazená-lo em algum lugar

            materiais.push({
                'id_mat': idMat,
                'nome_material': nomeMaterial,
                'qtd_material': qtdMaterial,
                'preco_unit': precoUnit,
                'preco_total': precoTotal
            });
        });
            // Editar solicitação
            $.ajax({
                url: '../controller/AlterarPoCompra.php',
                method: 'POST',
                data: {
                    Codigo: CodigoPO,
                    Titulo: tituloSolicitacao,
                    Prioridade: prioridadeSolicitacao,
                    Fornecedores: idForn,
                    Status: status,
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
