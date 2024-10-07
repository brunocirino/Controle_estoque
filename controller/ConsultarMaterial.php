<?php

require_once('../model/MaterialDAO.php');

$Codigo = $_POST['Codigo'];

$MaterialDAO = new MaterialDAO();

$consultaMaterais = $MaterialDAO->ConsultarMaterial($Codigo);

echo json_encode($consultaMaterais);

?>