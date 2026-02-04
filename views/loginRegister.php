<?php
    session_start();
    
    // Obtener mensajes y errores
    $errorLogin = $_SESSION['error_login'] ?? '';
    $errorRegistro = $_SESSION['error_registro'] ?? '';
    $erroresRegistro = $_SESSION['errores_registro'] ?? [];
    $datosFormulario = $_SESSION['datos_formulario'] ?? [];
    
    // Limpiar mensajes de sesión
    unset($_SESSION['error_login']);
    unset($_SESSION['error_registro']);
    unset($_SESSION['errores_registro']);
    unset($_SESSION['datos_formulario']);
    
    // Si ya está logueado, redirigir al perfil
    if (isset($_SESSION['usuario'])) {
        header('Location: perfilUsuario.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Castilla y Zoológicos - Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/buencss2.css">
    <style>
        .mensaje-error {
            background-color: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #fcc;
        }
        .lista-errores {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../include/header.php'; ?>

    <main class="seccionInicial fondoBlanco">
        <div class="gridLogin">
            <div class="contenedorFormulario">
                <div class="logoFooter">
                    <i class="logo"></i>
                    <span class="subtitulo negrita">FAUNA CYL</span>
                </div>

                <div class="panelPestanas">
                    <button class="pestana activa textoNormal" id="btnLogin">Iniciar Sesión</button>
                    <button class="pestana textoNormal" id="btnRegister">Registrarse</button>
                </div>

                <!-- FORMULARIO DE LOGIN -->
                <form class="formulario" id="formLogin" method="POST" action="../config/procesarLogin.php">
                    <?php if ($errorLogin): ?>
                        <div class="mensaje-error">
                            <?php echo htmlspecialchars($errorLogin); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Correo electrónico</label>
                        <input type="email" name="correo" class="inputFormulario subtextoNormal" placeholder="tu@email.com" required>
                    </div>
                    
                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Contraseña</label>
                        <input type="password" name="contrasena" class="inputFormulario subtextoNormal" placeholder="••••••••" required>
                    </div>
                    
                    <button type="submit" class="botonFormulario textoNormal negrita">Iniciar Sesión</button>
                    
                    <div class="textoAyuda">
                        <p class="subtextoNormal">¿No tienes cuenta? <span class="tituloInverso negrita cambiarPestana" data-target="register">Regístrate</span></p>
                    </div>
                </form>

                <!-- FORMULARIO DE REGISTRO -->
                <form class="formulario" id="formRegistrer" method="POST" action="../config/procesarRegistro.php" style="display: none;">
                    <?php if ($errorRegistro): ?>
                        <div class="mensaje-error">
                            <?php echo htmlspecialchars($errorRegistro); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($erroresRegistro)): ?>
                        <div class="mensaje-error">
                            <ul class="lista-errores">
                                <?php foreach ($erroresRegistro as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Nombre</label>
                        <input type="text" name="nombre" class="inputFormulario subtextoNormal" placeholder="tu nombre..." value="<?php echo htmlspecialchars($datosFormulario['nombre'] ?? ''); ?>" required>
                    </div>

                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Apellidos</label>
                        <input type="text" name="apellidos" class="inputFormulario subtextoNormal" placeholder="tu apellido..." value="<?php echo htmlspecialchars($datosFormulario['apellidos'] ?? ''); ?>" required>
                    </div>

                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Correo electrónico</label>
                        <input type="email" name="correo" class="inputFormulario subtextoNormal" placeholder="tu@email.com" value="<?php echo htmlspecialchars($datosFormulario['correo'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Contraseña</label>
                        <input type="password" name="contrasena" class="inputFormulario subtextoNormal" placeholder="mínimo 6 caracteres..." required>
                    </div>

                    <div class="campoFormulario">
                        <label class="textoNormal negrita">Repetir contraseña</label>
                        <input type="password" name="repetir_contrasena" class="inputFormulario subtextoNormal" placeholder="repite tu contraseña..." required>
                    </div>
                    
                    <button type="submit" class="botonFormulario textoNormal negrita">Registrarse</button>
                    
                    <div class="textoAyuda">
                        <p class="subtextoNormal">¿Ya tienes cuenta? <span class="tituloInverso negrita cambiarPestana" data-target="login">Inicia sesión</span></p>
                    </div>
                </form>
            </div>

            <div class="contenedorImagenLogin">
                <img src="../img/imgLogin.jpg" alt="Fauna de Castilla y León" class="imagenLogin">
                <div class="card sobreImagen">
                    <h2 class="tituloWeb">Descubre la riqueza natural de Castilla y León a través de nuestros núcleos zoológicos.</h2>
                    <p class="textoNormal">Portal oficial de la Junta de Castilla y León</p>
                </div>
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/../include/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnLogin = document.getElementById('btnLogin');
            const btnRegister = document.getElementById('btnRegister');
            const formLogin = document.getElementById('formLogin');
            const formRegistrer = document.getElementById('formRegistrer');
            
            // Función para cambiar entre pestañas
            function mostrarLogin() {
                btnLogin.classList.add('activa');
                btnRegister.classList.remove('activa');
                formLogin.style.display = 'block';
                formRegistrer.style.display = 'none';
            }
            
            function mostrarRegistro() {
                btnRegister.classList.add('activa');
                btnLogin.classList.remove('activa');
                formRegistrer.style.display = 'block';
                formLogin.style.display = 'none';
            }
            
            // Event listeners para botones
            btnLogin.addEventListener('click', mostrarLogin);
            btnRegister.addEventListener('click', mostrarRegistro);
            
            // Event listeners para enlaces de cambio
            const cambiarPestanas = document.querySelectorAll('.cambiarPestana');
            cambiarPestanas.forEach(span => {
                span.style.cursor = 'pointer';
                span.addEventListener('click', function() {
                    if (this.dataset.target === 'register') {
                        mostrarRegistro();
                    } else {
                        mostrarLogin();
                    }
                });
            });
            
            // Si hay errores de registro, mostrar formulario de registro
            <?php if (!empty($erroresRegistro) || $errorRegistro): ?>
                mostrarRegistro();
            <?php endif; ?>
        });
    </script>
    <script src="../js/validacionLoginRegistro.js"></script>
</body>
</html>