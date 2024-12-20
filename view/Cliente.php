<?php
session_start();

// Verifica se a sessão do id está definida
if ($_SESSION['Setor'] !== 'Admin' && $_SESSION['Setor'] !== 'Produção') {
    // Se não estiver definida ou se o ID não for 1, redireciona para a página de login
    echo '<script type="text/javascript">
    alert("Usuário não tem permissão a essa tela.");
    window.location.href = "../view/Estoque.php";
    </script>';
    exit();
}

$success = isset($_GET['success']) ? $_GET['success'] : null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/MenuLateral.css">
    <link rel="stylesheet" href="../assets/css/Material.css">
    <link rel="stylesheet" href="../assets/css/Modal_AlterarExcluirProduto.css"> <!-- Adicione o CSS do modal -->
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
                <input type="text" class="product-id" placeholder="Digite o codigo do Produto" required>
            </div>
            <div class="titulo">
                <h1>Tabela de clientes</h1>
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
                        <th>codigo cliente</th>
                        <th>cpf cliente</th>
                        <th>nome cliente</th>
                        <th>email cliente</th>
                        <th>telefone cliente</th>
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
        <h2 id="modal-title">Editar Cliente</h2>
        <form id="form-editar-usuario">
            <input type="hidden" id="edit-codigo">
            <div>
                <label for="edit-nome">CPF:</label>
                <input type="text" id="edit-cpf" name="edit-cpf">
            </div>
            <div>
                <label for="edit-quantidade">Nome:</label>
                <input type="text" id="edit-nome" name="edit-nome">
            </div>
            <div>
                <label for="edit-estado">Email:</label>
                <input type="text" id="edit-email" name="edit-email">
            </div>
            <div>
                <label for="edit-estado">CEP:</label>
                <input type="text" id="edit-cep" name="edit-cep">
            </div>
            <div>
                <label for="edit-estado">Bairro:</label>
                <input type="text" id="edit-bairro" name="edit-bairro">
            </div>
            <div>
                <label for="edit-estado">uf:</label>
                <input type="text" id="edit-uf" name="edit-email">
            </div>
            <div>
                <label for="edit-estado">Telefone:</label>
                <input type="text" id="edit-telefone" name="edit-telefone">
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
                <label for="view-CPF">CPF:</label>
                <input type="text" id="view-CPF" name="view-CPF" readonly>
            </div>
            <div>
                <label for="view-nome">Nome:</label>
                <input type="text" id="view-nome" name="view-nome" readonly>
            </div>
            <div>
                <label for="view-email">Email:</label>
                <input type="text" id="view-email" name="view-email" readonly>
            </div>
        </form>
        <h3>Endereço</h3>
        <table id="endereco-table" readonly>
            <thead>
                <tr>
                    <th>Bairro</th>
                    <th>UF</th>
                    <th>CEP</th>
                </tr>
            </thead>
            <tbody id="endereco-body">
                <!-- Linhas de materiais serão inseridas aqui -->
            </tbody>
        </table>

        <button id="btn-salvaredit">Salvar</button>
    </div>
</div>

<script src="../assets/js/Menu_lateral_Home.js"></script>
<script src="../assets/js/TrazerTodosClientes.js"></script>
<script src="../assets/js/AlterarExcluirCliente.js"></script> 
<script src="../assets/js/API_CEP.js"></script> 
<script src="../assets/js/viewDetalhesCliente.js"></script>
</body>
</html>
