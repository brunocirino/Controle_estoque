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
                    SUM(c.preco_total) AS total_preco, 
                    c.Prioridade, 
                    c.status, 
                    c.id_identificador, 
                    f.nomeFantasia
                FROM pedidocompra c
                JOIN fornecedores f ON c.id_forn = f.id
                GROUP BY 
                    c.Titulo, 
                    c.Prioridade, 
                    c.status, 
                    c.id_identificador, 
                    f.nomeFantasia;');
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        

        public function ConsultarPoCompra($codPO){
            $consulta = $this->banco->prepare('SELECT 
                p.Titulo, p.id_identificador, p.status, p.Prioridade, p.preco_unit, p.preco_total, f.nomeFantasia, m.nomeMat, p.qtdMat, 
                SUM(p.preco_total) OVER() AS total_preco
                FROM pedidocompra p
                JOIN fornecedores f ON p.id_forn = f.id
                JOIN materiais m ON p.id_mat = m.codMat
                WHERE p.id_identificador = :codPO
                GROUP BY p.Titulo, p.id_identificador, p.status, p.Prioridade, f.nomeFantasia, m.nomeMat, p.qtdMat, p.preco_unit, p.preco_total
                ');
                $consulta->bindValue(':codPO',$codPO);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function Atualizar_material($Codigo, $NomeMat, $descMat, $statusMat, $estoqueMin, $estoqueAtual, $movimentacao){

            $update = $this->banco->prepare("UPDATE materiais SET nomeMat=?, descMat=?, status=?, estoqueMin=?, estoqueAtual=?, contMov=? WHERE codMat=?");
            $editar = array($NomeMat, $descMat, $statusMat, $estoqueMin, $estoqueAtual ,$movimentacao, $Codigo);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
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
            $codigoMaterial= array($id_identificador);

            if($delete->execute($codigoMaterial)){
                return true;
            }
        
            return false;
        }
    
    }
?>