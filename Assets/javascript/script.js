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
    const container = document.getElementById("variant-container");

    const group = document.createElement("div");
    group.className = "variant-group";

    group.innerHTML = `
        <label>Size and Stocks</label>
        <input type="text" name="sizes[]" placeholder="Size (e.g. M)" required>
        <input type="number" name="stocks[]" placeholder="Stock Quantity" required>
        <button type="button" onclick="removeLastVariant()" class="removeSize-btn"><span class="material-icons">cancel</span></button>
    `;

    container.appendChild(group);
}

function removeLastVariant() {
    const container = document.getElementById("variant-container");
    const groups = container.querySelectorAll(".variant-group");

    if (groups.length > 1) {
        container.removeChild(groups[groups.length - 1]);
    } else {
        alert("You must have at least one size.");
    }
}
