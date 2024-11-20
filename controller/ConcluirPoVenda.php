<?php

require_once('../model/SolicitacaoVenda.php');
require_once('../model/SolicitacaoVendaDAO.php');

$resultado = false;

$id_identificador = $_POST['CodigoPO'];
$Status = $_POST['Status'];
$Produtos = isset($_POST['Produtos']) ? json_decode($_POST['Produtos'], true) : [];

$debug_info = [
    'id_identificador' => $id_identificador,
    'Status' => $Status,
    'Produtos' => $Produtos
];

$SolicitacaoVendaDAO = new SolicitacaoVendaDAO();

$resultado = $SolicitacaoVendaDAO->Entregar($id_identificador, $Produtos, $Status);

echo json_encode([
    'debug' => $debug_info,  
    'resultado' => $resultado
]);
