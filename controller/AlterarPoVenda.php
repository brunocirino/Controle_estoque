<?php

require_once('../model/SolicitacaoVendaDAO.php');

function logError($message) {
    $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

    $CodPo = $_POST['Codigo'];
    $NF = $_POST['NF'];
    $titulo = $_POST['Titulo'];
    $preco_total_PO = $_POST['preco_total_PO'];
    $Status = $_POST['Status'];
    
    logError("Alterar material, valor total: $preco_total_PO");

    $produtos = isset($_POST['produtos']) ? json_decode($_POST['produtos'], true) : [];

    $SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
    
    $SolicitacaoVendaDAO->Atualizar_PoVenda($CodPo, $titulo, $NF, $produtos, $Status, $preco_total_PO);

    echo '<script type="text/javascript">
        alert("Produto alterado com sucesso");
        window.location.href = "../view/Produto.php";
        </script>';

  ?> 
   