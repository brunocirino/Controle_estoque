<?php

require_once('../model/SolicitacaoVendaDAO.php');

    $CodPo = $_POST['Codigo'];
    $NF = $_POST['NF'];
    $titulo = $_POST['Titulo'];
    $nmCliente = $_POST['nmCliente'];
    $cpfCliente = $_POST['cpfCliente'];
    $preco_total_PO = $_POST['preco_total_PO'];
    $Status = $_POST['Status'];
    

    $produtos = isset($_POST['produtos']) ? json_decode($_POST['produtos'], true) : [];

    $SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
    
    $SolicitacaoVendaDAO->Atualizar_PoVenda($CodPo, $titulo, $NF, $nmCliente, $cpfCliente, $produtos, $Status, $preco_total_PO);

    echo '<script type="text/javascript">
        alert("Produto alterado com sucesso");
        window.location.href = "../view/Produto.php";
        </script>';

  ?> 
   