<?php
require_once('../model/Fornecedor.php');
require_once('../model/FornecedorDAO.php');

$idForn = $_POST['idForn'];
$Nome = $_POST['Nome'];
$nmFantasia = $_POST['nmFantasia'];
$CNPJ = $_POST['CNPJ'];
$Telefone  = $_POST['Telefone'];
$Contactante  = $_POST['Contactante'];
$endFaturamento   = $_POST['endFaturamento'];
$endEntrega   = $_POST['endEntrega'];
$endCobranca   = $_POST['endCobranca'];

        
    $Fornecedor = new Fornecedor($Nome, $nmFantasia, $CNPJ, $Telefone, $Contactante, $endCobranca, $endEntrega, $endCobranca);
    
    $FornecedorDAO = new FornecedorDAO();

    if($FornecedorDAO->cadastrarFornecedor($Fornecedor)){

        echo '<script type="text/javascript">
        alert("Fornecedor incluído com sucesso.");
        window.location.href = "../view/Fornecedores.php";
        </script>';

    } else {
       echo '<script type="text/javascript">
        alert("Erro inesperado!");
        window.location.href = "../view/Fornecedores.php";
        </script>';
    }
    
   
   