<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/loginRegister.php');
    exit;
}

// Verificar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/index.php');
    exit;
}

include_once __DIR__ . '/../dao/DAOResenas.php';

$correo = $_SESSION['usuario'];
$idZoologico = trim($_POST['id_zoologico'] ?? '');

if (empty($idZoologico)) {
    $_SESSION['error_resena'] = 'ID de zoológico no válido';
    header('Location: ../views/index.php');
    exit;
}

try {
    $dao = new DAOResenas();
    
    // Verificar que la reseña existe y pertenece al usuario
    $resenaExistente = $dao->leerResenaZoologicoUsuario($idZoologico, $correo);
    
    if (!$resenaExistente) {
        $_SESSION['error_resena'] = 'No se encontró tu reseña';
        header('Location: ../views/perfilUsuario.php');
        exit;
    }
    
    // Eliminar reseña
    $resultado = $dao->eliminarResena($correo, $idZoologico);
    
    if ($resultado) {
        $_SESSION['mensaje_exito'] = 'Reseña eliminada correctamente';
    } else {
        $_SESSION['error_resena'] = 'Error al eliminar la reseña';
    }
    
    header('Location: ../views/perfilUsuario.php');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_resena'] = 'Error en el sistema: ' . $e->getMessage();
    header('Location: ../views/perfilUsuario.php');
    exit;
}
?>