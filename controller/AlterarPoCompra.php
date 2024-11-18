<?php

require_once('../model/SolicitacaoCompraDAO.php');

// Caminho do arquivo de log
$logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';

function logError($message) {
    $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}


try {
    $CodPo = $_POST['Codigo'];
    $titulo = $_POST['Titulo'];
    $NF = $_POST['nf'];
    $Prioridade = $_POST['Prioridade'];
    $Fornecedores = isset($_POST['Fornecedores']) ? json_decode($_POST['Fornecedores'], true) : [];
    $Status = $_POST['Status'];
    $materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];


    foreach ($materiais as $index =>$material) {
        $Valor_total = 0;
        $preco_unit = $material['preco_unit'];
        $qtdMaterial = $material['qtd_material'];

        $Valor_total += $preco_unit * (int)$qtdMaterial;

        $materiais[$index]['preco_total'] = $Valor_total;

        logError("Alterar material, valor total: $Valor_total");
    }

   

     // Log de entrada de dados (opcional para depuração)
     file_put_contents(
        $logFile,
        date("Y-m-d H:i:s") . " - Dados recebidos: " . json_encode($_POST) . PHP_EOL,
        FILE_APPEND
    );

    // Cria uma instância de SolicitacaoCompraDAO e atualiza o pedido de compra
    $SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
    $resultado = $SolicitacaoCompraDAO->Atualizar_PoCompra($CodPo, $NF, $titulo, $Fornecedores, $materiais, $Prioridade, $Status);

    if ($resultado) {
        echo '<script type="text/javascript">
            alert("Produto alterado com sucesso");
            window.location.href = "../view/Produto.php";
            </script>';
    } else {
        throw new Exception("Erro ao atualizar o pedido de compra.");
    }

} catch (Exception $e) {
    // Log do erro com detalhes
    file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro ao processar atualização de pedido de compra: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    
    // Exibe uma mensagem de erro para o usuário (opcional)
    echo '<script type="text/javascript">
        alert("Erro ao atualizar o pedido de compra. Verifique o log para mais detalhes.");
        window.location.href = "../view/Produto.php";
        </script>';
}

?>
