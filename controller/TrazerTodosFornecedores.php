<?php

require_once('../model/FornecedorDAO.php');


$FornecedorDAO = new FornecedorDAO();

$consultaFornecedores = $FornecedorDAO->TrazerTodosFornecedores();

echo json_encode($consultaFornecedores);

?>