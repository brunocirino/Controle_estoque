<?php

require_once('../model/SolicitacaoVenda.php');
require_once('../model/SolicitacaoVendaDAO.php');

$resultado = false;

// Captura os dados do POST
$id_identificador = $_POST['CodigoPO'];
$Status = $_POST['Status'];
$materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];

// Exibe os valores para validação
$debug_info = [
    'id_identificador' => $id_identificador,
    'Status' => $Status,
    'Materiais' => $materiais
];

// Inicializa a classe DAO
$SolicitacaoCompraDAO = new SolicitacaoCompraDAO();

// Chama a função Concluir uma vez
$resultado = $SolicitacaoCompraDAO->Concluir($id_identificador, $materiais, $Status);

// Retorna os valores das variáveis e o resultado para depuração
echo json_encode([
    'debug' => $debug_info,   // Adiciona os valores das variáveis
    'resultado' => $resultado // Retorna o resultado da função Concluir
]);
