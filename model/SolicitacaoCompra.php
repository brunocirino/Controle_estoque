<?php

    class SolicitacaoCompra{
        protected $codigo;
        protected $titulo;
        protected $id_forn;
        protected $id_mat;
        protected $qtd_mat;
        protected $preco_unit;
        protected $preco_total;

        protected $prioridade;

        public function __construct($titulo, $id_forn, $id_mat, $qtd_mat, $preco_unit, $preco_total, $prioridade){
            $this->titulo = $titulo;
            $this->id_forn = $id_forn;
            $this->id_mat = $id_mat;
            $this->preco_unit = $preco_unit;
            $this->preco_total = $preco_total;
            $this->prioridade = $prioridade;
            $this->qtd_mat = $qtd_mat;
          
        }

        public function get_codigo(){
            return $this->codigo; 
        }

        public function get_qtdMat(){
            return $this->qtd_mat; 
        }

        public function get_titulo(){
            return $this->titulo; 
        }

        public function set_id($codigo){
            $this->codigo = $codigo;
        }

        public function get_id_forn(){
            return $this->id_forn;
        }

        public function get_id_mat(){
            return $this->id_mat;
        }

        public function get_preco_unit(){
            return $this->preco_unit;
        }

        public function get_preco_total(){
            return $this->preco_total;
        }

        public function get_prioiradade(){
            return $this->prioridade;
        }

    }

?>