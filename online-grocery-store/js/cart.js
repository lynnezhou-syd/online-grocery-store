document.addEventListener('DOMContentLoaded', function () {
    updateCartTotal();
    updateCartCount();
});

function updateQuantity(productId, newQuantity) {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&product_id=${productId}&quantity=${newQuantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error updating quantity:', error));
}



// 更新购物车数量的函数（保持不变）
function updateCartQuantity(productId, quantity) {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=update&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            if (quantity === 0) {
                removeItemFromCart(productId);
            }
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error updating cart quantity:", error));
}

// 从购物车中移除商品（调用后端移除逻辑）
function removeItem(productId) {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=remove&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 从 DOM 中移除对应行（如果存在）
            let productRow = document.getElementById(`row-${productId}`);
            if (productRow) productRow.remove();

            // 更新购物车总金额和数量
            updateCartTotal();
            updateCartCount();
            updateCartUI();
        
        }
    })
    .catch(error => console.error("Error removing item:", error));
}

// 内部调用的移除函数（数量为 0 时调用）
function removeItemFromCart(productId) {
    removeItem(productId);
}

// 更新右上角购物车数量
function updateCartCount() {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=count'
    })
    .then(response => response.json())
    .then(data => {
        let cartCountElement = document.getElementById('cart-total');
        if (cartCountElement) {
            cartCountElement.innerText = data.count;
        }
    })
    .catch(error => console.error("Error fetching cart count:", error));
}

// 更新购物车总金额
function updateCartTotal() {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=total'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const totalAmountElement = document.getElementById('totalAmount');
            if (totalAmountElement) {
                totalAmountElement.textContent = `$${data.total.toFixed(2)}`;
            }
        }
    })
    .finally(() => checkCartStatus())
    .catch(error => console.error("Error fetching total:", error));
}


// 清空购物车（调用后端逻辑并更新前端）
function clearCart() {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=clear'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 清空购物车表格
            const cartTable = document.getElementById('cartTable');
            if (cartTable) {
                cartTable.innerHTML = '<tr><td colspan="6">Your cart is empty</td></tr>';
            }

            // 更新总金额显示为 $0.00
            const totalAmountElement = document.getElementById('totalAmount');
            if (totalAmountElement) {
                totalAmountElement.textContent = '$0.00';
            }

            // 更新购物车数量显示为 0
            const cartTotalElement = document.getElementById('cart-total');
            if (cartTotalElement) {
                cartTotalElement.innerText = '0';
            }

            // 隐藏购物车侧边栏（如果存在）
            let sidebar = document.getElementById('cartSidebar');
            if (sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }

            checkCartStatus();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error clearing cart:", error));
}



// 直接调用后端接口将商品添加到购物车
function addToCart(productId) {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=add&product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error(error));
}


function checkCartStatus() {
    fetch('backend/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=get' // 获取购物车内容
    })
    .then(response => response.json())
    .then(data => {
        const checkoutButton = document.getElementById('checkoutButton');
        if (checkoutButton) {
            if (data.cart && data.cart.length > 0) {
                checkoutButton.disabled = false; // 购物车不为空，启用按钮
                checkoutButton.style.backgroundColor = ''; // 移除灰色背景
                checkoutButton.style.color = ''; // 移除灰色文字
            } else {
                checkoutButton.disabled = true; // 购物车为空，禁用按钮
                checkoutButton.style.backgroundColor = 'gray'; // 设置灰色背景
                checkoutButton.style.color = 'darkgray'; // 设置灰色文字
            }
        }
    })
    .catch(error => console.error("Error checking cart status:", error));
}

// 更新库存状态显示
function updateStockStatus(productId, remainingStock) {
    const stockStatusElement = document.getElementById(`stock-status-${productId}`);
    if (stockStatusElement) {
        if (remainingStock > 0) {
            stockStatusElement.classList.remove('out-of-stock', 'in-stock-default');
            stockStatusElement.classList.add('in-stock');
        } else {
            stockStatusElement.textContent = 'Out of Stock';
            stockStatusElement.classList.remove('in-stock', 'in-stock-default');
            stockStatusElement.classList.add('out-of-stock');
        }
    } else {
        console.error(`Element with id 'stock-status-${productId}' not found.`);
    }
}



function updateProductStock(productId, quantityToDeduct) { // 参数名更明确
    fetch('backend/update_stock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            product_id: productId,
            quantity: quantityToDeduct // 这里应该是要减去的数量
        })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert("库存更新失败: " + data.message);
            // 回滚UI状态
            document.getElementById(`add-to-cart-${productId}`).style.display = 'block';
            document.getElementById(`quantity-selector-${productId}`).style.display = 'none';
        } else {
            // 更新前端显示
            const currentStock = parseInt(document.getElementById(`stock-status-${productId}`).dataset.stock);
            const newStock = currentStock - quantityToDeduct;
            document.getElementById(`stock-status-${productId}`).dataset.stock = newStock;
            updateStockStatus(productId, newStock);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("网络错误，请刷新页面重试");
    });
}

