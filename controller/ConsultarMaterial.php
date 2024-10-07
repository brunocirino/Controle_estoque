<?php

require_once('../model/MaterialDAO.php');

$Codigo = $_POST['Codigo'];

$MaterialDAO = new MaterialDAO();

if($Codigo){
    $consultaMaterais = $MaterialDAO->ConsultarMaterial($Codigo);

    echo json_encode($consultaMaterais);
}else{
    $consultaMaterais = $MaterialDAO->TrazerTodosMateriais();

    echo json_encode($consultaMaterais);
}
?>