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

 // Close Modal
    function closeModal() {
    document.getElementById('productModal').style.display = 'none';
    }

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
};

//FOR ADD PRODUCT ADDING VARIANT
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
//EDIT VARIANT MODAL
function addExtraVariant() {
    const container = document.getElementById("extra-variants-container");

    const div = document.createElement("div");
    div.classList.add("variant-group");

    div.innerHTML = `
        <label>Size and Stocks</label>
        <input type="text" name="extra_sizes[]" placeholder="Size (e.g. L)" class="slim-input" required>
        <input type="number" name="extra_stocks[]" placeholder="Stock Quantity" class="slim-input" required>
        <button type="button" class="removeSize-btn" onclick="this.parentElement.remove()">
            <span class="material-icons">cancel</span>
        </button>
    `;

    container.appendChild(div);
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

function openEditModal(variantId) {
    const sizeText = document.querySelector(`#size-${variantId}`).innerText;
    const stockText = document.querySelector(`#stock-${variantId}`).innerText;

    // Remove ' pcs' from stock
    const cleanStock = parseInt(stockText.replace(' pcs', ''));

    document.getElementById('editVariantId').value = variantId;
    document.getElementById('editSize').value = sizeText;
    document.getElementById('editStock').value = cleanStock;

    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}


let deleteUrl = '';

function showConfirmModal(url, message) {
    deleteUrl = url;
    document.getElementById('confirmText').textContent = message;
    document.getElementById('confirmDeleteModal').style.display = 'block';
}

function closeConfirmModal() {
    document.getElementById('confirmDeleteModal').style.display = 'none';
    deleteUrl = '';
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (deleteUrl) {
        window.location.href = deleteUrl;
    }
});

function openEditModal(variantId, size, stock, productId) {
    document.getElementById('editVariantId').value = variantId;
    document.getElementById('editSize').value = size;
    document.getElementById('editStock').value = stock;
    document.getElementById('editProductId').value = productId;
    document.getElementById('extra-variants-container').innerHTML = ''; // Clear previous inputs
    document.getElementById('editModal').style.display = 'block';
}