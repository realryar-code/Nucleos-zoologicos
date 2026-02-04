<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../views/loginRegister.php');
    exit;
}

// Verificar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/perfilUsuario.php');
    exit;
}

include_once __DIR__ . '/../dao/DAOUsuarios.php';
include_once __DIR__ . '/../models/Usuarios.php';

$correoOriginal = $_SESSION['usuario'];
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$nuevaContrasena = $_POST['nueva_contrasena'] ?? '';

// Validaciones
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre es obligatorio';
}

if (empty($apellidos)) {
    $errores[] = 'Los apellidos son obligatorios';
}

if (!empty($nuevaContrasena) && strlen($nuevaContrasena) < 6) {
    $errores[] = 'La contraseña debe tener al menos 6 caracteres';
}

if (!empty($errores)) {
    $_SESSION['errores_actualizacion'] = $errores;
    header('Location: ../views/perfilUsuario.php');
    exit;
}

try {
    $dao = new DAOUsuarios();
    $datosActuales = $dao->leerUsuarioCorreo($correoOriginal);
    
    if (!$datosActuales) {
        $_SESSION['error_actualizacion'] = 'Usuario no encontrado';
        header('Location: ../views/perfilUsuario.php');
        exit;
    }
    
    $usuario = new Usuarios(
        $nombre,                           // nombre
        $apellidos,                        // apellidos
        $datosActuales['fotoPerfil'],      // fotoPerfil
        $correoOriginal,                   // correo
        $contrasenaFinal,                  // contrasena
        $datosActuales['administrador']    // administrador
    );
    
    // Si hay nueva contraseña, cifrarla; si no, mantener la actual
    if (!empty($nuevaContrasena)) {
        $usuario->setContrasena(md5($nuevaContrasena));
    } else {
        $usuario->setContrasena($datosActuales['contrasena']);
    }
    
    // Actualizar en BD
    $resultado = $dao->actualizarUsuario($usuario, $correoOriginal);
    
    if ($resultado) {
        // Actualizar datos en sesión
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['mensaje_exito'] = 'Datos actualizados correctamente';
    } else {
        $_SESSION['error_actualizacion'] = 'Error al actualizar los datos';
    }
    
    header('Location: ../views/perfilUsuario.php');
    exit;
    
} catch (Exception $e) {
    $_SESSION['error_actualizacion'] = 'Error en el sistema';
    header('Location: ../views/perfilUsuario.php');
    exit;
}
?>