<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../dao/DAOAnimales.php';

$dao = new DAOAnimales();
$accion = $_GET['accion'] ?? '';

if ($accion === 'buscar') {
    $query = $_GET['q'] ?? '';
    // Si la búsqueda está vacía, devolvemos todos los animales agrupados
    if (empty($query)) {
        $resultados = $dao->leerTodosAnimalesCodificado();
    } else {
        $resultados = $dao->buscarAnimalesAgrupados($query);
    }
    echo json_encode($resultados);
} 

elseif ($accion === 'detalles') {
    $nombre = $_GET['nombre'] ?? '';
    $centros = $dao->obtenerCentrosPorEspecie($nombre);
    
    echo json_encode([
        'nombre' => strtoupper($nombre), // Para que el título salga en mayúsculas como en la foto
        'centros' => $centros
    ]);
    exit;
}
?>