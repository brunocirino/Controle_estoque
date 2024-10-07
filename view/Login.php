<?php
$error = isset($_GET['error']) ? $_GET['error'] : null;



if ($error == 1) {
    ?>
    <script>alert('Erro ao logar, E-mail ou senha invalida');</script>
    <?php
}else{
    if (isset($_SESSION['id'])) {
        ?>
        <script>console.log($id);</script>
        <?php
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/MenuLateral.css">
    <link rel="stylesheet" href="../assets/css/Login.css">
    <title>Home - Controle de estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<nav class="menu-lateral">
    <div class="btn-expandir">
        <i class="bi bi-list-ul" id="btn-exp"></i>
    </div>

    <ul>
        <li class="item-menu"><a href="../view/Estoque.php">
                <span class="icon"><i class="bi bi-box-fill"></i></span>
                <span class="txt-link">Estoque</span>
            </a>
        </li>
        <li class="item-menu"><a href="../view/Material.php">
                <span class="icon"><i class="bi bi-cart4"></i></span>
                <span class="txt-link">Material</span>
            </a>
        </li>
        <li class="item-menu"><a href="../view/AdicionarUsuarios.php">
                <span class="icon"><i class="bi bi-clipboard"></i></span>
                <span class="txt-link">Produto</span>
            </a>
        </li>

        <li class="item-menu"><a href="../view/Cliente.php">
                <span class="icon"><i class="bi bi-person-vcard-fill"></i></span>
                <span class="txt-link">Cliente</span>
            </a>
        </li>
        <li class="item-menu"><a href="../view/Fornecedores.php">
                <span class="icon"><i class="bi bi-person-walking"></i></span>
                <span class="txt-link">Fornecedores</span>
            </a>
        </li>
        <li class="item-menu"><a href="../view/Usuarios.php">
                <span class="icon"><i class="bi bi-person-lines-fill"></i></span>
                <span class="txt-link">Usuários</span>
            </a>
        </li>
    </ul>
</nav>

    <main id="content">
        
        <form id="loginForm" action="../controller/UserLogin.php" method="post" class="login">
       
            <h2>Login</h2>
            <div class="box-user">
                <input type="text" name="email" required>
                <label>Usuário</label>
            </div>
            <div class="box-user">
                <input type="password" name="senha" required>
                <label>Senha</label>
            </div>

            <div class="container-button">
            <button type="submit"><a class="btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Entrar
            </a></button>

            </div>
        </form>
    </main>

    <script src="../assets/js/Menu_lateral_Home.js"></script>
</body>
</html>