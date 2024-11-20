<?php
require_once("UserDAO.php"); 

    class SolicitacaoCompraDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarSolicitacao($SolicitacaoCompra, $id_identificador, $status){

            $inserir = $this->banco->prepare("INSERT INTO pedidocompra (Titulo, id_forn, id_mat, qtdMat, preco_unit, preco_total, Prioridade, id_identificador, status) VALUES (?,?,?,?,?,?,?,?,?);");

            $nova_POCompra = array($SolicitacaoCompra->get_titulo(), $SolicitacaoCompra->get_id_forn(), $SolicitacaoCompra->get_id_mat(), $SolicitacaoCompra->get_qtdMat(), $SolicitacaoCompra->get_preco_unit(), $SolicitacaoCompra->get_preco_total(), $SolicitacaoCompra->get_prioiradade(), $id_identificador, $status);

            if($inserir->execute($nova_POCompra)){
                return true;
            }
            
            return false;
        }

        
        public function TrazerTodaSolicitacao() {
            $consulta = $this->banco->prepare('SELECT 
            c.Titulo, 
            SUM(DISTINCT c.preco_total) AS total_preco,
            c.Prioridade, 
            c.NR_NF,
            c.status, 
            c.id_identificador, 
            f.nomeFantasia
        FROM pedidocompra c
        JOIN fornecedores f ON c.id_forn = f.id
        GROUP BY 
            c.id_identificador
        ORDER BY 
            c.id_identificador;');
                    $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function ConsultarPoCompra($codPO){
            $consulta = $this->banco->prepare('SELECT 
                p.Titulo, p.id_identificador, p.status, p.NR_NF, p.Prioridade, GROUP_CONCAT(DISTINCT f.nomeFantasia) as nomes_Fantasias, p.id_mat, p.id_forn, p.preco_unit, p.preco_total, f.nomeFantasia, m.nomeMat, p.qtdMat, 
                SUM(p.preco_total) OVER() AS total_preco
                FROM pedidocompra p
                JOIN fornecedores f ON p.id_forn = f.id
                JOIN materiais m ON p.id_mat = m.codMat
                WHERE p.id_identificador = :codPO
                GROUP BY p.id_identificador, p.id_mat
                ');
                $consulta->bindValue(':codPO',$codPO);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function Atualizar_PoCompra($id_identificador, $NF, $titulo, $Fornecedores, $materiais, $Prioridade, $Status) {
            // Caminho do arquivo de log
            $logFile = 'C:\\Users\\bruno\\OneDrive\\Área de Trabalho\\Log_Erro_TCC\\Log_Erro_TCC.txt';
        
            // Inicia uma transação
            $this->banco->beginTransaction();
            
            try {
                // 1. Excluir todos os materiais antigos associados ao pedido
                $deletePoCompra = $this->banco->prepare("DELETE FROM pedidocompra WHERE id_identificador = ?");
                if (!$deletePoCompra) {
                    $errorInfo = $this->banco->errorInfo();
                    file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro ao preparar a query para excluir materiais antigos: " . $errorInfo[2] . PHP_EOL, FILE_APPEND);
                    throw new Exception("Erro ao preparar a query para excluir materiais antigos: " . $errorInfo[2]);
                }
                if (!$deletePoCompra->execute(array($id_identificador))) {
                    $errorInfo = $deletePoCompra->errorInfo();
                    file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro ao excluir materiais antigos (ID Identificador: $id_identificador): " . $errorInfo[2] . PHP_EOL, FILE_APPEND);
                    throw new Exception("Erro ao excluir materiais antigos (ID Identificador: $id_identificador): " . $errorInfo[2]);
                }
        
                // Consulta para inserir novos materiais
                $sql = "INSERT INTO pedidocompra (Titulo, id_forn, id_mat, qtdMat, preco_unit, preco_total, NR_NF, Prioridade, id_identificador, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
                // Prepara a consulta
                $inserir = $this->banco->prepare($sql);
                if (!$inserir) {
                    $errorInfo = $this->banco->errorInfo();
                    file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro ao preparar a query para inserir materiais: " . $errorInfo[2] . PHP_EOL, FILE_APPEND);
                    throw new Exception("Erro ao preparar a query para inserir materiais: " . $errorInfo[2]);
                }
        
                // Percorre cada material e executa o INSERT
                foreach ($materiais as $material) {

                    $preco_total = $material['preco_total'] = floatval(str_replace(",", ".", $material['preco_total']));

                    file_put_contents($logFile, date("Y-m-d H:i:s") . " - preco_total recebido: " . $material['preco_total'] . "\n", FILE_APPEND);

                    if (!$inserir->execute([
                        $titulo,                    // Título do pedido de compra
                        $Fornecedores[0]['id_fornecedor'],                   // ID do fornecedor
                        $material['id_mat'],        // ID do material
                        $material['qtd_material'],
                        $material['preco_unit'],  // Quantidade do material
                        $preco_total,    // Preço total
                        $NF,                        // Número da nota fiscal
                        $Prioridade,                // Prioridade
                        $id_identificador,          // ID identificador
                        $Status                     // Status
                    ])) {
                        $errorInfo = $inserir->errorInfo();
                        file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro ao inserir material ID " . $material['id_mat'] . ": " . $errorInfo[2] . PHP_EOL, FILE_APPEND);
                        throw new Exception("Erro ao inserir material ID " . $material['id_mat'] . ": " . $errorInfo[2]);
                    } else {
                        // Log do sucesso ao inserir o material
                        file_put_contents($logFile, date("Y-m-d H:i:s") . " - Material ID " . $material['id_mat'] . " inserido com sucesso.\n", FILE_APPEND);
                    }
                }
        
                // Confirma a transação
                $this->banco->commit();
                file_put_contents($logFile, date("Y-m-d H:i:s") . " - Transação confirmada com sucesso.\n", FILE_APPEND);
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
        
                // Log do erro geral
                file_put_contents($logFile, date("Y-m-d H:i:s") . " - Erro na transação: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
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
            $consulta = $this->banco->prepare('SELECT MAX(id_identificador) AS max_id FROM pedidocompra');
            $consulta->execute();
            
            // Busca o resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Retorna o maior id_identificador ou null se não encontrado
            return $resultado ? $resultado['max_id'] : null;
        }

        public function excluir_Po_Compra($id_identificador){    

            $delete = $this->banco->prepare("DELETE FROM pedidocompra WHERE id_identificador=?");
            $codiPOcompra= array($id_identificador);

            $delete->execute($codiPOcompra);
        
            return $delete->rowCount() > 0; 
        }
    
    }
?>