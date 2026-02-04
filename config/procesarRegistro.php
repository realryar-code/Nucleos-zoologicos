<?php
session_start();

// Incluir necesarios
include_once __DIR__ . '/../dao/DAOUsuarios.php';
include_once __DIR__ . '/../models/Usuarios.php';

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/loginRegister.php');
    exit;
}

// Obtener datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena'] ?? '';
$repetirContrasena = $_POST['repetir_contrasena'] ?? '';

// Validaciones
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre es obligatorio';
}

if (empty($apellidos)) {
    $errores[] = 'Los apellidos son obligatorios';
}

if (empty($correo)) {
    $errores[] = 'El correo es obligatorio';
} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El formato del correo no es válido';
}

if (empty($contrasena)) {
    $errores[] = 'La contraseña es obligatoria';
} elseif (strlen($contrasena) < 6) {
    $errores[] = 'La contraseña debe tener al menos 6 caracteres';
}

if ($contrasena !== $repetirContrasena) {
    $errores[] = 'Las contraseñas no coinciden';
}

// Si hay errores, volver al formulario
if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    $_SESSION['datos_formulario'] = [
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'correo' => $correo
    ];
    header('Location: ../views/loginRegister.php');
    exit;
}

try {
    $dao = new DAOUsuarios();
    
    // Verificar si el correo ya existe
    $usuarioExistente = $dao->leerUsuarioCorreo($correo);
    
    if ($usuarioExistente) {
        $_SESSION['error_registro'] = 'El correo electrónico ya está registrado';
        $_SESSION['datos_formulario'] = [
            'nombre' => $nombre,
            'apellidos' => $apellidos
        ];
        header('Location: ../views/loginRegister.php');
        exit;
    }

    $nuevoUsuario = new Usuarios(
        $nombre,
        $apellidos,
        'Oso.png',
        $correo,
        $contrasena,
        false
    );
    
    // Insertar en BD
    $resultado = $dao->insertarUsuario($nuevoUsuario);
    
    if ($resultado) {
        // Registro exitoso - iniciar sesión automáticamente
        $_SESSION['usuario'] = $correo;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellidos'] = $apellidos;
        $_SESSION['fotoPerfil'] = 'Oso.png';
        $_SESSION['administrador'] = 0;
        $_SESSION['mensaje_exito'] = '¡Registro completado con éxito! Bienvenido/a ' . $nombre;
        
        header('Location: ../views/perfilUsuario.php');
        exit;
    } else {
        $_SESSION['error_registro'] = 'Error al registrar el usuario. Inténtalo de nuevo';
        header('Location: ../views/loginRegister.php');
        exit;
    }
    
} catch (Exception $e) {
    $_SESSION['error_registro'] = 'Error en el sistema. Inténtalo más tarde';
    header('Location: ../views/loginRegister.php');
    exit;
}
?>