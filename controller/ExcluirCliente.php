<?php
require_once('../model/Cliente.php');
require_once('../model/ClienteDAO.php');

    $codCliente = $_POST['CodCliente'];
    $id_end = $_POST['id_end'];

    $ExcluirCliente = new ClienteDAO();
    
    $ExcluirCliente->excluir_cliente($codCliente, $id_end);

  ?> 
   