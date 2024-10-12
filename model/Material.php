<?php

    class Material{
        protected $codigo;
        protected $nome;
        protected $descricao;
        protected $status;
        protected $Estoquemin;
        protected $EstoqueAtual;
        protected $Movimentacao;

        protected $preco;

        public function __construct($nome, $descricao, $status, $estoquemin, $estoqueAtual, $Movimentacao, $preco){
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->status = $status;
            $this->Estoquemin = $estoquemin;
            $this->EstoqueAtual = $estoqueAtual;
            $this->Movimentacao = $Movimentacao;
            $this->preco = $preco;
        }

        public function get_codigo(){
            return $this->codigo; 
        }

        public function get_descricao(){
            return $this->descricao; 
        }

        public function set_id($codigo){
            $this->codigo = $codigo;
        }

        public function get_nome(){
            return $this->nome;
        }

        public function get_status(){
            return $this->status;
        }

        public function get_estoquemin(){
            return $this->Estoquemin;
        }

        public function get_EstoqueAtual(){
            return $this->EstoqueAtual;
        }

        public function get_Movimentacao(){
            return $this->Movimentacao;
        }

        public function get_Preco(){
            return $this->preco;
        }
    }

?>