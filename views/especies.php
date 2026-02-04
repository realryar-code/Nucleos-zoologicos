<?php
require_once __DIR__ . '/../dao/DAOAnimales.php';
$daoAnimal = new DAOAnimales();

/**
 * Función para convertir el nombre de especie a nombre de archivo de imagen
 * Ejemplos: "Perros" -> "perros.jpg", "Aves Psitaciformes" -> "avesPsitaciformes.jpg"
 */
function obtenerImagenEspecie($nombreEspecie) {
    // Convertir a minúsculas
    $nombre = mb_strtolower(trim($nombreEspecie));
    
    // Mapeo manual de casos especiales
    $mapeo = [
        'aves cautivas y ornamentales' => 'avesCautivasyOrnamentales.jpg',
        'aves psitaciformes' => 'avesPsitaciformes.jpg',
        'aves rapaces' => 'avesRapaces.jpg',
        'crustáceos ornamentales' => 'crustaceosOrnamentales.jpg',
        'lagartos y serpientes' => 'lagartosySerpientes.jpg',
        'moluscos ornamentales' => 'moluscosOrnamentales.jpg',
        'otros carnívoros' => 'otrosCarnivoros.jpg',
        'otros herbívoros' => 'otrosHerbivoros.jpg',
        'otros omnívoros' => 'otrosOmnivoros.jpg',
        'otros peces' => 'otrosPeces.jpg',
        'otras aves' => 'otrasAves.jpg',
        'peces ornamentales' => 'pecesOrnamentales.jpg',
        'zorro rojo' => 'zorroRojo.jpg',
        'anélidos (annelida)' => 'anelidos.jpg',
        'hurones (mustela putorious furo)' => 'hurones.jpg',
        'tortugas (chelonia)' => 'tortugas.jpg',
        'lagartos y serpientes (squamata)' => 'lagartosySerpientes.jpg',
        'otros carnívoros (carnivora)' => 'otrosCarnivoros.jpg',

    ];
    
    // Si existe en el mapeo, usar ese nombre
    if (isset($mapeo[$nombre])) {
        return $mapeo[$nombre];
    }
    
    // Si no, convertir directamente (eliminar espacios y acentos)
    $nombreArchivo = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['', 'a', 'e', 'i', 'o', 'u', 'n'], $nombre);
    return $nombreArchivo . '.jpg';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castilla y Zoológicos - Portal de Conservación</title>
    <link rel="stylesheet" href="../css/buencss2.css">
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>

    <div class="modalOverlay" id="modalEspecie" style="display: none;">
        <div class="modalCambiarFoto limitarModales">
            <button class="botonCerrarModal cerrarModalEditar">×</button>
            
            <div id="detalleEspecie" class="margenArriba"></div>
        </div>
    </div>

    <main class="seccionNormal">
        <div class="divTitulosPaginas">
            <h1 class="titulo">Especies</h1>
            <p class="textoNormal">Descubre las especies presentes en los núcleos zoológicos de Castilla y León.</p>
        </div>
    </main>

    <main class="seccionNormal">
        <div class="divTitulosPaginas">
            <div class="contenedorBusqueda">
                <input type="text" class="barraBusqueda subtextoNormal" placeholder="Buscar por nombre común...">
                <button class="botonBuscar" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                </button>
            </div>

            <div class="contenedorFiltros">
                <select class="subtextoNormal selectZoologicos">
                    <option value="Nombre(A-Z)">Nombre(A-Z)</option>
                    <option value="Nombre(Z-A)">Nombre(Z-A)</option>
                    <option value="Menos apariciones">Menos apariciones</option>
                    <option value="Más apariciones">Más apariciones</option>
                </select>
            </div>
        </div>
    </main>

    <main class="seccionNormal">
        <div class="contenedorCards grid3x3" id="contenedorCards">
            <?php
            $especies = $daoAnimal->leerTodosAnimalesCodificado();
            foreach ($especies as $especie) {
                $nombre = htmlspecialchars($especie['nombre']);
                $numCentros = $especie['num_centros'];
                $imagenArchivo = obtenerImagenEspecie($especie['nombre']);
            ?>
            <div class="card" data-nombre="<?php echo $nombre; ?>" data-num-centros="<?php echo $numCentros; ?>">
                <div class="contenedorImagen">
                    <img src="../img/imagenesEspecies/<?php echo htmlspecialchars($imagenArchivo); ?>" 
                         alt="<?php echo $nombre; ?>"
                         class="imagenCard"
                         onerror="this.src='../img/imagenesEspecies/otrosOmnivoros.jpg'">
                </div>
                <div class="contenidoCard">
                    <h3 class="textoNormal"><?php echo mb_strtoupper($nombre); ?></h3>
                    <div class="ubicacionCard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#314D1C"><path d="M80-160v-400l240-240 240 240v400H80Zm80-80h120v-120h80v120h120v-287L320-687 160-527v287Zm120-200v-80h80v80h-80Zm360 280v-433L433-800h113l174 174v466h-80Zm160 0v-499L659-800h113l108 108v532h-80Zm-640-80h320-320Z"/></svg>
                        <p class="subtextoNormal">En <?php echo $numCentros; ?> centro<?php echo $numCentros != 1 ? 's' : ''; ?></p>
                    </div>
                    <button class="botonCard">Ver detalles</button>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="paginacion"></div>
    </main>

    <?php include __DIR__ . '/../include/footer.php'; ?>
    
    <script src="../js/especies.js"></script>
</body>
</html>
