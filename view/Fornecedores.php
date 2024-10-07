<?php
session_start();

// Verifica se a sessão do id está definida
if ($_SESSION['Setor'] !== 'Admin' && $_SESSION['Setor'] !== 'Compras') {
    // Se não estiver definida ou se o ID não for 1, redireciona para a página de login
    echo '<script type="text/javascript">
    alert("Usuário não é um administrador.");
    window.location.href = "../view/Estoque.php";
    </script>';
    exit();
}

$success = isset($_GET['success']) ? $_GET['success'] : null;
?>

echo "<script>var idProfessor = " . $_SESSION['id'] . ";</script>";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/MenuLateral.css">
    <link rel="stylesheet" href="../assets/css/Usuários.css">
    <link rel="stylesheet" href="../assets/css/Modal_AlterarExcluirUser.css"> <!-- Adicione o CSS do modal -->
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
                <input type="text" class="product-id" placeholder="Digite o codigo do user" required>
            </div>
            <div class="titulo">
                <h1>Tabela Fornecedores</h1>
            </div>
            <div class="row-buttons">
                <button id="btn-adicionar">Adicionar</button>
                <button id="btn-editar">Editar</button>
                <button id="btn-Excluir">Excluir</button>
            </div>
        </div>
        <div class="tabela">
            <!-- Tabela para exibir informações do banco -->
            <table>
                <thead>
                    <tr>
                        <th>codigo</th>
                        <th>Nome</th>
                        <th>Nome fantasia</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>Contactante</th>
                        <th>Endereço faturamento</th>
                        <th>Endereço Entrega</th>
                        <th>Endereço Cobrança</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas de dados serão inseridas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para editar Fornecedor -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Editar Fornecedor</h2>
        <form id="form-editar-usuario">
            <input type="hidden" id="edit-codigo">
            <div>
                <label for="edit-nome">Nome:</label>
                <input type="text" id="edit-nome" name="edit-nome">
            </div>
            <div>
                <label for="edit-fantasia">Nome fantasia:</label>
                <input type="text" id="edit-fantasia" name="edit-fantasia">
            </div>
            <div>
                <label for="edit-CNPJ">CNPJ:</label>
                <input type="text" id="edit-CNPJ" name="edit-CNPJ">
            </div>
            <div>
                <label for="edit-telefone">Telefone:</label>
                <input type="text" id="edit-telefone" name="edit-telefone">
            </div>
            <div>
                <label for="edit-contactante">Contactante:</label>
                <input type="text" id="edit-contactante" name="edit-contactante">
            </div>
            <div>
                <label for="edit-faturamento">Endereço faturamento:</label>
                <input type="text" id="edit-faturamento" name="edit-faturamento">
            </div>
            <div>
                <label for="edit-entrega">Endereço Entrega:</label>
                <input type="text" id="edit-entrega" name="edit-entrega">
            </div>
            <div>
                <label for="edit-cobrança">Endereço cobrança:</label>
                <input type="text" id="edit-cobrança" name="edit-cobrança">
            </div>

            <button id="btn-salvar">Salvar</button>
        </form>
    </div>
</div>

<script src="../assets/js/Menu_lateral_Home.js"></script>
<script src="../assets/js/TrazerTodosFornecedores.js"></script>
<script src="../assets/js/AlterarExcluirFornecedor.js"></script> 
</body>
</html>
