<?php

require_once('../model/SolicitacaoVendaDAO.php');

    $CodPo = $_POST['Codigo'];
    $NF = $_POST['NF'];
    $titulo = $_POST['Titulo'];
    $preco_total_PO = $_POST['preco_total_PO'];
    $Status = $_POST['Status'];
    
    $produtos = isset($_POST['produtos']) ? json_decode($_POST['produtos'], true) : [];

    $clientes = isset($_POST['clientes']) ? json_decode($_POST['clientes'], true) : [];

    foreach ($produtos as $index =>$produto) {
        $Valor_total = 0;
        $preco_unit = $produto['preco_unit'];
        $qtdprodutos = $produto['qtdProduto'];

        $Valor_total += $preco_unit * (int)$qtdprodutos;

        $preco_total_PO = $Valor_total;

    };

    $SolicitacaoVendaDAO = new SolicitacaoVendaDAO();
    
    $SolicitacaoVendaDAO->Atualizar_PoVenda($CodPo, $titulo, $NF, $produtos, $clientes, $Status, $preco_total_PO);

    echo '<script type="text/javascript">
        alert("Produto alterado com sucesso");
        window.location.href = "../view/Produto.php";
        </script>';

  ?> 
   