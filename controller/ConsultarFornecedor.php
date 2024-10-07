<?php

require_once('../model/FornecedorDAO.php');

$Codigo = $_POST['Codigo'];

$FornecedorDAO = new FornecedorDAO();

$consultaFornecedores = $FornecedorDAO->ConsultarFornecedor($Codigo);

echo json_encode($consultaFornecedores);

?>