document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('graficoProvincias').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: DATA_GRAFICO.labels,
            datasets: [{
                label: 'Cantidad de Núcleos',
                data: DATA_GRAFICO.valores,
                backgroundColor: '#C05746', 
                borderColor: '#A84A3B',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { bottom: 10 } // Espacio extra abajo para las etiquetas
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 50,
                        color: '#666'
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: {
                        autoSkip: false,   // PROHIBIDO saltar etiquetas
                        maxTicksLimit: 20, // Forzamos a que acepte muchas etiquetas
                        maxRotation: 0,    // 100% Horizontal
                        minRotation: 0,
                        font: { 
                            size: 11,       // Tamaño 
                            weight: '700' 
                        },
                        color: '#333'
                    },
                    grid: { display: false }
                }
            }
        }
    });
});