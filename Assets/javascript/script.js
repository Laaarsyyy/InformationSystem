// CHART--------------------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const rootStyles = getComputedStyle(document.documentElement);
    const bgColor = rootStyles.getPropertyValue('--second-bg-color').trim();
    const borderColor = rootStyles.getPropertyValue('--text').trim(); 

    const ctx = document.getElementById('salesChart').getContext('2d');

    fetch('/Neverlonely/admin/getChartData.php')
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Sales",
                        data: data,
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
        })
        .catch(error => console.error('Chart fetch error:', error));
});

// ADD PRODUCT MODAL -----------------------------------------------------------
function openModal() {
    document.getElementById('productModal').style.display = 'block';
}

 // Close // ADD PRODUCT MODAL -------------------------------------------------------------------
    function closeModal() {
    document.getElementById('productModal').style.display = 'none';
    }

window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
};

//ADD PRODUCT ADDING VARIANT
function addVariant() {
    const container = document.getElementById("variant-container");

    const group = document.createElement("div");
    group.className = "variant-group";

    group.innerHTML = `
        <label>Size and Stocks</label>
        <input type="text" class="slim-input" name="sizes[]" placeholder="Size (e.g. M)" required>
        <input type="number" class="slim-input" name="stocks[]" placeholder="Stock Quantity" required>
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

function showConfirmModal(deleteUrl, variantSize) {
    document.getElementById("confirmText").innerText = `Are you sure you want to delete size "${variantSize}"?`;
    document.getElementById("confirmDeleteBtn").onclick = function () {
        window.location.href = deleteUrl;
    };
    document.getElementById("confirmDeleteModal").style.display = "block";
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

//-------------------------------------------TRANSACTION MODAL------------------------------------
function openTransactionModal() {
    document.getElementById("transactionModal").style.display = "flex";
    updateAllSubtotals();
}

function closeTransactionModal() {
    document.getElementById("transactionModal").style.display = "none";
}

function addTransactionItem() {
    const container = document.getElementById("transaction-items");
    const template = container.querySelector(".transaction-item-group").cloneNode(true);

    template.querySelector("select").selectedIndex = 0;
    template.querySelector("input").value = 1;
    template.querySelector(".transaction-subtotal").textContent = "0.00";

    container.appendChild(template);
    updateAllSubtotals();
}

function removeTransactionItem(btn) {
    const container = document.getElementById("transaction-items");
    if (container.children.length > 1) {
        btn.parentElement.remove();
        updateAllSubtotals();
    }
}

function updateSubtotal(el) {
    const itemGroup = el.closest(".transaction-item-group");
    const select = itemGroup.querySelector("select");
    const quantity = parseInt(itemGroup.querySelector("input").value) || 0;
    const price = parseFloat(select.selectedOptions[0].getAttribute("data-price")) || 0;

    const subtotal = price * quantity;
    itemGroup.querySelector(".transaction-subtotal").textContent = subtotal.toFixed(2);

    updateTotal();
}

function updateAllSubtotals() {
    document.querySelectorAll(".transaction-item-group").forEach(group => {
        updateSubtotal(group.querySelector("input"));
    });
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll(".transaction-subtotal").forEach(span => {
        total += parseFloat(span.textContent);
    });
    document.getElementById("transaction-total").textContent = total.toFixed(2);
}

function validateTransaction() {
    updateAllSubtotals();
    return true;
}
