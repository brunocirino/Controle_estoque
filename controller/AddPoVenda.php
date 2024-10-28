<?php

require_once('../model/SolicitacaoVenda.php');
require_once('../model/SolicitacaoVendaDAO.php');
require_once('../model/ProdutosDAO.php');
require_once('../model/ClienteDAO.php');

// Função para logar erros
function logError($message) {
    $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Captura os dados do POST e registra no log
$Titulo = $_POST['Titulo'] ?? null;
$Clientes = isset($_POST['Clientes']) ? json_decode($_POST['Clientes'], true) : [];
$Produtos = isset($_POST['Produtos']) ? json_decode($_POST['Produtos'], true) : [];
$status = 'Solicitado';

logError("Título: $Titulo, Clientes: " . json_encode($Clientes) . ", Produtos: " . json_encode($Produtos));

$SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
$ProdutosDAO = new ProdutosDAO();
$Valor_total = 0;


try {
    // Consulta o último ID identificador e registra no log
    $id_atual = $SolicitacaoVendaDAO->Consultarid_identificadorMax();
    $id_atual = $id_atual !== null ? $id_atual : 0; 
    $id_identificador = $id_atual + 1;
    logError("ID identificador atual: $id_identificador");

    // Itera sobre cada material
    foreach ($Produtos as $Produto) {
        $preco_total_produto = 0;
        $idproduto = $Produto['idProduto'];
        $qtdproduto = $Produto['qtdProduto'];
        $nmproduto = $Produto['nomeProduto'];
        
        logError("Processando produto: ID $idproduto, Quantidade $qtdproduto, Nome $nmproduto");

        // Consulta o preço unitário do material
        $Preco_unit = $ProdutosDAO->ConsultarPreco_unit_prod($idproduto);
        if ($Preco_unit === null) {
            logError("Erro: Preço unitário não encontrado para o material ID $idproduto");
            continue;
        }

        logError("Preço unitário do material ID $idproduto: $Preco_unit");

        // Soma o valor total
        $preco_total_produto += $Preco_unit * (int)$qtdproduto;

        $Valor_total +=$preco_total_produto;

        // Itera sobre cada fornecedor
        foreach ($Clientes as $cliente) {
            $idCliente = $cliente['id_cliente'];
            logError("Processando Cliente: ID $idCliente para o produto ID $idproduto");

            $ClienteDAO = new ClienteDAO();

            $nmCliente = $ClienteDAO->ConsultarNome($idCliente);

            $cpfCliente = $ClienteDAO->ConsultarCPF($idCliente);

            // Cria uma nova solicitação de compra
            $SolicitacaoVenda = new SolicitacaoVenda($Titulo, $nmCliente, $cpfCliente, $idproduto, $nmproduto, $qtdproduto, $Preco_unit, $preco_total_produto);

            // Cadastra a solicitação de compra e verifica sucesso
            $resultado = $SolicitacaoVendaDAO->cadastrarSolicitacao($SolicitacaoVenda, $id_identificador, $status);
            if (!$resultado) {
                logError("Erro ao cadastrar solicitação de venda para o produto ID $idproduto com o cliente ID $idCliente");
            } else {
                logError("Solicitação de compra cadastrada para o produto ID $idproduto com o cliente ID $idCliente");
            }
        }
    }

    $InserirValor_total = $SolicitacaoVendaDAO->Inserir_valor_total($Valor_total, $id_identificador);

    echo "Solicitação de Venda cadastrada com sucesso!";
    logError("Solicitação de Venda concluída com sucesso!");

} catch (Exception $e) {
    logError("Erro geral: " . $e->getMessage());
}

?>