<?php

require_once('../model/SolicitacaoCompraDAO.php');


$PoCompraDAO = new SolicitacaoCompraDAO();

$consultaPoCompra = $PoCompraDAO->TrazerTodaSolicitacao();

echo json_encode($consultaPoCompra);

?>