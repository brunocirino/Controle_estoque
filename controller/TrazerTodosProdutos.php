<?php

require_once('../model/ProdutosDAO.php');


$ProdutosDAO = new ProdutosDAO();

$consultaProdutos = $ProdutosDAO->TrazerTodosProdutos();

echo json_encode($consultaProdutos);

?>