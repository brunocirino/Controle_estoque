<?php

    class SolicitacaoVenda{
        protected $codigo;
        protected $Titulo;

        protected $nomeCli;
        protected $cpfCli;
        protected $cod_prod;
        protected $nome_prod;
        protected $qtd_prod;
        protected $preco_unit_prod;
        protected $preco_total;


        public function __construct($Titulo, $nomeCli, $cpfCli, $cod_prod, $nome_prod, $qtd_prod, $preco_unit_prod, $preco_total){
            $this->Titulo = $Titulo;
            $this->nomeCli = $nomeCli;
            $this->cpfCli = $cpfCli;
            $this->cod_prod = $cod_prod;
            $this->nome_prod = $nome_prod;
            $this->qtd_prod = $qtd_prod;
            $this->preco_unit_prod = $preco_unit_prod;
            $this->preco_total = $preco_total;
          
        }

        public function get_codigo(){
            return $this->codigo; 
        }

        public function get_Titulo(){
            return $this->Titulo; 
        }

        public function get_nomeCli(){
            return $this->nomeCli; 
        }

        public function get_cpfCli(){
            return $this->cpfCli; 
        }

        public function set_id($codigo){
            $this->codigo = $codigo;
        }

        public function get_cod_prod(){
            return $this->cod_prod;
        }

        public function get_nome_prod(){
            return $this->nome_prod;
        }

        public function get_qtd_prod(){
            return $this->qtd_prod;
        }

        public function get_preco_total(){
            return $this->preco_total;
        }

        public function get_preco_unit_prod(){
            return $this->preco_unit_prod;
        }

    }

?>