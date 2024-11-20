<?php
require_once('../model/MaterialDAO.php');

    $codMat = $_POST['CodMat'];
    $NomeMat = $_POST['NomeMat'];
    $descMat = $_POST['DescMat'];
    $estatusMat = $_POST['StatusMat'];
    $estoqueMin  = $_POST['EstoqueMin'];
    $estoqueAtual  = $_POST['EstoqueAtual'];
    $preco = $_POST['preco'];

    $AlterarUser = new MaterialDAO();
    
    $AlterarUser->Atualizar_material($codMat, $NomeMat, $descMat, $estatusMat, $estoqueMin, $estoqueAtual, $preco);


  ?> 
   