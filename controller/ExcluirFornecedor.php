<?php
require_once('../model/Fornecedor.php');
require_once('../model/FornecedorDAO.php');

    $idForn = $_POST['idForn'];

    $excluirFornecedor = new FornecedorDAO();
    
    $excluirFornecedor->excluir_fornecedor($idForn)

  ?> 
   