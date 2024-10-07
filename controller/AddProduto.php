<?php

require_once('../model/Produtos.php');
require_once('../model/ProdutosDAO.php');

$CodProd = $_POST['Codigo'];
$NomeProd = $_POST['Nome'];
$QtdProd = $_POST['QtdProd'];
$EstadoProd = $_POST['EstadoProd'];

$materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];

// Cria um novo produto
$AdicionarProduto = new ProdutosDAO();
$produto = new Produto($CodProd, $NomeProd, $QtdProd, $EstadoProd);

$id_atual = $AdicionarProduto->Consultarid_identificadorMax();
$id_identificador = $id_atual+1;

// Chama o método para cadastrar o produto
if ($AdicionarProduto->cadastrarProduto($produto, $materiais, $id_identificador)) {
    echo "Produto cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar o produto!";
}

?>