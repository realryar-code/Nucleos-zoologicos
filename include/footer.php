<?php
    $enViews = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
    $rutaBase = $enViews ? '../' : './';
?>

<footer class="footer">
    <div class="contenedorFooter">
        <div class="columnaFooter">
            <div class="logoFooter">
                <img src="<?php print $rutaBase; ?>img/logoJunta.png" alt="Logo Junta" style="width: 130px; height: auto; margin-right: 10px; vertical-align: middle;">
                <span class="nombreFooter blanco">Fauna CyL</span>
            </div>
            <p class="tetxoNormal blanco limitadorTexto">Portal de información de los núcleos zoológicos de Castilla y León para la preservación de especies y divulgación de la fauna ibérica.</p>
        </div>

        <div class="columnaFooter">
            <h3 class="nombreFooter blanco negrita">Navegación</h3>
            <ul class="listaFooter">
                <li><a href="<?php print $rutaBase; ?>index.php" class="enlaceFooter subtextoNormal">Inicio</a></li>
                <li><a href="<?php print $rutaBase; ?>views/zoologicos.php" class="enlaceFooter subtextoNormal">Zoológicos</a></li>
                <li><a href="<?php print $rutaBase; ?>views/especies.php" class="enlaceFooter subtextoNormal">Especies</a></li>
            </ul>
        </div>

        <div class="columnaFooter">
            <h3 class="nombreFooter blanco negrita">Legal</h3>
            <ul class="listaFooter">
                <li><a href="#" class="enlaceFooter subtextoNormal">Aviso Legal</a></li>
                <li><a href="#" class="enlaceFooter subtextoNormal">Política de Privacidad</a></li>
                <li><a href="#" class="enlaceFooter subtextoNormal">Términos de Uso</a></li>
                <li><a href="#" class="enlaceFooter subtextoNormal">Accesibilidad</a></li>
            </ul>
        </div>

        <div class="columnaFooter">
            <h3 class="nombreFooter blanco negrita">Enlaces de interés</h3>
            <ul class="listaFooter">
                <li><a href="#" class="enlaceFooter subtextoNormal">Junta de Castilla y León ↗</a></li>
                <li><a href="#" class="enlaceFooter subtextoNormal">Ministerio de Transición Ecológica ↗</a></li>
                <li><a href="#" class="enlaceFooter subtextoNormal">UICN - Lista Roja ↗</a></li>
            </ul>
        </div>
    </div>

    <div class="lineaFooter"></div>

    <div class="copyright">
        <p class="subtextoNormal blanco">© 2026 Castilla y Zoológicos. Junta de Castilla y León. Todos los derechos reservados.</p>
    </div>
</footer>