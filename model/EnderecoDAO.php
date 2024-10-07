<?php
require_once("UserDAO.php"); 

    class EnderecoDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarEndereco($endereco){

            $inserir = $this->banco->prepare("INSERT INTO enderecos (bairro, uf, cep) VALUES (?,?,?);");

            $novo_endereco = array($endereco->get_bairro(), $endereco->get_uf(), $endereco->get_cep());

            if($inserir->execute($novo_endereco)){
                return true;
            }
            
            return false;
        }
        public function inserir_id_user($id_user){

            $inserir = $this->banco->prepare("INSERT INTO enderecos (id_user) VALUES (?);");

            $adicionar_id_user = array($id_user);

            if($inserir->execute($adicionar_id_user)){
                return true;
            }
            
            return false;
        }

        public function ConsultarEndereco($id_end){
            $consulta = $this->banco->prepare('SELECT * FROM enderecos WHERE id_end LIKE :id_end');
                $consulta->bindValue(':id_end',$id_end);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function Consultar_ID_end() {
            // Prepara a consulta SQL para selecionar o último id_end
            $consulta = $this->banco->prepare('SELECT id_end FROM enderecos ORDER BY id_end DESC LIMIT 1');
            
            // Executa a consulta
            $consulta->execute();
            
            // Obtém o resultado da consulta
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Retorna o resultado
            return $resultado;
        }
        

        public function Atualizar_endereco($id_end, $bairro, $uf, $cep){

            $update = $this->banco->prepare("UPDATE enderecos SET bairro=?, uf=?, cep=? WHERE id_end=?");
            $editar = array($bairro, $uf, $cep, $id_end);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function excluir_endereco($id_end){    

            $delete = $this->banco->prepare("DELETE FROM enderecos WHERE id_end=?");
            $codigoEndereco= array($id_end);

            if($delete->execute($codigoEndereco)){
                return true;
            }
        
            return false;
        }
    
    }
?>