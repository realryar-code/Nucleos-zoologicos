<?php
include_once __DIR__ . '/../config/Conexion.php';
include_once __DIR__ . '/../models/Resenas.php';

class DAOResenas {
    public function leerTodasResenas() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM resenas ORDER BY correo");
            $sql->execute();

            $registros = $sql->fetchAll();
            $miConexion->desconectar();

            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function leerResenaZoologico(string $zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM resenas WHERE id_zoologico = :id_zoo");
            $sql->bindParam(':id_zoo', $zoologico);
            $sql->execute();

            $resultado = $sql->fetch();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return null;
        }
    }

    public function leerResenaUsuario(string $correo) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM resenas WHERE correo = :correo");
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

    public function leerResenaZoologicoUsuario(string $zoologico, string $correo) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM resenas WHERE correo = :correo AND id_zoologico = :id_zoo");
            $sql->bindParam(':correo', $correo);
            $sql->bindParam(':id_zoo', $zoologico);
            $sql->execute();

            $resultado = $sql->fetch();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return null;
        }
    }

    public function insertarResena(Resenas $resenas) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("INSERT INTO resenas (correo, id_zoologico, titulo, comentario) VALUES (:correo, :id_zoo, :titulo, :coment)");
            
            $correo = $resenas->getCorreo();
            $idZoo = $resenas->getIdNucleo();
            $titulo = $resenas->getTitulo();
            $coment = $resenas->getComentario();

            $sql->bindParam(':correo', $correo);
            $sql->bindParam(':id_zoo', $idZoo);
            $sql->bindParam(':titulo', $titulo);
            $sql->bindParam(':coment', $coment);

            $resultado = $sql->execute();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
    }

    public function actualizarResenaPropia(Resenas $resenas, string $correo) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("UPDATE resenas SET titulo = :titulo, comentario = :coment WHERE correo = :correo AND id_zoologico = :id_zoo");
            
            $titulo = $resenas->getTitulo();
            $coment = $resenas->getComentario();
            $idZoo = $resenas->getIdNucleo();

            $sql->bindParam(':titulo', $titulo);
            $sql->bindParam(':coment', $coment);
            $sql->bindParam(':correo', $correo);
            $sql->bindParam(':id_zoo', $idZoo);
            
            $resultado = $sql->execute();
            $miConexion->desconectar();

            return $resultado;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return false;
        }
    }

    public function eliminarResena(string $correo, string $idZoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("DELETE FROM resenas WHERE correo = :correo AND id_zoologico = :id_zoo");
            $sql->bindParam(':correo', $correo);
            $sql->bindParam(':id_zoo', $idZoologico);
            
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