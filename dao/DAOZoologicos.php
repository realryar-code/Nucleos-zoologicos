<?php
include_once __DIR__ . '/../config/Conexion.php';
include_once __DIR__ . '/../models/NucleoZoologico.php';

class DAOZoologicos {
    public function leerZoos() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("SELECT * FROM nucleos_zoologicos ORDER BY id_zoologico");
            $sql->execute();
            $registros = $sql->fetchAll();
            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            return [];
        }
    }

    public function leer3Zoos() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("SELECT * FROM nucleos_zoologicos ORDER BY id_zoologico LIMIT 3");
            $sql->execute();
            $registros = $sql->fetchAll();
            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            return [];
        }
    }

    public function obtenerEstadisticasProvincias() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            // CAMBIO: Ahora ordena por nombre de provincia (ASC) en lugar de por total
            $sql = $pdo->prepare("SELECT provincia, COUNT(*) as total FROM nucleos_zoologicos GROUP BY provincia ORDER BY provincia ASC");
            $sql->execute();
            $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);
            $miConexion->desconectar();

            $stats = [];
            foreach ($resultados as $row) {
                $stats[$row['provincia']] = (int)$row['total'];
            }
            return $stats;
        } catch (PDOException $ex) {
            return [];
        }
    }

    public function leerZooId(string $id_zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("SELECT * FROM nucleos_zoologicos WHERE id_zoologico = :id");
            $sql->bindParam(':id', $id_zoologico);
            $sql->execute();
            $resultado = $sql->fetch();
            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            return null;
        }
    }

    public function buscarZooPorProvincia_Municipio(string $nombre) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $nombreBusqueda = "%" . $nombre . "%";
            $sql = $pdo->prepare("SELECT * FROM nucleos_zoologicos WHERE municipio LIKE :nom OR provincia LIKE :prov");
            $sql->bindParam(':nom', $nombreBusqueda);
            $sql->bindParam(':prov', $nombreBusqueda);
            $sql->execute();
            $zoologicos = $sql->fetchAll();
            $miConexion->desconectar();
            return $zoologicos;
        } catch (PDOException $ex) {
            return [];
        }
    }

    public function insertarZoo(NucleoZoologico $zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("INSERT INTO nucleos_zoologicos (id_zoologico, fecha_alta, titular, municipio, provincia, imagen) VALUES (:id, :fecha, :titular, :mun, :prov, :img)");
            
            $id = $zoologico->getCodigo();
            $fecha = $zoologico->getFecha();
            $titular = $zoologico->getTitular();
            $mun = $zoologico->getMunicipio();
            $prov = $zoologico->getProvincia();
            $img = $zoologico->getImagen();

            $sql->bindParam(':id', $id);
            $sql->bindParam(':fecha', $fecha);
            $sql->bindParam(':titular', $titular);
            $sql->bindParam(':mun', $mun);
            $sql->bindParam(':prov', $prov);
            $sql->bindParam(':img', $img);

            $resultado = $sql->execute();
            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            return false;
        }
    }

    public function eliminarZoo(string $id_zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("DELETE FROM nucleos_zoologicos WHERE id_zoologico = :id");
            $sql->bindParam(':id', $id_zoologico);
            $resultado = $sql->execute();
            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            return false;
        }
    }

    public function importarDesdeJSON($rutaArchivo) {
        if (!file_exists($rutaArchivo)) return false;
        $jsonString = file_get_contents($rutaArchivo);
        $data = json_decode($jsonString, true);
        foreach ($data as $item) {
            $fields = $item['fields'];
            $zoo = new NucleoZoologico(
                $fields['codigo_de_nucleo_zoologico'],
                $fields['fecha'],
                $fields['nombre_titular_nucleo_zoologico'],
                $fields['muni_exp'],
                $fields['prov_exp'],
                "imagen_" . rand(1, 10) . ".jpg"
            );
            $this->insertarZooSemilla($zoo); 
        }
    }

    private function insertarZooSemilla(NucleoZoologico $n) {
        $miConexion = new Conexion();
        $pdo = $miConexion->conectar();
        $sql = $pdo->prepare("INSERT IGNORE INTO nucleos_zoologicos (id_zoologico, fecha_alta, titular, municipio, provincia, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->execute([$n->getCodigo(), $n->getFecha(), $n->getTitular(), $n->getMunicipio(), $n->getProvincia(), $n->getImagen()]);
    }

    public function obtenerResumenProvincia($provincia) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            $sql = $pdo->prepare("
                SELECT 
                    COUNT(DISTINCT n.id_zoologico) as numZoos,
                    COUNT(DISTINCT a.nombre) as numEspecies,
                    GROUP_CONCAT(DISTINCT n.municipio SEPARATOR ' | ') as listaMunicipios,
                    MAX(n.imagen) as foto
                FROM nucleos_zoologicos n
                LEFT JOIN animales a ON n.id_zoologico = a.id_zoologico
                WHERE n.provincia = :prov
            ");
            $sql->bindParam(':prov', $provincia);
            $sql->execute();
            $datos = $sql->fetch(PDO::FETCH_ASSOC);
            $miConexion->desconectar();
            return $datos;
        } catch (PDOException $ex) {
            return null;
        }
    }
}
?>