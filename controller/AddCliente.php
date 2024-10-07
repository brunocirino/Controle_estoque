<?php
require_once('../model/Cliente.php');
require_once('../model/ClienteDAO.php');
require_once('../model/Endereco.php');
require_once('../model/EnderecoDAO.php');

    $Nome = $_POST['Nome'];
    $cpfCli = $_POST['cpfCli'];
    $emailCli = $_POST['emailCli'];
    $CEP = $_POST['CEP'];
    $bairro = $_POST['Bairro'];
    $UF = $_POST['UF'];
    $Telefone = $_POST['Telefone'];

    $arquivo = 'C:/Users/bruno/OneDrive/Área de Trabalho/Log_Erro_TCC/Log_Erro_TCC.txt';
    $mensagemFormatada = 'Bairro: ' . $bairro . ', UF: ' . $UF . ', CEP: ' . $CEP . PHP_EOL; // Adiciona quebra de linha no final
    file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
    

    $Endereco = new Endereco($bairro, $UF, $CEP);

    $EnderecoDAO = new EnderecoDAO();

    $ultimo_id_end = $EnderecoDAO->Consultar_ID_end();

    $id_end = $ultimo_id_end['id_end'] + 1;
        
    $Cliente = new Cliente($cpfCli, $Nome, $emailCli, $Telefone, $id_end);
   
    $ClienteDAO = new ClienteDAO();
    
    if($EnderecoDAO->cadastrarEndereco($Endereco)){
        if($ClienteDAO->cadastrarCliente($Cliente)){

            echo 'cliente e endereco cadastrados com sucesso!';
    
        } else {
           echo 'Erro inesperado ao cadastrar cliente';
        }
    }else{
        echo 'Erro inesperado ao cadastrar endereço';
    }

    
    
   
   