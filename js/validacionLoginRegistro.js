document.addEventListener('DOMContentLoaded', () => {

    const formLogin = document.getElementById('formLogin');
    const formRegister = document.getElementById('formRegistrer');

    function emailValido(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    /* ================= LOGIN ================= */
    if (formLogin) {
        const correo = formLogin.correo;
        const contrasena = formLogin.contrasena;

        // LIMPIAR MENSAJES AL ESCRIBIR
        correo.addEventListener('input', () => correo.setCustomValidity(''));
        contrasena.addEventListener('input', () => contrasena.setCustomValidity(''));

        // MENSAJES PERSONALIZADOS (BOCADILLOS)
        correo.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu correo electrónico.');
            } else if (!emailValido(this.value)) {
                this.setCustomValidity('Introduce un correo electrónico válido.');
            }
        });

        contrasena.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu contraseña.');
            }
        });
    }

    /* ================= REGISTRO ================= */
    if (formRegister) {
        const nombre = formRegister.nombre;
        const apellidos = formRegister.apellidos;
        const correo = formRegister.correo;
        const pass1 = formRegister.contrasena;
        const pass2 = formRegister.repetir_contrasena;

        const inputs = [nombre, apellidos, correo, pass1, pass2];

        // LIMPIAR MENSAJES AL ESCRIBIR
        inputs.forEach(input => {
            input.addEventListener('input', () => input.setCustomValidity(''));
        });

        // NOMBRE
        nombre.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu nombre.');
            }
        });

        // APELLIDOS
        apellidos.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu apellido.');
            }
        });

        // CORREO
        correo.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu correo electrónico.');
            } else if (!emailValido(this.value)) {
                this.setCustomValidity('Correo electrónico no válido.');
            }
        });

        // CONTRASEÑA
        pass1.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Rellena tu contraseña.');
            } else if (this.value.length < 6) {
                this.setCustomValidity('La contraseña debe tener al menos 6 caracteres.');
            }
        });

        // REPETIR CONTRASEÑA
        pass2.addEventListener('invalid', function () {
            if (!this.value.trim()) {
                this.setCustomValidity('Repite tu contraseña.');
            } else if (this.value !== pass1.value) {
                this.setCustomValidity('Las contraseñas no coinciden.');
            }
        });
    }
});

