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
            GROUP BY p.id_identificador');
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function ConsultarPoVenda($codPO) {
            $consulta = $this->banco->prepare('SELECT * FROM pedidovenda WHERE id_identificador = :codPO');
            $consulta->bindValue(':codPO', $codPO);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function Atualizar_PoVenda($id_identificador, $titulo, $NF, $nomeCliente, $cpfCliente, $Produtos, $Status, $preco_total_PO) {
            // Inicia uma transação
            $this->banco->beginTransaction();
        
            try {
                // 1. Excluir todos os materiais antigos associados ao pedido
                $deletePoCompra = $this->banco->prepare("DELETE FROM pedidovenda WHERE id_identificador = ?");
                $deletePoCompra->execute(array($id_identificador));
                
                $sql = "INSERT INTO pedidovenda (Titulo, nomeCliente, cpfCliente, codProd, nomeProd, qtdProd, prcUnitProd, preco_total, preco_total_PO, NR_NF, status, id_identificador) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

                // Prepara a consulta
                $inserir = $this->banco->prepare($sql);

                // Percorre cada material e executa o INSERT
                foreach ($Produtos as $produto) {
                    $inserir->execute([
                        $titulo,       
                        $nomeCliente,
                        $cpfCliente,                 
                        $produto['id_prod'],       
                        $produto['nome_prod'],  
                        $produto['qtd_prod'],    
                        $produto['preco_unit'],
                        $produto['preco_total'],
                        $preco_total_PO, 
                        $NF,
                        $Status, 
                        $id_identificador,         
                    ]);
                }

                // Confirma a transação
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
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