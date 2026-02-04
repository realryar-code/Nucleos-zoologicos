<?php
    require_once __DIR__ . '/../dao/DAOZoologicos.php';
    require_once __DIR__ . '/../dao/DAOAnimales.php';
    require_once __DIR__ . '/../dao/DAOResenas.php';
    require_once __DIR__ . '/../dao/DAOUsuarios.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['error_resena'])) {
        echo '<div style="background: red; color: white; padding: 10px;">' . $_SESSION['error_resena'] . '</div>';
        unset($_SESSION['error_resena']);
    }

    if (isset($_SESSION['mensaje_exito'])) {
        echo '<div style="background: green; color: white; padding: 10px;">' . $_SESSION['mensaje_exito'] . '</div>';
        unset($_SESSION['mensaje_exito']);
    }

    try {
        $daoZoo = new DAOZoologicos();
        $daoAnimal = new DAOAnimales();
        $daoResena = new DAOResenas();
        $daoUsuario = new DAOUsuarios();
    } catch (Exception $e) {
        echo "Error en la carga: " . $e->getMessage();
    }

    $idNucleoZoologico = "";
    if (!empty($_GET['id'])) {
        $idNucleoZoologico = $_GET['id'];
    }

    // Capturamos la imagen enviada desde la card. Si no existe, usamos una por defecto.
    $imagenSeleccionada = !empty($_GET['img']) ? $_GET['img'] : "zoo1.jpg";
    $rutaImagen = "../img/imagenesZoologicos/" . $imagenSeleccionada;

    $usuarioLogueado = isset($_SESSION['usuario']);
    $resenaUsuario = null;
    
    if ($usuarioLogueado && !empty($idNucleoZoologico)) {
        $resenaUsuario = $daoResena->leerResenaZoologicoUsuario($idNucleoZoologico, $_SESSION['usuario']);
    }
    
    $todasResenas = [];
    if (!empty($idNucleoZoologico)) {
        $todasResenasRaw = $daoResena->leerTodasResenas();
        foreach ($todasResenasRaw as $resena) {
            if ($resena['id_zoologico'] == $idNucleoZoologico) {
                $todasResenas[] = $resena;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castilla y Zoológicos - Portal de Conservación</title>
    <link rel="stylesheet" href="../css/buencss2.css">
    <style>
        /* Quitamos el color salmón y ajustamos la imagen de la ficha */
        .grafico {
            background-color: transparent !important;
            display: block;
            overflow: hidden;
        }
        .grafico img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>
   
    <main class="seccionNormal">
        <div class="gridConservacion">
            <div class="contenedorGrafico">
                <div class="grafico">
                    <img src="<?php echo $rutaImagen; ?>" alt="Zoo">
                </div>
            </div>

            <?php
                $zoo = $daoZoo->leerZooId($idNucleoZoologico);
                $animales = $daoAnimal->leerTodosAnimalesPorCentro($idNucleoZoologico);
                $totalAnimales = count($animales);
            ?>
            <div class="contenedorTexto gap">
                <h2 class="subtitulo"><?php print htmlspecialchars($zoo['titular']); ?></h2>
                <div class="contenedorInformacion gap">
                    <p class="textoNormal negrita">Ubicación:</p>
                    <p class="textoNormal"><?php print htmlspecialchars($zoo['municipio'] . ', ' . $zoo['provincia']); ?></p>
                </div>
                <p class="textoNormal negrita">Especies (<?php print $totalAnimales; ?>):</p>
                <div class="contenedorEspecies">
                    <?php 
                    if ($totalAnimales > 0) {
                        foreach ($animales as $animal) {
                            print '<span class="chipEspecie subtextoNormal">' . htmlspecialchars($animal['nombre']) . '</span>';
                        }
                    } else {
                        print '<p class="subtextoNormal">No hay especies registradas en este centro.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="cabeceraComentario limitadorDiv">
            <h2 class="subtitulo">Comentarios de los usuarios</h2>

            <?php if ($usuarioLogueado): ?>
                <button class="botonTexto textoNormal" id="btnHacerComentario">
                    Comentar
                </button>
            <?php endif; ?>
        </div>

        <div class="zonaComentarios reducir">
            <?php 
            if (count($todasResenas) > 0) {
                foreach ($todasResenas as $resena) {
                    $datosUsuario = $daoUsuario->leerUsuarioCorreo($resena['correo']);
            ?>
                <div class="card perfil">
                    <div class="cabeceraComentario">
                        <div class="infoUsuario">
                            <div class="avatarComentario">
                                <?php if ($datosUsuario && !empty($datosUsuario['fotoPerfil'])): ?>
                                    <img src="../icons/<?php echo htmlspecialchars($datosUsuario['fotoPerfil']); ?>" alt="Avatar" class="imagenLogin">
                                <?php endif; ?>
                            </div>
                            <span class="subtitulo negrita">
                                <?php 
                                    echo htmlspecialchars($datosUsuario['nombre'] . ' ' . $datosUsuario['apellidos']); 
                                ?>
                            </span>
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
                <p class="textoNormal">No hay comentarios aún. ¡Sé el primero en comentar!</p>
            </div>
            <?php } ?>
        </div>
    </main>

    <div class="modalOverlay" id="modalCrearResena" style="display: none;">
        <div class="modalCambiarFoto limitarModales">
            <button class="botonCerrarModal cerrarModalCrear">×</button>
            
            <h2 class="subtitulo">Crear tu comentario</h2>
            
            <form class="formulario" id="formCrearResena" method="POST" action="../config/procesarResena.php">
                <div class="campoFormulario">
                    <label class="textoNormal negrita">Título</label>
                    <input type="text" name="titulo" class="inputFormulario subtextoNormal" placeholder="Título de tu reseña..." required maxlength="255">
                </div>
                
                <div class="campoFormulario">
                    <label class="textoNormal negrita">Comentario</label>
                    <textarea name="comentario" class="inputFormulario subtextoNormal" placeholder="Escribe tu comentario..." rows="5" required maxlength="255"></textarea>
                </div>
                
                <input type="hidden" name="id_zoologico" value="<?php echo htmlspecialchars($idNucleoZoologico); ?>">
                
                <button type="submit" class="botonFormulario textoNormal negrita">
                    Crear Reseña
                </button>
            </form>
        </div>
    </div>

    <div class="modalOverlay" id="modalYaCreado" style="display: none;">
        <div class="modalCambiarFoto limitarModales">
            <button class="botonCerrarModal cerrarModalYaCreado">×</button>
            
            <h2 class="subtitulo margenArriba">Ya has creado una reseña para este zoológico</h2>
            <p class="textoNormal">Puedes editar o eliminar tu reseña existente desde la sección de comentarios.</p>
        </div>
    </div>

    <?php include __DIR__ . '/../include/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnHacerComentario = document.getElementById('btnHacerComentario');
            const modalCrear = document.getElementById('modalCrearResena');
            const modalYaCreado = document.getElementById('modalYaCreado');
            
            const yaHayResena = <?php echo $resenaUsuario ? 'true' : 'false'; ?>;
            
            if (btnHacerComentario) {
                btnHacerComentario.addEventListener('click', function() {
                    if (yaHayResena) {
                        modalYaCreado.style.display = 'flex';
                    } else {
                        modalCrear.style.display = 'flex';
                    }
                });
            }
            
            document.querySelectorAll('.cerrarModalCrear').forEach(btn => {
                btn.addEventListener('click', () => modalCrear.style.display = 'none');
            });
            
            document.querySelectorAll('.cerrarModalYaCreado').forEach(btn => {
                btn.addEventListener('click', () => modalYaCreado.style.display = 'none');
            });
            
            [modalCrear, modalYaCreado].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>