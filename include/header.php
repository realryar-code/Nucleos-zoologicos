<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $paginaActual = basename($_SERVER['PHP_SELF']);
    $enViews = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
    $rutaBase = $enViews ? '../' : './';

    $usuarioLogueado = isset($_SESSION['usuario']);
    $nombreUsuario = $_SESSION['nombre'] ?? '';
    $fotoPerfil = $_SESSION['fotoPerfil'] ?? 1;
?>

<header class="barraNavegacion">
    <div class="divLogo">
        <i class="logo" alt="logo"></i>
        <span class="nombreWeb">CASTILLA Y ZOOLÓGICOS</span>
    </div>

    <button class="botonHamburguesa" id="btnMenu" aria-label="Menú">
        <span class="lineaHamburguesa"></span>
        <span class="lineaHamburguesa"></span>
        <span class="lineaHamburguesa"></span>
    </button>

    <nav class="navegacion" id="navMenu">
        <?php if ($usuarioLogueado): ?>
            <a href="<?php echo $rutaBase; ?>views/perfilUsuario.php" class="soloMovil botonPrincipal subtextoNormal blanco margenAbajo">
                <img src="<?php echo $rutaBase; ?>icons/<?php echo $_SESSION['fotoPerfil']; ?>" class="perfilNavegacionMini"><?php echo htmlspecialchars($nombreUsuario); ?>
            </a>
        <?php endif; ?>
        <a class="enlaceNav <?php echo ($paginaActual == 'index.php') ? 'activo' : ''; ?>" href="<?php echo $rutaBase; ?>index.php">Inicio</a>
        <a class="enlaceNav <?php echo ($paginaActual == 'zoologicos.php') ? 'activo' : ''; ?>" href="<?php echo $rutaBase; ?>views/zoologicos.php">Zoológicos</a>
        <a class="enlaceNav <?php echo ($paginaActual == 'especies.php') ? 'activo' : ''; ?>" href="<?php echo $rutaBase; ?>views/especies.php">Especies</a>
        <a class="enlaceNav" href="<?php echo $rutaBase; ?>index.php#provincias">Provincias</a>
        
        <?php if ($usuarioLogueado): ?>
            <a href="<?php echo $rutaBase; ?>config/logout.php" class="botonPrincipal subtextoNormal blanco soloMovil margenArriba">Cerrar Sesión</a>
        <?php else: ?>
            <a href="<?php echo $rutaBase; ?>views/loginRegister.php" class="botonPrincipal subtextoNormal soloMovil margenArriba">Iniciar Sesión</a>
        <?php endif; ?>
    </nav>
    
    <div class="cuenta">
    <?php if ($usuarioLogueado): ?>
        <div class="contenedorDropdown">
            <div class="botonPrincipal botonesPequenos" id="btnDropdown">
                <img src="<?php echo $rutaBase; ?>icons/<?php echo $_SESSION['fotoPerfil']; ?>" class="perfilNavegacion" alt="Perfil">
                <span class="textoNormal blanco"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                <i class="icono" alt="icono"></i></div>
            <div class="menuDropdown" id="dropdownMenu">
                <a href="<?php echo $rutaBase; ?>views/perfilUsuario.php" class="subtextoNormal">Mi Perfil</a>
                <hr>
                <a href="<?php echo $rutaBase; ?>config/logout.php" class="subtextoNormal deslog">Cerrar Sesión</a>
            </div>
        </div>
    <?php else: ?>
        <a href="<?php echo $rutaBase; ?>views/loginRegister.php" class="subtextoNormal botonPrincipal">Iniciar Sesión</a>
    <?php endif; ?>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnMenu = document.getElementById('btnMenu');
        const navMenu = document.getElementById('navMenu');
        
        if (btnMenu && navMenu) {
            btnMenu.addEventListener('click', () => {
                navMenu.classList.toggle('activo');
                btnMenu.classList.toggle('activo');
                document.body.classList.toggle('menu-abierto');
            });
            
            const enlaces = navMenu.querySelectorAll('.enlaceNav');
            enlaces.forEach(enlace => {
                enlace.addEventListener('click', () => {
                    navMenu.classList.remove('activo');
                    btnMenu.classList.remove('activo');
                    document.body.classList.remove('menu-abierto');
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!navMenu.contains(e.target) && !btnMenu.contains(e.target)) {
                    navMenu.classList.remove('activo');
                    btnMenu.classList.remove('activo');
                    document.body.classList.remove('menu-abierto');
                }
            });
        }

        // Lógica para el dropdown de usuario
        const btnDropdown = document.getElementById('btnDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (btnDropdown && dropdownMenu) {
            btnDropdown.addEventListener('click', (e) => {
                e.stopPropagation(); // Evita que el clic se propague al document
                dropdownMenu.classList.toggle('show');
            });

            // Cerrar el dropdown al hacer clic en cualquier otro lado
            document.addEventListener('click', (e) => {
                if (!btnDropdown.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    });
</script>
