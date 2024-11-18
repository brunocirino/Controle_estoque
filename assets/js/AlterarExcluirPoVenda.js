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
    var campoCPF = document.getElementById('view-cpf-cliente');
    var campoCliente = document.getElementById('view-cliente');
    var campoPrecoTotal = document.getElementById('view-preco-total');
    var campoStatus = document.getElementById('view-status');
    var campoNF = document.getElementById('view-nf');

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        btnSalvarEdit.style.display = 'block'
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar material";

        document.getElementById('edit-titulo').value = '';
        document.getElementById('edit-Cliente').value = '';
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
        var CodigoPoVenda = document.querySelector('.product-id').value;
        if (CodigoPoVenda) {
            $.ajax({
                url: '../controller/ConsultarPoVenda.php',
                method: 'POST',
                data: { Codigo: CodigoPoVenda }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var detalhes = JSON.parse(response);

                    if (detalhes[0] == undefined) {
                        alert('Não existe nenhuma solicitação de venda com esse id');
                        return;  // Interrompe a execução do restante do código
                    }
                    
                    document.getElementById('edit-titulo').value = detalhes[0].Titulo;
                    document.getElementById('edit-preco-total').value = detalhes[0].preco_total_PO;
                    document.getElementById('edit-status').value = detalhes[0].status;
                    document.getElementById('edit-nf').value = detalhes[0].NR_NF;

                    var campoProduto = document.getElementById('edit-Produtos');

                    
                    var idsProdutoSelecionados = detalhes.map(Produto => Produto.id_mat);
                    var quantidadesProduto = detalhes.map(Produto => Produto.qtdMat);

                    // Marca as opções selecionadas com base nos IDs de materiais retornados
                    for (var i = 0; i < campoProduto.options.length; i++) {
                        var option = campoProduto.options[i];
                        if (idsProdutoSelecionados.includes(option.value)) {
                            option.selected = true;
                        }
                    }

                    document.getElementById('edit-Produtos').dispatchEvent(new Event('change'));

                    // Definir as quantidades nos campos após a geração
                    const container = document.getElementById('quantidade-container');
                    idsProdutoSelecionados.forEach((codProd, index) => {
                        const campoQuantidade = container.querySelector(`#quantidade-${codProd}`);
                        if (campoQuantidade) {
                            campoQuantidade.value = quantidadesProduto[index];
                        }
                    });

    
                    // Exibir o modal
                    var modal = document.getElementById('editModal');
                    modal.style.display = 'block';
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
        let tituloSolicitacao = document.getElementById('edit-titulo').value;

        let Clientes = [];
        let selectClientes = document.getElementById('edit-Cliente'); // Certifique-se de selecionar o elemento correto

        for (let i = 0; i < selectClientes.selectedOptions.length; i++) {
            let id_Cliente = selectClientes.selectedOptions[i].value;
            Clientes.push({
                'id_cliente': id_Cliente
            });
        }

        console.log(Clientes); // Exibe os clientes selecionados no console para verificação

        
    
        // Captura os produtos selecionados e suas respectivas quantidades
        let produtos = [];
        let selectProdutos = document.querySelectorAll('#edit-Produtos option:checked'); // Seleciona os produtos selecionados

        selectProdutos.forEach(option => {
            let idProduto = option.value; // ID do produto
            let nomeProduto = option.textContent; // Nome do produto

            // Captura a quantidade para o produto específico
            let qtdProdutoInput = document.querySelector(`#quantidade-${idProduto}`);
            let qtdProduto = qtdProdutoInput ? qtdProdutoInput.value : 0;

            produtos.push({
                'idProduto': idProduto,
                'nomeProduto': nomeProduto,
                'qtdProduto': qtdProduto
            });
        });

        console.log(produtos); 

        if (Clientes.length === 0 || produtos.length === 0 || !tituloSolicitacao || !produtos[0].qtdProduto) {
            alert('Todos os campos são obrigatórios e devem ser preenchidos.');
            return;
        }

    
        // Verifica se estamos em modo de edição ou adição
        if (isEditMode) {
            // Editar solicitação
            $.ajax({
                url: '../controller/AlterarPoVenda.php',
                method: 'POST',
                data: {
                    Codigo: CodSolicitacao,
                    Titulo: tituloSolicitacao,
                    Clientes: JSON.stringify(Clientes),
                    Produtos: JSON.stringify(Produtos)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert('solicitação de venda alterada com sucesso!')
                    window.location.href = "../view/SolicitacaoCompra.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        } else {
            // Adicionar nova solicitação
            $.ajax({
                url: '../controller/AddPoVenda.php',
                method: 'POST',
                data: {
                    Titulo: tituloSolicitacao,
                    Clientes: JSON.stringify(Clientes),
                    Produtos: JSON.stringify(produtos)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert("Solicitação de venda adicionada com sucesso!");
                    window.location.href = "../view/SolicitacaoVenda.php";
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
        let nmCliente = campoCliente.value;
        let cpfCliente = campoCPF.value;
        let status = campoStatus.value;
        let preco_total_PO = campoPrecoTotal.value;
        let nf = campoNF.value;

        console.log(nf);
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let produtos = [];
        let linhas = document.querySelectorAll('#produtos-body tr'); 

        linhas.forEach((linha, index) => {
            let nomeProduto = linha.querySelector(`td:nth-child(1)`).value; 
            let qtdProduto = linha.querySelector(`#qtdProd-${index}`).value; 
            let precoUnit = linha.querySelector('td:nth-child(3)').textContent;
            let precoTotal = linha.querySelector('td:nth-child(4)').textContent; 
            let idProd = linha.querySelector(`#nomeProd-${index}`).getAttribute('data-id-produto');


            produtos.push({
                'id_prod': idProd,
                'nome_prod': nomeProduto,
                'qtd_prod': qtdProduto,
                'preco_unit': precoUnit,
                'preco_total': precoTotal
            });

            console.log(produtos)
        });

        if (
            !nmCliente || 
            !cpfCliente || 
            !tituloSolicitacao || 
            !status || 
            !preco_total_PO || 
            !nf || 
            produtos.length === 0 || 
            !produtos[0].qtd_prod ||
            isNaN(parseFloat(cpfCliente)) || 
            isNaN(parseFloat(preco_total_PO)) || 
            isNaN(parseFloat(nf)) 
        ) {
            alert('Todos os campos são obrigatórios, devem ser preenchidos, e certos campos devem ser números.');
            return;
        }
        

            // Editar solicitação
            $.ajax({
                url: '../controller/AlterarPoVenda.php',
                method: 'POST',
                data: {
                    Codigo: CodigoPO,
                    NF: nf,
                    Titulo: tituloSolicitacao,
                    nmCliente: nmCliente,
                    cpfCliente: cpfCliente,
                    preco_total_PO: preco_total_PO,
                    Status: status,
                    produtos: JSON.stringify(produtos)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert('solicitação de venda alterada com sucesso!')
                    window.location.href = "../view/SolicitacaoVenda.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
    });
    

    btnExcluir.addEventListener('click', function() {
        var CodigoPO = document.querySelector('.product-id').value;
        if (CodigoPO) {
            if (confirm("Tem certeza que deseja excluir esta solicitação de venda?")) {
                $.ajax({
                    url: '../controller/ExcluirPoVenda.php',
                    method: 'POST',
                    data: { id_identificador: CodigoPO },
                    success: function(response) {
                        console.log(response)
                        let responseParse = JSON.parse(response);

                        if (!responseParse.success) {  // Verifica o campo "success" no JSON
                            alert('Não existe nenhuma solicitação de venda com esse id');
                            return;
                        }

                        console.log('Solicitação de venda excluída com sucesso:', response);
                        alert("Excluído ou inativado com sucesso!");
                        window.location.href = "../view/SolicitacaoVenda.php";
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
        } else {
            alert('Por favor, digite o código da solicitação.');
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
