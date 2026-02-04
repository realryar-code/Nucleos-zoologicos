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
include_once __DIR__ . '/../models/Resenas.php';

$correo = $_SESSION['usuario'];
$titulo = trim($_POST['titulo'] ?? '');
$comentario = trim($_POST['comentario'] ?? '');
$idZoologico = trim($_POST['id_zoologico'] ?? '');

// Validaciones
$errores = [];

if (empty($titulo)) {
    $errores[] = 'El título es obligatorio';
}

if (empty($comentario)) {
    $errores[] = 'El comentario es obligatorio';
}

if (empty($idZoologico)) {
    $errores[] = 'ID de zoológico no válido';
}

if (strlen($titulo) > 255) {
    $errores[] = 'El título no puede superar los 255 caracteres';
}

if (strlen($comentario) > 255) {
    $errores[] = 'El comentario no puede superar los 255 caracteres';
}

if (!empty($errores)) {
    $_SESSION['errores_resena'] = $errores;
    header('Location: ../views/perfilUsuario.php');
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
    
    // Actualizar reseña
    $resena = new Resenas(
        $correo,
        $idZoologico,
        $titulo,
        $comentario
    );
    
    $resultado = $dao->actualizarResenaPropia($resena, $correo);
    
    if ($resultado) {
        $_SESSION['mensaje_exito'] = 'Reseña actualizada correctamente';
    } else {
        $_SESSION['error_resena'] = 'Error al actualizar la reseña';
    }
    
    header('Location: ../views/perfilUsuario.php');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_resena'] = 'Error en el sistema: ' . $e->getMessage();
    header('Location: ../views/perfilUsuario.php');
    exit;
}
?>