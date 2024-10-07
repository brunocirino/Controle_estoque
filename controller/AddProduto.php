<?php
require_once('../model/Produtos.php');
require_once('../model/ProdutosDAO.php');

    $CodProd = $_POST['Codigo'];
    $NomeProd = $_POST['Nome'];
    $QtdProd = $_POST['QtdProd'];
    $EstadoProd = $_POST['EstadoProd'];

    $AdicionarProduto = new ProdutosDAO();

    $produto = new Produto($CodProd, $NomeProd, $QtdProd, $EstadoProd);

    $AdicionarProduto->cadastrarProduto($produto);

  ?> 
   