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
                <h1>Tabela de produtos</h1>
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
                        <th>codigo produto</th>
                        <th>Nome produto</th>
                        <th>Quantidade produto</th>
                        <th>Preço</th>
                        <th>Estado produto</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas de dados serão inseridas aqui -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Editar produto</h2>
        <form id="form-editar-usuario">
            <input type="hidden" id="edit-codigo">
            <div>
                <label for="edit-nome">Nome:</label>
                <input type="text" id="edit-nome" name="edit-nome">
            </div>
            <div>
                <label for="edit-quantidade">Quantidade:</label>
                <input type="text" id="edit-quantidade" name="edit-senha">
            </div>
            <div>
                <label for="edit-preco">Preço:</label>
                <input type="number" id="edit-preco" name="edit-preco">
            </div>
            <div>
                <label for="edit-estado">Status:</label>
                <select id="edit-estado" name="edit-estado">
                    <option value="" disabled selected>Selecione um status</option>
                    <option value="Torno">Torno</option>  
                    <option value="Furadeira fixa">Furadeira fixa</option>    
                    <option value="Dobradeira">Dobradeira</option>                              
                    <option value="Prensa">Prensa</option>
                    <option value="Brochadeira">Brochadeira</option>   
                    <option value="Serra vertical">Serra vertical</option>   
                    <option value="Serra Horizontal">Serra Horizontal</option>   
                    <option value="Retifica">Retifica</option>   
                                         
                </select>
            </div>

            <div class="Material-select">
                <label for="edit-materiais">Materiais:</label>
                <select id="edit-materiais" name="materiais[]" multiple></select>
            </div>

            <div id="quantidade-container"></div>

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
                <label for="view-nome">Nome produto:</label>
                <input type="text" id="view-nome" name="view-nome" readonly>
            </div>
            <div>
                <label for="view-qtd-prod">Quanitdade do produto:</label>
                <input type="text" id="view-qtd-prod" name="view-qtd-prod" readonly>
            </div>
            <div>
                <label for="view-preco">Preço:</label>
                <input type="text" id="view-preco" name="view-preco" readonly>
            </div>
            <div>
                <label for="view-estado">Estado do produto:</label>
                <input type="text" id="view-estado" name="view-estado" readonly>
            </div>
        </form>
        <h3>Materiais</h3>
        <table id="materiais-table">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Material</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody id="materiais-body">
                <!-- Linhas de materiais serão inseridas aqui -->
            </tbody>
        </table>

        <button id="btn-salvaredit">Salvar</button>
    </div>
</div>

<script src="../assets/js/Menu_lateral_Home.js"></script>
<script type="module" src="../assets/js/ProdutoSelectMaterial.js"></script>
<script src="../assets/js/TrazerTodosProdutos.js"></script>
<script type="module" src="../assets/js/AlterarExcluirProduto.js"></script> 
<script src="../assets/js/viewDetalhesProduto.js"></script>
</body>
</html>
