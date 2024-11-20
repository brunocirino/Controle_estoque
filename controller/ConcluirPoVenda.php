<?php

require_once('../model/SolicitacaoVenda.php');
require_once('../model/SolicitacaoVendaDAO.php');

$resultado = false;

$id_identificador = $_POST['CodigoPO'];
$Status = $_POST['Status'];
$materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];

$debug_info = [
    'id_identificador' => $id_identificador,
    'Status' => $Status,
    'Materiais' => $materiais
];

$SolicitacaoCompraDAO = new SolicitacaoCompraDAO();

$resultado = $SolicitacaoCompraDAO->Concluir($id_identificador, $materiais, $Status);

echo json_encode([
    'debug' => $debug_info,  
    'resultado' => $resultado
]);
