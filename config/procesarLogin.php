<?php
session_start();

// Incluir DAO
include_once __DIR__ . '/../dao/DAOUsuarios.php';

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/loginRegister.php');
    exit;
}

// Obtener y limpiar datos
$correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
$contrasena = $_POST['contrasena'] ?? '';

// Validar que no estén vacíos
if (empty($correo) || empty($contrasena)) {
    $_SESSION['error_login'] = 'Por favor, completa todos los campos';
    header('Location: ../views/loginRegister.php');
    exit;
}

try {
    $dao = new DAOUsuarios();
    $usuario = $dao->leerUsuarioCorreo($correo);
    
    // Verificar si el usuario existe y la contraseña (MD5 según tu DAO)
    if (!$usuario || md5($contrasena) !== $usuario['contrasena']) {
        $_SESSION['error_login'] = 'Correo o contraseña incorrectos';
        header('Location: ../views/loginRegister.php');
        exit;
    }
    
    // --- LOGIN EXITOSO ---
    
    // 1. Regenerar ID de sesión por seguridad (evita fijación de sesión)
    session_regenerate_id(true);

    // 2. Guardar datos clave en la sesión
    $_SESSION['usuario'] = $usuario['correo'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['apellidos'] = $usuario['apellidos'];
    $_SESSION['administrador'] = (int)$usuario['administrador'];

    // 3. Manejo de la foto de perfil (Si no existe en BD, asignamos la por defecto)
    $_SESSION['fotoPerfil'] = !empty($usuario['fotoPerfil']) ? $usuario['fotoPerfil'] : 'Gato.png';
    
    // 4. Mensaje de bienvenida
    $_SESSION['mensaje_exito'] = '¡Bienvenido/a de nuevo, ' . htmlspecialchars($usuario['nombre']) . '!';
    
    // Redirigir al perfil o al index
    header('Location: ../views/perfilUsuario.php');
    exit;
    
} catch (Exception $e) {
    // Es mejor no mostrar el error real del sistema al usuario por seguridad
    $_SESSION['error_login'] = 'Error en el servidor. Por favor, inténtalo de nuevo.';
    header('Location: ../views/loginRegister.php');
    exit;
}