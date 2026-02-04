<?php
include_once 'config.php';

class Conexion {
    private $host = sql101.infinityfree.com;
    private $bd = if0_41076028_db_zoologicos;
    private $user = if0_41076028;
    private $pass = wowysera4ever;

    public function conectar() {
        try {
            $opciones = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            $dsn = "mysql:host={$this->host};dbname={$this->bd};charset={$this->charset}";
            
            $this->conexion = new PDO($dsn, $this->user, $this->pass, $opciones);
            
            return $this->conexion;

        } catch (PDOException $e) {
            print "¡Error de conexión!: " . $e->getMessage();
            die();
        }
    }

    public function desconectar() {
        $this->conexion = null;
    }
}
?>