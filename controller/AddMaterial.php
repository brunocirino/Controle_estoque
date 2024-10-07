<?php
require_once('../model/Material.php');
require_once('../model/MaterialDAO.php');

$codMat = $_POST['CodMat'];
$NomeMat = $_POST['NomeMat'];
$descMat = $_POST['DescMat'];
$statusMat = $_POST['StatusMat'];
$estoqueMin  = $_POST['EstoqueMin'];
$estoqueAtual  = $_POST['EstoqueAtual'];
$movimentacao   = $_POST['Movimentacao'];
        
    $Material = new Material($NomeMat, $descMat, $statusMat, $estoqueMin, $estoqueAtual, $movimentacao);
    $MaterialDAO = new MaterialDAO();
    

    if($MaterialDAO->cadastrarMaterial($Material)){

        echo '<script type="text/javascript">
        alert("Usuário incluído com sucesso.");
        window.location.href = "../view/AdicionarUsuarios.php";
        </script>';

    } else {
       echo '<script type="text/javascript">
        alert("Erro inesperado!");
        window.location.href = "../view/AdicionarUsuarios.php";
        </script>';
    }
    
   
   