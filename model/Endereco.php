<?php

    class Endereco{
        protected $id_end;
        protected $bairro;
        protected $uf;
        protected $cep;
        public function __construct($bairro, $uf, $cep){
            $this->bairro = $bairro;
            $this->uf = $uf;
            $this->cep = $cep;
        }

        public function get_id_end(){
            return $this->id_end; 
        }

        public function get_bairro(){
            return $this->bairro; 
        }

        public function set_id_end($id_end){
            $this->id_end = $id_end;
        }

        public function get_uf(){
            return $this->uf;
        }

        public function get_cep(){
            return $this->cep;
        }

    }

?>