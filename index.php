<?php
    require_once __DIR__ . '/dao/DAOZoologicos.php';
    require_once __DIR__ . '/dao/DAOAnimales.php';

    try {
        $rutaJSON = __DIR__ . '/db/nucleos-zoologicos-de-castilla-y-leon.json';
        $daoZoo = new DAOZoologicos();
        $daoZoo->importarDesdeJSON($rutaJSON);
        
        $daoAnimal = new DAOAnimales();
        $daoAnimal->importarDesdeJSON($rutaJSON);

        // --- DATOS PARA EL GRÁFICO ---
        $statsProvincias = $daoZoo->obtenerEstadisticasProvincias();
        $labelsGrafico = array_keys($statsProvincias);
        $valoresGrafico = array_values($statsProvincias);

    } catch (Exception $e) {
        // Error silencioso para producción o log
    }

    /**
     * Función para convertir el nombre de especie a nombre de archivo de imagen
     * (Replicada de especies.php para index)
     */
    function obtenerImagenEspecie($nombreEspecie) {
        $nombre = mb_strtolower(trim($nombreEspecie));
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
        
        if (isset($mapeo[$nombre])) {
            return $mapeo[$nombre];
        }
        
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
    <link rel="stylesheet" href="./css/buencss2.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .contenedorImagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* ESTILOS PARA EL MODAL CENTRADO */
    .modal {
        display: none; /* Se activa con JS */
        position: fixed; /* Fijo en la pantalla */
        z-index: 10000; /* Por encima de todo */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7); /* Fondo oscuro */
        
        /* Centrado absoluto */
        justify-content: center;
        align-items: center;
    }

    .contenidoModal {
        background-color: #fff;
        padding: 25px;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh; /* Evita que sea más alto que la pantalla */
        overflow-y: auto; /* Scroll interno si hay muchos centros */
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .cerrarModalEditar {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 30px;
        cursor: pointer;
        color: #333;
    }
</style>
</head>
<body>
    <?php include __DIR__ . '/include/header.php'; ?>

    <section class="seccionInicial" id="inicio">
        <div class="divSeccionInicial">
            <h1 class="titulo blanco">Descubre los Núcleos <br> Zoológicos de <span class="tituloInverso">Castilla y León</span></h1>
            <div class="divSubtitulo"><p class="textoNormal blanco">Explora los centros dedicados a la preservación de especies, conoce la fauna ibérica y participa en la comunidad de amantes de la naturaleza.</p></div>
            <div class="contenedorBotones">
                <a href="views/zoologicos.php" class="botonPrincipal">Explorar zoológicos ⬩➤</a>
                <a href="views/especies.php" class="botonSecundario">Ver especies &nbsp;<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#314D1C"><path d="M607.5-372.5Q660-425 660-500t-52.5-127.5Q555-680 480-680t-127.5 52.5Q300-575 300-500t52.5 127.5Q405-320 480-320t127.5-52.5Zm-204-51Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM214-281.5Q94-363 40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200q-146 0-266-81.5ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg></a>
            </div>
        </div>
        <div class="iconoScroll"></div>
    </section>
    <section class="seccionNormal">
        <div class="gridConservacion">
            <div class="contenedorTexto">
                <h2 class="subtitulo">Conservación y educación ambiental</h2>
                <p class="textoNormal">Los núcleos zoológicos de Castilla y León desempeñan un papel fundamental en la conservación de especies autóctonas y la educación ambiental. Nuestro portal te permite conocer todos los centros registrados, las especies que albergan y los titulares responsables de su gestión.</p>
                <p class="textoNormal">Desde el majestuoso lobo ibérico hasta las diversas aves rapaces, descubre la riqueza natural de nuestra tierra.</p>
                <div class="contenedorInformacion">
                    <div class="bloqueInfo">
                        <div class="circuloIcono">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M180-475q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Zm109-189q-29-29-29-71t29-71q29-29 71-29t71 29q29 29 29 71t-29 71q-29 29-71 29t-71-29Zm240 0q-29-29-29-71t29-71q29-29 71-29t71 29q29 29 29 71t-29 71q-29 29-71 29t-71-29Zm251 189q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM266-75q-45 0-75.5-34.5T160-191q0-52 35.5-91t70.5-77q29-31 50-67.5t50-68.5q22-26 51-43t63-17q34 0 63 16t51 42q28 32 49.5 69t50.5 69q35 38 70.5 77t35.5 91q0 47-30.5 81.5T694-75q-54 0-107-9t-107-9q-54 0-107 9t-107 9Z"/></svg>
                        </div>
                        <div class="textoInfo">
                            <span class="textoNormal negrita">+1000</span>
                            <span class="subtextoNormal">Núcleos zoológicos</span>
                        </div>
                    </div>
                    
                    <div class="bloqueInfo">
                        <div class="circuloIcono">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-400h160v-80H400v80Zm0-120h320v-80H400v80Zm0-120h320v-80H400v80Zm-80 400q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z"/></svg>
                        </div>
                        <div class="textoInfo">
                            <span class="textoNormal negrita">+50</span>
                            <span class="subtextoNormal">Especies catalogadas</span>
                        </div>
                    </div>
                    
                    <div class="bloqueInfo">
                        <div class="circuloIcono">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M194-80v-395h80v315h280v-193l105-105q29-29 45-65t16-77q0-40-16.5-76T659-741l-25-26-127 127H347l-43 43-57-56 67-67h160l160-160 82 82q40 40 62 90.5T800-600q0 57-22 107.5T716-402l-82 82v240H194Zm197-187L183-475q-11-11-17-26t-6-31q0-16 6-30.5t17-25.5l84-85 124 123q28 28 43.5 64.5T450-409q0 40-15 76.5T391-267Z"/></svg>
                        </div>
                        <div class="textoInfo">
                            <span class="textoNormal negrita">100%</span>
                            <span class="subtextoNormal">Conservación de especies</span>
                        </div>
                    </div>
                </div>
                <a href="views/zoologicos.php" class="botonTexto">Conocer más ⬩➤</a>
            </div>
            <div class="contenedorGrafico">
                <canvas id="graficoProvincias"></canvas>
            </div>
        </div>
    </section>

    <section class="seccionNormal">
        <div class="divPresentacionCards">
            <div class="titulacion">
            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#314D1C"><path d="M529.5-510.5Q550-531 550-560t-20.5-49.5Q509-630 480-630t-49.5 20.5Q410-589 410-560t20.5 49.5Q451-490 480-490t49.5-20.5ZM480-159q133-121 196.5-219.5T740-552q0-118-75.5-193T480-820q-109 0-184.5 75T220-552q0 75 65 173.5T480-159Zm0 79Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>
                <h2 class="subtitulo">Núcleos Zoológicos</h2>
            </div>
            <p class="textoNormal">Explora los centros zoológicos registrados en toda la comunidad.</p>
        </div>
        <div class="contenedorCards">
        <?php
            $zoologicos = $daoZoo->leer3Zoos();
            $i = 1; // Para rotar imágenes zoo1 a zoo10
            
            foreach ($zoologicos as $zoo) {
                if ($i > 3) break;
                $numImg = ($i % 10 == 0) ? 10 : ($i % 10);
                $nombreImagenZoo = "zoo" . $numImg . ".jpg";
                $i++;
            ?>
            <div class="card">
                <div class="contenedorImagen">
                    <img src="./img/imagenesZoologicos/<?php echo $nombreImagenZoo; ?>" alt="Zoo">
                </div>
                <div class="contenidoCard">
                    <h3 class="textoNormal"><?php echo htmlspecialchars($zoo['titular']); ?></h3>
                    <div class="ubicacionCard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#314D1C"><path d="M307-113.5Q240-147 240-200q0-24 14.5-44.5T295-280l63 59q-9 4-19.5 9T322-200q13 16 60 28t98 12q51 0 98.5-12t60.5-28q-7-8-18-13t-21-9l62-60q28 16 43 36.5t15 45.5q0 53-67 86.5T480-80q-106 0-173-33.5ZM481-300q99-73 149-146.5T680-594q0-102-65-154t-135-52q-70 0-135 52t-65 154q0 67 49 139.5T481-300Zm-1 100Q339-304 269.5-402T200-594q0-71 25.5-124.5T291-808q40-36 90-54t99-18q49 0 99 18t90 54q40 36 65.5 89.5T760-594q0 94-69.5 192T480-200Zm0-320q33 0 56.5-23.5T560-600q0-33-23.5-56.5T480-680q-33 0-56.5 23.5T400-600q0 33 23.5 56.5T480-520Zm0-80Z"/></svg>
                        <p class="subtextoNormal"><?php echo htmlspecialchars($zoo['municipio'] . ', ' . $zoo['provincia']); ?></p>
                    </div>
                    <a class="botonCard" href="views/fichaZoos.php?id=<?php echo $zoo['id_zoologico']; ?>">Más información</a>
                </div>
            </div>
            <?php } ?>
        </div>

        <a href="views/zoologicos.php" class="botonTexto">Ver más zoológicos&nbsp;<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M607.5-372.5Q660-425 660-500t-52.5-127.5Q555-680 480-680t-127.5 52.5Q300-575 300-500t52.5 127.5Q405-320 480-320t127.5-52.5Zm-204-51Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM214-281.5Q94-363 40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200q-146 0-266-81.5ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg></a>
    </section>

    <section class="seccionNormal">
        <div class="divPresentacionCards">
            <div class="titulacion">
            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#314D1C"><path d="m324-83-55-23 60-145q-110-24-179.5-111.5T80-562.24V-727q0-64 45.24-108.5T234-880q17.39 0 33.19 4Q283-872 299-865l234 97-146 54v76l453 288 40 190h-80l-38-84H552v164h-60v-164H391L324-83Zm74-221h402l-101-64H397.6q-68.6 0-119.1-46T228-528h60q0 43 32.31 71.5Q352.63-428 398-428h207L327-605v-122q0-38.36-27.45-65.68-27.45-27.32-66-27.32t-66.05 27Q140-766 140-727v165q0 107.5 75.25 182.75T398-304ZM212.68-705.68q-8.68-8.67-8.68-21.5 0-12.82 8.68-20.82 8.67-8 21.5-8 12.82 0 20.82 8t8 20.82q0 12.83-8 21.5-8 8.68-20.82 8.68-12.83 0-21.5-8.68ZM398-368Z"/></svg>
                <h2 class="subtitulo">Especies Destacadas</h2>
            </div>
            <p class="textoNormal">Conoce las especies más representativas de nuestros centros.</p>
        </div>
        <div class="contenedorCards">
            <?php
            $especies = $daoAnimal->leer3Animales();
            $contadorEspecies = 0;
            
            foreach ($especies as $especie) {
                if ($contadorEspecies >= 3) break;
                $contadorEspecies++;
                $imagenEspecie = obtenerImagenEspecie($especie['nombre']);
            ?>
            <div class="card">
                <div class="contenedorImagen">
                    <img src="./img/imagenesEspecies/<?php echo htmlspecialchars($imagenEspecie); ?>" 
                         alt="<?php echo htmlspecialchars($especie['nombre']); ?>"
                         onerror="this.src='./img/imagenesEspecies/otrosOmnivoros.jpg'">
                </div>
                <div class="contenidoCard">
                    <h3 class="textoNormal"><?php echo htmlspecialchars($especie['nombre']); ?></h3>
                    <div class="ubicacionCard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#314D1C"><path d="M80-160v-400l240-240 240 240v400H80Zm80-80h120v-120h80v120h120v-287L320-687 160-527v287Zm120-200v-80h80v80h-80Zm360 280v-433L433-800h113l174 174v466h-80Zm160 0v-499L659-800h113l108 108v532h-80Zm-640-80h320-320Z"/></svg>
                        <p class="subtextoNormal">En <?php echo $especie['num_centros']; ?> centro<?php echo $especie['num_centros'] > 1 ? 's' : ''; ?></p>
                    </div>
                    <button class="botonCard">Ver detalles</button>
                </div>
            </div>
            <?php } ?>
        </div>

        <a href="views/especies.php" class="botonTexto">Ver más especies&nbsp;<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M180-475q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Zm109-189q-29-29-29-71t29-71q29-29 71-29t71 29q29 29 29 71t-29 71q-29 29-71 29t-71-29Zm240 0q-29-29-29-71t29-71q29-29 71-29t71 29q29 29 29 71t-29 71q-29 29-71 29t-71-29Zm251 189q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29ZM266-75q-45 0-75.5-34.5T160-191q0-52 35.5-91t70.5-77q29-31 50-67.5t50-68.5q22-26 51-43t63-17q34 0 63 16t51 42q28 32 49.5 69t50.5 69q35 38 70.5 77t35.5 91q0 47-30.5 81.5T694-75q-54 0-107-9t-107-9q-54 0-107 9t-107 9Z"/></svg></a>
    </section>

    <section class="seccionNormal" id="provincias">
        <div class="divPresentacionCards">
            <div class="titulacion">
            <svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#314D1C"><path d="m612-120-263-93-179 71q-17 9-33.5-1T120-173v-558q0-13 7.5-23t19.5-15l202-71 263 92 178-71q17-8 33.5 1.5T840-788v565q0 11-7.5 19T814-192l-202 72Zm-34-75v-505l-196-66v505l196 66Zm60 0 142-47v-512l-142 54v505Zm-458-12 142-54v-505l-142 47v512Zm458-493v505-505Zm-316-66v505-505Z"/></svg>
                <h2 class="subtitulo">Explora por Provincias</h2>
            </div>
            <p class="textoNormal">Selecciona una provincia para descubrir sus núcleos zoológicos.</p>
        </div>
        <div class="gridConservacion">
            <div class="contenedorMapaInteractivo">
                <select class="subtextoNormal selectProvincias" id="selectorProvincias">
                    <option value="">Selecciona una provincia</option>
                    <option value="Avila">Ávila</option>
                    <option value="Burgos">Burgos</option>
                    <option value="Leon">León</option>
                    <option value="Palencia">Palencia</option>
                    <option value="Salamanca">Salamanca</option>
                    <option value="Segovia">Segovia</option>
                    <option value="Soria">Soria</option>
                    <option value="Valladolid">Valladolid</option>
                    <option value="Zamora">Zamora</option>
                </select>
                <div class="contenedorImagenMapa" id="mapaCastilla">
                </div>
            </div>
            <div class="card perfil" id="infoProvincia">
                <p class="textoNormal">Selecciona una provincia en el mapa o en el selector para ver los núcleos zoológicos disponibles.</p>
            </div>
        </div>
    </section>
    <script>
        const DATA_GRAFICO = {
            labels: <?php echo json_encode($labelsGrafico); ?>,
            valores: <?php echo json_encode($valoresGrafico); ?>
        };
    </script>
    <script src="./js/graficoZoos.js"></script>
    
    <div id="modalEspecie" class="modal" style="display: none;">
        <div class="contenidoModal">
            <span class="cerrarModalEditar">&times;</span>
            <div id="detalleEspecie"></div>
        </div>
    </div>

    <script src="./js/mapa.js"></script>
    <script>
        // Datos para Chart.js
        const DATA_GRAFICO = {
            labels: <?php echo json_encode($labelsGrafico); ?>,
            valores: <?php echo json_encode($valoresGrafico); ?>
        };
    </script>
    <script src="./js/graficoZoos.js"></script>
    <script src="./js/indexModal.js"></script> 
    
    <?php include __DIR__ . '/include/footer.php'; ?>
</body>
</html>
