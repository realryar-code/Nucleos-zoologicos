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
include_once __DIR__ . '/../dao/Conexion.php'; // Asegúrate de incluir la conexión

$correo = $_SESSION['usuario'];
// Recogemos el string (ej: "Aguila.png")
$fotoSeleccionada = $_POST['foto_seleccionada'] ?? 'Aguila.png';

// Lista blanca de fotos permitidas (Seguridad)
$fotosPermitidas = [
    'Aguila.png', 'Ballena.png', 'Buho.png', 'Flamenco.png', 
    'Gato.png', 'Leon.png', 'Lobo.png', 'Oso.png', 
    'Rana.png', 'Zorro.png'
];

if (!in_array($fotoSeleccionada, $fotosPermitidas)) {
    $_SESSION['error_actualizacion'] = 'La foto seleccionada no es válida';
    header('Location: ../views/perfilUsuario.php');
    exit;
}

try {
    $miConexion = new Conexion();
    $pdo = $miConexion->conectar();
    
    // El campo fotoPerfil debe ser VARCHAR en tu base de datos
    $sql = $pdo->prepare("UPDATE usuarios SET fotoPerfil = :foto WHERE correo = :correo");
    $sql->bindParam(':foto', $fotoSeleccionada, PDO::PARAM_STR); // Cambiado a PARAM_STR
    $sql->bindParam(':correo', $correo);
    
    $resultado = $sql->execute();
    $miConexion->desconectar();
    
    if ($resultado) {
        // ¡Importante! Actualizar la variable de sesión para que el cambio sea instantáneo
        $_SESSION['fotoPerfil'] = $fotoSeleccionada;
        $_SESSION['mensaje_exito'] = 'Foto de perfil actualizada correctamente';
    } else {
        $_SESSION['error_actualizacion'] = 'No se pudo actualizar la foto en la base de datos';
    }
    
} catch (Exception $e) {
    $_SESSION['error_actualizacion'] = 'Error en el sistema: ' . $e->getMessage();
}

header('Location: ../views/perfilUsuario.php');
exit;