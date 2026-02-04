<?php
    require_once __DIR__ . '/../dao/DAOZoologicos.php';
    $daoZoo = new DAOZoologicos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castilla y Zoológicos - Portal de Conservación</title>
    <link rel="stylesheet" href="../css/buencss2.css">
    <style>
        /* Aseguramos que el contenedor de imagen de la card respete el diseño */
        .contenedorImagen {
            overflow: hidden;
            background-color: #e0e0e0; /* Color de respaldo */
        }
        .contenedorImagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>

    <main class="seccionNormal">
        <div class="divTitulosPaginas">
            <h1 class="titulo">Núcleos Zoológicos</h1>
            <p class="textoNormal">Explora todos los centros zoológicos registrados en Castilla y León.</p>
        </div>
    </main>

    <main class="seccionNormal">
        <div class="divTitulosPaginas">
            <div class="contenedorBusqueda">
                <input type="text" id="inputBusquedaZoo" class="barraBusqueda subtextoNormal" placeholder="Buscar zoológicos, municipios o provincias...">
                <button class="botonBuscar" style="display:none;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg></button>
            </div>

            <div class="contenedorFiltros">
                <select id="selectProvincia" class="subtextoNormal selectZoologicos">
                    <option value="Todas">Todas las Provincias</option>
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

                <select id="selectMunicipio" class="subtextoNormal selectZoologicos">
                    <option value="Todos">Todos los Municipios</option>
                </select>
            </div>
        </div>
    </main>

    <main class="seccionNormal">
        <div class="contenedorCards grid3x3" id="contenedorCardsZoo">
            <?php
            $zoologicos = $daoZoo->leerZoos();
            $i = 1; // Contador para rotar las 10 imágenes
            foreach ($zoologicos as $zoo) {
                $titular = trim($zoo['titular']);
                $prov = trim($zoo['provincia']);
                $muni = trim($zoo['municipio']);
                
                // Determinamos qué imagen le toca (zoo1.jpg hasta zoo10.jpg)
                $numImg = ($i % 10 == 0) ? 10 : ($i % 10);
                $nombreImagen = "zoo" . $numImg . ".jpg";
                $i++;
            ?>
            <div class="card" 
                 data-titular="<?php echo htmlspecialchars($titular); ?>" 
                 data-provincia="<?php echo htmlspecialchars($prov); ?>" 
                 data-municipio="<?php echo htmlspecialchars($muni); ?>">
                
                <div class="contenedorImagen">
                    <img src="../img/imagenesZoologicos/<?php echo $nombreImagen; ?>" alt="Zoo">
                </div>
                
                <div class="contenidoCard">
                    <h3 class="textoNormal"><?php echo mb_strtoupper(htmlspecialchars($titular)); ?></h3>
                    <div class="ubicacionCard">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#314D1C"><path d="M307-113.5Q240-147 240-200q0-24 14.5-44.5T295-280l63 59q-9 4-19.5 9T322-200q13 16 60 28t98 12q51 0 98.5-12t60.5-28q-7-8-18-13t-21-9l62-60q28 16 43 36.5t15 45.5q0 53-67 86.5T480-80q-106 0-173-33.5ZM481-300q99-73 149-146.5T680-594q0-102-65-154t-135-52q-70 0-135 52t-65 154q0 67 49 139.5T481-300Zm-1 100Q339-304 269.5-402T200-594q0-71 25.5-124.5T291-808q40-36 90-54t99-18q49 0 99 18t90 54q40 36 65.5 89.5T760-594q0 94-69.5 192T480-200Zm0-320q33 0 56.5-23.5T560-600q0-33-23.5-56.5T480-680q-33 0-56.5 23.5T400-600q0 33 23.5 56.5T480-520Zm0-80Z"/></svg>
                        <p class="subtextoNormal"><?php echo htmlspecialchars($muni) . ', ' . htmlspecialchars($prov); ?></p>
                    </div>
                    <a class="botonCard" href="fichaZoos.php?id=<?php echo $zoo['id_zoologico']; ?>&img=<?php echo $nombreImagen; ?>">
                        Más información
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="paginacion" id="paginacionZoo"></div>
    </main>

    <?php include __DIR__ . '/../include/footer.php'; ?>

    <script src="../js/zoologicos.js"></script>
</body>
</html>