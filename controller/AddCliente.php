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


$Endereco = new Endereco($bairro, $UF, $CEP);
$EnderecoDAO = new EnderecoDAO();

$ultimo_id_end = $EnderecoDAO->Consultar_ID_end();

$id_end = $ultimo_id_end['id_end'] + 1;


$Cliente = new Cliente($cpfCli, $Nome, $emailCli, $Telefone, $id_end);
$ClienteDAO = new ClienteDAO();


if ($EnderecoDAO->cadastrarEndereco($Endereco)) {


    if ($ClienteDAO->cadastrarCliente($Cliente)) {
        echo 'Cliente e endereço cadastrados com sucesso!';

    } else {
        echo 'Erro inesperado ao cadastrar cliente';
  
    }
} else {
    echo 'Erro inesperado ao cadastrar endereço';

}
