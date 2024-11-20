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
    var isEditMode = false;  


    var campoTitulo = document.getElementById('edit-titulo');
    var campoFornecedor = document.getElementById('edit-Fornecedor');
    var campoMateriais = document.getElementById('edit-materiais');
    var campoPrecoTotal = document.getElementById('edit-preco-total');
    var campoPrioridade = document.getElementById('edit-prioridade');
    var campoStatus = document.getElementById('edit-status');
    var campoNF = document.getElementById('edit-nf');

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar material";

        
        document.getElementById('edit-titulo').style.display = 'block'; 
        document.getElementById('edit-prioridade').style.display = 'block'; 
        document.getElementById('edit-Fornecedor').style.display = 'block'; 
        document.getElementById('edit-materiais').style.display = 'block'; 
    
           
        document.getElementById('edit-preco-total-div').style.display = 'none'; 
        document.getElementById('edit-nf-div').style.display = 'none';
        document.getElementById('edit-status-div').style.display = 'none'; 
        document.getElementById('edit-prioridade-div').style.display = 'none';
        

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

                if (dados.length === 0) {
                    alert('Não existe nenhuma solicitação de compra com esse id');
                    return;  // Interrompe a execução do restante do código
                }
    
                // Verifica o status do primeiro item no array
                if (dados[0].status === "Entregue") {
                    alert('Solicitação de compra já entregue!');
                    return;
                }
                
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
                            alert("Solicitação de compra entregue com sucesso!");
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

                    if (detalhes[0] == undefined) {
                        alert('Não existe nenhuma solicitação de compra com esse id');
                        return;  // Interrompe a execução do restante do código
                    }

                    campoTitulo.value = detalhes[0].Titulo;
                     // Preencher o campo de Fornecedor com o valor selecionado
                    var campoFornecedor = document.getElementById('edit-Fornecedor');
                    var idFornecedorSelecionado = 0;
                    var idFornecedorSelecionado = detalhes[0].id_forn;

                     // Limpando seleção anterior no campo <select>
                     for (var i = 0; i < campoFornecedor.options.length; i++) {
                        campoFornecedor.options[i].selected = false;
                    }

                    const container = document.getElementById('quantidade-container');
                    container.innerHTML = ''; 
                
                    // Definir o valor selecionado no campo de fornecedores
                    for (var i = 0; i < campoFornecedor.options.length; i++) {
                        if (campoFornecedor.options[i].value == idFornecedorSelecionado) {
                            campoFornecedor.options[i].selected = true;
                            break;
                        }
                    }

                    var idsMateriaisSelecionados = detalhes.map(material => material.id_mat.toString());
                    var quantidadesMateriais = detalhes.map(material => material.qtdMat);


                    console.log(idsMateriaisSelecionados, quantidadesMateriais)

                    // Marca as opções selecionadas com base nos IDs de materiais retornados
                    for (var i = 0; i < campoMateriais.options.length; i++) {
                        var option = campoMateriais.options[i];
                        console.log(option, option.value)
                        if (idsMateriaisSelecionados.includes(option.value.toString())) {
                            option.selected = true;
                            console.log(option.selected)
                        }
                    }

                    // Chama a função de evento manualmente para exibir os campos de quantidade já selecionados
                    document.getElementById('edit-materiais').dispatchEvent(new Event('change'));
                 

                    idsMateriaisSelecionados.forEach((materialId, index) => {
                        const campoQuantidade = container.querySelector(`#quantidade-${materialId}`);
                        if (campoQuantidade) {
                            campoQuantidade.value = quantidadesMateriais[index];
                        }
                    });



                    campoFornecedor.setAttribute('data-id-fornecedor', detalhes[0].id_forn);
                    campoPrecoTotal.value = detalhes[0].total_preco;
                    campoPrioridade.value = detalhes[0].Prioridade;
                    campoStatus.value = detalhes[0].status;
                    campoNF.value = detalhes[0].NR_NF;

    
                    // Exibir o modal
                    var modalDetalhes = document.getElementById('editModal');
                    document.getElementById('edit-prioridade-select').style.display = 'none';
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


    function carregarMateriais(Codigo, selectMateriais, precoTotal) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../controller/ConsultarPoCompra.php',
                method: 'POST',
                data: { Codigo }, // Passando o código do material como parâmetro
                success: function(response) {
                    let materiais = [];
                    let materiaisData = typeof response === 'string' ? JSON.parse(response) : response;
    
                    selectMateriais.forEach(option => {
                        let idMaterial = option.value;
    
                        // Captura a quantidade para o material específico
                        let qtdMaterialInput = document.querySelector(`#quantidade-${idMaterial}`);
                        let qtdMaterial = qtdMaterialInput ? qtdMaterialInput.value : 0;
    
                        let materialInfo = materiaisData.find(material => material.id_mat === idMaterial);
    
                        materiais.push({
                            'id_mat': idMaterial,
                            'preco_unit': materialInfo ? materialInfo.preco_unit : null,
                            'preco_total': precoTotal,
                            'qtd_material': qtdMaterial
                        });
    
                    });
    
                    resolve(materiais);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar materiais:', error);
                    reject(error);
                }
            });
        });
    }
    
    

    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault();
    
        let CodSolicitacao = document.querySelector('.product-id').value;
        let tituloSolicitacao = document.getElementById('edit-titulo').value;
        let prioridadeSolicitacao = document.getElementById('edit-prioridade-select').value;
        let campoPrioridadeEdit = document.getElementById('edit-prioridade').value;
        let precoTotal = document.getElementById('edit-preco-total').value;
        let NF = document.getElementById('edit-nf').value;
        let Status = document.getElementById('edit-status').value;
    
        let fornecedores = [];
        let selectFornecedores = document.getElementById('edit-Fornecedor'); 
        for (let i = 0; i < selectFornecedores.selectedOptions.length; i++) {
            let id_fornecedor = selectFornecedores.selectedOptions[i].value;
            fornecedores.push({ 'id_fornecedor': id_fornecedor });
        }
    
        let selectMateriais = document.querySelectorAll('#edit-materiais option:checked');
    
        // Aguarda o carregamento de materiais antes de prosseguir
        carregarMateriais(CodSolicitacao, selectMateriais, precoTotal)
            .then(materiais => {

                if (isEditMode) {
                    if (
                        materiais.length === 0 ||
                        !materiais.every(item => item.qtd_material && item.qtd_material.trim()) ||
                        fornecedores.length === 0 ||
                        !tituloSolicitacao.trim() || 
                        !precoTotal.trim() ||
                        !NF.trim() ||
                        !Status.trim() ||
                        !campoPrioridadeEdit.trim()
                    ) {
                        alert('Todos os campos são obrigatórios e devem ser preenchidos.');
                        return;
                    }
                } else {
                    if (
                        materiais.length === 0 || 
                        fornecedores.length === 0 ||
                        !materiais.every(item => item.qtd_material && item.qtd_material.trim()) ||
                        !tituloSolicitacao.trim() || 
                        !prioridadeSolicitacao.trim()
                    ) {
                        alert('Todos os campos são obrigatórios e devem ser preenchidos.');
                        return;
                    }
                }
                
                
    
                if (isEditMode) {
                    console.log('Modo de edição. Salvando...');
                    $.ajax({
                        url: '../controller/AlterarPoCompra.php',
                        method: 'POST',
                        data: {
                            Codigo: CodSolicitacao,
                            Titulo: tituloSolicitacao,
                            Prioridade: prioridadeSolicitacao,
                            nf: NF,
                            Status: Status,
                            Fornecedores: JSON.stringify(fornecedores),
                            Materiais: JSON.stringify(materiais)
                        },
                        success: function(response) {
                            console.log('Requisição AJAX bem-sucedida:', response);
                            alert('Solicitação de compra alterada com sucesso!');
                            window.location.href = "../view/SolicitacaoCompra.php";
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro na requisição AJAX:', error);
                            alert('Erro');
                        }
                    });
                } else {
                    console.log('Modo de adição. Salvando...');
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
                            console.log('Requisição AJAX bem-sucedida:', response);
                            alert('Solicitação de compra adicionada com sucesso!');
                            window.location.href = "../view/SolicitacaoCompra.php";
                        },
                        error: function(xhr, status, error) {
                            console.error('Erro na requisição AJAX:', error);
                            alert('Erro!');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Erro ao carregar materiais:', error);
                alert('Erro ao carregar materiais. Verifique os dados.');
            });
    });
    

    btnSalvarEdit.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão

        console.log('eu')

        var CodigoPO = document.querySelector('.product-id').value;

        let tituloSolicitacao = campoTitulo.value;
        let prioridadeSolicitacao = campoPrioridade.value;
        let status = campoStatus.value;
        let idForn = campoFornecedor.getAttribute('data-id-fornecedor'); 
        let nf = campoNF.value;
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let materiais = [];
        let linhas = document.querySelectorAll('#materiais-body tr'); // Seleciona todas as linhas da tabela

        

        linhas.forEach((linha, index) => {
            let qtdMaterial = linha.querySelector(`#qtdMat-${index}`).value; // Captura o valor da quantidade
            let precoTotal = linha.querySelector('td:nth-child(4)').textContent; // Captura o valor total
            let idMat = linha.querySelector(`#nomeMat-${index}`).getAttribute('data-id-material');

            materiais.push({
                'id_mat': idMat,
                'qtd_material': qtdMaterial,
                'preco_total': precoTotal
            });
        });

        if (!tituloSolicitacao || !prioridadeSolicitacao || !campoPrecoTotal || !nf || !idForn || !status || !materiais[0].nome_material || !materiais[0].qtd_material
        ) {
            
            alert('Todos os campos são obrigatórios e devem ser preenchidos.');
            return;  // Interrompe a execução se algum campo estiver vazio
            }
            // Editar solicitação
            $.ajax({
                url: '../controller/AlterarPoCompra.php',
                method: 'POST',
                data: {
                    Codigo: CodigoPO,
                    Titulo: tituloSolicitacao,
                    Prioridade: prioridadeSolicitacao,
                    nf: nf,
                    Fornecedores: idForn,
                    Status: status,
                    Materiais: JSON.stringify(materiais)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert('Solicitação de compra alterada com sucesso!')
                    window.location.href = "../view/SolicitacaoCompra.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                    alert('Erro!')
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
                        console.log(response)
                        let responseParse = JSON.parse(response);

                        if (!responseParse.success) {  // Verifica o campo "success" no JSON
                            alert('Não existe nenhuma solicitação de compra com esse id');
                            return;
                        }

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
