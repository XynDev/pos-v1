import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm.js';

import { Chart, registerables } from 'chart.js';
import flatpickr from 'flatpickr';
import './bootstrap';

Chart.register(...registerables);

Chart.defaults.font.family = '"Inter", sans-serif';
Chart.defaults.font.weight = 500;
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.displayColors = false;
Chart.defaults.plugins.tooltip.mode = 'nearest';
Chart.defaults.plugins.tooltip.intersect = false;
Chart.defaults.plugins.tooltip.position = 'nearest';
Chart.defaults.plugins.tooltip.caretSize = 0;
Chart.defaults.plugins.tooltip.caretPadding = 20;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.padding = 8;

Chart.register({
    id: 'chartAreaPlugin',
    beforeDraw: (chart) => {
        if (chart.config.options?.chartArea?.backgroundColor) {
            const ctx = chart.canvas.getContext('2d');
            const { chartArea } = chart;
            ctx.save();
            ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
            ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
            ctx.restore();
        }
    },
});

const initializePageScripts = () => {

    const lightSwitches = document.querySelectorAll('.light-switch');
    if (lightSwitches.length > 0) {
        lightSwitches.forEach(lightSwitch => {
            lightSwitch.addEventListener('change', () => {
                const isDarkMode = lightSwitch.checked;
                localStorage.setItem('dark-mode', isDarkMode ? 'true' : 'false');

                if (isDarkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            });
        });
    }

    flatpickr('.datepicker', {
        mode: 'range',
        static: true,
        monthSelectorType: 'static',
        dateFormat: 'M j, Y',
        defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
        prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
        nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
    });

    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        if (window.mySalesChart instanceof Chart) {
            window.mySalesChart.destroy();
        }
        const salesDataElement = document.getElementById('salesChartData');
        if (salesDataElement) {
            const salesData = JSON.parse(salesDataElement.textContent);
            const ctx = salesChartCanvas.getContext('2d');
            window.mySalesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: salesData.data,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } } },
                    plugins: { tooltip: { callbacks: { label: context => `Total Penjualan: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed.y)}` } } }
                }
            });
        }
    }
};

document.addEventListener('DOMContentLoaded', initializePageScripts);

document.addEventListener('livewire:navigated', () => {
    setTimeout(initializePageScripts, 1);
});

Livewire.start();
