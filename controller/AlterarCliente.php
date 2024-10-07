<?php
require_once('../model/ClienteDAO.php');
require_once('../model/EnderecoDAO.php');

  $id_end = $_POST['id_end'];
  $codCli = $_POST['Codigo'];
  $Nome = $_POST['Nome'];
  $cpfCli = $_POST['cpfCli'];
  $emailCli = $_POST['emailCli'];
  $CEP = $_POST['CEP'];
  $bairro = $_POST['Bairro'];
  $UF = $_POST['UF'];
  $Telefone = $_POST['Telefone'];

    $ClienteDAO = new ClienteDAO();
    $EnderecoDAO = new EnderecoDAO();
    
    $ClienteDAO->Atualizar_Cliente($codCli, $cpfCli, $Nome, $emailCli, $Telefone);

    $EnderecoDAO->Atualizar_endereco($id_end, $bairro, $UF, $CEP);


  ?> 
   