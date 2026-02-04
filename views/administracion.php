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

    <main class="seccionNormal">
        <div class="cabeceraAdmin">
            <h1 class="titulo">Administrar nucleos zoologicos</h1>
            <button class="botonPrincipal" id="btnAnadirZoologico">
                <span>Añadir Núcleo Zoológico</span>
                <i class="icono"></i>
            </button>
        </div>

        <div class="divTitulosPaginas">
            <div class="contenedorBusqueda">
                <input type="text" class="barraBusqueda subtextoNormal" placeholder="Buscar zoológicos, especies o provincias...">
                <button class="botonBuscar">
                    <i class="icono" alt="buscar"></i>
                </button>
            </div>
            
            <div class="contenedorFiltros">
                <select class="subtextoNormal selectZoologicos">
                    <option class="subtextoNormal opciones">Selecciona una provincia</option>
                    <option class="opciones">Ávila</option>
                    <option class="opciones">Burgos</option>
                    <option class="opciones">León</option>
                    <option class="opciones">Palencia</option>
                    <option class="opciones">Salamanca</option>
                    <option class="opciones">Segovia</option>
                    <option class="opciones">Soria</option>
                    <option class="opciones">Valladolid</option>
                    <option class="opciones">Zamora</option>
                </select>

                <select class="subtextoNormal selectZoologicos">
                    <option class="opciones">Selecciona un municipio</option>
                </select>
                
                <select class="selectZoologicos subtextoNormal" id="ordenFecha">
                    <option value="desc">Más recientes primero</option>
                    <option value="asc">Más antiguos primero</option>
                </select>

                <p class="textoNormal">
                    Mostrando <span id="totalMostrado">50</span> de <span id="totalRegistros">150</span> registros
                </p>
            </div>
        </div>

        <div class="contenedorTabla">
            <table class="tablaAdmin">
                <thead>
                    <tr>
                        <th>ID Zoológico</th>
                        <th>Fecha de Alta</th>
                        <th>Titular</th>
                        <th>Municipio</th>
                        <th>Provincia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaBody">
                    <tr>
                        <td>ZOO-001</td>
                        <td>15/01/2024</td>
                        <td>Fundación Naturaleza Viva</td>
                        <td>Salamanca</td>
                        <td>Salamanca</td>
                        <td>
                            <div class="accionesTabla">
                                <button class="botonTexto botonesPequenos textoNormal">
                                    Editar
                                </button>
                                <button class="botonTexto botonesPequenos textoNormal">
                                    Borrar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>ZOO-001</td>
                        <td>15/01/2024</td>
                        <td>Fundación Naturaleza Viva</td>
                        <td>Salamanca</td>
                        <td>Salamanca</td>
                        <td>
                            <div class="accionesTabla">
                                <button class="botonTexto botonesPequenos textoNormal">
                                    Editar
                                </button>
                                <button class="botonTexto botonesPequenos textoNormal">
                                    Borrar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pieTabla">
            <div class="paginacion">
                <button class="botonPagina"><<</button>
                <button class="botonPagina"><</button>
                <button class="botonPagina activo">1</button>
                <button class="botonPagina">2</button>
                <button class="botonPagina">></button>
                <button class="botonPagina">>></button>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../include/footer.php'; ?>

</body>
</html>