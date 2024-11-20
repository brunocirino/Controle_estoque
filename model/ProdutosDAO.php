<?php

require_once("UserDAO.php"); ;

    class ProdutosDAO{

        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarProduto($Produto, $materiais, $id_identificador) {
            
            $this->banco->beginTransaction();
        
            try {
                $placeholders = [];
                $values = [];
        
                foreach ($materiais as $material) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?,?)"; 
                    $values[] = $Produto->get_nome(); 
                    $values[] = $Produto->get_quantidade(); 
                    $values[] = $Produto->get_estadoProd(); 
                    $values[] = $material['id_material']; 
                    $values[] = $material['qtd_material']; 
                    $values[] = $id_identificador;
                    $values[] = $Produto->get_preco();
                }
        
                $placeholdersString = implode(', ', $placeholders);
        
                $sql = "INSERT INTO produtos (nomeProd, qtdProd, estadoProd, id_material, qtd_material, id_identificador, preco) VALUES " . $placeholdersString;
        
                $inserir = $this->banco->prepare($sql);
                $inserir->execute($values);
        
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
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

        public function ConsultarPreco_unit_prod($codProd) {
            $consulta = $this->banco->prepare('
                SELECT 
                    preco
                FROM 
                    produtos 
                WHERE 
                    id_identificador = :id_identificador
                GROUP BY id_identificador
            ');
        
            $consulta->bindValue(':id_identificador', $codProd);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            return !empty($resultados) ? $resultados[0]['preco'] : null;
        }
        

        public function Consultarid_identificadorMax() {

            $consulta = $this->banco->prepare('SELECT MAX(id_identificador) AS max_id FROM produtos');
            $consulta->execute();
            
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            
            return $resultado ? $resultado['max_id'] : null;
        }
        

        public function Atualizar_Produto($codProd, $NomeProd, $qtdProd, $preco, $estadoProd, $materiais) {
           
            $this->banco->beginTransaction();
        
            try {
               
                $deleteMateriais = $this->banco->prepare("DELETE FROM produtos WHERE id_identificador = ?");
                $deleteMateriais->execute(array($codProd));
        
                
                $placeholders = [];
                $values = [];
        
                foreach ($materiais as $material) {
                    $placeholders[] = "(?, ?, ?, ?, ?, ?, ?)";
                    $values[] = $NomeProd; 
                    $values[] = $qtdProd;  
                    $values[] = $estadoProd; 
                    $values[] = $material['id_material']; 
                    $values[] = $material['qtd_material'];
                    $values[] = $codProd; 
                    $values[] = $preco;
                }
        
                if (!empty($placeholders)) {
                    $sql = "INSERT INTO produtos (nomeProd, qtdProd, estadoProd, id_material, qtd_material, id_identificador, preco) VALUES " . implode(', ', $placeholders);
                    $inserir = $this->banco->prepare($sql);
                    $inserir->execute($values);
                }
        
                $this->banco->commit();
                return true;
        
            } catch (Exception $e) {
                $this->banco->rollBack();
                return false;
            }
        }
        

        public function excluir_produto($codProd){    

            $delete = $this->banco->prepare("DELETE FROM produtos WHERE id_identificador=?");
            $codigoProduto= array($codProd);

            $delete->execute($codigoProduto);
        
            return $delete->rowCount() > 0; 
        }
    }


?>