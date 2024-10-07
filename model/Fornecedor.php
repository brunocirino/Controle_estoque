<?php

    class Fornecedor{
        protected $id;
        protected $nome;
        protected $Nome_fantasia;
        protected $CNPJ;
        protected $Telefone;
        protected $Contactante;
        protected $End_Faturamento;
        protected $End_entrega;
        protected $End_cobranca;

        public function __construct($nome, $Nome_fantasia, $CNPJ, $Telefone, $Contactante, $End_Faturamento, $End_entrega, $End_cobranca){
            $this->nome = $nome;
            $this->Nome_fantasia = $Nome_fantasia;
            $this->CNPJ = $CNPJ;
            $this->Telefone = $Telefone;
            $this->Contactante = $Contactante;
            $this->End_Faturamento = $End_Faturamento;
            $this->End_entrega = $End_entrega;
            $this->End_cobranca = $End_cobranca;

        }

        public function get_id(){
            return $this->id; 
        }

        public function get_Nome_fantasia(){
            return $this->Nome_fantasia; 
        }

        public function set_id($id){
            $this->id = $id;
        }

        public function get_nome(){
            return $this->nome;
        }

        public function get_CNPJ(){
            return $this->CNPJ;
        }

        public function get_Telefone(){
            return $this->Telefone;
        }

        public function get_Contactante(){
            return $this->Contactante;
        }

        public function get_End_Faturamento(){
            return $this->End_Faturamento;
        }


        public function get_End_entrega(){
            return $this->End_entrega;
        }


        public function get_End_cobranca(){
            return $this->End_cobranca;
        }


    }

?>