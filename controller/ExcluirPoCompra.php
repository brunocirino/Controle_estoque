<?php

require_once('../model/SolicitacaoCompraDAO.php');

    $CodPo = $_POST['id_identificador'];

    $SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
    
    $resultados = $SolicitacaoCompraDAO->excluir_Po_Compra($CodPo);

    echo json_encode(["success" => $resultados]);

  ?> 
   