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
        isEditMode = true;  // Definir modo de edição
        var CodigoProd = document.querySelector('.product-id').value;
        if (CodigoProd) {
            $.ajax({
                url: '../controller/ConsultarProduto.php',
                method: 'POST',
                data: { Codigo: CodigoProd }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var Produto = JSON.parse(response)[0];

                    console.log(Produto);
                    
                    CodProd = Produto.codMat;
                    nomeProd = Produto.nomeProd;
                    qtdProd = Produto.qtdProd;
                    estadoProd = Produto.estadoProd;

                    document.getElementById('edit-nome').value = nomeProd;
                    document.getElementById('edit-quantidade').value = qtdProd;
                    document.getElementById('edit-estado').value = estadoProd;

                    Titulo.textContent = "Editar Produto";
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

    function concatenarQuantidadesMateriais() {
        var inputsQuantidade = document.querySelectorAll('.edit-qtdMat');
        var quantidadesMateriais = ''; // Inicializar como uma string vazia
        
        inputsQuantidade.forEach(function(input) {
            var quantidade = input.value.trim();
            if (quantidade !== '') {
                // Concatenar a quantidade do material à string
                quantidadesMateriais += quantidade + ',';
            }

            QTDMaterial = quantidadesMateriais;
        });
    
        // Remover a vírgula extra no final da string, se houver
        quantidadesMateriais = quantidadesMateriais.slice(0, -1);
    
        console.log('Quantidades de materiais inseridas:', quantidadesMateriais);
        return quantidadesMateriais;
    }

    btnSalvar.addEventListener('click', function(){
        event.preventDefault();
        CodProd = document.querySelector('.product-id').value;
        nomeProd = document.getElementById('edit-nome').value ;
        qtdProd = document.getElementById('edit-quantidade').value ;
        estadoProd = document.getElementById('edit-estado').value;

       if(isEditMode){
        $.ajax({
            url: '../controller/AlterarProduto.php',
            method: 'POST',
            data: {Codigo: CodProd, Nome: nomeProd, QtdProd: qtdProd, EstadoProd: estadoProd},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                window.location.href = "../view/Produto.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }else{
        $.ajax({
            url: '../controller/AddProduto.php',
            method: 'POST',
            data: {Codigo: CodProd, Nome: nomeProd, QtdProd: qtdProd, EstadoProd: estadoProd},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert("Produto adicionado com sucesso!");
                window.location.href = "../view/Produto.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }
    })

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
