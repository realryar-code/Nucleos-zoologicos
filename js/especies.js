document.addEventListener('DOMContentLoaded', function() {
    const especiesPorPagina = 12;
    let paginaActual = 1;

    const contenedor = document.getElementById('contenedorCards');
    const inputBusqueda = document.querySelector('.barraBusqueda');
    const selectOrden = document.querySelector('.selectZoologicos');
    const modal = document.getElementById('modalEspecie');
    const btnCerrar = document.querySelector('.cerrarModalEditar');
    const contenedorPaginacion = document.querySelector('.paginacion');

    // Guardamos las cards originales para no perderlas al filtrar
    let todasLasCardsOriginales = Array.from(contenedor.querySelectorAll('.card'));
    let todasLasCardsFiltradas = [...todasLasCardsOriginales]; 

    // --- 1. FUNCIÓN MOSTRAR PÁGINA ---
    function mostrarPagina(numeroPagina) {
        // Ocultamos absolutamente todas primero
        todasLasCardsOriginales.forEach(card => card.style.display = 'none');
        
        const inicio = (numeroPagina - 1) * especiesPorPagina;
        const fin = inicio + especiesPorPagina;
        
        // Mostramos solo las filtradas que corresponden a la página
        todasLasCardsFiltradas.slice(inicio, fin).forEach(card => card.style.display = '');
        
        actualizarBotones(numeroPagina);
    }

    // --- 2. ACTUALIZAR BOTONES PAGINACIÓN (IDÉNTICO A ZOOLOGICOS) ---
    function actualizarBotones(numeroPagina) {
        contenedorPaginacion.innerHTML = '';
        const totalPaginas = Math.ceil(todasLasCardsFiltradas.length / especiesPorPagina);
        if (totalPaginas <= 1) return;

        const crearBoton = (texto, onClick, activo = false) => {
            const btn = document.createElement('button');
            btn.className = `botonPagina ${activo ? 'activo' : ''}`;
            btn.textContent = texto;
            btn.onclick = onClick;
            return btn;
        };

        // Botón Inicio <<
        contenedorPaginacion.appendChild(crearBoton('<<', () => { 
            paginaActual = 1; 
            mostrarPagina(1); 
            window.scrollTo({top: 0, behavior: 'smooth'});
        }));
        
        // Cálculo de rango de 5 números
        let inicio = Math.max(1, numeroPagina - 2);
        let fin = Math.min(totalPaginas, inicio + 4);
        if (fin - inicio < 4) inicio = Math.max(1, fin - 4);

        for (let i = inicio; i <= fin; i++) {
            contenedorPaginacion.appendChild(crearBoton(i, () => {
                paginaActual = i;
                mostrarPagina(i);
                window.scrollTo({top: 0, behavior: 'smooth'});
            }, i === numeroPagina));
        }

        // Botón Fin >>
        contenedorPaginacion.appendChild(crearBoton('>>', () => { 
            paginaActual = totalPaginas; 
            mostrarPagina(totalPaginas); 
            window.scrollTo({top: 0, behavior: 'smooth'});
        }));
    }

    // --- 3. ORDENACIÓN ---
    selectOrden.addEventListener('change', function() {
        const opcion = this.value;
        todasLasCardsFiltradas.sort((a, b) => {
            switch(opcion) {
                case 'Nombre(A-Z)': return a.dataset.nombre.localeCompare(b.dataset.nombre);
                case 'Nombre(Z-A)': return b.dataset.nombre.localeCompare(a.dataset.nombre);
                case 'Menos apariciones': return parseInt(a.dataset.numCentros) - parseInt(b.dataset.numCentros);
                case 'Más apariciones': return parseInt(b.dataset.numCentros) - parseInt(a.dataset.numCentros);
                default: return 0;
            }
        });
        // Reordenar en el DOM
        todasLasCardsFiltradas.forEach(card => contenedor.appendChild(card));
        paginaActual = 1;
        mostrarPagina(1);
    });

    // --- 4. MODAL DETALLES ---
    contenedor.addEventListener('click', (e) => {
        const btn = e.target.closest('.botonCard');
        if (btn) {
            const nombre = btn.closest('.card').querySelector('h3').textContent;
            abrirModalEspecie(nombre);
        }
    });

    function abrirModalEspecie(nombre) {
        const contenedorDetalle = document.getElementById('detalleEspecie');
        modal.style.display = "flex";
        contenedorDetalle.innerHTML = '<p>Cargando centros...</p>';

        fetch(`../endpoints/buscar_especies.php?accion=detalles&nombre=${encodeURIComponent(nombre)}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <h1 class="subtitulo">${data.nombre}</h1>
                    <p class="textoNormal margenAbajo">Centros que albergan esta especie (${data.centros.length}):</p>
                    <div class="mostrarDatosProvincias mostrarDatosEspecie">
                `;
                data.centros.forEach(c => {
                    html += `
                        <a href="fichaZoos.php?id=${c.id_zoologico}" class="cardEnlace">
                            <div><p class="textoNormal"><strong>${c.titular}</strong></p><p class="subtextoNormal">${c.municipio}</p></div>
                            <span class="botonPrincipal"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M607.5-372.5Q660-425 660-500t-52.5-127.5Q555-680 480-680t-127.5 52.5Q300-575 300-500t52.5 127.5Q405-320 480-320t127.5-52.5Zm-204-51Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM214-281.5Q94-363 40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200q-146 0-266-81.5ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg></span>
                        </a>`;
                });
                html += `</div>`;
                contenedorDetalle.innerHTML = html;
            });
    }

    // --- 5. LÓGICA DE BÚSQUEDA ---
    inputBusqueda.addEventListener('input', function(e) {
        const termino = e.target.value.toLowerCase().trim();
        
        todasLasCardsFiltradas = todasLasCardsOriginales.filter(card => {
            const nombreEspecie = card.dataset.nombre.toLowerCase();
            return nombreEspecie.includes(termino);
        });

        paginaActual = 1;
        mostrarPagina(1);
    });

    // Cerrar Modal
    if (btnCerrar) btnCerrar.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };

    // Inicio inicial
    mostrarPagina(1);
});
