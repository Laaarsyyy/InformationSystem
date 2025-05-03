document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("salesChart");

    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    const salesChart = new Chart(ctx, {
        type: "line", 
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Sales",
                data: [34, 18, 35, 40, 45, 60],
                borderColor: 'rgb(0, 0, 0)',
                backgroundColor: 'rgba(227, 227, 227, 0.5)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    })
})