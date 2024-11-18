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
    <link rel="stylesheet" href="../assets/css/Material.css">
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
        <li class="item-menu"><a href="../view/SolicitacaoCompra.php">
                <span class="icon"><i class="bi bi-cart-plus"></i></span>
                <span class="txt-link">Solicitação de compra</span>
            </a>
        </li>
        <li class="item-menu"><a href="../view/SolicitacaoVenda.php">
                <span class="icon"><i class="bi bi-shop"></i></span>
                <span class="txt-link">Solicitação de venda</span>
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
                <input type="text" class="product-id" placeholder="Digite o codigo da PO" required>
            </div>
            <div class="titulo">
                <h1>Solicitaçôes de venda</h1>
            </div>
            <div class="row-buttons">
                <button id="btn-entregue">Entregue</button>
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
                        <th>titulo</th>
                        <th>Nome cliente</th>
                        <th>Preço total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas de dados serão inseridas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para editar usuário -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Editar Solicitação de venda</h2>
        <form id="form-editar-usuario">
            <input type="hidden" id="edit-codigo">
            <div>
                <label for="edit-titulo">Titulo:</label>
                <input type="text" id="edit-titulo" name="edit-titulo">
            </div>
            <div id="div-edit-preco-total">
                <label for="edit-preco-total">Preço total:</label>
                <input type="text" id="edit-preco-total" name="edit-preco-total" readonly>
            </div>
            <div id="div-edit-status">
                <label for="edit-status">Status:</label>
                <input type="text" id="edit-status" name="edit-status" readonly>
            </div>
            <div id="div-edit-nf">
                <label for="edit-nf">Numero NF:</label>
                <input type="text" id="edit-nf" name="edit-nf" readonly>
            </div>
                <div class="Produtos-select">
                    <label for="edit-Produtos">Produtos:</label>
                    <select id="edit-Produtos" name="Produtos[]" multiple></select>
                </div>

            <div class="Cliente-select">
                <label for="edit-Cliente">Cliente:</label>
                <select id="edit-Cliente" name="Cliente[]" multiple></select>
            </div>

            <div id="quantidade-container"></div>

            <div class="Prioridade-select">
                <label for="edit-prioridade">Prioridade:</label>
                <select id="edit-prioridade" name="edit-prioridade">
                    <option value="alta">Alta</option>
                    <option value="media">Média</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>
            

            <button id="btn-salvar">Salvar</button>
        </form>
    </div>
</div>

<div id="viewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Detalhes</h2>
        <form id="form-view-PO">
            <input type="hidden" id="edit-codigo">
            <div>
                <label for="view-Codigo">Código:</label>
                <input type="text" id="view-Codigo" name="view-Codigo" readonly>
            </div>
            <div>
                <label for="view-titulo">Título:</label>
                <input type="text" id="view-titulo" name="view-titulo" readonly>
            </div>
            <div>
                <label for="view-cliente">Nome do cliente:</label>
                <input type="text" id="view-cliente" name="view-cliente" readonly>
            </div>
            <div>
                <label for="view-cpf-client">CPF do cliente:</label>
                <input type="text" id="view-cpf-cliente" name="view-cpf-client" readonly>
            </div>
            <div>
                <label for="view-preco-total">Preço total:</label>
                <input type="text" id="view-preco-total" name="view-preco-total" readonly>
            </div>
            <div>
                <label for="view-status">Status:</label>
                <input type="text" id="view-status" name="view-status" readonly>
            </div>
            <div>
                <label for="view-nf">Numero NF:</label>
                <input type="text" id="view-nf" name="view-nf" readonly>
            </div>
        </form>
        <h3>Produtos</h3>
        <table id="produtos-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Valor total</th>
                </tr>
            </thead>
            <tbody id="produtos-body">
                <!-- Linhas de materiais serão inseridas aqui -->
            </tbody>
        </table>

        <button id="btn-salvaredit">Salvar</button>
    </div>
</div>


<script src="../assets/js/Menu_lateral_Home.js"></script>
<script type="module" src="../assets/js/ProdutoSelectProduto.js"></script>
<script type="module" src="../assets/js/ProdutoSelectCliente.js"></script>
<script src="../assets/js/TrazerTodaSolicitacaoVenda.js"></script>
<script src="../assets/js/AlterarExcluirPoVenda.js"></script> 
<script src="../assets/js/viewDetalhesPOVenda.js"></script>
</body>
</html>
