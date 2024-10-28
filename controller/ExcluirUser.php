<?php
require_once('../model/user.php');
require_once('../model/userDAO.php');

    $Codigo = $_POST['Codigo'];

    $AlterarUser = new UserDAO();
    
    $resultado = $AlterarUser->excluir_usuario($Codigo);

    echo json_encode(["success" => $resultado]);

  ?> 
   