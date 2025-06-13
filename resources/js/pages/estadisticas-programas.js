import Chart from 'chart.js/auto'; // Importar Chart.js

document.addEventListener('DOMContentLoaded', function () {
    // --- Lógica para el filtro dinámico de Lugar ---
    const programaSelect = document.getElementById('programa_filter');
    const lugarSelect = document.getElementById('lugar_filter');

    if (programaSelect && lugarSelect) {
        const RUTA_LUGARES = lugarSelect.dataset.rutaLugares;

        programaSelect.addEventListener('change', function() {
            const selectedPrograma = this.value;
            lugarSelect.innerHTML = '<option value="">Cargando...</option>';
            lugarSelect.disabled = true;

            if (!selectedPrograma) {
                lugarSelect.innerHTML = '<option value="">Seleccione un programa</option>';
                return;
            }

            fetch(`${RUTA_LUGARES}?programa=${encodeURIComponent(selectedPrograma)}`)
                .then(response => response.json())
                .then(data => {
                    lugarSelect.innerHTML = '<option value="">Todos los lugares</option>';
                    if (Array.isArray(data)) {
                        data.forEach(lugar => {
                            const option = document.createElement('option');
                            option.value = lugar;
                            option.textContent = lugar;
                            lugarSelect.appendChild(option);
                        });
                    }
                    lugarSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error al cargar los lugares:', error);
                    lugarSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        });
    }

    // --- Lógica para los Gráficos ---
    const isDarkMode = () => document.documentElement.classList.contains('dark');
    const gridColor = () => isDarkMode() ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
    const textColor = () => isDarkMode() ? 'rgba(255, 255, 255, 0.7)' : '#6b7280'; // gray-500
    const charts = [];

    const initChart = (elementId, chartConfig) => {
        const ctx = document.getElementById(elementId);
        if (ctx && chartConfig.data) {
            // Destruir gráfico existente si lo hay, para evitar conflictos en HMR
            const existingChart = Chart.getChart(ctx);
            if (existingChart) {
                existingChart.destroy();
            }
            charts.push(new Chart(ctx, chartConfig));
        }
    };

    // Configuración y renderizado de Gráfico por Género
    const genderData = JSON.parse(document.getElementById('genderChartData')?.textContent || '{}');
    if (Object.keys(genderData).length > 0) {
        initChart('genderChart', {
            type: 'doughnut',
            data: {
                labels: Object.keys(genderData),
                datasets: [{
                    data: Object.values(genderData),
                    backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(236, 72, 153, 0.8)', 'rgba(107, 114, 128, 0.8)'],
                    borderColor: isDarkMode() ? '#1f2937' : '#fff', // Para el espaciado
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: textColor(), boxWidth: 12, padding: 20 } } }
            }
        });
    }

    // Configuración y renderizado de Gráfico por Grado
    const gradeData = JSON.parse(document.getElementById('gradeChartData')?.textContent || '{}');
    if (Object.keys(gradeData).length > 0) {
        initChart('gradeChart', {
            type: 'bar',
            data: {
                labels: Object.keys(gradeData),
                datasets: [{
                    label: 'Nº de Participantes',
                    data: Object.values(gradeData),
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { color: textColor(), stepSize: 1 }, grid: { color: gridColor() } }, x: { ticks: { color: textColor() }, grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });
    }

    // Configuración y renderizado de Gráfico por Grupo de Edad
    const ageGroupData = JSON.parse(document.getElementById('ageGroupChartData')?.textContent || '{}');
    if (Object.keys(ageGroupData).length > 0) {
        initChart('ageGroupChart', {
            type: 'bar',
            data: {
                labels: Object.keys(ageGroupData),
                datasets: [{
                    label: 'Nº de Participantes',
                    data: Object.values(ageGroupData),
                    backgroundColor: 'rgba(14, 165, 233, 0.7)',
                    borderColor: 'rgba(14, 165, 233, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { color: textColor(), stepSize: 1 }, grid: { color: gridColor() } }, x: { ticks: { color: textColor() }, grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });
    }

    // Configuración y renderizado de Gráfico por Sub-Programa
    const subProgramData = JSON.parse(document.getElementById('subProgramChartData')?.textContent || '{}');
    if (Object.keys(subProgramData).length > 0) {
        initChart('subProgramChart', {
            type: 'bar', // Horizontal bar
            data: {
                labels: Object.keys(subProgramData),
                datasets: [{
                    label: 'Nº de Participantes',
                    data: Object.values(subProgramData),
                    backgroundColor: 'rgba(168, 85, 247, 0.7)',
                    borderColor: 'rgba(168, 85, 247, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                scales: { y: { ticks: { color: textColor() }, grid: { display: false } }, x: { ticks: { color: textColor(), stepSize: 1 }, grid: { color: gridColor() } } },
                plugins: { legend: { display: false } }
            }
        });
    }

    // Actualizar colores de los gráficos al cambiar el tema (modo oscuro/claro)
    const observer = new MutationObserver(() => {
        const newTextColor = textColor();
        const newGridColor = gridColor();
        const newBorderColor = isDarkMode() ? '#1f2937' : '#fff';
        charts.forEach(chart => {
            if (chart.options.scales) {
                chart.options.scales.x.ticks.color = newTextColor;
                chart.options.scales.x.grid.color = chart.options.scales.x.grid.display === false ? 'transparent' : newGridColor;
                chart.options.scales.y.ticks.color = newTextColor;
                chart.options.scales.y.grid.color = chart.options.scales.y.grid.display === false ? 'transparent' : newGridColor;
            }
            if (chart.options.plugins.legend) {
                chart.options.plugins.legend.labels.color = newTextColor;
            }
            if(chart.config.type === 'doughnut' || chart.config.type === 'pie'){
                 chart.data.datasets.forEach(dataset => {
                    dataset.borderColor = newBorderColor;
                });
            }
            chart.update();
        });
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});
