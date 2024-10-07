document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var Titulo = document.getElementById('modal-title');
    var isEditMode = false;  // Variável para rastrear o modo atual
    var id_end;

    btnAdicionar.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar Cliente";

        document.getElementById('edit-cpf').value = '';
        document.getElementById('edit-nome').value = '';
        document.getElementById('edit-email').value = '';
        document.getElementById('edit-cep').value = '';
        document.getElementById('edit-bairro').value = '';
        document.getElementById('edit-uf').value = '';
        document.getElementById('edit-telefone').value = '';
    });
    
    // Adicionar event listener para o primeiro select
   

    btnEditar.addEventListener('click', function() {
        isEditMode = true;  // Definir modo de edição
        var CodigoCliente = document.querySelector('.product-id').value;
        if (CodigoCliente) {
            $.ajax({
                url: '../controller/ConsultarCliente.php',
                method: 'POST',
                data: { Codigo: CodigoCliente }, // Passando o código do material como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    var Cliente = JSON.parse(response)[0];

                    console.log(Cliente);
                    
                    CodCli = Cliente.CodCli;
                    cpf = Cliente.cpfCli;
                    Nome = Cliente.nomeCli;
                    email = Cliente.emailCli;
                    Bairro = Cliente.bairro
                    var CEP = Cliente.cep;        
                    var Telefone = Cliente.fone;
                    var UF = Cliente.uf; 
                    id_end = Cliente.id_end;

                    document.getElementById('edit-cpf').value = cpf;
                    document.getElementById('edit-nome').value = Nome;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-cep').value = CEP;
                    document.getElementById('edit-bairro').value = Bairro;
                    document.getElementById('edit-uf').value = UF;
                    document.getElementById('edit-telefone').value = Telefone;

                    Titulo.textContent = "Editar Cliente";
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
            modal.style.display = 'block';
        } else {
            alert('Por favor, digite o código do cliente.');
        }
    });

    btnSalvar.addEventListener('click', function(){
        event.preventDefault();
        CodCli = document.querySelector('.product-id').value;
        nomeCli = document.getElementById('edit-nome').value ;
        cpfCli = document.getElementById('edit-cpf').value ;
        emailCli = document.getElementById('edit-email').value;
        CEP = document.getElementById('edit-cep').value;
        Bairro = document.getElementById('edit-bairro').value;
        UF = document.getElementById('edit-uf').value;
        Telefone = document.getElementById('edit-telefone').value;

       if(isEditMode){
        $.ajax({
            url: '../controller/AlterarCliente.php',
            method: 'POST',
            data: {Codigo: CodCli, Nome: nomeCli, cpfCli: cpfCli, emailCli: emailCli, CEP: CEP, Bairro: Bairro, UF: UF, Telefone: Telefone, id_end: id_end},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert('1');
                window.location.href = "../view/Cliente.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }else{
        $.ajax({
            url: '../controller/AddCliente.php',
            method: 'POST',
            data: {Codigo: CodCli, Nome: nomeCli, cpfCli: cpfCli, emailCli: emailCli, CEP: CEP, Bairro: Bairro, UF: UF, Telefone: Telefone},
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                alert("Cliente adicionado com sucesso!");
                window.location.href = "../view/Cliente.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }
    })

    btnExcluir.addEventListener('click', function() {
        var CodigoCli = document.querySelector('.product-id').value;
        var linha = document.querySelector(`tr[data-cod-cli="${CodigoCli}"]`);
        var id_end = linha.getAttribute('data-id-end')

        console.log(id_end);

        if (CodigoCli) {
            if (confirm("Tem certeza que deseja excluir este produto?")) {
                $.ajax({
                    url: '../controller/ExcluirCliente.php',
                    method: 'POST',
                    data: { CodCliente: CodigoCli , id_end: id_end},
                    success: function(response) {
                        console.log('Cliente excluído com sucesso:', response);
                        alert("Excluído com sucesso!");
                        window.location.href = "../view/Cliente.php";
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
