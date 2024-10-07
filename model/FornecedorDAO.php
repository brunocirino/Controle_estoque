<?php

    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASSWORD', '');
    define('DB_NAME', 'controle_estoque');

    require_once("Fornecedor.php");

    class FornecedorDAO{

        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarFornecedor($Fornecedor){

            $inserir = $this->banco->prepare("INSERT INTO fornecedores (Nome, nomeFantasia, CNPJ, Telefone, Contactante, endFaturamento, endEntrega, endCobranca) VALUES (?,?,?,?,?,?,?,?);");

            $novo_Fornecedor = array($Fornecedor->get_nome(), $Fornecedor->get_Nome_fantasia(), $Fornecedor->get_CNPJ(), $Fornecedor->get_Telefone(), $Fornecedor->get_Contactante(), $Fornecedor->get_End_Faturamento(), $Fornecedor->get_End_entrega(), $Fornecedor->get_End_cobranca());

            if($inserir->execute($novo_Fornecedor)){
                return true;
            }
            
            return false;
        }

        public function TrazerTodosFornecedores(){
            $consulta = $this->banco->prepare('SELECT id, Nome, nomeFantasia, CNPJ, Telefone, Contactante, endFaturamento, endEntrega, endCobranca FROM fornecedores');
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function excluir_fornecedor($id){    

            $delete = $this->banco->prepare("DELETE FROM fornecedores WHERE id=?");
            $idFornecedor = array($id);

            if($delete->execute($idFornecedor)){
                return true;
            }
        
            return false;
        }

        public function ConsultarIDFornecedor($CNPJ){    

            $consulta = $this->banco->prepare('SELECT id FROM usuarios WHERE CNPJ = :CNPJ');
            $consulta->bindParam(':CNPJ', $CNPJ);
            $consulta->execute();

            $idFornecedor = $consulta->fetchColumn();
            
            return $idFornecedor;
        }

        public function ConsultarFornecedor($id){
            $consulta = $this->banco->prepare('SELECT * FROM fornecedores WHERE id LIKE :id');
                $consulta->bindValue(':id',$id);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function Atualizar_ID_Fornecedor($idFornecedor, $CNPJ){

            $update = $this->banco->prepare("UPDATE fornecedores SET id=? WHERE CNPJ=?");
            $editar_id_fornecedor = array($idFornecedor, $CNPJ);

            if($update->execute($editar_id_fornecedor)){
                return true;
            }
            
            return false;
        }

        public function Atualizar_Fornecedor($id, $Nome, $Nome_fantasia, $CNPJ, $telefone, $Contactante, $End_Faturamento, $End_entrega, $End_cobranca){

            $update = $this->banco->prepare("UPDATE fornecedores SET nome=?, nomeFantasia=?, CNPJ=?, Telefone=?, Contactante=?, endFaturamento=?, endEntrega=?, endCobranca=? WHERE id=?");
            $editar = array($Nome, $Nome_fantasia, $CNPJ, $telefone, $Contactante, $End_Faturamento, $End_entrega, $End_cobranca ,$id);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

    }

?>