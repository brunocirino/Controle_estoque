<?php
require_once('../model/Material.php');
require_once('../model/MaterialDAO.php');

    $CodMat = $_POST['CodMat'];

    $AlterarMaterial = new MaterialDAO();
    
    $AlterarMaterial->excluir_material($CodMat);

  ?> 
   