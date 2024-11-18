<?php

require_once('../model/SolicitacaoCompra.php');
require_once('../model/SolicitacaoCompraDAO.php');
require_once('../model/MaterialDAO.php');

// Função para logar erros
function logError($message) {
    $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Captura os dados do POST e registra no log
$Titulo = $_POST['Titulo'] ?? null;
$Prioridade = $_POST['Prioridade'] ?? null;
$materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];
$Fornecedores = isset($_POST['Fornecedores']) ? json_decode($_POST['Fornecedores'], true) : [];
$status = 'Solicitado';

logError("Título: $Titulo, Prioridade: $Prioridade, Materiais: " . json_encode($materiais) . ", Fornecedores: " . json_encode($Fornecedores));

$SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
$MaterialDAO = new MaterialDAO();
$Valor_total = 0;

try {
    // Consulta o último ID identificador e registra no log
    $id_atual = $SolicitacaoCompraDAO->Consultarid_identificadorMax();
    $id_atual = $id_atual !== null ? $id_atual : 0; 
    $id_identificador = $id_atual + 1;
    logError("ID identificador atual: $id_identificador");

    // Itera sobre cada material
    foreach ($materiais as $material) {
        $Valor_total = 0;
        $idMaterial = $material['id_mat'];
        $qtdMaterial = $material['qtd_material'];
        
        logError("Processando material: ID $idMaterial, Quantidade $qtdMaterial");

        // Consulta o preço unitário do material
        $Preco_unit = $MaterialDAO->ConsultarPreco_unit_mat($idMaterial);
        if ($Preco_unit === null) {
            logError("Erro: Preço unitário não encontrado para o material ID $idMaterial");
            continue;
        }

        logError("Preço unitário do material ID $idMaterial: $Preco_unit e qtd: $qtdMaterial");

        // Soma o valor total
        $Valor_total += $Preco_unit * (int)$qtdMaterial;

        logError("Valor_total do material ID $idMaterial: $Valor_total");

        // Itera sobre cada fornecedor
        foreach ($Fornecedores as $fornecedor) {
            $idForn = $fornecedor['id_fornecedor'];
            logError("Processando fornecedor: ID $idForn para o material ID $idMaterial");

            logError("Prioridade $Prioridade");

            // Cria uma nova solicitação de compra
            $SolicitacaoCompra = new SolicitacaoCompra($Titulo, $idForn, $idMaterial, $qtdMaterial, $Preco_unit, $Valor_total, $Prioridade);

            // Cadastra a solicitação de compra e verifica sucesso
            $resultado = $SolicitacaoCompraDAO->cadastrarSolicitacao($SolicitacaoCompra, $id_identificador, $status);
            if (!$resultado) {
                logError("Erro ao cadastrar solicitação de compra para o material ID $idMaterial com o fornecedor ID $idForn");
            } else {
                logError("Solicitação de compra cadastrada para o material ID $idMaterial com o fornecedor ID $idForn");
            }
        }
    }

    echo "Solicitação de compra cadastrada com sucesso!";
    logError("Solicitação de compra concluída com sucesso!");

} catch (Exception $e) {
    logError("Erro geral: " . $e->getMessage());
}

?>