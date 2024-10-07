<?php

require_once('../model/UserDAO.php');

$Codigo = $_POST['Codigo'];

$userDAO = new UserDAO();

$consultaUsers = $userDAO->ConsultarUsuario($Codigo);

echo json_encode($consultaUsers);

?>