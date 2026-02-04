<?php
header('Content-Type: application/json');

// Ajusta estas rutas según tu estructura de carpetas real
include_once __DIR__ . '/../dao/DAOZoologicos.php'; 

$provincia = $_GET['provincia'] ?? '';

if (empty($provincia)) {
    echo json_encode(['error' => 'No se especificó provincia']);
    exit;
}

$daoZoos = new DAOZoologicos();
// Usamos el nuevo método que añadiste al DAO
$datos = $daoZoos->obtenerResumenProvincia($provincia);

if (!$datos || $datos['numZoos'] == 0) {
    echo json_encode(['error' => 'No hay núcleos registrados en ' . $provincia]);
} else {
    // Devolvemos el JSON con los nombres exactos que espera tu JS
    echo json_encode([
        'provincia' => strtoupper($provincia),
        'numZoos' => $datos['numZoos'],
        'numEspecies' => $datos['numEspecies'],
        'listaMunicipios' => $datos['listaMunicipios'],
        'foto' => $datos['foto']
    ]);
}