<?php

require_once('../model/SolicitacaoVendaDAO.php');


$PoVendaDAO = new SolicitacaoVendaDAO();

$consultaPoVenda = $PoVendaDAO->TrazerTodaSolicitacao();

echo json_encode($consultaPoVenda);

?>