<?php

require_once('../model/SolicitacaoCompraDAO.php');

    $CodPo = $_POST['Codigo'];
    $titulo = $_POST['Titulo'];
    $NF = $_POST['nf'];
    $Prioridade = $_POST['Prioridade'];
    $Fornecedores = $_POST['Fornecedores'];
    $Status = $_POST['Status'];

    $materiais = isset($_POST['Materiais']) ? json_decode($_POST['Materiais'], true) : [];

    $SolicitacaoCompraDAO = new SolicitacaoCompraDAO();
    
    $SolicitacaoCompraDAO->Atualizar_PoCompra($CodPo, $NF, $titulo, $Fornecedores, $materiais, $Prioridade, $Status);

    echo '<script type="text/javascript">
        alert("Produto alterado com sucesso");
        window.location.href = "../view/Produto.php";
        </script>';

  ?> 
   