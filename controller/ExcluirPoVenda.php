<?php

require_once('../model/SolicitacaoVendaDAO.php');

    $CodPo = $_POST['id_identificador'];

    $SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
    
    $resultados = $SolicitacaoVendaDAO->excluir_Po_Venda($CodPo);

    echo json_encode(["success" => $resultados]);

  ?> 
   