<?php
include_once __DIR__ . '/../config/Conexion.php';
include_once __DIR__ . '/../models/Animales.php';

class DAOAnimales {

    public function leerTodosAnimales() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM animales ORDER BY id_animal");
            $sql->execute();

            $registros = $sql->fetchAll();

            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function leer3Animales() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT nombre, COUNT(DISTINCT id_zoologico) as num_centros FROM animales GROUP BY nombre ORDER BY id_animal LIMIT 3");
            $sql->execute();

            $registros = $sql->fetchAll();

            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function leerTodosAnimalesCodificado() {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT nombre, COUNT(DISTINCT id_zoologico) as num_centros FROM animales GROUP BY nombre ORDER BY id_animal");
            $sql->execute();

            $registros = $sql->fetchAll();

            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function leerTodosAnimalesPorCentro($id_zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT nombre FROM animales WHERE id_zoologico = :id GROUP BY nombre ORDER BY nombre");
            $sql->bindParam(':id', $id_zoologico, PDO::PARAM_STR);
            $sql->execute();
            
            $registros = $sql->fetchAll();
            $miConexion->desconectar();
            
            return $registros;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }
    
    public function contarAnimalesPorCentro($id_zoologico) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
            
            $sql = $pdo->prepare("SELECT COUNT(*) as total FROM animales WHERE id_zoologico = :id");
            $sql->bindParam(':id', $id_zoologico, PDO::PARAM_INT);
            $sql->execute();
            
            $resultado = $sql->fetch();
            $miConexion->desconectar();
            
            return $resultado['total'];
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return 0;
        }
    }

    public function leerAnimalId(int $id_animal) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM animales WHERE id_animal = :id");
            $sql->bindParam(':id', $id_animal, PDO::PARAM_INT);
            $sql->execute();

            $animal = $sql->fetch();

            $miConexion->desconectar();
            return $animal;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return null;
        }
    }

    public function leerAnimalPorNombre(string $nombre) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("SELECT * FROM animales WHERE nombre LIKE :nombre");
            $nombreBusqueda = "%$nombre%";
            $sql->bindParam(':nombre', $nombreBusqueda, PDO::PARAM_STR);
            $sql->execute();

            $animales = $sql->fetchAll();

            $miConexion->desconectar();
            return $animales;
        } catch (PDOException $ex) {
            print "Error: " . $ex->getMessage();
            return [];
        }
    }

    public function insertarAnimal(Animales $animal) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("INSERT INTO animales (id_animal, id_zoologico, nombre) VALUES (:id, :id_zoo, :nombre)");
            
            $id = $animal->getIdEspecie();
            $id_zoo = $animal->getIdNucleo();
            $nombre = $animal->getNombre();

            $sql->bindParam(':id', $id);
            $sql->bindParam(':id_zoo', $id_zoo);
            $sql->bindParam(':nombre', $nombre);

            $resultado = $sql->execute();

            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            print "Error al insertar: " . $ex->getMessage();
            return false;
        }
    }

    public function actualizarAnimal(Animales $animal, string $id_animal) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("UPDATE animales SET id_zoologico = :id_zoo, nombre = :nombre WHERE id_animal = :id_orig");
            
            $id_zoo = $animal->getIdNucleo();
            $nombre = $animal->getNombre();

            $sql->bindParam(':id_zoo', $id_zoo);
            $sql->bindParam(':nombre', $nombre);
            $sql->bindParam(':id_orig', $id_animal);

            $resultado = $sql->execute();

            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            print "Error al actualizar: " . $ex->getMessage();
            return false;
        }
    }

    public function eliminarAnimal(string $id_animal) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            $sql = $pdo->prepare("DELETE FROM animales WHERE id_animal = :id");
            $sql->bindParam(':id', $id_animal);
            
            $resultado = $sql->execute();

            $miConexion->desconectar();
            return $resultado;
        } catch (PDOException $ex) {
            print "Error al eliminar: " . $ex->getMessage();
            return false;
        }
    }

    public function importarDesdeJSON($rutaArchivo) {
        if (!file_exists($rutaArchivo)) return false;

        $jsonString = file_get_contents($rutaArchivo);
        $data = json_decode($jsonString, true);

        $miConexion = new Conexion();
        $pdo = $miConexion->conectar();

        foreach ($data as $item) {
            $fields = $item['fields'];
            $nombre = $fields['especie_grupo_de_especies'];
            $idZoo = $fields['codigo_de_nucleo_zoologico'];

            $check = $pdo->prepare("SELECT COUNT(*) FROM animales WHERE id_zoologico = ? AND nombre = ?");
            $check->execute([$idZoo, $nombre]);
            
            if ($check->fetchColumn() == 0) {
                $ins = $pdo->prepare("INSERT INTO animales (id_zoologico, nombre) VALUES (?, ?)");
                $ins->execute([$idZoo, $nombre]);
            }
        }
        $miConexion->desconectar();
    }

    // Añade esto a tu DAOAnimales.php
    public function buscarAnimalesAgrupados(string $nombre) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();

            // Buscamos por nombre, agrupamos para contar centros y ordenamos
            $sql = $pdo->prepare("SELECT nombre, COUNT(DISTINCT id_zoologico) as num_centros 
                                  FROM animales 
                                  WHERE nombre LIKE :nombre 
                                  GROUP BY nombre 
                                  ORDER BY nombre");
            
            $nombreBusqueda = "%$nombre%";
            $sql->bindParam(':nombre', $nombreBusqueda, PDO::PARAM_STR);
            $sql->execute();

            $registros = $sql->fetchAll(PDO::FETCH_ASSOC);

            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            return [];
        }
    }

    // También necesitaremos este para los detalles del modal
    public function obtenerCentrosPorEspecie(string $nombre) {
        try {
            $miConexion = new Conexion();
            $pdo = $miConexion->conectar();
    
            $sql = $pdo->prepare("SELECT z.id_zoologico, z.titular, z.municipio 
                                  FROM animales a
                                  INNER JOIN nucleos_zoologicos z ON a.id_zoologico = z.id_zoologico
                                  WHERE UPPER(a.nombre) = UPPER(:nombre)
                                  GROUP BY z.id_zoologico");
            
            $sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $sql->execute();
    
            $registros = $sql->fetchAll(PDO::FETCH_ASSOC);
            $miConexion->desconectar();
            return $registros;
        } catch (PDOException $ex) {
            return [];
        }
    }
}
?>