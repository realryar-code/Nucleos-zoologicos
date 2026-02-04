<?php
include_once __DIR__ . '/../config/Conexion.php';
include_once __DIR__ . '/../models/Usuarios.php';

class DAOUsuarios {
    public function leerTodosUsuarios() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM usuarios ORDER BY correo");
            $sql->execute();

            $registros = $sql->fetchAll();
            $miConexion->desconectar();

            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function leerUsuarioCorreo(string $correo) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
            $sql->bindParam(':correo', $correo);
            $sql->execute();

            $resultado = $sql->fetch();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return null;
        }
    }

    public function insertarUsuario(Usuarios $usuario) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, correo, contrasena, fotoPerfil, administrador) 
                                 VALUES (:nom, :ape, :correo, :pass, :foto, :admin)");
            
            $nombre = $usuario->getNombre();
            $apellidos = $usuario->getApellidos();
            $correo = $usuario->getCorreo();
            $contrasena = md5($usuario->getContrasena());
            $foto = $usuario->getFotoPerfil();
            $admin = $usuario->getAdministrador() ? 1 : 0;

            $sql->bindParam(':nom', $nombre);
            $sql->bindParam(':ape', $apellidos);
            $sql->bindParam(':correo', $correo);
            $sql->bindParam(':pass', $contrasena);
            $sql->bindParam(':foto', $foto, PDO::PARAM_INT);
            $sql->bindParam(':admin', $admin, PDO::PARAM_INT);
            
            $resultado = $sql->execute();
            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
    }

    public function actualizarUsuario(Usuarios $usuario, string $correo_original) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("UPDATE usuarios SET nombre = :nom, apellidos = :ape,
                                 contrasena = :pass, fotoPerfil = :foto WHERE correo = :correo_orig");
            
            $nombre = $usuario->getNombre();
            $apellidos = $usuario->getApellidos();
            $contrasena = $usuario->getContrasena();
            $foto = $usuario->getFotoPerfil();

            $sql->bindParam(':nom', $nombre);
            $sql->bindParam(':ape', $apellidos);
            $sql->bindParam(':pass', $contrasena);
            $sql->bindParam(':foto', $foto, PDO::PARAM_INT);
            $sql->bindParam(':correo_orig', $correo_original);
            
            $resultado = $sql->execute();
            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
    }

    public function eliminarUsuario(string $correo) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("DELETE FROM usuarios WHERE correo = :correo");
            $sql->bindParam(':correo', $correo);
            
            $resultado = $sql->execute();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
    }
}
?>