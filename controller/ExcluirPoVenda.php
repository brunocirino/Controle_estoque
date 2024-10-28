<?php

require_once('../model/SolicitacaoVendaDAO.php');

    $CodPo = $_POST['id_identificador'];

    $SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
    
    $SolicitacaoVendaDAO->excluir_Po_Venda($CodPo);

  ?> 
   