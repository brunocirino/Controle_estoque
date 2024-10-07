<?php

require_once('../model/MaterialDAO.php');


$MaterialDAO = new MaterialDAO();

$consultaMaterial = $MaterialDAO->TrazerTodosMateriais();

echo json_encode($consultaMaterial);

?>