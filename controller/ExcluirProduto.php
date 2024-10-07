<?php
require_once('../model/Produtos.php');
require_once('../model/ProdutosDAO.php');

    $codProd = $_POST['CodProd'];
    

    $ExcluirProd = new ProdutosDAO();
    
    $ExcluirProd->excluir_produto($codProd);

  ?> 
   