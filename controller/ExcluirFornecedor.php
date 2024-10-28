<?php
require_once('../model/Fornecedor.php');
require_once('../model/FornecedorDAO.php');

    $idForn = $_POST['idForn'];

    $excluirFornecedor = new FornecedorDAO();
    
    $resultados = $excluirFornecedor->excluir_fornecedor($idForn);

    echo json_encode(["success" => $resultados]);

  ?> 
   