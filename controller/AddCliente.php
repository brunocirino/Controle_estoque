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

// Adicionando logs para verificar os valores das variáveis recebidas
$mensagemFormatada = "Dados Recebidos: \n" .
    "Nome: $Nome, CPF: $cpfCli, Email: $emailCli, CEP: $CEP, Bairro: $bairro, UF: $UF, Telefone: $Telefone" . PHP_EOL;
file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);

$Endereco = new Endereco($bairro, $UF, $CEP);
$EnderecoDAO = new EnderecoDAO();

$ultimo_id_end = $EnderecoDAO->Consultar_ID_end();
$mensagemFormatada = "Último ID de Endereço Consultado: " . json_encode($ultimo_id_end) . PHP_EOL;
file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);

$id_end = $ultimo_id_end['id_end'] + 1;

// Log para verificar a criação do Cliente
$mensagemFormatada = "Criando Cliente com ID de Endereço: $id_end" . PHP_EOL;
file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);

$Cliente = new Cliente($cpfCli, $Nome, $emailCli, $Telefone, $id_end);
$ClienteDAO = new ClienteDAO();

// Log para verificar antes de cadastrar endereço
$mensagemFormatada = "Tentando cadastrar endereço: " . json_encode($Endereco) . PHP_EOL;
file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);

if ($EnderecoDAO->cadastrarEndereco($Endereco)) {
    $mensagemFormatada = "Endereço cadastrado com sucesso, tentando cadastrar cliente..." . PHP_EOL;
    file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);

    if ($ClienteDAO->cadastrarCliente($Cliente)) {
        echo 'Cliente e endereço cadastrados com sucesso!';
        $mensagemFormatada = "Cliente cadastrado com sucesso." . PHP_EOL;
        file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
    } else {
        echo 'Erro inesperado ao cadastrar cliente';
        $mensagemFormatada = "Erro ao cadastrar cliente." . PHP_EOL;
        file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
    }
} else {
    echo 'Erro inesperado ao cadastrar endereço';
    $mensagemFormatada = "Erro ao cadastrar endereço." . PHP_EOL;
    file_put_contents($arquivo, $mensagemFormatada, FILE_APPEND);
}
