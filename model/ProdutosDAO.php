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
            // Caminho do arquivo de log
            $logFile = 'C:\Users\bruno\OneDrive\Área de Trabalho\Log_Erro_TCC\Log_Erro_TCC.txt';
            
            // Inicia uma transação
            $this->banco->beginTransaction();
        
            try {
                // Prepara a consulta de inserção
                $placeholders = [];
                $values = [];
        
                // Adiciona os dados do produto para cada material
                foreach ($materiais as $material) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?)"; // Para cada material, cria placeholders
                    $values[] = $Produto->get_nome(); // nomeProd
                    $values[] = $Produto->get_quantidade(); // qtdProd
                    $values[] = $Produto->get_estadoProd(); // estadoProd
                    $values[] = $material['id_material']; // id_material
                    $values[] = $material['qtd_material']; // qtd_material
                    $values[] = $id_identificador;
                }
        
                // Junta os placeholders para a inserção
                $placeholdersString = implode(', ', $placeholders);
        
                // A consulta de inserção para produtos
                $sql = "INSERT INTO produtos (nomeProd, qtdProd, estadoProd, id_material, qtd_material, id_identificador) VALUES " . $placeholdersString;
        
                // Prepara e executa a consulta
                $inserir = $this->banco->prepare($sql);
                $inserir->execute($values);
        
                // Confirma a transação
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                // Reverte a transação em caso de erro
                $this->banco->rollBack();
        
                // Grava a mensagem de erro no log
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Erro: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
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
        

        public function Atualizar_Produto($codProd, $NomeProd, $qtdProd, $estadoProd){

            $update = $this->banco->prepare("UPDATE produtos SET nomeProd=?, qtdProd=?, estadoProd=? WHERE codProd=?");
            $editar = array($NomeProd, $qtdProd, $estadoProd, $codProd);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function excluir_produto($codProd){    

            $delete = $this->banco->prepare("DELETE FROM produtos WHERE codProd=?");
            $codigoProduto= array($codProd);

            if($delete->execute($codigoProduto)){
                return true;
            }
        
            return false;
        }
    }


?>