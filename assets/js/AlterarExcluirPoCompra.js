document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var Titulo = document.getElementById('modal-title');
    var isEditMode = false;  // Variável para rastrear o modo atual

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
                url: '../controller/ConsultarMaterial.php',
                method: 'POST',
                data: { Codigo: CodigoMat }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var material = JSON.parse(response)[0];

                    console.log(material);
                    var codMat = material.codMat;
                    var nomeMat = material.nomeMat;
                    var descMat = material.descMat;
                    var status = material.status;
                    var estoqueMin = material.estoqueMin;
                    var estoqueAtual = material.estoqueAtual;
                    var contMov = material.contMov;

                    document.getElementById('edit-codigo').value = codMat;
                    document.getElementById('edit-nome').value = nomeMat;
                    document.getElementById('edit-descricao').value = descMat;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-estoqueMin').value = estoqueMin;
                    document.getElementById('edit-estoqueAtual').value = estoqueAtual;
                    document.getElementById('edit-movimentacao').value = contMov;

                    Titulo.textContent = "Editar Material";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
            modal.style.display = 'block';
        } else {
            alert('Por favor, digite o código do material.');
        }
    });

    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
        console.log(isEditMode)
    
        // Captura os valores dos campos do modal
        let CodSolicitacao = document.getElementById('edit-codigo').value;
        let tituloSolicitacao = document.getElementById('edit-titulo').value;
        let prioridadeSolicitacao = document.getElementById('edit-prioridade').value;
    
        // Captura os fornecedores selecionados
        let fornecedores = [];
        let selectFornecedores = document.getElementById('edit-Fornecedor');
        for (let i = 0; i < selectFornecedores.selectedOptions.length; i++) {
            let id_fornecedor = selectFornecedores.selectedOptions[i].value;
            fornecedores.push({
                'id_fornecedor':id_fornecedor});
        }
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let materiais = [];
        let selectMateriais = document.getElementById('edit-materiais');
        for (let i = 0; i < selectMateriais.selectedOptions.length; i++) {
            let id_material = selectMateriais.selectedOptions[i].value;  // Obtém o valor do ID do material
            let qtd_material = document.getElementById('quantidade-' + id_material).value;  // Obtém a quantidade correspondente
        
            materiais.push({
                'id_material': id_material,
                'qtd_material': qtd_material
            });
        }
    
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
