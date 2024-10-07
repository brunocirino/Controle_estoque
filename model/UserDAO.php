<?php

    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASSWORD', '');
    define('DB_NAME', 'controle_estoque');

    require_once("user.php");

    class UserDAO{

        private $banco;

        public function __construct(){
            $this->banco = new PDO('mysql:host='.HOST.'; dbname='.DB_NAME,USER,PASSWORD);
        }

        public function cadastrarUsuario($usuario){

            $inserir = $this->banco->prepare("INSERT INTO usuarios (nome, login, senha, Status, setor) VALUES (?,?,?,?,?);");

            $novo_usuario = array($usuario->get_nome(), $usuario->get_login(), $usuario->get_senha(), $usuario->get_Status(), $usuario->get_setor());

            if($inserir->execute($novo_usuario)){
                return true;
            }
            
            return false;
        }

        public function TrazerTodosUsers(){
            $consulta = $this->banco->prepare('SELECT codigo, nome, login, senha, Status, setor FROM usuarios');
                $consulta->execute();
                $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $resultados;
        }

        public function login($login, $senha){

            $query = $this->banco->prepare("SELECT COUNT(codigo) as count FROM usuarios WHERE login = :login AND senha = :senha");
            $query->bindParam(":login", $login);
            $query->bindParam(":senha", $senha);
            $query->execute();

            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result['count'] > 0) {
                return true;
            } 
            
            return false;
        }

        public function excluir_usuario($codigo){    

            $delete = $this->banco->prepare("DELETE FROM usuarios WHERE codigo=?");
            $codigoUser = array($codigo);

            if($delete->execute($codigoUser)){
                return true;
            }
        
            return false;
        }

        public function ConsultarIDUsuario($login){    

            $consulta = $this->banco->prepare('SELECT codigo FROM usuarios WHERE login = :login');
            $consulta->bindParam(':login', $login);
            $consulta->execute();

            $idUsuario = $consulta->fetchColumn();
            
            return $idUsuario;
        }

        public function ConsultarSetorUsuario($login){    

            $consulta = $this->banco->prepare('SELECT setor FROM usuarios WHERE login = :login');
            $consulta->bindParam(':login', $login);
            $consulta->execute();

            $idUsuario = $consulta->fetchColumn();
            
            return $idUsuario;
        }

        public function ConsultarUsuario($id){    

            $consulta = $this->banco->prepare('SELECT codigo, nome, login, senha, Status, setor FROM usuarios WHERE codigo = :id');
            $consulta->bindParam(':id', $id);
            $consulta->execute();

            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
            
            return $usuario;
        }

        public function Atualizar_ID_usuario($idUsuario, $email){

            $update = $this->banco->prepare("UPDATE usuários SET id=? WHERE email=?");
            $editar_endereco = array($idUsuario, $email);

            if($update->execute($editar_endereco)){
                return true;
            }
            
            return false;
        }

        public function Atualizar_usuario($Codigo, $Nome, $Login, $Senha, $Status, $Setor){

            $update = $this->banco->prepare("UPDATE usuarios SET nome=?, login=?, senha=?, status=?, setor=? WHERE codigo=?");
            $editar = array($Nome, $Login, $Senha, $Status, $Setor ,$Codigo);

            if($update->execute($editar)){
                return true;
            }
            
            return false;
        }

    }

?>