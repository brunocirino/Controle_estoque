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

        document.getElementById('edit-codigo').value = '';
        document.getElementById('edit-nome').value = '';
        document.getElementById('edit-descricao').value = '';
        document.getElementById('edit-status').value = '';
        document.getElementById('edit-estoqueMin').value = '';
        document.getElementById('edit-estoqueAtual').value = '';
        document.getElementById('edit-preco').value = '';
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

                    if (material == undefined) {
                        alert('Não existe nenhum material com esse id');
                        return;  // Interrompe a execução do restante do código
                    }

                    var codMat = material.codMat;
                    var nomeMat = material.nomeMat;
                    var descMat = material.descMat;
                    var status = material.status;
                    var estoqueMin = material.estoqueMin;
                    var estoqueAtual = material.estoqueAtual;
                    var preco = material.preco;

                    document.getElementById('edit-codigo').value = codMat;
                    document.getElementById('edit-nome').value = nomeMat;
                    document.getElementById('edit-descricao').value = descMat;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-estoqueMin').value = estoqueMin;
                    document.getElementById('edit-estoqueAtual').value = estoqueAtual;
                    document.getElementById('edit-preco').value = preco;

                    Titulo.textContent = "Editar Material";

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

    btnSalvar.addEventListener('click', function(){
        event.preventDefault();
        var CodigoMat = document.getElementById('edit-codigo').value;
        var nomeMat = document.getElementById('edit-nome').value;
        var descMat = document.getElementById('edit-descricao').value;
        var statusMat = document.getElementById('edit-status').value;
        var estoqueMin = document.getElementById('edit-estoqueMin').value;
        var estoqueAtual = document.getElementById('edit-estoqueAtual').value;
        var preco = document.getElementById('edit-preco').value;

        console.log(statusMat)

        function isNumber(value) {
            return !isNaN(value) && value.trim() !== "";  // Confirma que é um número e não está vazio
        }

        if (!nomeMat || !descMat || !statusMat ||
        !estoqueMin || !estoqueAtual  || 
        !preco) {
        
        alert('Todos os campos são obrigatórios e devem ser preenchidos.');
        return;  // Interrompe a execução se algum campo estiver vazio
        }
    
        // Verificação para garantir que todos os campos contenham números
        if (!isNumber(preco) || !isNumber(estoqueMin) || !isNumber(estoqueAtual)) {
            alert('Os campos Preço, Estoque Mínimo e Estoque Atual devem conter apenas números.');
            return; // Interrompe a execução se algum campo não for numérico
        }

        

       if(isEditMode){
        $.ajax({
            url: '../controller/AlterarMaterial.php',
            method: 'POST',
            data: {CodMat: CodigoMat, NomeMat: nomeMat, DescMat: descMat, StatusMat: statusMat, EstoqueMin: estoqueMin, EstoqueAtual: estoqueAtual, preco : preco},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert('Material alterado com sucesso');
                window.location.href = "../view/Material.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }else{
        $.ajax({
            url: '../controller/AddMaterial.php',
            method: 'POST',
            data: {CodMat: CodigoMat, NomeMat: nomeMat, DescMat: descMat, StatusMat: statusMat, EstoqueMin: estoqueMin, EstoqueAtual: estoqueAtual, preco : preco},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert('Material adicionado com sucesso')
                window.location.href = "../view/Material.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }
    })

    btnExcluir.addEventListener('click', function() {
        var CodigoMat = document.querySelector('.product-id').value;
        if (CodigoMat) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                $.ajax({
                    url: '../controller/ExcluirMaterial.php',
                    method: 'POST',
                    data: { CodMat: CodigoMat },
                    success: function(response) {
                        response = JSON.parse(response); 
                        
                        if (!response.success) {  // Verifica o campo "success" no JSON
                            alert('Não existe nenhum usuário com esse id');
                            return;
                        }
                        console.log('Material excluído com sucesso:', response);
                        alert("Excluído ou inativado com sucesso!");
                        window.location.href = "../view/Material.php";
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
