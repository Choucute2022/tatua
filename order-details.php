<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .main-container {
            display: flex;
            justify-content: space-between;
            max-width: 800px;
            margin: 0 auto;
            gap: 20px;
        }
        .container, .delivery-info {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .delivery-info h2, .container h2 {
            margin: 0 0 20px;
            font-size: 18px;
        }
        .delivery-info .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .delivery-info .info-item img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        .delivery-info select, .delivery-info input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .delivery-info textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .item img {
            width: 80px;
            height: 80px;
            margin-right: 10px;
            border-radius: 5px;
        }
        .item-details h3 {
            margin: 0;
            font-size: 16px;
        }
        .item-details p {
            margin: 5px 0;
            color: #555;
        }
        .promo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .promo input {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .promo button {
            padding: 5px 10px;
            background-color: #f5e6cc;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary p {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .notes {
            margin-bottom: 20px;
        }
        .notes textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .order-btn {
            width: 100%;
            padding: 10px;
            background-color: #ff5733;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .continue-shopping {
            width: 100%;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Thông tin giao hàng -->
        <div class="delivery-info">
            <h2>Thông tin giao hàng</h2>
            <div class="info-item">
                <img src="https://via.placeholder.com/20" alt="Icon">
                <input type="text" placeholder="Tên nguồn hàng" id="source-name">
            </div>
            <div class="info-item">
                <img src="https://via.placeholder.com/20" alt="Icon">
                <input type="text" placeholder="Số điện thoại nguồn hàng" id="source-phone">
            </div>
            <div class="info-item">
                <img src="https://via.placeholder.com/20" alt="Icon">
                <input type="text" placeholder="Địa chỉ nguồn hàng" id="source-address">
            </div>
            <div class="info-item">
                <img src="https://via.placeholder.com/20" alt="Icon">
                <textarea placeholder="Ghi chú địa chỉ..." id="address-notes"></textarea>
            </div>
            <p>Giao hàng 20:30 - hôm nay 04/05/2025</p>
            <h2>Phương thức thanh toán</h2>
            <select id="payment-method">
                <option value="">Chọn phương thức thanh toán</option>
                <option value="cash">Tiền mặt</option>
                <option value="card">Thẻ tín dụng</option>
            </select>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="container">
            <div class="header">
                <h2>Chọn cửa hàng</h2>
                <select id="store-select">
                    <option>Cửa hàng A</option>
                    <option>Cửa hàng B</option>
                </select>
            </div>
            <div id="order-items">
                <!-- Danh sách sản phẩm sẽ được thêm động bằng JavaScript -->
            </div>
            <div class="promo">
                <input type="text" placeholder="Mã khuyến mãi" id="promo-code">
                <button onclick="applyPromo()">Thêm khuyến mãi</button>
            </div>
            <div class="summary">
                <p>Số lượng cốc: <span id="total-quantity">0 cốc</span></p>
                <p>Tổng: <span id="subtotal">0đ</span></p>
                <p>Phí vận chuyển: <span id="shipping-fee">0đ</span></p>
                <p>Khuyến mãi: <span id="discount">0đ</span></p>
                <p>Tổng cộng: <span id="total">0đ</span></p>
            </div>
            <div class="notes">
                <textarea placeholder="Thêm ghi chú..." id="notes"></textarea>
            </div>
            <button class="order-btn" onclick="placeOrder()">ĐẶT HÀNG</button>
            <button class="continue-shopping" onclick="continueShopping()">TIẾP TỤC MUA HÀNG</button>
        </div>
    </div>

    <script>
        // Lấy dữ liệu giỏ hàng từ localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let discount = 0;

        // Hàm khởi tạo: Hiển thị dữ liệu từ giỏ hàng
        function init() {
            const orderItems = document.querySelector("#order-items");
            if (cart.length === 0) {
                orderItems.innerHTML = "<p>Chưa có sản phẩm nào!</p>";
                return;
            }

            orderItems.innerHTML = "";
            cart.forEach(item => {
                const itemElement = document.createElement("div");
                itemElement.className = "item";
                itemElement.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <div class="item-details">
                        <h3>${item.name} (${item.sizeLevel || 'M'})</h3>
                        <p>${item.sugarLevel} đường, ${item.iceLevel} đá, ${item.toppings}</p>
                        <p>${item.price.toLocaleString('vi-VN')}đ x ${item.quantity} = ${item.totalPrice.toLocaleString('vi-VN')}đ</p>
                    </div>
                `;
                orderItems.appendChild(itemElement);
            });

            updateSummary();
        }

        // Cập nhật thông tin tổng quan
        function updateSummary() {
            let totalQuantity = 0;
            let subtotal = 0;

            cart.forEach(item => {
                totalQuantity += item.quantity;
                subtotal += item.totalPrice;
            });

            document.querySelector("#total-quantity").textContent = totalQuantity + " cốc";
            document.querySelector("#subtotal").textContent = subtotal.toLocaleString('vi-VN') + "đ";
            updateTotal();
        }

        // Áp dụng mã khuyến mãi (giả lập)
        function applyPromo() {
            const promoCode = document.querySelector("#promo-code").value;
            discount = 0;
            if (promoCode === "DISCOUNT10") { // Ví dụ mã giảm giá
                discount = 10000;
            }
            document.querySelector("#discount").textContent = discount.toLocaleString('vi-VN') + "đ";
            updateTotal();
        }

        // Cập nhật tổng cộng
        function updateTotal() {
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += item.totalPrice;
            });
            const shipping = parseInt(document.querySelector("#shipping-fee").textContent) || 0;
            const total = subtotal + shipping - discount;
            document.querySelector("#total").textContent = total.toLocaleString('vi-VN') + "đ";
        }

        // Xử lý nút "Đặt hàng"
        function placeOrder() {
            const notes = document.querySelector("#notes").value;
            const promoCode = document.querySelector("#promo-code").value;
            const store = document.querySelector("#store-select").value;
            const sourceName = document.querySelector("#source-name").value;
            const sourcePhone = document.querySelector("#source-phone").value;
            const sourceAddress = document.querySelector("#source-address").value;
            const addressNotes = document.querySelector("#address-notes").value;
            const paymentMethod = document.querySelector("#payment-method").value;
            const subtotal = parseInt(document.querySelector("#subtotal").textContent.replace(/[^0-9]/g, '')) || 0;
            const shipping = parseInt(document.querySelector("#shipping-fee").textContent.replace(/[^0-9]/g, '')) || 0;
            const total = parseInt(document.querySelector("#total").textContent.replace(/[^0-9]/g, '')) || 0;

            if (!sourceName || !sourcePhone || !sourceAddress || !paymentMethod) {
                alert("Vui lòng điền đầy đủ thông tin giao hàng và chọn phương thức thanh toán!");
                return;
            }

            const orderData = {
                cart: cart,
                notes: notes,
                promoCode: promoCode,
                discount: discount,
                subtotal: subtotal,
                shipping: shipping,
                total: total,
                store: store,
                sourceName: sourceName,
                sourcePhone: sourcePhone,
                sourceAddress: sourceAddress,
                addressNotes: addressNotes,
                paymentMethod: paymentMethod
            };

            // Gửi dữ liệu đơn hàng qua AJAX
            fetch('save_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                    // Xóa giỏ hàng sau khi đặt hàng thành công
                    localStorage.removeItem('cart');
                    cart = [];
                    // Chuyển hướng về trang chính
                    window.location.href = 'duan1.php';
                } else {
                    alert("Lỗi: " + data.message);
                }
            })
            .catch(error => {
                alert("Lỗi khi gửi đơn hàng: " + error);
            });
        }

        // Xử lý nút "Tiếp tục mua hàng"
        function continueShopping() {
            window.location.href = 'duan1.php'; // Quay lại trang chính
        }

        // Khởi tạo giao diện
        init();
    </script>
</body>
</html>