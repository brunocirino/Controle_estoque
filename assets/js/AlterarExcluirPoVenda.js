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

    var campoEdit_Preco_total = document.getElementById('div-edit-preco-total')
    var campoEdit_status = document.getElementById('div-edit-status')
    var campoEdit_NF = document.getElementById('div-edit-nf')

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        campoEdit_NF.style.display = 'none'
        campoEdit_Preco_total.style.display = 'none'
        campoEdit_status.style.display = 'none'
        btnSalvarEdit.style.display = 'block'
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar material";

        document.getElementById('edit-titulo').value = '';
        document.getElementById('edit-Cliente').value = '';
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

                    document.getElementById('edit-preco-total').removeAttribute('readonly');
                    document.getElementById('edit-status').removeAttribute('readonly');
                    document.getElementById('edit-nf').removeAttribute('readonly');



                    var campoProduto = document.getElementById('edit-Produtos');
                    

                    // Limpando seleção anterior no campo <select>
                    for (var i = 0; i < campoProduto.options.length; i++) {
                        campoProduto.options[i].selected = false;
                    }

                    var container = document.getElementById('quantidade-container');
                     // Limpando os campos de quantidade existentes
                     container.innerHTML = ''; // Remove todos os campos anteriores

                    
                    var idsProdutoSelecionados = detalhes.map(Produto => Produto.codProd);
                    var quantidadesProduto = detalhes.map(Produto => Produto.qtdProd);

                    // Marca as opções selecionadas com base nos IDs de materiais retornados
                    for (var i = 0; i < campoProduto.options.length; i++) {
                        var option = campoProduto.options[i];
                        if (idsProdutoSelecionados.includes(option.value)) {
                            option.selected = true;
                        }
                    }

                    document.getElementById('edit-Produtos').dispatchEvent(new Event('change'));

                    // Definir as quantidades nos campos após a geração
                    idsProdutoSelecionados.forEach((codProd, index) => {
                        const campoQuantidade = container.querySelector(`#quantidade-${codProd}`);
                        if (campoQuantidade) {
                            campoQuantidade.value = quantidadesProduto[index];
                        }
                    });


                    var campoCliente = document.getElementById('edit-Cliente');

                    for (var i = 0; i < campoCliente.options.length; i++) {
                        campoCliente.options[i].selected = false;
                    }
                    
                    var idsClientesSelecionados = detalhes.map(Clientes => Clientes.codCli);
                   
                    // Marca as opções selecionadas com base nos IDs de materiais retornados
                    for (var i = 0; i < campoCliente.options.length; i++) {
                        var option = campoCliente.options[i];
                        if (idsClientesSelecionados.includes(option.value)) {
                            option.selected = true;
                        }
                    }

    
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
    
    function carregarProdutos(Codigo, selectProdutos, selectClientes, preco_total_PO) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../controller/ConsultarPoVenda.php',
                method: 'POST',
                data: { Codigo }, // Passando o código do material como parâmetro
                success: function(response) {
                    let produtos = [];
                    let clientes = [];
                    let ProdutosData = typeof response === 'string' ? JSON.parse(response) : response;
    
                    // Processa os produtos
                    selectProdutos.forEach(option => {
                        let idProduto = option.value; // ID do produto
                        let nomeProduto = option.textContent; // Nome do produto
    
                        // Captura a quantidade para o produto específico
                        let qtdProdutoInput = document.querySelector(`#quantidade-${idProduto}`);
                        let qtdProduto = qtdProdutoInput ? qtdProdutoInput.value : 0;
    
                        let ProdutoInfo = ProdutosData.find(Produto => Produto.codProd === idProduto);
    
                        produtos.push({
                            'idProduto': idProduto,
                            'nomeProduto': nomeProduto,
                            'preco_unit': ProdutoInfo ? ProdutoInfo.prcUnitProd : null,
                            'qtdProduto': qtdProduto,
                            'preco_total': preco_total_PO
                        });
                    });
                    
                    selectClientes.forEach(option => {
                        let idCliente = option.value; // ID do produto
                        let nomeCliente = option.textContent; // Nome do produto

    
                        let ClienteInfo = ProdutosData.find(Cliente => Cliente.codCli === idCliente);
    
                        clientes.push({
                            'id_cliente': idCliente,
                            'nmCli': nomeCliente,
                            'cpfCli': ClienteInfo ? ClienteInfo.cpfCli : null,
                        });
                    });

                    const data = {
                        produtos: produtos,
                        clientes: clientes
                    };
    
                    console.log("Produtos:", JSON.stringify(produtos, null, 2));
                    console.log("Clientes:", JSON.stringify(clientes, null, 2));
    
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar produtos:', error);
                    reject(error);
                }
            });
        });
    }
    

    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
        console.log(isEditMode)

        let CodSolicitacao = document.querySelector('.product-id').value;
        let tituloSolicitacao = document.getElementById('edit-titulo').value;
        let preco_total_PO = document.getElementById('edit-preco-total').value;
        let status = document.getElementById('edit-status').value;
        let nf = document.getElementById('edit-nf').value;
        let prioridadeSolicitacao = document.getElementById('edit-prioridade').value;


        let selectProdutos = document.querySelectorAll('#edit-Produtos option:checked'); // Seleciona os produtos selecionados

        let selectClientes = document.querySelectorAll('#edit-Cliente option:checked'); // Seleciona os produtos selecionados

        carregarProdutos(CodSolicitacao, selectProdutos, selectClientes, preco_total_PO)
            .then(({ produtos, clientes})  => {
            // Verifica se estamos em modo de edição ou adição
            if (isEditMode) {

                if (
                    produtos.length === 0 ||
                    !produtos.every(item => item.qtdProduto && item.qtdProduto.trim()) ||
                    clientes.length === 0 ||
                    !tituloSolicitacao.trim() || 
                    !preco_total_PO.trim() ||
                    !nf.trim() ||
                    !status.trim()
                ) {
                    alert('Todos os campos são obrigatórios e devem ser preenchidos.');
                    return;
                }
                    // Editar solicitação
                    $.ajax({
                        url: '../controller/AlterarPoVenda.php',
                        method: 'POST',
                        data: {
                            Codigo: CodSolicitacao,
                            NF: nf,
                            Titulo: tituloSolicitacao,
                            preco_total_PO: preco_total_PO,
                            Status: status,
                            produtos: JSON.stringify(produtos),
                            clientes: JSON.stringify(clientes), 
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
            }else{
                if (
                    produtos.length === 0 ||
                    !produtos.every(item => item.qtdProduto && item.qtdProduto.trim()) ||
                    clientes.length === 0 ||
                    !tituloSolicitacao.trim() ||
                    !prioridadeSolicitacao.trim()

                ) {
                    alert('Todos os campos são obrigatórios e devem ser preenchidos.');
                    return;
                }
                $.ajax({
                    url: '../controller/AddPoVenda.php',
                    method: 'POST',
                    data: {
                        Titulo: tituloSolicitacao,
                        Clientes: JSON.stringify(clientes), 
                        Produtos: JSON.stringify(produtos), 
                        Status: status,
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
            }
        })
        .catch(error => {
            console.error('Erro ao carregar produtos:', error.message || error);
            alert('Erro ao carregar produtos. Verifique os dados.');
        });
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
            let precoTotal = qtdProduto * precoUnit;
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
