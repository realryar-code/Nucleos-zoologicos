document.addEventListener('DOMContentLoaded', function() {
    const zoosPorPagina = 12;
    let paginaActual = 1;

    const contenedor = document.getElementById('contenedorCardsZoo');
    const inputBusqueda = document.getElementById('inputBusquedaZoo');
    const selectProvincia = document.getElementById('selectProvincia');
    const selectMunicipio = document.getElementById('selectMunicipio');
    const contenedorPaginacion = document.getElementById('paginacionZoo');

    const todasLasCardsOriginales = Array.from(contenedor.querySelectorAll('.card'));
    let todasLasCardsFiltradas = [...todasLasCardsOriginales];

    // FUNCIÓN CLAVE: Normaliza el texto para que "Leon" sea igual a "León"
    const normalizar = (texto) => {
        return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
    };

    function mostrarPagina(numeroPagina) {
        todasLasCardsOriginales.forEach(card => card.style.display = 'none');
        const inicio = (numeroPagina - 1) * zoosPorPagina;
        const fin = inicio + zoosPorPagina;
        // Usamos '' en lugar de 'block' para respetar el CSS original
        todasLasCardsFiltradas.slice(inicio, fin).forEach(card => card.style.display = '');
        actualizarBotones(numeroPagina);
    }

    function actualizarBotones(numeroPagina) {
        contenedorPaginacion.innerHTML = '';
        const totalPaginas = Math.ceil(todasLasCardsFiltradas.length / zoosPorPagina);
        if (totalPaginas <= 1) return;

        const crearBoton = (texto, onClick, activo = false) => {
            const btn = document.createElement('button');
            btn.className = `botonPagina ${activo ? 'activo' : ''}`;
            btn.textContent = texto;
            btn.onclick = onClick;
            return btn;
        };

        contenedorPaginacion.appendChild(crearBoton('<<', () => { paginaActual = 1; mostrarPagina(1); }));
        
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

        contenedorPaginacion.appendChild(crearBoton('>>', () => { paginaActual = totalPaginas; mostrarPagina(totalPaginas); }));
    }

    function filtrarZoos() {
        const busqueda = normalizar(inputBusqueda.value);
        const provSel = normalizar(selectProvincia.value);
        const muniSel = normalizar(selectMunicipio.value);

        todasLasCardsFiltradas = todasLasCardsOriginales.filter(card => {
            const titular = normalizar(card.dataset.titular);
            const provincia = normalizar(card.dataset.provincia);
            const municipio = normalizar(card.dataset.municipio);

            const coincideTexto = titular.includes(busqueda) || provincia.includes(busqueda) || municipio.includes(busqueda);
            const coincideProv = (provSel === "todas" || provincia === provSel);
            const coincideMuni = (muniSel === "todos" || municipio === muniSel);

            return coincideTexto && coincideProv && coincideMuni;
        });

        paginaActual = 1;
        mostrarPagina(1);
    }

    // ESTA ES LA PARTE QUE FALLABA CON LEÓN:
    selectProvincia.addEventListener('change', function() {
        // Normalizamos el valor seleccionado (ej: "leon")
        const provActualNorm = normalizar(this.value);
        
        selectMunicipio.innerHTML = '<option value="Todos">Todos los Municipios</option>';
        
        if (provActualNorm !== "todas") {
            // Filtramos las cards normalizando también su provincia
            const municipiosFiltrados = todasLasCardsOriginales
                .filter(card => normalizar(card.dataset.provincia) === provActualNorm)
                .map(card => card.dataset.municipio.trim());

            const unicos = [...new Set(municipiosFiltrados)].sort();

            unicos.forEach(muni => {
                const opt = document.createElement('option');
                opt.value = muni; 
                opt.textContent = muni;
                selectMunicipio.appendChild(opt);
            });
        }
        filtrarZoos();
    });

    inputBusqueda.addEventListener('input', filtrarZoos);
    selectMunicipio.addEventListener('change', filtrarZoos);

    mostrarPagina(1);
});