<?php
require_once('../model/EstoqueDAO.php');

$quantidadeProduto = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0; // Quantidade de produtos informada
$id_identificador = $_POST['id_identificador'];
$EntradaMode = isset($_POST['Entrada']) && $_POST['Entrada'] === 'true';

$Estoque = new EstoqueDAO();

// Array para armazenar avisos
$avisos = [];

// Consulta os materiais associados ao produto
$materiais = $Estoque->ConsultarProduto($id_identificador);

// Verifica a quantidade atual do produto antes de qualquer operação
$qtd_produto_atual = $materiais[0]['qtdProd']; // Presumindo que o produto seja o primeiro na lista

// Se o modo não for de entrada e a quantidade do produto for zero, retorna um erro
if (!$EntradaMode && $qtd_produto_atual <= 0) {
    echo json_encode(['erro' => "A quantidade do produto ID: $id_identificador é zero. Não é possível realizar a baixa."]);
    exit;
}

// Loop através de cada material associado ao produto
foreach ($materiais as &$material) {
    $id_material = $material['id_material'];
    $qtd_usada_material = $material['qtd_material']; // Quantidade de material necessária por produto
    
    // Consulta a quantidade atual do material no banco de dados
    $qtd_atual_array = $Estoque->ConsultarQTDAtualMat($id_material);
    
    // Verifica se algum resultado foi retornado
    if (!$qtd_atual_array) {
        echo json_encode(['erro' => "Material ID: $id_material não encontrado."]);
        exit;
    }
    
    $qtd_atual = $qtd_atual_array['estoqueAtual'];
    $estoqueMin = $qtd_atual_array['estoqueMin'];

    // Calcula a quantidade total a ser baixada ou adicionada
    $quantidadeTotal = $quantidadeProduto * $qtd_usada_material; // Multiplica a quantidade de produtos pela quantidade de material necessária

    // Verifica se a quantidade a ser baixada é maior que o estoque atual
    if (!$EntradaMode && $quantidadeTotal > $qtd_atual) {
        // Retorna um erro se o estoque não for suficiente
        echo json_encode(['erro' => "Estoque insuficiente para o material ID: $id_material. Disponível: $qtd_atual, Necessário: $quantidadeTotal"]);
        exit;
    }

    // Verifica se a nova quantidade após a operação ficará abaixo do estoque mínimo
    $nova_qtd = $EntradaMode ? $qtd_atual - $quantidadeTotal : $qtd_atual + $quantidadeTotal;
    if ($nova_qtd < $estoqueMin) {
        $avisos[] = "Atenção: Estoque do material ID: $id_material ficará abaixo do mínimo após atualização.";
    }

    // Atualiza a nova quantidade do material no banco de dados
    $Estoque->AtualizarQuantidadeMaterial($id_material, $nova_qtd);
}

// Atualiza a quantidade do produto
$quantidadeProdutoTotal = $EntradaMode ? ($quantidadeProduto + $qtd_produto_atual) : ($qtd_produto_atual - $quantidadeProduto); // Verifique se `materiais[0]['qtdProd']` existe antes de acessar
$Estoque->Atualizar_Produto($id_identificador, $quantidadeProdutoTotal);

// Retorna sucesso ao final do processamento, incluindo os avisos
echo json_encode([
    'sucesso' => 'Estoque atualizado com sucesso!',
    'avisos' => $avisos // Inclui os avisos
]);
?>
