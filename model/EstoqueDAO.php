<?php
require_once("UserDAO.php"); 

    class EstoqueDAO{
        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function Atualizar_Produto($id_identificador, $Quantidade) {
            $logFile = 'C:\Users\bruno\OneDrive\Área de Trabalho\Log_Erro_TCC\Log_Erro_TCC.txt';
        
            // Log da tentativa de atualização
            error_log("Atualizando Produto: ID Identificador = $id_identificador, Nova Quantidade = $Quantidade\n", 3, $logFile);
        
            $update = $this->banco->prepare("UPDATE produtos SET qtdProd=? WHERE id_identificador=?");
            $editar = array($Quantidade, $id_identificador); // Corrigido a ordem dos parâmetros
        
            if ($update->execute($editar)) {
                // Log de sucesso
                error_log("Atualização bem-sucedida para ID Identificador: $id_identificador, Nova Quantidade: $Quantidade\n", 3, $logFile);
                return true;
            }
            
            // Log de falha
            error_log("Falha na atualização para ID Identificador: $id_identificador\n", 3, $logFile);
            return false;
        }
        

        public function AtualizarQuantidadeMaterial($id_material, $Quantidade){

            $update = $this->banco->prepare("UPDATE materiais SET estoqueAtual=? WHERE codMat=? ");
            $editar = array($Quantidade, $id_material);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

        public function ConsultarProduto($id_identificador) {

            $logFile = 'C:\Users\bruno\OneDrive\Área de Trabalho\Log_Erro_TCC\Log_Erro_TCC.txt';

            error_log("ConsultarProduto: $id_identificador\n", 3, $logFile);
            // Corrigir a consulta SQL
            $consulta = $this->banco->prepare('
                SELECT 
                    id_material,
                    qtdProd,
                    qtd_material
                FROM
                    produtos
                WHERE
                    id_identificador = :id_identificador
            ');
        
            // Vincular o valor do identificador ao placeholder correto
            $consulta->bindValue(':id_identificador', $id_identificador, PDO::PARAM_INT);
            
            // Executar a consulta
            $consulta->execute();
            
            // Obter os resultados
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

            error_log("ConsultarProduto: $id_identificador - " . json_encode($resultados) . "\n", 3, $logFile);
            
            // Retornar os resultados
            return $resultados;
        }

        public function ConsultarQTDAtualMat($id_material) {
            // Cria ou abre o arquivo de log
            $logFile = 'C:\Users\bruno\OneDrive\Área de Trabalho\Log_Erro_TCC\Log_Erro_TCC.txt'; // Caminho do arquivo de log
        
            // Registro do ID do material consultado
            error_log("Consultando material com ID: $id_material\n", 3, $logFile);
        
            // Corrigir a consulta SQL
            $consulta = $this->banco->prepare('
                SELECT 
                    estoqueAtual,
                    estoqueMin
                FROM
                    materiais
                WHERE
                    codMAt = :id_material
            ');
        
            // Vincular o valor do identificador ao placeholder correto
            $consulta->bindValue(':id_material', $id_material, PDO::PARAM_INT);
            
            // Executar a consulta
            $consulta->execute();
            
            // Obter um único resultado
            $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            
            // Registrar o resultado obtido
            error_log("Resultado da consulta para ID: $id_material - " . json_encode($resultado) . "\n", 3, $logFile);
            
            // Retornar o resultado
            return $resultado;
        }
        
        

        
    
    }
?>