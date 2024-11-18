<?php
require_once("UserDAO.php"); 
    class SolicitacaoVendaDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarSolicitacao($SolicitacaoVenda, $id_identificador, $status){

            $inserir = $this->banco->prepare("INSERT INTO pedidovenda (Titulo, nomeCliente, cpfCliente, codProd, nomeProd, qtdProd, prcUnitProd, preco_total, id_identificador, status) VALUES (?,?,?,?,?,?,?,?,?,?);");

            $nova_POCompra = array($SolicitacaoVenda->get_Titulo(), $SolicitacaoVenda->get_nomeCli(), $SolicitacaoVenda->get_cpfCli(), $SolicitacaoVenda->get_cod_prod(), $SolicitacaoVenda->get_nome_prod(), $SolicitacaoVenda->get_qtd_prod(), $SolicitacaoVenda->get_preco_unit_prod(), $SolicitacaoVenda->get_preco_total(), $id_identificador, $status);

            if($inserir->execute($nova_POCompra)){
                return true;
            }
            
            return false;
        }

        
        public function TrazerTodaSolicitacao() {
            $consulta = $this->banco->prepare('SELECT p.Titulo, p.nomeCliente, p.cpfCliente, p.NR_NF, p.preco_total_PO, p.nomeProd, p.qtdProd, p.preco_total, p.status, p.id_identificador
            FROM pedidovenda as p
            GROUP BY p.id_identificador
            ORDER BY p.id_identificador');
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function ConsultarPoVenda($codPO) {
            $consulta = $this->banco->prepare('SELECT *, c.codCli FROM pedidovenda pv INNER JOIN clientes c ON pv.cpfCliente = c.cpfCli WHERE id_identificador = :codPO GROUP BY pv.id_identificador, pv.codProd');
            $consulta->bindValue(':codPO', $codPO);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function Atualizar_PoVenda($id_identificador, $titulo, $NF, $Produtos, $clientes, $Status, $preco_total_PO) {
            // Caminho do arquivo de log
            $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
            
            // Inicia uma transação
            $this->banco->beginTransaction();
            
            try {
                // Log inicial com todas as informações recebidas
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Iniciando atualização de PO.\n";
                $logMessage .= "Dados recebidos:\n";
                $logMessage .= "ID Identificador: $id_identificador\n";
                $logMessage .= "Título: $titulo\n";
                $logMessage .= "NF: $NF\n";
                $logMessage .= "Status: $Status\n";
                $logMessage .= "Preço Total PO: $preco_total_PO\n";
                
                // Log de clientes
                $logMessage .= "Clientes:\n";
                foreach ($clientes as $cliente) {
                    $logMessage .= sprintf("  - ID Cliente: %s, Nome: %s, CPF: %s\n", $cliente['id_cliente'], $cliente['nmCli'], $cliente['cpfCli']);
                }
        
                // Log de produtos
                $logMessage .= "Produtos:\n";
                foreach ($Produtos as $produto) {
                    $logMessage .= sprintf(
                        "  - ID: %s, Nome: %s, QTD: %s, Preço Unit: %s, Preço Total: %s\n",
                        $produto['idProduto'],
                        $produto['nomeProduto'],
                        $produto['qtdProduto'],
                        $produto['preco_unit'],
                        $produto['preco_total']
                    );
                }
                file_put_contents($logFile, $logMessage, FILE_APPEND);
        
                // 1. Excluir todos os materiais antigos associados ao pedido
                $deletePoCompra = $this->banco->prepare("DELETE FROM pedidovenda WHERE id_identificador = ?");
                $deletePoCompra->execute(array($id_identificador));
                file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Materiais antigos removidos para o ID: $id_identificador.\n", FILE_APPEND);
                
                // 2. Inserir novos produtos para o pedido
                $sqlProduto = "INSERT INTO pedidovenda (Titulo, nomeCliente, cpfCliente, codProd, nomeProd, qtdProd, prcUnitProd, preco_total, preco_total_PO, NR_NF, status, id_identificador) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $inserirProduto = $this->banco->prepare($sqlProduto);
        
                // Percorrer os clientes e produtos para associá-los corretamente
                foreach ($clientes as $cliente) {
                    foreach ($Produtos as $produto) {
                        // Log de inserção de produto e cliente
                        $logMessage = sprintf(
                            "[" . date('Y-m-d H:i:s') . "] Inserindo produto e cliente: Cliente ID=%s, Produto ID=%s, Nome Produto=%s, QTD=%s, Preço Unit=%s, Preço Total=%s.\n",
                            $cliente['id_cliente'],
                            $produto['idProduto'],
                            $produto['nomeProduto'],
                            $produto['qtdProduto'],
                            $produto['preco_unit'],
                            $produto['preco_total']
                        );
                        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
                        // Inserir os dados do produto e cliente no banco
                        $inserirProduto->execute([
                            $titulo, // Título
                            $cliente['nmCli'], // Nome do Cliente
                            $cliente['cpfCli'], // CPF do Cliente
                            $produto['idProduto'], // ID do Produto
                            $produto['nomeProduto'], // Nome do Produto
                            $produto['qtdProduto'], // Quantidade do Produto
                            $produto['preco_unit'], // Preço Unitário
                            $produto['preco_total'], // Preço Total
                            $preco_total_PO, // Preço Total PO
                            $NF, // Número da Nota Fiscal
                            $Status, // Status
                            $id_identificador, // ID do Pedido
                        ]);
                    }
                }
        
                // Confirma a transação
                $this->banco->commit();
                file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Atualização de PO concluída com sucesso para o ID: $id_identificador.\n", FILE_APPEND);
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
                $errorMessage = "[" . date('Y-m-d H:i:s') . "] Erro ao atualizar PO: " . $e->getMessage() . "\n";
                file_put_contents($logFile, $errorMessage, FILE_APPEND);
                return false;
            }
        }
        
        
        
        public function Concluir($id_identificador, $materiais, $Status) {
            try {
                // Inicia a transação
            
                $this->banco->beginTransaction();
                
                $sql = "UPDATE pedidocompra SET status = ? WHERE id_identificador = ?";
                $atualizarPedido = $this->banco->prepare($sql);
                $atualizarPedido->execute([$Status, $id_identificador]);
        
        
                // Atualizar a quantidade dos materiais
                $sqlMaterialSelect = "SELECT estoqueAtual FROM materiais WHERE codMat = ?";
                $sqlMaterialUpdate = "UPDATE materiais SET estoqueAtual = ? WHERE codMat = ?";
                $selecionarMaterial = $this->banco->prepare($sqlMaterialSelect);
                $atualizarMaterial = $this->banco->prepare($sqlMaterialUpdate);
        
                // Percorre cada material e executa o UPDATE
                foreach ($materiais as $material) {
                    // Seleciona a quantidade atual do estoque
                    $selecionarMaterial->execute([$material['id_mat']]);
                    $resultado = $selecionarMaterial->fetch(PDO::FETCH_ASSOC);
        
                    if ($resultado) {
                        // Obtém a quantidade atual
                        $quantidadeAtual = $resultado['estoqueAtual'];            
                        
                        // Nova quantidade após o incremento
                        $novaQuantidade = $quantidadeAtual + $material['qtdMat']; // Verifique se é 'qtd_material' aqui
        
                        // Atualiza o estoque com a nova quantidade
                        $atualizarMaterial->execute([$novaQuantidade, $material['id_mat']]);
                }
            }
        
                // Confirma a transação
                $this->banco->commit();
                
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function Consultarid_identificadorMax() {
            // Prepara a consulta para selecionar o maior id_identificador
            $consulta = $this->banco->prepare('SELECT MAX(id_identificador) AS max_id FROM pedidovenda');
            $consulta->execute();
            
            // Busca o resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Retorna o maior id_identificador ou null se não encontrado
            return $resultado ? $resultado['max_id'] : null;
        }

        public function excluir_Po_Venda($id_identificador){    

            $delete = $this->banco->prepare("DELETE FROM pedidovenda WHERE id_identificador=?");
            $codigoMaterial= array($id_identificador);

            $delete->execute($codigoMaterial);
        
            return $delete->rowCount() > 0; 
        }

        public function Inserir_valor_total($valor_total, $id_identificador) {    
            // Prepare a query para atualizar o valor total na tabela pedidovenda
            $update = $this->banco->prepare("UPDATE pedidovenda SET preco_total_PO = ? WHERE id_identificador = ?");
            
            // Cria um array com os valores a serem inseridos na query
            $InserirValorTotal = array($valor_total, $id_identificador);
        
            // Executa a query e verifica se foi bem sucedida
            if ($update->execute($InserirValorTotal)) {
                return true; // Retorna true se a execução foi bem-sucedida
            }
            
            return false; // Retorna false caso contrário
        }
        
    
    }
?>