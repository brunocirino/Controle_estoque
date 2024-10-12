<?php

require_once('../model/SolicitacaoCompra.php');
require_once('../model/SolicitacaoCompraDAO.php');
require_once('../model/MaterialDAO.php');

// Captura os dados do POST
$Titulo = $_POST['Titulo'];
$Prioridade = $_POST['Prioridade'];
$materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];
$Fornecedores = isset($_POST['Fornecedores']) ? json_decode($_POST['Fornecedores'], true) : [];
$status = 'Solicitado';

$SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
$MaterialDAO = new MaterialDAO();

$Valor_total = 0;

 // Consulta o último ID identificador
 $id_atual = $SolicitacaoCompraDAO->Consultarid_identificadorMax();
 $id_atual = $id_atual !== null ? $id_atual : 0; 
 $id_identificador = $id_atual + 1; 

// Itera sobre cada material
foreach ($materiais as $material) {
    $idMaterial = $material['id_material'];
    $qtdMaterial = $material['qtd_material'];

    // Consulta o preço unitário do material
    $Preco_unit = $MaterialDAO->ConsultarPreco_unit_mat($idMaterial);

    // Soma o valor total
    $Valor_total += $Preco_unit * (int)$qtdMaterial;

    // Itera sobre cada fornecedor
    foreach ($Fornecedores as $fornecedor) {
        $idForn = $fornecedor['id_fornecedor']; // Acessa o valor correto do id_fornecedor

        // Cria uma nova solicitação de compra
        $SolicitacaoCompra = new SolicitacaoCompra($Titulo, $idForn, $idMaterial, $Preco_unit, $Valor_total, $Prioridade);

        // Cadastra a solicitação de compra
        $SolicitacaoCompraDAO->cadastrarSolicitacao($SolicitacaoCompra, $id_identificador,$status);
    }
}

// Se necessário, exiba uma mensagem de sucesso
echo "Solicitação de compra cadastrada com sucesso!";
