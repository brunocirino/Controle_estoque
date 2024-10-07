<?php
require_once('../model/user.php');
require_once('../model/userDAO.php');

    $Nome = $_POST['nome'];
    $Login = $_POST['login'];
    $Senha = $_POST['senha'];
    $Setor = $_POST['setor'];
        
    $user = new User($Nome, $Login, $Senha, $Setor);
    $userDAO = new UserDAO();
    

    if($userDAO->cadastrarUsuario($user)){

        echo '<script type="text/javascript">
        alert("Usuário incluído com sucesso.");
        window.location.href = "../view/AdicionarUsuarios.php";
        </script>';

    } else {
       echo '<script type="text/javascript">
        alert("Erro inesperado!");
        window.location.href = "../view/AdicionarUsuarios.php";
        </script>';
    }
    
   
   