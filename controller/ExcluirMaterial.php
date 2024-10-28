<?php
require_once('../model/Material.php');
require_once('../model/MaterialDAO.php');

    $CodMat = $_POST['CodMat'];

    $AlterarMaterial = new MaterialDAO();
    
    $resultados = $AlterarMaterial->excluir_material($CodMat);

    echo json_encode(["success" => $resultados]);

  ?> 
   