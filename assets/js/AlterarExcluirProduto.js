import { TrazerTodosMateriais } from './ProdutoSelectMaterial.js';

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var Titulo = document.getElementById('modal-title');
    var isEditMode = false;  // Variável para rastrear o modo atual
    var idMaterial;
    var QTDMaterial;
    var ProcessoTotal;

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar produto";

        document.getElementById('edit-codigo').value = '';
        document.getElementById('edit-nome').value = '';
        document.getElementById('edit-quantidade').value = '';
        document.getElementById('edit-estado').value = '';
        document.querySelectorAll('.edit-material').forEach(function(select) {
            select.value = '';
        });
        document.querySelectorAll('.edit-processos').forEach(function(input) {
            input.value = '';
        });

    });
    
    // Adicionar event listener para o primeiro select
   

    btnEditar.addEventListener('click', function() {
        isEditMode = true; // Definir modo de edição
        var CodigoProd = document.querySelector('.product-id').value;
    
        if (CodigoProd) {
            // Primeiro, busque todos os materiais
            TrazerTodosMateriais().then(() => {
                // Depois busque os detalhes do produto para edição
                return $.ajax({
                    url: '../controller/ConsultarProduto.php',
                    method: 'POST',
                    data: { Codigo: CodigoProd },
                });
            }).then(produtoResponse => {
                console.log('Requisição AJAX bem sucedida:', produtoResponse);
                var produtos = JSON.parse(produtoResponse);
    
                // Objeto para armazenar dados do produto
                var produtoData = {};
    
                produtos.forEach(produto => {
                    if (!produtoData[produto.id_identificador]) {
                        produtoData[produto.id_identificador] = {
                            nomeProd: produto.nomeProd,
                            qtdProd: produto.qtdProd,
                            estadoProd: produto.estadoProd,
                            materiais_nomes: [produto.materiais_nomes],
                            materiais_ids: [produto.materiais_ids],
                            materiais_qtd: [produto.materiais_qtd],
                        };
                    } else {
                        produtoData[produto.id_identificador].materiais_nomes.push(produto.materiais_nomes);
                        produtoData[produto.id_identificador].materiais_ids.push(produto.materiais_ids);
                        produtoData[produto.id_identificador].materiais_qtd.push(produto.materiais_qtd);
                    }
                });
    
                var produtoFinal = Object.values(produtoData)[0]; // Pega o primeiro produto (se existir)
    
                if (produtoFinal) {
                    document.getElementById('edit-nome').value = produtoFinal.nomeProd;
                    document.getElementById('edit-quantidade').value = produtoFinal.qtdProd;
                    document.getElementById('edit-estado').value = produtoFinal.estadoProd;
    
                    // Adiciona os materiais ao select
                    var materiaisSelect = document.getElementById('edit-materiais');
                    materiaisSelect.innerHTML = ''; // Limpa o select antes de adicionar
    
                    // Popula o select com todos os materiais e seleciona os já associados ao produto
                    materiaisCadastrados.forEach(material => {
                        const option = document.createElement("option");
                        option.value = material.id;
                        option.textContent = material.nome;
    
                        // Verifica se o material já está associado ao produto e seleciona
                        if (produtoFinal.materiais_ids.includes(material.id)) {
                            option.selected = true; // Marca como selecionado
                        }
    
                        materiaisSelect.appendChild(option);
                    });
    
                    // Adiciona a quantidade correspondente aos materiais
                    var quantidadeContainer = document.getElementById('quantidade-container');
                    quantidadeContainer.innerHTML = ''; // Limpa o container antes de adicionar
    
                    produtoFinal.materiais_qtd.forEach((qtd, index) => {
                        var materialId = produtoFinal.materiais_ids[index];
                        var materialNome = produtoFinal.materiais_nomes[index];
    
                        var div = document.createElement('div');
                        div.innerHTML = `
                            <label for="quantidade-${materialId}">Quantidade de ${materialNome}:</label>
                            <input type="number" id="quantidade-${materialId}" name="quantidades[${materialId}]" value="${qtd}" min="0">
                        `;
                        quantidadeContainer.appendChild(div);
                    });
    
                    Titulo.textContent = "Editar Produto";
                    modal.style.display = 'block'; // Exibe o modal
                }
            }).catch(error => {
                console.error('Erro na requisição AJAX:', error);
            });
        } else {
            alert('Por favor, digite o código do material.');
        }
    });
    

    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
    
        let CodProd = document.querySelector('.product-id').value; // Declarar a variável
        let nomeProd = document.getElementById('edit-nome').value;
        let qtdProd = document.getElementById('edit-quantidade').value;
        let estadoProd = document.getElementById('edit-estado').value;
    
        // Captura os materiais selecionados e suas respectivas quantidades
        let materiais = [];  // Array para armazenar os materiais selecionados
        let selectMateriais = document.getElementById('edit-materiais');
    
        // Percorre as opções selecionadas no campo de seleção múltipla
        for (let i = 0; i < selectMateriais.selectedOptions.length; i++) {
            let id_material = selectMateriais.selectedOptions[i].value;  // Obtém o valor do ID do material
            let qtd_material = document.getElementById('quantidade-' + id_material).value;  // Obtém a quantidade correspondente
    
            materiais.push({
                'id_material': id_material,
                'qtd_material': qtd_material
            });
        }
    
        if (isEditMode) {
            $.ajax({
                url: '../controller/AlterarProduto.php',
                method: 'POST',
                data: {
                    Codigo: CodProd,
                    Nome: nomeProd,
                    QtdProd: qtdProd,
                    EstadoProd: estadoProd,
                    Materiais: JSON.stringify(materiais)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    window.location.href = "../view/Produto.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        } else {
            $.ajax({
                url: '../controller/AddProduto.php',
                method: 'POST',
                data: {
                    Codigo: CodProd,
                    Nome: nomeProd,
                    QtdProd: qtdProd,
                    EstadoProd: estadoProd,
                    Materiais: JSON.stringify(materiais)
                },
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    alert("Produto adicionado com sucesso!");
                    window.location.href = "../view/Produto.php";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
        }
    });
    
    btnExcluir.addEventListener('click', function() {
        var CodigoProd = document.querySelector('.product-id').value;
        if (CodigoProd) {
            if (confirm("Tem certeza que deseja excluir este produto?")) {
                $.ajax({
                    url: '../controller/ExcluirProduto.php',
                    method: 'POST',
                    data: { CodProd: CodigoProd },
                    success: function(response) {
                        console.log('Produto excluído com sucesso:', response);
                        alert("Excluído com sucesso!");
                        window.location.href = "../view/Produto.php";
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
        } else {
            alert('Por favor, digite o código do Produto.');
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
