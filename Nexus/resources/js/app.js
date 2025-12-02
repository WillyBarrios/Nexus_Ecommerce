import './bootstrap';

import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

document.addEventListener("DOMContentLoaded", () => {
    // Código del carrusel existente...
    const carousel = document.getElementById("carouselProducts");
    const btnRight = document.getElementById("btnRight");
    const btnLeft = document.getElementById("btnLeft");

    if (carousel) {
        const cardWidth = 280; // tamaño + gap aprox

        btnRight.addEventListener("click", () => {
            carousel.scrollBy({ left: cardWidth, behavior: "smooth" });
        });

        btnLeft.addEventListener("click", () => {
            carousel.scrollBy({ left: -cardWidth, behavior: "smooth" });
        });
    }

    // --- Gráficos del Panel de Administración ---

    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        const ctx = salesChartCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['16 Oct', '19 Oct', '22 Oct', '25 Oct', '28 Oct', '31 Oct', '03 Nov', '06 Nov', '09 Nov', '12 Nov', '15 Nov'],
                datasets: [{
                    label: 'Ventas',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 1400, 1200],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1600
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    const visitorsChartCanvas = document.getElementById('visitorsChart');
    if (visitorsChartCanvas) {
        const ctx = visitorsChartCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['16 Oct', '19 Oct', '22 Oct', '25 Oct', '28 Oct', '31 Oct', '03 Nov', '06 Nov', '09 Nov', '12 Nov', '15 Nov'],
                datasets: [{
                    label: 'Visitantes',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(236, 239, 241, 0.5)',
                    borderColor: 'rgba(117, 117, 117, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1.0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});