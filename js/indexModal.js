document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalEspecie');
    const btnCerrar = document.querySelector('.cerrarModalEditar');
    const contenedorDetalle = document.getElementById('detalleEspecie');

    // Escuchar clicks en los botones "Ver detalles" de las cards de especies
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('botonCard') && e.target.textContent === 'Ver detalles') {
            const card = e.target.closest('.card');
            const nombreEspecie = card.querySelector('h3').textContent.trim();
            abrirModal(nombreEspecie);
        }
    });


function abrirModal(nombre) {
    const modal = document.getElementById('modalEspecie');
    modal.style.display = "flex"; // Usar flex para centrar contenido
        contenedorDetalle.innerHTML = '<p class="textoNormal">Cargando centros...</p>';

        // Llamada al endpoint existente
        fetch(`./endpoints/buscar_especies.php?accion=detalles&nombre=${encodeURIComponent(nombre)}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <h2 class="subtitulo">${data.nombre}</h2>
                    <p class="textoNormal" style="margin-bottom: 20px;">Centros que albergan esta especie (${data.centros.length}):</p>
                    <div class="mostrarDatosProvincias">`;
                
                data.centros.forEach(centro => {
                    html += `
                        <div class="cardEnlace" style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 8px;">
                            <div style="text-align: left;">
                                <p class="textoNormal" style="font-weight: bold; margin: 0; text-transform: uppercase;">${centro.titular}</p>
                                <p class="subtextoNormal" style="margin: 5px 0 0 0; color: #666;">${centro.municipio}</p>
                            </div>
                            <a href="views/fichaZoos.php?id=${centro.id_zoologico}" class="botonPrincipal" style="padding: 10px 20px; text-decoration: none; font-size: 0.9em;"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M607.5-372.5Q660-425 660-500t-52.5-127.5Q555-680 480-680t-127.5 52.5Q300-575 300-500t52.5 127.5Q405-320 480-320t127.5-52.5Zm-204-51Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM214-281.5Q94-363 40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200q-146 0-266-81.5ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z"/></svg></a>
                        </div>`;
                });

                html += `</div>`;
                contenedorDetalle.innerHTML = html;
            })
            .catch(err => {
                contenedorDetalle.innerHTML = '<p>Error al cargar los datos.</p>';
                console.error(err);
            });
    }

    // Cerrar modal
    if (btnCerrar) {
        btnCerrar.onclick = () => modal.style.display = "none";
    }
    window.onclick = (event) => {
        if (event.target == modal) modal.style.display = "none";
    };
}); 