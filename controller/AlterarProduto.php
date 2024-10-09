<?php

require_once('../model/ProdutosDAO.php');

    $CodProd = $_POST['Codigo'];
    $NomeProd = $_POST['Nome'];
    $QtdProd = $_POST['QtdProd'];
    $EstadoProd = $_POST['EstadoProd'];
    $CodMat = $_POST['CodMat'];
    $qtdMat = $_POST['qtdMat'];
    $Processos = $_POST['Processos'];

    $materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];

    $AlterarProduto = new ProdutosDAO();
    
    $AlterarProduto->Atualizar_Produto($CodProd, $NomeProd, $QtdProd, $EstadoProd, $materiais);

    echo '<script type="text/javascript">
        alert("Produto alterado com sucesso");
        window.location.href = "../view/Produto.php";
        </script>';

  ?> 
   