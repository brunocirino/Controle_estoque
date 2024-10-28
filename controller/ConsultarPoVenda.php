<?php

require_once('../model/SolicitacaoVendaDAO.php');

$Codigo = $_POST['Codigo'];

$SolicitacaoVendaDAO = new SolicitacaoVendaDAO();

$ConsultarPoVenda = $SolicitacaoVendaDAO->ConsultarPoVenda($Codigo);

echo json_encode($ConsultarPoVenda);

?>