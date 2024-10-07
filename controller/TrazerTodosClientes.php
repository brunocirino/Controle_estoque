<?php

require_once('../model/ClienteDAO.php');


$ClienteDAO = new ClienteDAO();

$consultaClientes = $ClienteDAO->TrazerTodosClientes();

echo json_encode($consultaClientes);

?>