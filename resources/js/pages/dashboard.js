/**
 * =================================================================================
 * Dashboard Charts Script (Refactored)
 * =================================================================================
 *
 * Mejoras aplicadas:
 * 1.  Lectura de datos desde atributos `data-*` en lugar de variables globales (`window`).
 * 2.  Función auxiliar `getCommonChartOptions` para reducir la duplicación de código.
 * 3.  Inicialización segura de gráficos, verificando la existencia del canvas.
 */
import Chart from 'chart.js/auto'; // Importa la librería Chart.js
document.addEventListener('DOMContentLoaded', function () {
    // Helper function to detect dark mode
    const isDarkMode = () => document.documentElement.classList.contains('dark');

    // Helper functions for chart styling (sin cambios)
    const getChartFontColor = () => isDarkMode() ? '#cbd5e1' : '#374151';
    const getGridLineColor = () => isDarkMode() ? 'rgba(71, 85, 105, 0.5)' : 'rgba(203, 213, 225, 0.5)';
    const getTooltipBgColor = () => isDarkMode() ? 'rgba(30, 41, 59, 0.9)' : 'rgba(255,255,255,0.9)';
    const getTooltipTitleColor = () => isDarkMode() ? '#f1f5f9' : '#334155';
    const getTooltipBodyColor = () => isDarkMode() ? '#e2e8f0' : '#475569';
    const getTooltipBorderColor = () => isDarkMode() ? '#475569' : '#e2e8f0';
    const getChartColors = (numColors, type = 'bar') => {
        const baseColorsLight = ['rgba(106, 90, 205, 1.5)', 'rgba(60, 179, 113, 0.7)', 'rgba(0, 0, 255, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(245, 158, 11, 0.7)', 'rgba(239, 68, 68, 0.7)', 'rgba(34, 197, 94, 0.7)'];
        const baseColorsDark = ['rgba(99, 102, 241, 0.8)', 'rgba(192, 132, 252, 0.8)', 'rgba(244, 114, 182, 0.8)', 'rgba(96, 165, 250, 0.8)', 'rgba(52, 211, 153, 0.8)', 'rgba(251, 191, 36, 0.8)', 'rgba(252, 165, 165, 0.8)', 'rgba(74, 222, 128, 0.8)'];
        const lineChartColorLight = 'rgba(75, 59, 255, 0.8)';
        const lineChartColorDark = 'rgba(99, 102, 241, 0.9)';

        if (type === 'line') return isDarkMode() ? lineChartColorDark : lineChartColorLight;
        const selectedPalette = isDarkMode() ? baseColorsDark : baseColorsLight;
        return Array.from({ length: numColors }, (_, i) => selectedPalette[i % selectedPalette.length]);
    };

    // MEJORA: Función auxiliar para opciones comunes de Chart.js
    function getCommonChartOptions(isLineChart = false) {
        const options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, color: getChartFontColor() }, grid: { color: getGridLineColor() } },
                x: { ticks: { color: getChartFontColor() }, grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: getTooltipBgColor(),
                    titleColor: getTooltipTitleColor(),
                    bodyColor: getTooltipBodyColor(),
                    borderColor: getTooltipBorderColor(),
                    borderWidth: 1, padding: 10, cornerRadius: 6,
                }
            }
        };
        if (isLineChart) {
            options.plugins.tooltip.mode = 'index';
            options.plugins.tooltip.intersect = false;
            options.hover = { mode: 'nearest', intersect: true };
        }
        return options;
    }

    // Chart instances
    let chartInstances = {};

    // Generic Chart Renderer
    function renderChart(canvasId, type) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return; // Salir si el canvas no existe

        // MEJORA: Leer datos desde el atributo data-*
        const chartData = JSON.parse(ctx.dataset.chartData || '{}');
        const labels = Object.keys(chartData);
        const counts = Object.values(chartData);

        if (chartInstances[canvasId]) {
            chartInstances[canvasId].destroy();
        }

        if (labels.length === 0) return; // No renderizar si no hay datos

        let data, options;
        switch (type) {
            case 'bar':
                data = {
                    labels,
                    datasets: [{
                        label: 'Total inscritos',
                        data: counts,
                        backgroundColor: getChartColors(labels.length, 'bar'),
                        borderColor: getChartColors(labels.length, 'bar').map(c => c.replace(/0\.[78]/, '1')),
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                };
                options = getCommonChartOptions();
                break;

            case 'doughnut':
                data = {
                    labels,
                    datasets: [{
                        label: 'Total inscritos',
                        data: counts,
                        backgroundColor: getChartColors(labels.length, 'doughnut'),
                        hoverOffset: 8,
                        borderColor: isDarkMode() ? '#1e293b' : '#fff',
                        borderWidth: 2,
                    }]
                };
                options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20, color: getChartFontColor(), font: { size: 11 } } },
                        tooltip: getCommonChartOptions().plugins.tooltip
                    }
                };
                break;

            case 'line':
                data = {
                    labels,
                    datasets: [{
                        label: 'Total inscritos',
                        data: counts,
                        backgroundColor: getChartColors(1, 'line').replace(/0\.[89]/, '0.2'),
                        borderColor: getChartColors(1, 'line'),
                        borderWidth: 2, tension: 0.3,
                        pointBackgroundColor: getChartColors(1, 'line'),
                        pointBorderColor: isDarkMode() ? '#1e293b' : '#fff',
                        pointHoverBackgroundColor: isDarkMode() ? '#fff' : '#1e293b',
                        pointHoverBorderColor: getChartColors(1, 'line'),
                        fill: true,
                    }]
                };
                options = getCommonChartOptions(true);
                break;
        }

        chartInstances[canvasId] = new Chart(ctx, { type, data, options });
    }

    function renderAllCharts() {
        renderChart('participantsByProgramChart', 'bar');
        renderChart('participantsByPlaceChart', 'doughnut');
        renderChart('newParticipantsByMonthChart', 'line');
    }

    renderAllCharts();

    const observer = new MutationObserver(() => renderAllCharts());
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    const anioActualEl = document.getElementById('anio-actual');
    if(anioActualEl) {
       anioActualEl.textContent = new Date().getFullYear();
    }
});
