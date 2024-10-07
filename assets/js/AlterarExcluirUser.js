document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnEditar = document.getElementById('btn-editar');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnSalvar = document.getElementById('btn-salvar');
    var btnExcluir = document.getElementById('btn-Excluir');
    var btnAdicionar = document.getElementById('btn-adicionar');
    var Titulo = document.getElementById('modal-title');
    var isEditMode = false;  // Variável para rastrear o modo atual

    btnAdicionar.addEventListener('click', function(){
        isEditMode = false;  // Definir modo de adição
        modal.style.display = 'block';
        Titulo.textContent = "Adicionar Usuário";

        document.getElementById('edit-codigo').value = '';
        document.getElementById('edit-nome').value = '';
        document.getElementById('edit-login').value = '';
        document.getElementById('edit-senha').value = '';
        document.getElementById('edit-status').value = '';
        document.getElementById('edit-setor').value = '';

        
    })
    // Quando o botão Editar for clicado, exibe o modal
    btnEditar.addEventListener('click', function() {
        isEditMode = true;  // Definir modo de edição
        var CodigoUser = document.querySelector('.product-id').value;
        if (CodigoUser) {
            $.ajax({
                url: '../controller/ConsultarUser.php',
                method: 'POST',
                data: {Codigo: CodigoUser }, // Passando o código do usuário como parâmetro
                success: function(response) {
                    console.log('Requisição AJAX bem sucedida:', response);
                    // Preencher a tabela com os dados retornados
                    var usuario = JSON.parse(response);

                    // Extrair os valores do objeto
                    var codigo = usuario.codigo;
                    var nome = usuario.nome;
                    var login = usuario.login;
                    var senha = usuario.senha;
                    var status = usuario.Status;
                    var setor = usuario.setor;
                
                    // Preencher os campos do formulário com os valores extraídos
                    document.getElementById('edit-codigo').value = codigo;
                    document.getElementById('edit-nome').value = nome;
                    document.getElementById('edit-login').value = login;
                    document.getElementById('edit-senha').value = senha;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-setor').value = setor;
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição AJAX:', error);
                }
            });
            modal.style.display = 'block';
        } else {
            alert('Por favor, digite o código do usuário.');
        }
    });

    btnSalvar.addEventListener('click', function(){
        event.preventDefault();
        CodigoUser = document.getElementById('edit-codigo').value ;
        nome = document.getElementById('edit-nome').value ;
        login = document.getElementById('edit-login').value ;
        senha = document.getElementById('edit-senha').value ;
        statusUser = document.getElementById('edit-status').value;
        setor = document.getElementById('edit-setor').value;

       if(isEditMode){
        $.ajax({
            url: '../controller/AlterarUser.php',
            method: 'POST',
            data: {Codigo: CodigoUser, Nome: nome, Login: login, Senha: senha, Status: statusUser, Setor: setor },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                window.location.href = "../view/Usuarios.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }else{
        $.ajax({
            url: '../controller/AddUser.php',
            method: 'POST',
            data: {nome: nome, login: login, senha: senha, statusUser: statusUser, setor: setor },
            success: function(response) {
                console.log('Requisição AJAX bem sucedida:', response);
                window.location.href = "../view/Usuarios.php"
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
       }
    })

    btnExcluir.addEventListener('click', function(){
        var CodigoUser = document.querySelector('.product-id').value;
        if (CodigoUser) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                $.ajax({
                    url: '../controller/ExcluirUser.php',
                    method: 'POST',
                    data: { Codigo: CodigoUser },
                    success: function(response) {
                        console.log('Usuário excluído com sucesso:', response);
                        alert("Excluído ou inativado com sucesso!");
                        window.location.href = "../view/Usuarios.php";
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição AJAX:', error);
                    }
                });
            }
        } else {
            alert('Por favor, digite o código do usuário.');
        }  
    })

    // Quando o usuário clicar no botão de fechar (x), oculta o modal
    spanClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Quando o usuário clicar em qualquer lugar fora do modal, oculta o modal
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Captura o valor do input (opcional)
    var productIdInput = document.querySelector('.product-id');
    productIdInput.addEventListener('input', function(event) {
        var valorDigitado = event.target.value;
        console.log('Valor digitado:', valorDigitado);
        // Aqui você pode adicionar qualquer outra lógica que deseja realizar com o valor digitado
    });

    
});