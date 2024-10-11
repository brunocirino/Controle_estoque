<?php

class Produto{
    protected $codProd;
    protected $nomeProd;
    protected $qtdProd;
    protected $estadoProd;

    protected $preco;

    public function __construct($codProd, $nomeProd, $qtdProd, $estadoProd, $preco){
        $this->codProd = $codProd;
        $this->nomeProd = $nomeProd;
        $this->qtdProd = $qtdProd;
        $this->estadoProd = $estadoProd;
        $this->preco = $preco;
    }

    public function get_id(){
        return $this->codProd;
    }

    public function get_preco(){
        return $this->preco;
    }

    public function set_id($codProd){
        $this->codProd = $codProd;
    }


    public function get_nome(){
        return $this->nomeProd;
    }

    public function set_nome($nomeProd){
        $this->nomeProd = $nomeProd;
    }

    public function get_quantidade(){
        return $this->qtdProd;
    }

    public function get_estadoProd(){
        return $this->estadoProd;
    }

    public function set_quantidade($qtdProd){
        $this->qtdProd = $qtdProd;
    }

}


?>