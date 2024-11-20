<?php
require_once('../model/EstoqueDAO.php');

$quantidadeProduto = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0; 
$id_identificador = $_POST['id_identificador'];
$EntradaMode = isset($_POST['Entrada']) && $_POST['Entrada'] === 'true';
$PerdaMode = isset($_POST['Perda']) && $_POST['Perda'] === 'true';

$Estoque = new EstoqueDAO();

$avisos = [];


$materiais = $Estoque->ConsultarProduto($id_identificador);


$qtd_produto_atual = $materiais[0]['qtdProd']; 

if (!$EntradaMode && $qtd_produto_atual <= 0) {
    echo json_encode(['erro' => "A quantidade do produto ID: $id_identificador é zero. Não é possível realizar a baixa."]);
    exit;
}

//
foreach ($materiais as &$material) {
    $id_material = $material['id_material'];
    $qtd_usada_material = $material['qtd_material']; 
    
   
    $qtd_atual_array = $Estoque->ConsultarQTDAtualMat($id_material);
    
    
    if (!$qtd_atual_array) {
        echo json_encode(['erro' => "Material ID: $id_material não encontrado."]);
        exit;
    }
    
    $qtd_atual = $qtd_atual_array['estoqueAtual'];
    $estoqueMin = $qtd_atual_array['estoqueMin'];

    
    $quantidadeTotal = $quantidadeProduto * $qtd_usada_material; 

    
    if (!$EntradaMode && $quantidadeTotal > $qtd_atual) {
      
        echo json_encode(['erro' => "Estoque insuficiente para o material ID: $id_material. Disponível: $qtd_atual, Necessário: $quantidadeTotal"]);
        exit;
    }

   
    $nova_qtd = $EntradaMode ? $qtd_atual - $quantidadeTotal : $qtd_atual + $quantidadeTotal;
    if ($nova_qtd < $estoqueMin) {
        $avisos[] = "Atenção: Estoque do material ID: $id_material ficará abaixo do mínimo após atualização.";
    }

    
    $Estoque->AtualizarQuantidadeMaterial($id_material, $nova_qtd);
}

// Atualiza a quantidade do produto


if ($PerdaMode == false){
    $quantidadeProdutoTotal = $qtd_produto_atual - $quantidadeProduto;
}else{
    $quantidadeProdutoTotal = $EntradaMode ? ($quantidadeProduto + $qtd_produto_atual) : ($qtd_produto_atual - $quantidadeProduto);
}

$Estoque->Atualizar_Produto($id_identificador, $quantidadeProdutoTotal);

echo json_encode([
    'sucesso' => 'Estoque atualizado com sucesso!',
    'avisos' => $avisos 
]);
?>
