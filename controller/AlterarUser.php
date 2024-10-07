<?php
require_once('../model/user.php');
require_once('../model/userDAO.php');

    $Codigo = $_POST['Codigo'];
    $Nome = $_POST['Nome'];
    $login = $_POST['Login'];
    $senha = $_POST['Senha'];
    $Status = $_POST['Status'];
    $Setor = $_POST['Setor'];

    echo $Codigo;

    $AlterarUser = new UserDAO();
    
    $AlterarUser->Atualizar_usuario($Codigo, $Nome, $login, $senha, $Status, $Setor);

    echo '<script type="text/javascript">
        alert("Usuário alterado com sucesso");
        window.location.href = "../view/Usuarios.php";
        </script>';

  ?> 
   