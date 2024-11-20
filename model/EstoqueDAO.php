<?php
require_once("UserDAO.php"); 

    class EstoqueDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function Atualizar_Produto($id_identificador, $Quantidade) {
        

        
            $update = $this->banco->prepare("UPDATE produtos SET qtdProd=? WHERE id_identificador=?");
            $editar = array($Quantidade, $id_identificador); // Corrigido a ordem dos parâmetros
        
            if ($update->execute($editar)) {

                return true;
            }
            
            return false;
        }
        

        public function AtualizarQuantidadeMaterial($id_material, $Quantidade){

            $update = $this->banco->prepare("UPDATE materiais SET estoqueAtual=? WHERE codMat=? ");
            $editar = array($Quantidade, $id_material);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function ConsultarProduto($id_identificador) {


            // Corrigir a consulta SQL
            $consulta = $this->banco->prepare('
                SELECT 
                    id_material,
                    qtdProd,
                    qtd_material
                FROM
                    produtos
                WHERE
                    id_identificador = :id_identificador
            ');
        
            // Vincular o valor do identificador ao placeholder correto
            $consulta->bindValue(':id_identificador', $id_identificador, PDO::PARAM_INT);
            
            // Executar a consulta
            $consulta->execute();
            
            // Obter os resultados
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Retornar os resultados
            return $resultados;
        }

        public function ConsultarQTDAtualMat($id_material) {
                  
        
            // Corrigir a consulta SQL
            $consulta = $this->banco->prepare('
                SELECT 
                    estoqueAtual,
                    estoqueMin
                FROM
                    materiais
                WHERE
                    codMAt = :id_material
            ');
        
            // Vincular o valor do identificador ao placeholder correto
            $consulta->bindValue(':id_material', $id_material, PDO::PARAM_INT);
            
            // Executar a consulta
            $consulta->execute();
            
            // Obter um único resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        
            // Retornar o resultado
            return $resultado;
        }
        
        

        
    
    }
?>