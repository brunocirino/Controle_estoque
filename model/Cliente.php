<?php

    class Cliente{
        protected $codCli;
        protected $cpfCli;
        protected $nomeCli;
        protected $emailCli;
        protected $telefone;
        protected $id_end;
        public function __construct($cpfCli, $nomeCli, $emailCli, $telefone, $id_end){
            $this->cpfCli = $cpfCli;
            $this->nomeCli = $nomeCli;
            $this->emailCli = $emailCli;
            $this->telefone = $telefone;
            $this->id_end = $id_end;
        }

        public function get_codigo(){
            return $this->codCli; 
        }

        public function get_cpfCli(){
            return $this->cpfCli; 
        }

        public function set_id($codCli){
            $this->codCli = $codCli;
        }

        public function set_id_end($id_end){
            $this->codCli = $id_end;
        }

        public function get_nome(){
            return $this->nomeCli;
        }

        public function get_emailCli(){
            return $this->emailCli;
        }

        public function get_id_end(){
            return $this->id_end;
        }

        public function get_telefone(){
            return $this->telefone;
        }
    }

?>