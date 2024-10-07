<?php
require_once("UserDAO.php"); 
require_once("EnderecoDAO.php"); 

    class ClienteDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarCliente($cliente){

            $inserir = $this->banco->prepare("INSERT INTO clientes (codCli, cpfCli, nomeCli, emailCli, fone, id_end) VALUES (?,?,?,?,?,?);");

            $novo_cliente = array($cliente->get_nome(), $cliente->get_cpfCli(), $cliente->get_nome(), $cliente->get_emailCli(),$cliente->get_telefone(), $cliente->get_id_end());

            if($inserir->execute($novo_cliente)){
                return true;
            }
            
            return false;
        }

        
        public function TrazerTodosClientes(){
            $consulta = $this->banco->prepare('SELECT codCli, cpfCli, nomeCli, emailCli, fone, id_end FROM clientes');
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function ConsultarCliente($codCli) {
            // Prepara a consulta SQL com JOIN entre clientes e enderecos
            $consulta = $this->banco->prepare('
                SELECT clientes.*, enderecos.* 
                FROM clientes 
                INNER JOIN enderecos ON clientes.id_end = enderecos.id_end
                WHERE clientes.codCli LIKE :codCli
            ');
            
            // Vincula o valor do parâmetro :codCli
            $consulta->bindValue(':codCli', $codCli);
            
            // Executa a consulta
            $consulta->execute();
            
            // Obtém todos os resultados da consulta
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Retorna os resultados
            return $resultados;
        }
        

        public function Atualizar_Cliente($codCli, $cpfCli, $nomeCli, $emailCli, $Fone){

            $update = $this->banco->prepare("UPDATE clientes SET cpfCli=?, nomeCli=?, emailCli=?, Fone=? WHERE codCli=?");
            $editar = array($cpfCli, $nomeCli, $emailCli, $Fone, $codCli);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function excluir_cliente($codCli, $id_end){    

            $excluirEndereco = new EnderecoDAO();

            $excluirEndereco->excluir_endereco($id_end);

            $delete = $this->banco->prepare("DELETE FROM clientes WHERE codCli=?");
            $codigoCliente= array($codCli);

            if($delete->execute($codigoCliente)){
                return true;
            }
        
            return false;
        }
    
    }
?>