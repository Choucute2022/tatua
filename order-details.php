<?php
session_start();

// Khởi tạo CSRF token nếu chưa có
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$shipping_fee = 0; // Phí vận chuyển được đặt về 0
$discount = 0; // Khuyến mãi mặc định

// Ngày hiện tại và ngày mai
$current_date = date('d/m/Y'); // Định dạng: 23/05/2025
$tomorrow_date = date('d/m/Y', strtotime('+1 day')); // Định dạng: 24/05/2025
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --orange: #ff9800;
            --orange-dark: #ef6c00;
            --yellow-light: #fff3e0;
            --white: #ffffff;
            --gray: #555;
        }

        body {
            font-family: 'Quicksand', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--yellow-light);
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            max-width: 1000px;
            margin: auto;
            gap: 24px;
        }

        .container, .delivery-info {
            flex: 1;
            background-color: var(--white);
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .delivery-info h2, .container h2 {
            margin: 0 0 20px;
            font-size: 20px;
            color: var(--orange-dark);
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-item i {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            color: var(--gray);
            font-size: 20px;
            display: inline-block;
            line-height: 20px;
            text-align: center;
        }

        .info-item input, .info-item textarea,
        .delivery-info select, .notes textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Quicksand', sans-serif;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-left: 30px;
            display: none;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .item-details h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .item-details p {
            margin: 4px 0;
            color: var(--gray);
            font-size: 14px;
        }

        .promo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .promo input {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .promo button {
            padding: 10px 16px;
            background-color: var(--orange);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .summary p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 15px;
        }

        .order-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--orange-dark);
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .order-btn:hover {
            background-color: #e65100;
        }

        .continue-shopping {
            width: 100%;
            padding: 12px;
            background-color: transparent;
            border: 2px solid var(--orange);
            border-radius: 10px;
            text-align: center;
            color: var(--orange-dark);
            font-weight: 600;
            font-size: 16px;
            font-family: 'Quicksand', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .continue-shopping:hover {
            background-color: var(--orange);
            color: var(--white);
        }

        select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Thông tin giao hàng -->
        <div class="delivery-info">
            <h2>Thông tin giao hàng</h2>
            <div class="info-item">
                <i class="fa-solid fa-user"></i>
                <input type="text" placeholder="Tên người nhận" id="source-name" required>
                <span class="error" id="source-name-error"></span>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-phone"></i>
                <input type="tel" placeholder="Số điện thoại" id="source-phone" required>
                <span class="error" id="source-phone-error"></span>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" placeholder="Địa chỉ giao hàng" id="source-address" required>
                <span class="error" id="source-address-error"></span>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-note-sticky"></i>
                <textarea placeholder="Ghi chú địa chỉ..." id="address-notes"></textarea>
                <span class="error" id="address-notes-error"></span>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-calendar-day"></i>
                <select id="delivery-date" required>
                    <option value="">Chọn ngày giao hàng</option>
                    <option value="<?php echo $current_date; ?>" selected>Hôm nay (<?php echo $current_date; ?>)</option>
                    <option value="<?php echo $tomorrow_date; ?>">Ngày mai (<?php echo $tomorrow_date; ?>)</option>
                </select>
                <span class="error" id="delivery-date-error"></span>
            </div>
            <div class="info-item">
                <i class="fa-solid fa-clock"></i>
                <select id="delivery-time" required>
                    <option value="">Chọn giờ giao hàng</option>
                </select>
                <span class="error" id="delivery-time-error"></span>
            </div>
            <h2>Phương thức thanh toán</h2>
            <select id="payment-method" required>
                <option value="">Chọn phương thức thanh toán</option>
                <option value="cash">Tiền mặt</option>
                <option value="card">Thẻ tín dụng</option>
            </select>
            <span class="error" id="payment-method-error"></span>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="container">
            <div class="header">
                <h2>Chọn cửa hàng</h2>
                <select id="store-select">
                    <option value="">Chọn cửa hàng</option>
                    <option value="store-a">Cửa hàng A</option>
                    <option value="store-b">Cửa hàng B</option>
                </select>
                <span class="error" id="store-select-error"></span>
            </div>
            <div id="order-items">
                <?php if (empty($_SESSION['cart'])): ?>
                    <p>Chưa có sản phẩm nào!</p>
                <?php else: ?>
                    <?php
                    $total_quantity = 0;
                    $subtotal = 0;
                    foreach ($_SESSION['cart'] as $item):
                        $total_quantity += $item['quantity'];
                        $subtotal += $item['totalPrice'];
                    ?>
                        <div class="item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?> (<?php echo htmlspecialchars($item['sizeLevel']); ?>)</h3>
                                <p><?php echo htmlspecialchars($item['sugarLevel']); ?> đường, <?php echo htmlspecialchars($item['iceLevel']); ?> đá, <?php echo htmlspecialchars($item['toppings']); ?></p>
                                <p><?php echo number_format($item['price'], 0, ',', '.'); ?>đ x <?php echo $item['quantity']; ?> = <?php echo number_format($item['totalPrice'], 0, ',', '.'); ?>đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="promo">
                <input type="text" placeholder="Mã khuyến mãi" id="promo-code">
                <button onclick="applyPromo()">Thêm khuyến mãi</button>
            </div>
            <div class="summary">
                <p>Số lượng cốc: <span id="total-quantity"><?php echo $total_quantity; ?> cốc</span></p>
                <p>Tổng: <span id="subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span></p>
                <p>Khuyến mãi: <span id="discount"><?php echo number_format($discount, 0, ',', '.'); ?>đ</span></p>
                <p>Tổng cộng: <span id="total"><?php echo number_format($subtotal - $discount, 0, ',', '.'); ?>đ</span></p>
            </div>
            <div class="notes">
                <textarea placeholder="Thêm ghi chú..." id="notes"></textarea>
            </div>
            <div>
                <button class="order-btn" onclick="placeOrder()">ĐẶT HÀNG</button>
                <button class="continue-shopping" onclick="window.location.href='duan1.php'">TIẾP TỤC MUA HÀNG</button>
            </div>
        </div>
    </div>

    <script>
        let cart = <?php echo json_encode($_SESSION['cart']); ?>;
        let discount = <?php echo $discount; ?>;
        const shippingFee = <?php echo $shipping_fee; ?>;
        const currentHour = <?php echo (int)date('H'); ?>;
        const currentMinute = <?php echo (int)date('i'); ?>;
        const currentDate = '<?php echo $current_date; ?>';

        // Tạo danh sách giờ giao hàng từ 09:00 đến 22:00
        const deliveryTimeSelect = document.querySelector("#delivery-time");
        const deliveryDateSelect = document.querySelector("#delivery-date");

        function updateDeliveryTimes() {
            const selectedDate = deliveryDateSelect.value;
            deliveryTimeSelect.innerHTML = '<option value="">Chọn giờ giao hàng</option>';

            const startHour = 9;
            const endHour = 22;

            for (let hour = startHour; hour <= endHour; hour++) {
                for (let minute = 0; minute <= 30; minute += 30) {
                    const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                    // Nếu ngày được chọn là hôm nay, chỉ hiển thị các giờ sau thời gian hiện tại
                    if (selectedDate === currentDate) {
                        if (hour > currentHour || (hour === currentHour && minute >= currentMinute)) {
                            const option = document.createElement('option');
                            option.value = time;
                            option.textContent = time;
                            deliveryTimeSelect.appendChild(option);
                        }
                    } else {
                        // Nếu là ngày mai, hiển thị tất cả giờ
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        deliveryTimeSelect.appendChild(option);
                    }
                }
            }

            // Mặc định chọn giờ gần nhất nếu là hôm nay
            if (selectedDate === currentDate && deliveryTimeSelect.options.length > 1) {
                deliveryTimeSelect.selectedIndex = 1; // Chọn giờ đầu tiên khả dụng
            }
        }

        // Cập nhật giờ khi thay đổi ngày giao hàng
        deliveryDateSelect.addEventListener('change', updateDeliveryTimes);

        // Khởi tạo danh sách giờ khi tải trang
        updateDeliveryTimes();

        // Cập nhật thông tin tổng quan
        function updateSummary() {
            let totalQuantity = 0;
            let subtotal = 0;

            cart.forEach(item => {
                totalQuantity += item.quantity;
                subtotal += item.totalPrice;
            });

            document.querySelector("#total-quantity").textContent = totalQuantity + " cốc";
            document.querySelector("#subtotal").textContent = Number(subtotal).toLocaleString('vi-VN') + "đ";
            updateTotal();
        }

        // Áp dụng mã khuyến mãi
        function applyPromo() {
            const promoCode = document.querySelector("#promo-code").value;
            discount = 0;
            if (promoCode === "DISCOUNT10") {
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
            const total = subtotal - discount;
            document.querySelector("#total").textContent = Number(total).toLocaleString('vi-VN') + "đ";
        }

        // Xử lý nút đặt hàng
        function placeOrder() {
            const notes = document.querySelector("#notes").value;
            const promoCode = document.querySelector("#promo-code").value;
            const store = document.querySelector("#store-select").value;
            const sourceName = document.querySelector("#source-name").value.trim();
            const sourcePhone = document.querySelector("#source-phone").value.trim();
            const sourceAddress = document.querySelector("#source-address").value.trim();
            const addressNotes = document.querySelector("#address-notes").value.trim();
            const paymentMethod = document.querySelector("#payment-method").value;
            const deliveryDate = document.querySelector("#delivery-date").value;
            const deliveryTime = document.querySelector("#delivery-time").value;

            // Reset thông báo lỗi
            document.querySelectorAll('.error').forEach(error => {
                error.textContent = '';
                error.style.display = 'none';
            });

            // Kiểm tra các trường bắt buộc
            let isValid = true;

            if (!sourceName) {
                isValid = false;
                document.getElementById('source-name-error').textContent = 'Vui lòng điền Họ và tên.';
                document.getElementById('source-name-error').style.display = 'block';
            }

            if (!sourcePhone) {
                isValid = false;
                document.getElementById('source-phone-error').textContent = 'Vui lòng điền Số điện thoại.';
                document.getElementById('source-phone-error').style.display = 'block';
            } else if (!/^\d{10}$/.test(sourcePhone)) {
                isValid = false;
                document.getElementById('source-phone-error').textContent = 'Số điện thoại phải là số và có đúng 10 chữ số.';
                document.getElementById('source-phone-error').style.display = 'block';
            }

            if (!sourceAddress) {
                isValid = false;
                document.getElementById('source-address-error').textContent = 'Vui lòng điền Địa chỉ.';
                document.getElementById('source-address-error').style.display = 'block';
            }

            // Kiểm tra ghi chú địa chỉ (bắt buộc)
            if (!addressNotes) {
                isValid = false;
                document.getElementById('address-notes-error').textContent = 'Vui lòng điền Ghi chú địa chỉ.';
                document.getElementById('address-notes-error').style.display = 'block';
            }

            if (!deliveryDate) {
                isValid = false;
                document.getElementById('delivery-date-error').textContent = 'Vui lòng chọn Ngày giao hàng.';
                document.getElementById('delivery-date-error').style.display = 'block';
            }

            if (!deliveryTime) {
                isValid = false;
                document.getElementById('delivery-time-error').textContent = 'Vui lòng chọn Giờ giao hàng.';
                document.getElementById('delivery-time-error').style.display = 'block';
            } else if (deliveryDate === currentDate) {
                // Kiểm tra giờ giao hàng nếu ngày là hôm nay
                const [selectedHour, selectedMinute] = deliveryTime.split(':').map(Number);
                if (selectedHour < currentHour || (selectedHour === currentHour && selectedMinute < currentMinute)) {
                    isValid = false;
                    document.getElementById('delivery-time-error').textContent = 'Giờ giao hàng phải sau thời gian hiện tại.';
                    document.getElementById('delivery-time-error').style.display = 'block';
                }
            }

            if (!paymentMethod) {
                isValid = false;
                document.getElementById('payment-method-error').textContent = 'Vui lòng chọn Phương thức thanh toán.';
                document.getElementById('payment-method-error').style.display = 'block';
            }

            if (!store) {
                isValid = false;
                document.getElementById('store-select-error').textContent = 'Vui lòng chọn Cửa hàng.';
                document.getElementById('store-select-error').style.display = 'block';
            }

            // Kiểm tra giỏ hàng
            if (!cart || cart.length === 0) {
                isValid = false;
                alert('Giỏ hàng trống! Vui lòng thêm sản phẩm trước khi đặt hàng.');
            }

            // Nếu có lỗi, dừng lại
            if (!isValid) {
                return;
            }

            let subtotal = 0;
            cart.forEach(item => {
                subtotal += item.totalPrice;
            });
            const total = subtotal - discount;

            const orderData = {
                cart: cart,
                notes: notes,
                promoCode: promoCode,
                discount: discount,
                subtotal: subtotal,
                shipping: shippingFee,
                total: total,
                store: store,
                sourceName: sourceName,
                sourcePhone: sourcePhone,
                sourceAddress: sourceAddress,
                addressNotes: addressNotes,
                deliveryDate: deliveryDate,
                deliveryTime: deliveryTime,
                paymentMethod: paymentMethod,
                csrfToken: '<?php echo $_SESSION['csrf_token']; ?>'
            };

            // Gửi dữ liệu đơn hàng qua AJAX
            fetch('save-order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Phản hồi từ server không thành công: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                    // Xóa giỏ hàng sau khi đặt hàng thành công
                    cart = [];
                    fetch('update-cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ cart: cart, csrfToken: '<?php echo $_SESSION['csrf_token']; ?>' })
                    });
                    window.location.href = 'duan1.php';
                } else {
                    console.error('Lỗi từ server:', data.message);
                    alert("Lỗi: " + data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi đơn hàng:', error);
                alert("Lỗi khi gửi đơn hàng: " + error.message);
            });
        }

        // Xử lý nút "Tiếp tục mua hàng"
        function continueShopping() {
            window.location.href = 'duan1.php';
        }
    </script>
</body>
</html>