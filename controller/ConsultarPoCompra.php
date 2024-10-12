<?php

require_once('../model/SolicitacaoCompraDAO.php');

$Codigo = $_POST['Codigo'];

$SolicitacaoCompraDAO = new SolicitacaoCompraDAO();

$ConsultarPoCompras = $SolicitacaoCompraDAO->ConsultarPoCompra($Codigo);

echo json_encode($ConsultarPoCompras);

?>