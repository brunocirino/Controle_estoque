<?php
require_once("UserDAO.php"); 

    class MaterialDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarMaterial($material){

            $inserir = $this->banco->prepare("INSERT INTO materiais (nomeMat, descMat, status, estoqueMin, estoqueAtual, contMov, preco) VALUES (?,?,?,?,?,?,?);");

            $novo_usuario = array($material->get_nome(), $material->get_descricao(), $material->get_status(), $material->get_estoquemin(), $material->get_EstoqueAtual(), $material->get_Movimentacao(), $material->get_Preco());

            if($inserir->execute($novo_usuario)){
                return true;
            }
            
            return false;
        }

        
        public function TrazerTodosMateriais(){
            $consulta = $this->banco->prepare('SELECT codMat, nomeMat, descMat, status, estoqueMin, estoqueAtual, contMov, preco FROM materiais');
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function ConsultarMaterial($codMat){
            $consulta = $this->banco->prepare('SELECT * FROM materiais WHERE codMat LIKE :codMat');
                $consulta->bindValue(':codMat',$codMat);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function ConsultarPreco_unit_mat($codMat) {
            $consulta = $this->banco->prepare('SELECT preco FROM materiais WHERE codMat LIKE :codMat');
            $consulta->bindValue(':codMat', $codMat);
            $consulta->execute();
            
            // Pega o primeiro resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Retorna apenas o preço se encontrado, caso contrário retorna null
            return $resultado ? $resultado['preco'] : null;
        }
        


        public function Atualizar_material($Codigo, $NomeMat, $descMat, $statusMat, $estoqueMin, $estoqueAtual, $movimentacao, $preco){

            $update = $this->banco->prepare("UPDATE materiais SET nomeMat=?, descMat=?, status=?, estoqueMin=?, estoqueAtual=?, contMov=?, preco=? WHERE codMat=?");
            $editar = array($NomeMat, $descMat, $statusMat, $estoqueMin, $estoqueAtual ,$movimentacao,$preco ,$Codigo);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function excluir_material($codMat){    

            $delete = $this->banco->prepare("DELETE FROM materiais WHERE codMat=?");
            $codigoMaterial= array($codMat);

            if($delete->execute($codigoMaterial)){
                return true;
            }
        
            return false;
        }
    
    }
?>