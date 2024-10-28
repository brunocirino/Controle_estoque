<?php
require_once('../model/Produtos.php');
require_once('../model/ProdutosDAO.php');

    $codProd = $_POST['CodProd'];
    

    $ExcluirProd = new ProdutosDAO();
    
    $resultados = $ExcluirProd->excluir_produto($codProd);

    echo json_encode(["success" => $resultados]);

  ?> 
   