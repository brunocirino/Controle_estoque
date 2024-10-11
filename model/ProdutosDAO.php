<?php

    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASSWORD', '');
    define('DB_NAME', 'controle_estoque');

    class ProdutosDAO{

        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarProduto($Produto, $materiais, $id_identificador) {
            
            // Inicia uma transação
            $this->banco->beginTransaction();
        
            try {
                // Prepara a consulta de inserção
                $placeholders = [];
                $values = [];
        
                // Adiciona os dados do produto para cada material
                foreach ($materiais as $material) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?,?)"; // Para cada material, cria placeholders
                    $values[] = $Produto->get_nome(); // nomeProd
                    $values[] = $Produto->get_quantidade(); // qtdProd
                    $values[] = $Produto->get_estadoProd(); // estadoProd
                    $values[] = $material['id_material']; // id_material
                    $values[] = $material['qtd_material']; // qtd_material
                    $values[] = $id_identificador;
                    $values[] = $Produto->get_preco();
                }
        
                // Junta os placeholders para a inserção
                $placeholdersString = implode(', ', $placeholders);
        
                // A consulta de inserção para produtos
                $sql = "INSERT INTO produtos (nomeProd, qtdProd, estadoProd, id_material, qtd_material, id_identificador, preco) VALUES " . $placeholdersString;
        
                // Prepara e executa a consulta
                $inserir = $this->banco->prepare($sql);
                $inserir->execute($values);
        
                // Confirma a transação
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
                return false;
            }
        }
        
        
        

        public function TrazerTodosProdutos() {
            $consulta = $this->banco->prepare('
            SELECT 
                p.nomeProd, 
                p.qtdProd, 
                p.estadoProd,
                p.id_identificador,
                p.preco,
                GROUP_CONCAT(DISTINCT m.nomeMat ORDER BY m.codMat SEPARATOR ",") AS materiais_nomes,
                GROUP_CONCAT(DISTINCT p.qtd_material ORDER BY m.codMat SEPARATOR ",") AS materiais_qtd
            FROM 
                produtos p
            JOIN 
                materiais m ON p.id_material = m.codMat
            GROUP BY 
                p.nomeProd, p.qtdProd, p.estadoProd, p.id_identificador
            ORDER BY 
                p.id_identificador ASC;
            ');
        
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }
        
        

        public function ConsultarProdutos($codProd) {
            $consulta = $this->banco->prepare('
                SELECT 
                    p.nomeProd, 
                    p.qtdProd, 
                    p.estadoProd, 
                    p.preco,
                    GROUP_CONCAT(m.nomeMat ORDER BY m.codMat SEPARATOR ",") AS materiais_nomes,
                    GROUP_CONCAT(p.id_material ORDER BY m.codMat SEPARATOR ",") AS materiais_ids,
                    GROUP_CONCAT(p.qtd_material ORDER BY m.codMat SEPARATOR ",") AS materiais_qtd,
                    p.id_identificador
                FROM 
                    produtos p
                LEFT JOIN 
                    materiais m ON p.id_material = m.codMat 
                WHERE 
                    p.id_identificador = :id_identificador
                GROUP BY 
                    p.codProd, p.nomeProd, p.qtdProd, p.estadoProd, p.id_identificador
            ');
        
            $consulta->bindValue(':id_identificador', $codProd);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            return $resultados;
        }

        public function Consultarid_identificadorMax() {
            // Prepara a consulta para selecionar o maior id_identificador
            $consulta = $this->banco->prepare('SELECT MAX(id_identificador) AS max_id FROM produtos');
            $consulta->execute();
            
            // Busca o resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Retorna o maior id_identificador ou null se não encontrado
            return $resultado ? $resultado['max_id'] : null;
        }
        

        public function Atualizar_Produto($codProd, $NomeProd, $qtdProd, $preco, $estadoProd, $materiais) {
            // Inicia uma transação
            $this->banco->beginTransaction();
        
            try {
                // Excluir todos os registros do produto com materiais existentes
                $deleteMateriais = $this->banco->prepare("DELETE FROM produtos WHERE id_identificador = ?");
                $deleteMateriais->execute(array($codProd));
        
                // Inserir novamente o produto com os materiais atualizados
                $placeholders = [];
                $values = [];
        
                foreach ($materiais as $material) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?, ?)";
                    $values[] = $NomeProd; // nomeProd
                    $values[] = $qtdProd;  // qtdProd
                    $values[] = $estadoProd; // estadoProd
                    $values[] = $material['id_material']; // id_material
                    $values[] = $material['qtd_material']; // qtd_material
                    $values[] = $codProd; // id_identificador
                    $values[] = $preco;
                }
        
                if (!empty($placeholders)) {
                    // Consulta para inserir os novos materiais
                    $sql = "INSERT INTO produtos (nomeProd, qtdProd, estadoProd, id_material, qtd_material, id_identificador, preco) VALUES " . implode(', ', $placeholders);
                    $inserir = $this->banco->prepare($sql);
                    $inserir->execute($values);
                }
        
                // Confirma a transação
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
                return false;
            }
        }
        

        public function excluir_produto($codProd){    

            $delete = $this->banco->prepare("DELETE FROM produtos WHERE id_identificador=?");
            $codigoProduto= array($codProd);

            if($delete->execute($codigoProduto)){
                return true;
            }
        
            return false;
        }
    }


?>