<?php

    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASSWORD', '');
    define('DB_NAME', 'controle_estoque');

    class ProdutosDAO{

        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarProduto($Produto){

            $inserir = $this->banco->prepare("INSERT INTO produtos (nomeProd, qtdProd, estadoProd) VALUES (?,?,?);");

            $novo_produto = array($Produto->get_nome(), $Produto->get_quantidade(), $Produto->get_estadoProd());

            if($inserir->execute($novo_produto)){
                return true;
            }
            
            return false;
        }

        public function TrazerTodosProdutos(){
            $consulta = $this->banco->prepare('SELECT codProd, nomeProd, qtdProd, estadoProd FROM produtos');
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function ConsultarProdutos($codProd){
            $consulta = $this->banco->prepare('SELECT * FROM produtos WHERE codProd LIKE :codProd');
                $consulta->bindValue(':codProd',$codProd);
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function Atualizar_Produto($codProd, $NomeProd, $qtdProd, $estadoProd){

            $update = $this->banco->prepare("UPDATE produtos SET nomeProd=?, qtdProd=?, estadoProd=? WHERE codProd=?");
            $editar = array($NomeProd, $qtdProd, $estadoProd, $codProd);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function excluir_produto($codProd){    

            $delete = $this->banco->prepare("DELETE FROM produtos WHERE codProd=?");
            $codigoProduto= array($codProd);

            if($delete->execute($codigoProduto)){
                return true;
            }
        
            return false;
        }
    }


?>