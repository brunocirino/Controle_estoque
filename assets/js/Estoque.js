document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('editModal');
    var btnbaixa = document.getElementById('btn-baixa');
    var spanClose = document.getElementsByClassName('close')[0];
    var btnentrada = document.getElementById('btn-entrada');
    var Titulo = document.getElementById('modal-title');
    var btnSalvar = document.getElementById('btn-salvar');
    var isEntradaMode;

    btnentrada.addEventListener('click', function() {
        modal.style.display = 'block';
        Titulo.textContent = "Entrada";
        isEntradaMode = true;
    });

    btnbaixa.addEventListener('click', function() {
        isEditMode = false;  // Definir modo de adição
        modal.style.display = 'block';
        Titulo.textContent = "Baixa";
        isEntradaMode = false;
    });


    btnSalvar.addEventListener('click', function(event) {
        event.preventDefault(); // Agora o evento é capturado corretamente
        var Quantidade = document.getElementById('edit-quantidade').value;
        var id_identificador = document.getElementById('product-id').value;
        console.log(isEntradaMode);
    
        $.ajax({
            url: '../controller/Estoque.php',
            method: 'POST',
            data: { quantidade: Quantidade, id_identificador: id_identificador, Entrada: isEntradaMode },
            success: function(response) {
                try {
                    // Tenta fazer o parse da resposta como JSON
                    var jsonResponse = typeof response === "string" ? JSON.parse(response) : response;
    
                    // Se a resposta JSON tiver um erro, mostra o erro
                    if (jsonResponse.erro) {
                        console.error('Erro no processamento:', jsonResponse.erro);
                        alert(jsonResponse.erro);
                        window.location.href = "../view/Estoque.php"; 
                    } else {
                        console.log('Requisição AJAX bem-sucedida:', jsonResponse);
    
                        // Verifica se existem avisos e exibe um por um
                        if (jsonResponse.avisos && jsonResponse.avisos.length > 0) {
                            jsonResponse.avisos.forEach(function(aviso) {
                                alert(aviso); // Exibe cada aviso
                            });
                        }
                        if(isEntradaMode){
                            alert('Entrada realizada com sucesso!');
                        } else {
                            alert('Perda realizada com sucesso!');
                        }
                        
                        window.location.href = "../view/Estoque.php"; // Redireciona em caso de sucesso
                    }
                } catch (e) {
                    // Se JSON.parse falhar, exibe uma mensagem informativa
                    console.error('Erro ao processar a resposta. A resposta não está em formato JSON válido:', e);
                    alert("Produto com esse ID não existe");
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
                alert("Erro na comunicação com o servidor. Tente novamente mais tarde.");
            }
        });
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