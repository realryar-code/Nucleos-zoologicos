<?php
    require_once __DIR__ . '/../dao/DAOZoologicos.php';
    require_once __DIR__ . '/../dao/DAOAnimales.php';
    require_once __DIR__ . '/../dao/DAOResenas.php';
    require_once __DIR__ . '/../dao/DAOUsuarios.php';
    session_start();
    
    // Verificar si hay sesión activa
    if (!isset($_SESSION['usuario'])) {
        header('Location: loginRegister.php');
        exit;
    }
    
    // Obtener datos de sesión
    $correo = $_SESSION['usuario'];
    $nombre = $_SESSION['nombre'] ?? '';
    $apellidos = $_SESSION['apellidos'] ?? '';
    $fotoPerfil = $_SESSION['fotoPerfil'] ?? '';
    $esAdmin = $_SESSION['administrador'] ?? 0;
    
    try {
        $daoZoo = new DAOZoologicos();
        $daoAnimal = new DAOAnimales();
        $daoResena = new DAOResenas();
        $daoUsuario = new DAOUsuarios();
    } catch (Exception $e) {
        echo "Error en la carga: " . $e->getMessage();
    }
    
    $datosUsuario = $daoUsuario->leerUsuarioCorreo($correo);
    
    $usuarioLogueado = isset($_SESSION['usuario']);
    
    // Obtener TODAS las reseñas del usuario logueado (no de un zoológico específico)
    $resenasUsuario = [];
    $todasResenasRaw = $daoResena->leerTodasResenas();
    foreach ($todasResenasRaw as $resena) {
        if ($resena['correo'] == $correo) {
            $resenasUsuario[] = $resena;
        }
    }
    
    // Mensajes
    $mensajeExito = $_SESSION['mensaje_exito'] ?? '';
    unset($_SESSION['mensaje_exito']);
    
    $errorResena = $_SESSION['error_resena'] ?? '';
    unset($_SESSION['error_resena']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Castilla y Zoológicos</title>
    <link rel="stylesheet" href="../css/buencss2.css">
    <style>
        .mensaje-exito {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .mensaje-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        .boton-logout {
            background-color: #c33;
            margin-top: 15px;
        }
        .boton-logout:hover {
            background-color: #a22;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>
   
    <main class="seccionNormal">
        <?php if ($mensajeExito): ?>
            <div class="mensaje-exito">
                <?php echo htmlspecialchars($mensajeExito); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorResena): ?>
            <div class="mensaje-error">
                <?php echo htmlspecialchars($errorResena); ?>
            </div>
        <?php endif; ?>
        
        <div class="gridPerfil">
            <div class="contenedorFormulario perfil">
                <div class="card perfil">
                    <h2 class="subtitulo margenAbajo">Datos Personales</h2>
                    
                    <div class="campoFormulario centrarContenidoCard">
                        <div class="contenedorImagenPerfil">
                            <img src="../icons/<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de perfil" class="imagenLogin">
                        </div>
                        
                        <button type="button" class="botonTexto botonesPequenos textoNormal" id="btnCambiarFoto">
                            Cambiar foto
                        </button>
                        
                        <input type="file" id="inputFoto" style="display: none;" accept="image/*">
                    </div>
                    <form class="formulario" id="formUpdateData" method="POST" action="../config/procesarActualizacion.php">
                        <div class="campoFormulario">
                            <label class="textoNormal negrita">Correo electrónico</label>
                            <input type="email" class="inputFormulario subtextoNormal" value="<?php echo htmlspecialchars($correo); ?>" disabled>
                        </div>
                        
                        <div class="campoFormulario">
                            <label class="textoNormal negrita">Nombre</label>
                            <input type="text" name="nombre" class="inputFormulario subtextoNormal" placeholder="tu nombre..." value="<?php echo htmlspecialchars($nombre); ?>" required>
                        </div>
                        <div class="campoFormulario">
                            <label class="textoNormal negrita">Apellidos</label>
                            <input type="text" name="apellidos" class="inputFormulario subtextoNormal" placeholder="tu apellido..." value="<?php echo htmlspecialchars($apellidos); ?>" required>
                        </div>
                        
                        <div class="campoFormulario">
                            <label class="textoNormal negrita">Nueva contraseña <br> (dejar en blanco para no cambiar)</label>
                            <input type="password" name="nueva_contrasena" class="inputFormulario subtextoNormal" placeholder="nueva contraseña...">
                        </div>
                        
                        <button type="submit" class="botonFormulario textoNormal negrita">Actualizar datos</button>
                    </form>
                </div>
            </div>
            <div class="contenedorRestoPerfil perfil">
                <div class="zonaTemas">
                <h2 class="subtitulo">Tus comentarios:</h2>
                <div class="zonaComentarios">
                    <?php 
                        if (count($resenasUsuario) > 0) {
                            foreach ($resenasUsuario as $resena) {
                                // Obtener datos del zoológico para mostrar nombre
                                $zoo = $daoZoo->leerZooId($resena['id_zoologico']);
                    ?>
                        <div class="card perfil">
                            <div class="cabeceraComentario">
                                <div class="infoUsuario">
                                    <div class="avatarComentario">
                                        <img src="../icons/<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Avatar" class="imagenLogin">
                                    </div>
                                    <div>
                                        <span class="subtitulo negrita">
                                            <?php echo htmlspecialchars($nombre . ' ' . $apellidos); ?>
                                        </span>
                                        <p class="subtextoNormal" style="margin: 0;">
                                            En: <?php echo htmlspecialchars($zoo['titular'] ?? 'Zoológico'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="accionesComentario">
                                    <button class="botonTexto botonesPequenos textoNormal btnEditarResena" 
                                            data-titulo="<?php echo htmlspecialchars($resena['titulo']); ?>"
                                            data-comentario="<?php echo htmlspecialchars($resena['comentario']); ?>"
                                            data-zoologico="<?php echo htmlspecialchars($resena['id_zoologico']); ?>">
                                        Editar
                                    </button>
                                    <button class="botonTexto botonesPequenos textoNormal btnEliminarResena"
                                            data-zoologico="<?php echo htmlspecialchars($resena['id_zoologico']); ?>">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                            <div class="lineaFooter"></div>
                            <div class="cuerpoComentario">
                                <h3 class="textoWeb negrita margenAbajo"><?php echo htmlspecialchars($resena['titulo']); ?></h3>
                                <p class="subtextoNormal">
                                    <?php echo htmlspecialchars($resena['comentario']); ?>
                                </p>
                            </div>
                        </div>
                    <?php 
                            }
                        } else {
                    ?>
                        <div class="card perfil">
                            <p class="textoNormal">No has hecho todavía ningún comentario.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        
                </div>
              
        <?php if ($esAdmin): ?>
        <h2 class="subtitulo margenAbajo">Panel de Administración</h2>
        <div class="card perfil contenidoCentrado">
            <h2 class="textoNormal margenIzquierdo">Ver las tablas zoológicos:</h2>
            <a href="administracion.php" class="botonTexto botonesPequenos textoNormal">Visitar</a>
        </div>
        <div class="card perfil contenidoCentrado">
            <h2 class="textoNormal margenIzquierdo">Ver las tablas especies:</h2>
            <a href="administracion.php" class="botonTexto botonesPequenos textoNormal">Visitar</a>
        </div>
        <div class="card perfil contenidoCentrado">
            <h2 class="textoNormal margenIzquierdo">Ver las tablas usuarios:</h2>
            <a href="administracion.php" class="botonTexto botonesPequenos textoNormal">Visitar</a>
        </div>
        <div class="card perfil contenidoCentrado">
            <h2 class="textoNormal margenIzquierdo">Ver las tablas comentarios:</h2>
            <a href="administracion.php" class="botonTexto botonesPequenos textoNormal">Visitar</a>
        </div>
        <?php endif; ?>
    </main>
    
    <!-- Modal: Cambiar foto -->
    <div class="modalOverlay" id="modalCambiarFoto" style="display: none;">
        <div class="modalCambiarFoto">
            <button class="botonCerrarModal cerrarModalFoto">×</button>
            
            <h2 class="subtitulo">Selecciona tu foto de perfil</h2>
            
            <form method="POST" action="../config/cambiarFoto.php" id="formCambiarFoto">
                <div class="gridFotosPerfil">
                    <div class="contenedorImagenPerfil <?php echo ('Aguila.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Aguila.png">
                        <img src="../icons/Aguila.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Ballena.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Ballena.png">
                        <img src="../icons/Ballena.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Buho.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Buho.png">
                        <img src="../icons/Buho.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Flamenco.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Flamenco.png">
                        <img src="../icons/Flamenco.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Gato.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Gato.png">
                        <img src="../icons/Gato.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Leon.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Leon.png">
                        <img src="../icons/Leon.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Lobo.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Lobo.png">
                        <img src="../icons/Lobo.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Oso.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Oso.png">
                        <img src="../icons/Oso.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Rana.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Rana.png">
                        <img src="../icons/Rana.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                    <div class="contenedorImagenPerfil <?php echo ('Zorro.png' == $fotoPerfil) ? 'seleccionada' : ''; ?>" data-foto="Zorro.png">
                        <img src="../icons/Zorro.png" alt="Foto de perfil" class="imagenLogin">
                    </div>
                </div>
                
                <input type="hidden" name="foto_seleccionada" id="fotoSeleccionada" value="<?php echo htmlspecialchars($fotoPerfil); ?>">
                <button type="submit" class="botonFormulario textoNormal negrita">
                    Confirmar selección
                </button>
            </form>
        </div>
    </div>
    
    <!-- Modal: Editar Reseña -->
    <div class="modalOverlay" id="modalEditarResena" style="display: none;">
        <div class="modalCambiarFoto limitarModales">
            <button class="botonCerrarModal cerrarModalEditar">×</button>
            
            <h2 class="subtitulo">Editar tu comentario</h2>
            
            <form class="formulario" id="formEditarResena" method="POST" action="../config/editarResenas.php">
                <div class="campoFormulario">
                    <label class="textoNormal negrita">Título</label>
                    <input type="text" name="titulo" id="editarTitulo" class="inputFormulario subtextoNormal" placeholder="Título de tu reseña..." required maxlength="255">
                </div>
                
                <div class="campoFormulario">
                    <label class="textoNormal negrita">Comentario</label>
                    <textarea name="comentario" id="editarComentario" class="inputFormulario subtextoNormal" placeholder="Escribe tu comentario..." rows="5" required maxlength="255"></textarea>
                </div>
                
                <input type="hidden" name="id_zoologico" id="editarIdZoologico" value="">
                
                <button type="submit" class="botonFormulario textoNormal negrita">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
    
    <!-- Modal: Confirmar Eliminación -->
    <div class="modalOverlay" id="modalEliminarResena" style="display: none;">
        <div class="modalCambiarFoto limitarModales">
            <button class="botonCerrarModal cerrarModalEliminar">×</button>
            
            <h2 class="subtitulo">¿Eliminar reseña?</h2>
            <p class="textoNormal">Esta acción no se puede deshacer.</p>
            
            <form method="POST" action="../config/eliminarResenas.php">
                <input type="hidden" name="id_zoologico" id="eliminarIdZoologico" value="">
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="button" class="botonFormulario textoNormal negrita cerrarModalEliminar" style="background: #ccc;">
                        Cancelar
                    </button>
                    <button type="submit" class="botonFormulario textoNormal negrita" style="background: #dc3545;">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include __DIR__ . '/../include/footer.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnCambiarFoto = document.getElementById('btnCambiarFoto');
            const modalFoto = document.getElementById('modalCambiarFoto');
            const modalEditar = document.getElementById('modalEditarResena');
            const modalEliminar = document.getElementById('modalEliminarResena');
            
            const contenedoresFoto = document.querySelectorAll('.contenedorImagenPerfil[data-foto]');
            const inputFotoSeleccionada = document.getElementById('fotoSeleccionada');
            
            // Abrir modal de cambiar foto
            if (btnCambiarFoto) {
                btnCambiarFoto.addEventListener('click', function() {
                    modalFoto.style.display = 'flex';
                });
            }
            
            // Cerrar modales
            document.querySelectorAll('.cerrarModalFoto').forEach(btn => {
                btn.addEventListener('click', () => modalFoto.style.display = 'none');
            });
            
            document.querySelectorAll('.cerrarModalEditar').forEach(btn => {
                btn.addEventListener('click', () => modalEditar.style.display = 'none');
            });
            
            document.querySelectorAll('.cerrarModalEliminar').forEach(btn => {
                btn.addEventListener('click', () => modalEliminar.style.display = 'none');
            });
            
            // Cerrar al hacer clic fuera
            [modalFoto, modalEditar, modalEliminar].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
            
            // Seleccionar foto
            contenedoresFoto.forEach(contenedor => {
                contenedor.addEventListener('click', function() {
                    contenedoresFoto.forEach(c => c.classList.remove('seleccionada'));
                    this.classList.add('seleccionada');
                    inputFotoSeleccionada.value = this.dataset.foto;
                });
            });
            
            // Botones de editar
            document.querySelectorAll('.btnEditarResena').forEach(btn => {
                btn.addEventListener('click', function() {
                    const titulo = this.dataset.titulo;
                    const comentario = this.dataset.comentario;
                    const zoologico = this.dataset.zoologico;
                    
                    document.getElementById('editarTitulo').value = titulo;
                    document.getElementById('editarComentario').value = comentario;
                    document.getElementById('editarIdZoologico').value = zoologico;
                    
                    modalEditar.style.display = 'flex';
                });
            });
            
            // Botones de eliminar
            document.querySelectorAll('.btnEliminarResena').forEach(btn => {
                btn.addEventListener('click', function() {
                    const zoologico = this.dataset.zoologico;
                    document.getElementById('eliminarIdZoologico').value = zoologico;
                    modalEliminar.style.display = 'flex';
                });
            });
        });
    </script>
</body>
</html>
