document.addEventListener('DOMContentLoaded', () => {
    // Get the CSS variables
    const rootStyles = getComputedStyle(document.documentElement);
    const bgColor = rootStyles.getPropertyValue('--second-bg-color').trim(); // fill color
    const borderColor = rootStyles.getPropertyValue('--text').trim(); // line color

    // Initialize the chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Sales",
                data: [34, 18, 35, 40],
                color: borderColor,
                borderColor: borderColor,
                backgroundColor: bgColor,
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
    });
});

// Modal
function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

    function closeModal() {
    document.getElementById('productModal').style.display = 'none';
    }

  // Close Modal
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
};

function addVariant() {
    const container = document.getElementById('variant-container');
    const group = document.createElement('div');
    group.classList.add('variant-group');
    group.innerHTML = `
                    <label>Size and Stocks
                        <input type="text" name="sizes[]" placeholder="Size (e.g. S)" required>
                        <input type="number" name="stocks[]" placeholder="Stock Quantity" required>
                    </label>
        `;
        container.appendChild(group);
}

