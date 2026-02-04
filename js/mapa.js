document.addEventListener("DOMContentLoaded", () => {
    const contenedorMapa = document.getElementById('mapaCastilla');
    const selector = document.getElementById('selectorProvincias');

    // 1. CARGA DEL SVG
    fetch('./img/Provincias_de_CyL.svg')
        .then(response => {
            if (!response.ok) throw new Error("No se pudo cargar el archivo SVG");
            return response.text();
        })
        .then(svgData => {
            contenedorMapa.innerHTML = svgData;

            const svgElement = contenedorMapa.querySelector('svg');
            if (svgElement) {
                svgElement.setAttribute('width', '100%');
                svgElement.setAttribute('height', 'auto');
            }

            configurarInteraccionMapa();
        })
        .catch(error => console.error("Error cargando el mapa:", error));

    function configurarInteraccionMapa() {
        const provincias = document.querySelectorAll('#mapaCastilla path');

        provincias.forEach(prov => {
            prov.classList.add('provincia-path');

            prov.addEventListener('click', function () {
                const nombreProvincia = this.getAttribute('id');

                if (nombreProvincia && !nombreProvincia.includes('path')) {
                    if (selector) selector.value = nombreProvincia;
                    cargarDatosProvincia(nombreProvincia);
                    resaltarProvincia(this);
                } else {
                    console.warn("Esta provincia tiene un ID genérico. Cámbialo en el SVG.");
                }
            });
        });
    }

    function resaltarProvincia(elemento) {
        document.querySelectorAll('.provincia-path').forEach(p => p.classList.remove('activa'));
        elemento.classList.add('activa');
    }
});

// Conexión al endpoint y funcionalidad del mapa
function cargarDatosProvincia(nombreProvincia) {
    const contenedorInfo = document.getElementById('infoProvincia');
    contenedorInfo.innerHTML = '<div class="spinner">Cargando información...</div>';

    fetch(`./endpoints/get_info_provincia.php?provincia=${nombreProvincia}`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            if (data.error) {
                contenedorInfo.innerHTML = `<p class="textoNormal">${data.error}</p>`;
                return;
            }

            // Convertir municipios en chips
            const municipios = data.listaMunicipios 
                ? data.listaMunicipios.split(' | ')
                    .filter(m => m.trim()) // Filtrar vacíos
                    .map(municipio => `<span class="chipEspecie subtextoNormal">${municipio.trim()}</span>`)
                    .join('')
                : '<span class="subtextoNormal">Sin municipios registrados</span>';

            contenedorInfo.innerHTML = `
                    <div class="contenidoCard">
                        <h2 class="subtitulo">${data.provincia}</h2>
                        <hr>
                        <p class="textoNormal"><strong>Núcleos zoológicos localizados:</strong> ${data.numZoos}</p>
                        <p class="textoNormal"><strong>Especies distintas:</strong> ${data.numEspecies}</p>
                        <p class="textoNormal"><strong>Municipios:</strong></p>
                        <div class="mostrarDatosProvincias">
                            <div class="contenedorChips">
                                ${municipios}
                            </div>
                        </div>
                        
                    </div>
            `;
        })
        .catch(error => {
            console.error("Error detallado:", error);
            contenedorInfo.innerHTML = `<p>Error al conectar con la base de datos. Revisa la consola.</p>`;
        });
}

// Selector desplegable
document.addEventListener('DOMContentLoaded', () => {
    const selector = document.getElementById('selectorProvincias');

    if (selector) {
        selector.addEventListener('change', function() {
            const provinciaSeleccionada = this.value;

            if (provinciaSeleccionada !== "") {
                cargarDatosProvincia(provinciaSeleccionada);
            } else {
                document.getElementById('infoProvincia').innerHTML = `
                    <div class="cardProvincia">
                        <p class="textoNormal">Selecciona una provincia en el mapa o en el selector para ver los núcleos zoológicos disponibles.</p>
                    </div>`;
            }
        });
    }
});
