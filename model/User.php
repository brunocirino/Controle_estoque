<?php

    class User{
        protected $id;
        protected $nome;
        protected $login;
        protected $senha;
        protected $setor;
        protected $Status = 'Ativo';

        public function __construct($nome, $login, $senha, $setor){
            $this->nome = $nome;
            $this->login = $login;
            $this->senha = $senha;
            $this->setor = $setor;
        }

        public function get_id(){
            return $this->id; 
        }

        public function get_Status(){
            return $this->Status; 
        }

        public function set_id($id){
            $this->id = $id;
        }

        public function get_nome(){
            return $this->nome;
        }

        public function get_login(){
            return $this->login;
        }

        public function get_senha(){
            return $this->senha;
        }

        public function get_setor(){
            return $this->setor;
        }

    }

?>