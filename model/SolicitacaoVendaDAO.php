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
            
            $this->banco->beginTransaction();
            
            try {
        
               
        
                $deletePoCompra = $this->banco->prepare("DELETE FROM pedidovenda WHERE id_identificador = ?");
                $deletePoCompra->execute(array($id_identificador));
                
                $sqlProduto = "INSERT INTO pedidovenda (Titulo, nomeCliente, cpfCliente, codProd, nomeProd, qtdProd, prcUnitProd, preco_total, preco_total_PO, NR_NF, status, id_identificador) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $inserirProduto = $this->banco->prepare($sqlProduto);
        
                foreach ($clientes as $cliente) {
                    foreach ($Produtos as $produto) {
                        
                
        
                        $inserirProduto->execute([
                            $titulo, 
                            $cliente['nmCli'], 
                            $cliente['cpfCli'], 
                            $produto['idProduto'], 
                            $produto['nomeProduto'], 
                            $produto['qtdProduto'], 
                            $produto['preco_unit'], 
                            $produto['preco_total'], 
                            $preco_total_PO, 
                            $NF, 
                            $Status, 
                            $id_identificador, 
                        ]);
                    }
                }
        
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                $this->banco->rollBack();
                $errorMessage = "[" . date('Y-m-d H:i:s') . "] Erro ao atualizar PO: " . $e->getMessage() . "\n";
                return false;
            }
        }
        
        
        
        public function Entregar($id_identificador, $Produtos, $Status) {
            try {
            
                $this->banco->beginTransaction();
                
                $sql = "UPDATE pedidoVenda SET status = ? WHERE id_identificador = ?";
                $atualizarPedido = $this->banco->prepare($sql);
                $atualizarPedido->execute([$Status, $id_identificador]);
        
                $sqlMaterialSelect = "SELECT qtdProd FROM produtos WHERE id_identificador = ?";
                $sqlMaterialUpdate = "UPDATE produtos SET qtdProd = ? WHERE id_identificador = ?";
                $selecionarMaterial = $this->banco->prepare($sqlMaterialSelect);
                $atualizarMaterial = $this->banco->prepare($sqlMaterialUpdate);
        
                foreach ($Produtos as $produto) {
                    $selecionarMaterial->execute([$produto['codProd']]);
                    $resultado = $selecionarMaterial->fetch(PDO::FETCH_ASSOC);
        
                    if ($resultado) {

                        $quantidadeAtual = $resultado['qtdProd'];            
                        
                        $novaQuantidade = $quantidadeAtual - $produto['qtdProd']; 
        
                        $atualizarMaterial->execute([$novaQuantidade, $produto['codProd']]);
                }
            }
        
                $this->banco->commit();
                
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function Consultarid_identificadorMax() {

            $consulta = $this->banco->prepare('SELECT MAX(id_identificador) AS max_id FROM pedidovenda');
            $consulta->execute();
            
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado['max_id'] : null;
        }

        public function excluir_Po_Venda($id_identificador){    

            $delete = $this->banco->prepare("DELETE FROM pedidovenda WHERE id_identificador=?");
            $codigoMaterial= array($id_identificador);

            $delete->execute($codigoMaterial);
        
            return $delete->rowCount() > 0; 
        }

        public function Inserir_valor_total($valor_total, $id_identificador) {    

            $update = $this->banco->prepare("UPDATE pedidovenda SET preco_total_PO = ? WHERE id_identificador = ?");
            
            $InserirValorTotal = array($valor_total, $id_identificador);
        
            if ($update->execute($InserirValorTotal)) {
                return true; 
            }
            
            return false; 
        }
        
    
    }
?>