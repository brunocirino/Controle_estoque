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
        Titulo.textContent = "Adicionar Fornecedor";

        document.getElementById('edit-codigo').value = '';
        document.getElementById('edit-nome').value = '';
        document.getElementById('edit-fantasia').value = '';
        document.getElementById('edit-CNPJ').value = '';
        document.getElementById('edit-telefone').value = '';
        document.getElementById('edit-contactante').value = '';
        document.getElementById('edit-faturamento').value = '';
        document.getElementById('edit-entrega').value = '';
        document.getElementById('edit-cobrança').value = '';
    });

    btnEditar.addEventListener('click', function() {
        isEditMode = true;  // Definir modo de edição
        var CodigoForn = document.querySelector('.product-id').value;
        if (CodigoForn) {
            $.ajax({
                url: '../controller/ConsultarFornecedor.php',
                method: 'POST',
                data: { Codigo: CodigoForn }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var fornecedor = JSON.parse(response)[0];

                    if (fornecedor == undefined) {
                        alert('Não existe nenhum fornecedor com esse id');
                        return;  // Interrompe a execução do restante do código
                    }

                    console.log(fornecedor);
                    var codForn = fornecedor.id;
                    var nome = fornecedor.Nome;
                    var nomeFantasia = fornecedor.nomeFantasia;
                    var CNPJ = fornecedor.CNPJ;
                    var Telefone = fornecedor.Telefone;
                    var Contactante = fornecedor.Contactante;
                    var endFaturamento = fornecedor.endFaturamento;
                    var endEntrega = fornecedor.endEntrega;
                    var endCobranca = fornecedor.endCobranca;

                    document.getElementById('edit-codigo').value = codForn;
                    document.getElementById('edit-nome').value = nome;
                    document.getElementById('edit-fantasia').value = nomeFantasia;
                    document.getElementById('edit-CNPJ').value = CNPJ;
                    document.getElementById('edit-telefone').value = Telefone;
                    document.getElementById('edit-contactante').value = Contactante;
                    document.getElementById('edit-faturamento').value = endFaturamento;
                    document.getElementById('edit-entrega').value = endEntrega;
                    document.getElementById('edit-cobrança').value = endCobranca;

                    Titulo.textContent = "Editar Fornecedor";
                    modal.style.display = 'block';
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
            
        } else {
            alert('Por favor, digite o código do Fornecedor.');
        }
    });

    btnSalvar.addEventListener('click', function(){
        event.preventDefault();
        var idForn = document.getElementById('edit-codigo').value;
        var Nome = document.getElementById('edit-nome').value;
        var nmFantasia = document.getElementById('edit-fantasia').value;
        var CNPJ = document.getElementById('edit-CNPJ').value;
        var Telefone = document.getElementById('edit-telefone').value;
        var Contactante = document.getElementById('edit-contactante').value;
        var endFaturamento = document.getElementById('edit-faturamento').value;
        var endEntrega = document.getElementById('edit-entrega').value;
        var endCobranca = document.getElementById('edit-cobrança').value;

        if (!Nome || !nmFantasia || !CNPJ ||
            !Telefone || !Contactante || !endFaturamento || 
            !endEntrega || !endCobranca) {
            
            alert('Todos os campos são obrigatórios e devem ser preenchidos.');
            return;  // Interrompe a execução se algum campo estiver vazio
            }
        

        console.log(Telefone)
       if(isEditMode){
        $.ajax({
            url: '../controller/AlterarFornecedor.php',
            method: 'POST',
            data: {idForn: idForn, Nome: Nome, nmFantasia: nmFantasia, CNPJ: CNPJ, Telefone: Telefone, Contactante: Contactante, endFaturamento: endFaturamento, endEntrega: endEntrega, endCobranca: endCobranca},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert('Fornecedor alterado com sucesso');
                window.location.href = "../view/Fornecedores.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }else{
        $.ajax({
            url: '../controller/AddFornecedor.php',
            method: 'POST',
            data: {idForn: idForn, Nome: Nome, nmFantasia: nmFantasia, CNPJ: CNPJ, Telefone: Telefone, Contactante: Contactante, endFaturamento: endFaturamento, endEntrega: endEntrega, endCobranca: endCobranca},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert('Fornecedor adicionado com sucesso')
                window.location.href = "../view/Fornecedores.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }
    })

    btnExcluir.addEventListener('click', function() {
        var idForn = document.querySelector('.product-id').value;
        if (idForn) {
            if (confirm("Tem certeza que deseja excluir este Fornecedor?")) {
                $.ajax({
                    url: '../controller/ExcluirFornecedor.php',
                    method: 'POST',
                    data: { idForn: idForn },
                    success: function(response) {

                        response = JSON.parse(response); 
                        
                        if (!response.success) {  // Verifica o campo "success" no JSON
                            alert('Não existe nenhum fornecedor com esse id');
                            return;
                        }
                        console.log('Fornecedor excluído com sucesso:', response);
                        alert("Excluído ou inativado com sucesso!");
                        window.location.href = "../view/Fornecedores.php";
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
