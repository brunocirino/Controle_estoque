<?php
require_once('../model/user.php');
require_once('../model/userDAO.php');

    $Codigo = $_POST['Codigo'];

    $AlterarUser = new UserDAO();
    
    $AlterarUser->excluir_usuario($Codigo);

  ?> 
   