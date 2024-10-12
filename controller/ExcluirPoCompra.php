<?php

require_once('../model/SolicitacaoCompraDAO.php');

    $CodPo = $_POST['id_identificador'];

    $SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
    
    $SolicitacaoCompraDAO->excluir_Po_Compra($CodPo);

  ?> 
   