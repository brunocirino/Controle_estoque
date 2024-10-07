<?php
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

    $AlterarForn = new FornecedorDAO();
    
    $AlterarForn->Atualizar_Fornecedor($idForn, $Nome, $nmFantasia, $CNPJ, $Telefone, $Contactante, $endCobranca, $endEntrega, $endCobranca);


  ?> 
   