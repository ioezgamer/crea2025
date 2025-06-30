import Chart from 'chart.js/auto';

// Componente de Alpine para manejar todos los gráficos del dashboard
export default (programData, placeData, monthlyData) => ({
    // State: Almacena los datos y las instancias de los gráficos
    programChartData: programData,
    placeChartData: placeData,
    monthlyChartData: monthlyData,
    chartInstances: {},
    isDarkMode: document.documentElement.classList.contains('dark'),

    // Se ejecuta una vez cuando Alpine inicializa el componente
    init() {
        // Renderiza todos los gráficos por primera vez.
        this.refreshAllCharts();

        // Observador para detectar cambios en el modo oscuro y refrescar los gráficos.
        const observer = new MutationObserver(() => {
            this.isDarkMode = document.documentElement.classList.contains('dark');
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // $watch de Alpine: Cuando la propiedad isDarkMode cambie, vuelve a renderizar todo.
        this.$watch('isDarkMode', () => this.refreshAllCharts());
    },

    // --- LÓGICA DE (RE)DIBUJADO ---
    refreshAllCharts() {
        // $nextTick asegura que el DOM esté listo antes de intentar dibujar. Es crucial.
        this.$nextTick(() => {
            this.renderChart(this.$refs.participantsByProgramChart, 'bar', this.programChartData);
            this.renderChart(this.$refs.participantsByPlaceChart, 'doughnut', this.placeChartData);
            this.renderChart(this.$refs.newParticipantsByMonthChart, 'line', this.monthlyChartData);
        });
    },

    // Función genérica para renderizar un gráfico
    renderChart(canvas, type, data) {
        if (!canvas) return; // Salida segura si el canvas no existe

        const chartId = canvas.getAttribute('x-ref');
        if (this.chartInstances[chartId]) {
            this.chartInstances[chartId].destroy(); // Destruir gráfico antiguo
        }

        const labels = Object.keys(data);
        const counts = Object.values(data);

        if (labels.length === 0) return; // No renderizar si no hay datos

        // Configuración para cada tipo de gráfico
        const chartConfig = this.getChartConfig(type, labels, counts);

        if(chartConfig) {
            this.chartInstances[chartId] = new Chart(canvas, chartConfig);
        }
    },

    // --- CONFIGURACIONES Y HELPERS ---

    // Centraliza la creación de la configuración del gráfico
    getChartConfig(type, labels, counts) {
        let config;
        switch (type) {
            case 'bar':
                config = {
                    type: 'bar',
                    data: { labels, datasets: [{ label: 'Total inscritos', data: counts, backgroundColor: this.getChartColors(labels.length, 'bar'), borderColor: this.getChartColors(labels.length, 'bar').map(c => c.replace(/0\.\d+/, '1')), borderWidth: 1, borderRadius: 6 }] },
                    options: this.getCommonChartOptions()
                };
                break;
            case 'doughnut':
                config = {
                    type: 'doughnut',
                    data: { labels, datasets: [{ label: 'Total inscritos', data: counts, backgroundColor: this.getChartColors(labels.length, 'doughnut'), hoverOffset: 8, borderColor: this.isDarkMode ? '#1e293b' : '#fff', borderWidth: 2 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 20, color: this.getChartFontColor(), font: { size: 11 } } }, tooltip: this.getCommonChartOptions().plugins.tooltip } }
                };
                break;
            case 'line':
                config = {
                    type: 'line',
                    data: { labels, datasets: [{ label: 'Total inscritos', data: counts, backgroundColor: this.getChartColors(1, 'line').replace(/0\.\d+/, '0.2'), borderColor: this.getChartColors(1, 'line'), borderWidth: 2, tension: 0.3, pointBackgroundColor: this.getChartColors(1, 'line'), pointBorderColor: this.isDarkMode ? '#1e293b' : '#fff', pointHoverBackgroundColor: this.isDarkMode ? '#fff' : '#1e293b', pointHoverBorderColor: this.getChartColors(1, 'line'), fill: true }] },
                    options: this.getCommonChartOptions(true)
                };
                break;
        }
        return config;
    },

    getCommonChartOptions(isLineChart = false) {
        const options = {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, color: this.getChartFontColor() }, grid: { color: this.getGridLineColor() } },
                x: { ticks: { color: this.getChartFontColor() }, grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: this.getTooltipBgColor(), titleColor: this.getTooltipTitleColor(), bodyColor: this.getTooltipBodyColor(), borderColor: this.getTooltipBorderColor(), borderWidth: 1, padding: 10, cornerRadius: 6 }
            }
        };
        if (isLineChart) {
            options.plugins.tooltip.mode = 'index';
            options.plugins.tooltip.intersect = false;
            options.hover = { mode: 'nearest', intersect: true };
        }
        return options;
    },

    getChartFontColor() { return this.isDarkMode ? '#cbd5e1' : '#374151'; },
    getGridLineColor() { return this.isDarkMode ? 'rgba(71, 85, 105, 0.5)' : 'rgba(203, 213, 225, 0.5)'; },
    getTooltipBgColor() { return this.isDarkMode ? 'rgba(30, 41, 59, 0.9)' : 'rgba(255,255,255,0.9)'; },
    getTooltipTitleColor() { return this.isDarkMode ? '#f1f5f9' : '#334155'; },
    getTooltipBodyColor() { return this.isDarkMode ? '#e2e8f0' : '#475569'; },
    getTooltipBorderColor() { return this.isDarkMode ? '#475569' : '#e2e8f0'; },
    getChartColors(numColors, type = 'bar') {
        const baseColorsLight = ['rgba(106, 90, 205, 0.7)', 'rgba(60, 179, 113, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(245, 158, 11, 0.7)', 'rgba(239, 68, 68, 0.7)'];
        const baseColorsDark = ['rgba(99, 102, 241, 0.8)', 'rgba(192, 132, 252, 0.8)', 'rgba(244, 114, 182, 0.8)', 'rgba(96, 165, 250, 0.8)', 'rgba(52, 211, 153, 0.8)', 'rgba(251, 191, 36, 0.8)'];
        const lineChartColorLight = 'rgba(75, 59, 255, 0.8)';
        const lineChartColorDark = 'rgba(99, 102, 241, 0.9)';
        if (type === 'line') return this.isDarkMode ? lineChartColorDark : lineChartColorLight;
        const selectedPalette = this.isDarkMode ? baseColorsDark : baseColorsLight;
        return Array.from({ length: numColors }, (_, i) => selectedPalette[i % selectedPalette.length]);
    },
});
