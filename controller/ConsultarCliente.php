<?php

require_once('../model/ClienteDAO.php');
require_once('../model/EnderecoDAO.php');

$CodCliente = $_POST['Codigo'];

$ClienteDAO = new ClienteDAO();

$consultaClientes = $ClienteDAO->ConsultarCliente($CodCliente);

echo json_encode($consultaClientes);

?>