<?php

require_once('../model/UserDAO.php');


$userDAO = new UserDAO();

$consultaUsers = $userDAO->TrazerTodosUsers();

echo json_encode($consultaUsers);

?>