<?php
    session_start();

    $success = isset($_GET['success']) ? $_GET['success'] : null;

    // Verifica se a sessão do id está definida
    if(!isset($_SESSION['id'])) {
        // Se não estiver definida, redireciona para a página de login
        
        header("Location: ../view/Login.php?from=Estoque");
        exit();
    } 

    echo "<script>var idProfessor = " . $_SESSION['id'] . ";</script>";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css//MenuLateral.css">
    <link rel="stylesheet" href="../assets/css//Estoque.css">
    <title>Controle de estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
        <li class="item-menu"><a href="../view/Produto.php">
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
        <li class="item-menu"><a href="../view/Logout.php">
                <span class="icon"><i class="bi bi-person-circle"></i></span>
                <span class="txt-link">Conta</span>
            </a>
        </li>
    </ul>
</nav>
    <div class="conteudo">
        <div class="quadro">
            <!-- Linha para entrada de ID do produto e botões -->
            <div class="cabecalho">
                <div class="row-input">
                    <input type="text" class="product-id" placeholder="Digite o ID do produto" required>
                </div>
                <div class="titulo">
                <h1>Tabela de produtos</h1>
                </div>
                <div class="row-buttons">
                    <button id="btn-baixa">Perda</button>
                    <button id="btn-entrada">Entrada</button>
                </div>
            </div>
            <div class="tabela">
                <!-- Tabela para exibir informações do banco -->
                <table>
                    <thead>
                        <tr>
                            <th>Selecionar</th>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <!-- Adicione mais cabeçalhos conforme necessário -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- As linhas de dados serão inseridas aqui -->
                        <!-- Exemplo de linha de dados:-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../assets/js/Menu_lateral_Home.js"></script>
    <script src="../assets/js/TrazerTodosProdutos.js"></script>
    <script src="../assets/js/SelecionarLinha.js"></script>
</body>
</html>