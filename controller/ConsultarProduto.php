<?php

require_once('../model/ProdutosDAO.php');

$CodProd = $_POST['Codigo'];

$ProdutoDAO = new ProdutosDAO();

$consultaProdutos = $ProdutoDAO->ConsultarProdutos($CodProd);

echo json_encode($consultaProdutos);

?>