<?php
// include_once './Views/header.php';
include_once 'tatua-chou/register.html';
session_start();

// Khởi tạo giỏ hàng trong session nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ứng dụng đặt đồ uống</title>
    <link rel="stylesheet" href="duan01.css">
</head>
<body>

<div class="container">
    <!-- Danh mục bên trái -->
    <div class="categories">
        <h2>DANH MỤC</h2>
        <div class="category active" data-category="highlight" onclick="showCategory('highlight')">
            Món nổi bật <span class="count">42</span>
        </div>
        
        <div class="category" data-category="milk-tea" onclick="showCategory('milk-tea')">
            Trà Sữa
        </div>
        <div class="category" data-category="fruit-tea" onclick="showCategory('fruit-tea')">
            Fresh Fruit Tea
        </div>
        <div class="category" data-category="macchiato" onclick="showCategory('macchiato')">
            Macchiato Cream Cheese
        </div>
        <div class="category" data-category="coffee" onclick="showCategory('coffee')">
            Cà Phê
        </div>
        <div class="category" data-category="ice-cream" onclick="showCategory('ice-cream')">
            Ice Cream
        </div>
        <div class="category" data-category="special" onclick="showCategory('special')">
            Special Menu
        </div>
    </div>

    <!-- Sản phẩm ở giữa -->
    <div class="products">
        <h2 id="category-title" onclick="toggleDropdown()">Món nổi bật
            <span class="dropdown-arrow" id="arrow">+</span>
        </h2>
        <div class="product-list" id="product-list">
            <!-- Sản phẩm sẽ được hiển thị động bằng JavaScript -->
        </div>
    </div>

    <!-- Giỏ hàng bên phải -->
    <div class="cart">
        <div class="cart-title">
            <h2>GIỎ HÀNG CỦA TÔI</h2>
            <span class="clear-cart" onclick="clearCart()">Xóa tất cả</span>
        </div>
        <div id="cart-content">
            <p class="cart-empty">Chưa có sản phẩm nào!</p>
            <div class="cart-items" style="display: none;"></div>
            <div class="cart-total">
                <img src="https://tocotocotea.com/wp-content/themes/tocotocotea/assets/images/icon-glass-tea.png" alt="Icon" class="cart-icon">
                <span id="cart-total-text">x 0 = 0đ</span>
            </div>
        </div>
        <button class="checkout-btn" onclick="window.location.href ='order-details.php'">Thanh toán</button>
    </div>
</div>

<!-- Phần chi tiết sản phẩm (ban đầu ẩn đi) -->
<div class="product-details" id="product-details" style="display: none;">
    <div class="product-container">
        <div class="product-header">
            <img id="detail-image" src="" alt="" class="product-image">
            <div class="product-info">
                <h2 id="detail-name"></h2>
                <div class="price">
                    <span class="current-price" id="detail-price"></span>
                    <span class="original-price" id="detail-original-price"></span>
                </div>
                <p class="description" id="detail-description"></p>
                <div class="quantity">
                    <button class="minus" onclick="updateQuantity(-1)">-</button>
                    <span id="quantity">1</span>
                    <button class="plus" onclick="updateQuantity(1)">+</button>
                </div>
                <button class="add-to-cart" id="add-to-cart-final">+ <span id="total-price"></span></button>
            </div>
        </div>

        <div class="options">
            <div class="option-container">
                <h3>Chọn size</h3>
                <div class="option"><label><input type="radio" name="size" value="M"> Size M</label></div>
            </div>

            <div class="option-container">
                <h3>Chọn đường</h3>
                <div class="option"><label><input type="radio" name="sugar" value="100"> 100% đường</label></div>
                <div class="option"><label><input type="radio" name="sugar" value="50"> Ít đường</label></div>
                <div class="option"><label><input type="radio" name="sugar" value="0"> Không đường</label></div>
            </div>

            <div class="option-container">
                <h3>Chọn đá</h3>
                <div class="option"><label><input type="radio" name="ice" value="100"> 100% đá</label></div>
                <div class="option"><label><input type="radio" name="ice" value="50"> Ít đá</label></div>
                <div class="option"><label><input type="radio" name="ice" value="0"> Không đá</label></div>
            </div>

            <div class="option-group">
                <h3>Chọn topping</h3>
                <div class="topping-options">
                    <label class="topping">
                        <input type="checkbox" name="topping" value="tran-chau-hong-kim" data-price="5000" onchange="updateTotalPrice()">
                        <span>Thêm trân châu hồng kim</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="thach-hoa-moc-que" data-price="5000" onchange="updateTotalPrice()">
                        <span>Thêm Thạch Hoa Mộc Quế</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="macchiato" data-price="7000" onchange="updateTotalPrice()">
                        <span>Thêm macchiato</span>
                        <span class="topping-price">+7,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="rau-cau" data-price="5000" onchange="updateTotalPrice()">
                        <span>Thêm rau câu</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="thach-ca-phe" data-price="5000" onchange="updateTotalPrice()">
                        <span>Thêm thạch cà phê</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="tran-chau-soi" data-price="5000" onchange="updateTotalPrice()">
                        <span>Thêm trân châu sợi</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                    <label class="topping">
                        <input type="checkbox" name="topping" value="thach-dua" data-price="5000" onchange="updateTotalPrice()">
                        <span>Topping Thạch Dừa</span>
                        <span class="topping-price">+5,000đ</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dữ liệu sản phẩm (42 sản phẩm, danh mục "highlight" chứa tất cả 42 món)
const productsData = {
    'highlight': [
        { id: 1, name: "Trà đào tiên quế hoa", current_price: 35000, current_price_display: "", original_price: "38,000đ", description: "Sự kết hợp đậm đà của trà ô long khói và đào má hồng có vị thơm ngọt tự nhiên hấp đãn kết hợp với thạch konjac giòn giòn tạo cảm giác thú vị.", image: "img/img1.png", isNew: true },
        { id: 2, name: "Xanh sữa nhài đào tiên", current_price: 25000, current_price_display: "25,000đ", original_price: "34,000đ", description: "Hương vị ngọt ngào của đào chín mọng kết hợp cùng sự thanh mát của trà xanh, thêm chút béo béo của trà sữa, tạo nên một thức uống hoàn hảo, sảng khoái.", image: "img/img2.jpg", isNew: true },
        { id: 3, name: "Ô long dâu tây", current_price: 25000, current_price_display: "25,000đ", original_price: "30,000đ", description: "Kết hợp đậm đà của trà ô lonhg khói và hương ngọt dịu, tươi mát từ dâu tây và giòn ngọt của thạch konjac mang đến trải nghiệm độc đáo, trọn vẹn từng ngụm.", image: "img/img3.jpg", isNew: true },
        { id: 4, name: "Ôlong Dâu Tây Kem Phô Mai", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Thức uống thơn ngon bổ dưỡng với sự kết hợp hạt dẻ cao cấp, thơm béo, vị bùi bùi và trân chầu hoàng kim dẻo dai kết hợp cùng hương thơm khói ấm nồng, vị béo ngậy, đậm đà của trà sữa ô long.", image: "img/img4.jpg", isNew: true },
        { id: 5, name: "Ôlong Sữa Hạt Dẻ Hồng Kim", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Thức uống thơn ngon bổ dưỡng với sự kết hợp hạt dẻ cao cấp, thơm béo, vị bùi bùi và trân chầu hoàng kim dẻo dai kết hợp cùng hương thơm khói ấm nồng, vị béo ngậy, đậm đà của trà sữa ô long.", image: "img/img5.png", isNew: true },
        { id: 6, name: "Trà sữa dâu tây", current_price: 25000, current_price_display: "25,000đ", original_price: "34,000đ", description: " Hương vị chua ngọt hài hòa đặc trưng của Dâu tây và trà thanh mát.", image: "img/img6.jpg", isNew: true },
        { id: 7, name: "Hồng trà mận quế hoa khổng lồ", current_price: 22000, current_price_display: "22,000đ", original_price: "25,000đ", description: " Hồng Trà Mận Quế Hoa Khổng Lồ là sự hòa quyện giữa hương vị đậm đà của hồng trà kết hợp hoàn hảo cùng mứt mận Mộc Châu chín đỏ mọng nước đã được tách hạt với vị chua ngọt và thạch quế hoa mềm mướt, vị ngọt nhẹ, điểm bên trong là hoa mộc quế cùng các lát chanh vàng tươi mát tạo ra trải nghiệm đầy thú vị và lôi cuốn.", image: "img/img7.jpg", isNew: true },
        { id: 8, name: "Trà sữa trân châu hoàng gia", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Hương vị trà sữa thơm béo cùng hương thơm đặc trưng của hồng trà kết hợp với trân châu được tẩm với mật ong nguyên chất, tạo ra hương vị ngọt ngào đặc trưng.", image: "img/img8.jpg", isNew: true },
        { id: 9, name: "Sữa tươi yến mạch", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sự kết hợp yến mạch dẻo thơm cùng sữa tươi béo nhẹ mang đến một món uống vừa bổ dưỡng vừa thơm ngon.", image: "img/img9.png", isNew: true },
        { id: 10, name: "Sữa tươi nếp cẩm", current_price: 30000, current_price_display: "30,000đ", original_price: "40,000đ", description: " Sự kết hợp hoàn hảo giữa vị ngọt bùi, béo nhẹ của nếp cẩm và vị thanh mát, mịn màng của sữa tươi mang cảm giác thú vị và lôi cuốn.", image: "img/img10.png", isNew: true },
        { id: 11, name: "Ô long yến mạch", current_price: 30000, current_price_display: "30,000đ", original_price: "35,000đ", description: "Sự hòa quyện giữa vị béo thơm của yến mạch và tà ô long khói đậm vị mang lại cảm giác thư thái và bổ dưỡng.", image: "img/img11.png", isNew: true },
        { id: 12, name: "Trà sữa yến mạch", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Vị ngọt tự nhiên, dinh dưỡng hoàn hảo của hồng trà sữa thơm béo kết hợp cùng yến mạch dẻo, mềm thanh gọt bùi.", image: "img/img12.png", isNew: true },
        { id: 13, name: "Trà sữa nếp cẩm", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: " Hương vị đạm đà của hồng trà kết hợp hoàn hảo với nếp cẩm dẻo, thơm bùi tạo nên cảm giác thú vị với mỗi ngụm trà.", image: "img/img13.png", isNew: true },
        { id: 14, name: "Kem vani trà sữa trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "33,000đ", description: "Kem thơm vị hồng trà, hương caramel của đường đen cùng chút ngậy béo trà sữa, thưởng thức cùng trân châu hoàng kim dẻo dai, độc đáo.", image: "img/img14.png", isNew: true },
        { id: 15, name: "Kem trà sữa trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "40,000đ", description: "Kem thơm vị hồng trà, hương caramel của đường đen cùng chút ngậy béo trà sữa, thưởng thức cùng trân châu hoàng kim dẻo dai, độc đáo.", image: "img/img15.png", isNew: true },
        { id: 16, name: "Ô long đào lê tây bắc khổng lồ", current_price: 35000, current_price_display: "35,000đ", original_price: "45,000đ", description: "Sự kết hợp hoàn hảo của lê Tây Bắc và đào tiên má hồng giòn ngọt tự nhiên, thưởng thức cùng trà ô long khói vô cùng thanh lọc, bổ dưỡng.", image: "img/img16.png", isNew: true },
        { id: 17, name: "Xanh nhài lê tây bắc khổng lồ", current_price: 35000, current_price_display: "35,000đ", original_price: "45,000đ", description: "Lê Tây Bắc giòn ngọt, thạch dừa mềm mướt hòa cùng trà xanh nhài organic thơm thoang thoảng hoa nhài mang đến trải nghiệm giải khát tuyệt vời.", image: "img/img17.png", isNew: true },
        { id: 18, name: "Trà sữa Boba Cheese", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Boba cheese được làm từ phô mai con bò cười, mang đến trải nghiệm mềm trong, dai ngoài, hào cùng vị trà sữa thơm béo.", image: "img/img18.png", isNew: true },
        { id: 19, name: "Xanh nhài mãng cầu", current_price: 25000, current_price_display: "25,000đ", original_price: "30,000đ", description:  "Mãng cầu tươi dai dai chua ngọt tự nhiên, trà xanh organic thơm hương mhaif kết hợp với những lát chanh vàng tạo nên tổng thể hương vị tươi mát, bổ dưỡng.", image: "img/img19.png", isNew: true },
        { id: 20, name: "Xanh nhài sữa tươi Toco", current_price: 30000, current_price_display: "30,000đ", original_price: "", description: "Trà xanh organic thơm hương nhài hào cùng sữa tươi nguyên kem béo béo, có sẵn topping trân châu hoàng kim dẻo dai và ngọt thơm.", image: "img/img20.png", isNew: true },
        { id: 21, name: "Ô long đào quế hoa kem phô mai", current_price: 25000, current_price_display: "25,000đ", original_price: "40,000đ", description: "Hồng trà đào đậm vị, topping đào tiên má hồng giòn ngọt tự nhiên kết hợp cùng thạch quế hoa mềm mướt, trên cùng là lớp macchiato kem muối ngậy béo vị phô mai, mang đến thức uống hoàn hảo.", image: "img/img21.png", isNew: true },
        { id: 22, name: "Phê sữa kem phô mai", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Sự hòa quyện giữa cà phê đậm vị robusta xen lẫn vào đó là vị béo của sữa đặc, thêm lớp macchiato kem béo ngậy béo vị phô mai tuyệt vời.", image: "img/img22.png", isNew: true },
        { id: 23, name: "Ô long sữa kem cafe trân châu sợi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long sữa thơm béo, kết hợp cùng trân châu sợi dẻo dai và trên cùng là lớp macchiato kem cafe thơm đậm đà.", image: "img/img23.png", isNew: true },
        { id: 24, name: "Trà xanh nhài đào tiên", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà xanh đào thoảng hương hoa nhài, topping đào  tiên má hồng giòn ngọt tự nhiên kết hợp cùng thạch quế hoa mềm mướt. Thức uống thanh mát sảng khoái đặc biệt cho mùa hè.", image: "img/img24.png", isNew: true },
        { id: 25, name: "Ô long trâ châu ngũ cốc", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà sữa ô long khói thanh nhiệt thơm béo, topping trân châu ngũ cốc dẻo bùi được làm từ khoai lang Đà Lạt. Sản phẩm có thể uống nóng hoặc lạnh.", image: "img/img25.jpg", isNew: true },
        { id: 26, name: "Trà sữa phô mai tươi", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Hồng trà đậm đà hòa cùng sữa béo béo, kết hợp pudding phô mai tươi thơm thơm, vừa dẻo dai vừa mịn kích thích vị giác.", image: "img/img26.png", isNew: true },
        { id: 27, name: "Trà sữa trân châu đường hổ", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Điểm nhấn là những hạt trân châu dẻo dai kết hợp vị ngọt thơm đặc trưng của siro đường hổ hoà quyện với vị béo thơm của trà sữa.", image: "img/img27.jpg", isNew: true },
        { id: 28, name: "Trà chanh giã tay mật ong", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Vị chua nhẹ từ chanh vàng được giã bằng tay kết hợp với trà xanh lài cùng mật ong tự nhiên ngọt thanh. Có sẵn topping thạch băng tuyết. Sản phẩm có thể uống nóng hoặc lạnh.", image: "img/img28.jpg", isNew: true },
        { id: 29, name: "Trà dâu tầm pha lê tuyết", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Mứt dâu tằm chua chua ngọt hòa cùng vị trà chát nhẹ, kết hợp với topping thạch băng tuyết tạo nên thức uống giải khát tuyệt vời.", image: "img/img29.jpg", isNew: true },
        { id: 30, name: "Trà dứa thạch konjac", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Hương vị đậm đà, chua ngọt hài hòa đặc trưng của mứt dứa kết hợp với vị trà xanh nhài thanh mát, topping thạch konjac giòn ngọt vui miệng.", image: "img/img30.jpg", isNew: true },
        { id: 31, name: "Jelly milk coffee", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sự hòa quyện giữa cà phê robusta đậm vị cùng sữa béo thơm, vị ngọt nhẹ nhàng từ topping thạch cafe giòn ngọt.", image: "img/img31.jpg", isNew: true },
        { id: 32, name: "Cheese milk coffee", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Sự hòa quyện giữa cà phê robusta đậm vị cùng sữa béo thơm, vị ngọt nhẹ nhàng từ topping sương sáo mềm mịn.", image: "img/img32.png", isNew: true },
        { id: 33, name: "Kem ly vani dâu", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Sản phẩm ngon hơn khi thưởng thức tại cửa hàng.", image: "img/img33.jpg", isNew: true },
        { id: 34, name: "Cafe kem trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Sản phẩm ngon hơn khi thưởng thức tại cửa hàng.", image: "img/img34.jpg", isNew: true },
        { id: 35, name: "Kem trà sữa trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Sản phẩm ngon hơn khi thưởng thức tại cửa hàng.", image: "img/img35.jpg", isNew: true },
        { id: 36, name: "Ô long dâu tây kem phô mai", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Thức uống thơm ngon bổ dưỡng với sự kết hợp hạt dẻ cao cấp, thơm béo, vị bùi bùi và trân châu hoàng kim dẻo dai kết hợp cùng hương thơm khói ấm nồng, vị béo ngậy, đậm đà của trà sữa ô long.", image: "img/img36.jpg", isNew: true },
        { id: 37, name: "Ô long hạt dẻ hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "30,000đ", description: "Thức uống thơm ngon bổ dưỡng với sự kết hợp hạt dẻ cao cấp, thơm béo, vị bùi bùi và trân châu hoàng kim dẻo dai kết hợp cùng hương thơm khói ấm nồng, vị béo ngậy, đậm đà của trà sữa ô long.", image: "img/img37.png", isNew: true },
        { id: 38, name: "Dâu tầm kem phô mai", current_price: 30000, current_price_display: "30,000đ", original_price: "", description: "Hương hồng trà thơm mát xen lẫn vị thơm béo của lớp kem phô mai kết hợp mứt dâu và topping thcachj quế hoa mềm mướt, hậu vị ngọt dịu nhẹ.", image: "img/img38.png", isNew: true },
        { id: 39, name: "Phê sữa kem cheese ", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sự hòa quyện giữa cà phê đậm vị robusta xen lẫn vào đó là vị béo của sữa đặc, thêm lớp macchiato kem béo ngậy béo vị phô mai tuyệt vời.", image: "img/img39.png", isNew: true },
        { id: 40, name: "Xanh nhài sữa tươi Toco", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Trà xanh organic thơm hương nhài hào cùng sữa tươi nguyên kem béo béo, có sẵn topping trân châu hoàng kim dẻo dai và ngọt thơm.", image: "img/img40.png", isNew: true },
        { id: 41, name: "Ôlong sữa kem cafe trân châu sợi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long sữa thơm béo, kết hợp cùng trân châu sợi dẻo dai và trên cùng là lớp macchiato kem cafe thơm đậm đà.", image: "img/img41.png", isNew: true },
        { id: 42, name: "Ôlong sữa tươi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sữa tươi Happy Barn nguyên kem nhập khẩu thơm béo kết hợp với vị trà oolong khói thanh nhiệt thơm nồng, tươi mát, giải khát và vị tự nhiên.", image: "img/img42.png", isNew: true },
    ],
    'instant-milk-tea': [
        { id: 6, name: "Trà sữa dâu tây", current_price: 25000, current_price_display: "25,000đ", original_price: "34,000đ", description: "Hương vị chua ngọt hài hòa đặc trưng của dâu tây hòa quyện cùng trà sữa thơm béo, mang đến trải nghiệm ngọt ngào và tươi mát.", image: "img/img6.jpg", isNew: true },
        { id: 7, name: "Hồng trà mận quế hoa khổng lồ", current_price: 22000, current_price_display: "22,000đ", original_price: "25,000đ", description: "Sự hòa quyện giữa hồng trà đậm đà và mứt mận Mộc Châu chua ngọt, kết hợp với thạch quế hoa mềm mướt và chanh vàng tươi mát, tạo nên thức uống lôi cuốn.", image: "img/img7.jpg", isNew: true },
        { id: 8, name: "Trà sữa trân châu hoàng gia", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Trà sữa thơm béo với hương hồng trà đặc trưng, kết hợp cùng trân châu tẩm mật ong nguyên chất, tạo nên vị ngọt ngào độc đáo.", image: "img/img8.jpg", isNew: true },
        { id: 9, name: "Sữa tươi yến mạch", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sữa tươi béo nhẹ kết hợp cùng yến mạch dẻo thơm, mang đến thức uống bổ dưỡng, thơm ngon và tốt cho sức khỏe.", image: "img/img9.png", isNew: true },
        { id: 10, name: "Sữa tươi nếp cẩm", current_price: 30000, current_price_display: "30,000đ", original_price: "40,000đ", description: "Vị ngọt bùi của nếp cẩm hòa quyện cùng sữa tươi thanh mát, tạo nên thức uống mịn màng, hấp dẫn và đầy thú vị.", image: "img/img10.png", isNew: true }
    ],
    'milk-tea': [
        { id: 11, name: "Ô long yến mạch", current_price: 30000, current_price_display: "30,000đ", original_price: "35,000đ", description: "Trà ô long khói đậm vị kết hợp cùng yến mạch béo thơm, mang đến thức uống thư giãn, bổ dưỡng với hương vị hài hòa.", image: "img/img11.png", isNew: true },
        { id: 12, name: "Trà sữa yến mạch", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Hồng trà sữa thơm béo kết hợp cùng yến mạch dẻo mềm, mang đến vị ngọt tự nhiên và dinh dưỡng tuyệt vời.", image: "img/img12.png", isNew: true },
        { id: 13, name: "Trà sữa nếp cẩm", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Hồng trà đậm đà hòa quyện cùng nếp cẩm dẻo thơm, tạo nên thức uống bùi bùi, thú vị với mỗi ngụm.", image: "img/img13.png", isNew: true },
        { id: 14, name: "Trà sữa trân châu đường hổ", current_price: 25000, current_price_display: "25,000đ", original_price: "33,000đ", description: "Trà sữa béo ngậy kết hợp với trân châu dẻo dai và siro đường hổ ngọt thơm, tạo nên hương vị độc đáo và hấp dẫn.", image: "img/img14.png", isNew: true },
        { id: 15, name: "Trà sữa phô mai tươi", current_price: 25000, current_price_display: "25,000đ", original_price: "40,000đ", description: "Hồng trà sữa đậm đà kết hợp với pudding phô mai tươi dẻo mịn, mang đến trải nghiệm thơm ngon, kích thích vị giác.", image: "img/img15.png", isNew: true }
    ],
    'fruit-tea': [
        { id: 16, name: "Ô long đào lê tây bắc khổng lồ", current_price: 35000, current_price_display: "35,000đ", original_price: "45,000đ", description: "Trà ô long khói thanh lọc kết hợp với lê Tây Bắc giòn ngọt và đào tiên má hồng, mang đến thức uống bổ dưỡng, sảng khoái.", image: "img/img16.png", isNew: true },
        { id: 17, name: "Xanh nhài lê tây bắc khổng lồ", current_price: 35000, current_price_display: "35,000đ", original_price: "45,000đ", description: "Trà xanh nhài organic thơm nhẹ kết hợp với lê Tây Bắc giòn ngọt và thạch dừa mềm mướt, tạo nên thức uống giải khát tuyệt vời.", image: "img/img17.png", isNew: true },
        { id: 18, name: "Trà xanh nhài đào tiên", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Trà xanh nhài thoảng hương hoa kết hợp với đào tiên má hồng giòn ngọt và thạch quế hoa mềm mướt, mang đến thức uống thanh mát lý tưởng.", image: "img/img18.png", isNew: true },
        { id: 19, name: "Trà chanh mật ong giã tay", current_price: 25000, current_price_display: "25,000đ", original_price: "30,000đ", description: "Chanh vàng giã tay chua nhẹ kết hợp với trà xanh nhài và mật ong ngọt thanh, đi kèm thạch băng tuyết, tươi mát và bổ dưỡng.", image: "img/img19.jpg", isNew: true },
        { id: 20, name: "Trà dâu tằm pha lê tuyết", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Mứt dâu tằm chua ngọt hòa quyện với trà chát nhẹ, kết hợp thạch pha lê tuyết giòn ngon, tạo nên thức uống giải khát tuyệt hảo.", image: "img/img20.png", isNew: true },
        { id: 21, name: "Trà dứa thạch Konjac", current_price: 25000, current_price_display: "25,000đ", original_price: "40,000đ", description: "Mứt dứa chua ngọt kết hợp với trà xanh nhài thanh mát, đi kèm thạch konjac giòn ngọt, mang đến trải nghiệm vui miệng.", image: "img/img21.png", isNew: true }
    ],
    'macchiato': [
        { id: 22, name: "Trà sữa Boba Cheese", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Trà sữa thơm béo kết hợp với boba cheese làm từ phô mai con bò cười, mang đến trải nghiệm mềm trong, dai ngoài, đầy hấp dẫn.", image: "img/img22.png", isNew: true },
        { id: 23, name: "Ô long đào quế hoa kem cheese", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long đào đậm vị, kết hợp đào tiên má hồng giòn ngọt, thạch quế hoa mềm mướt và lớp kem cheese béo ngậy, tạo nên thức uống hoàn hảo.", image: "img/img23.png", isNew: true },
        { id: 24, name: "Ôlong dâu tây kem phô mai", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long khói kết hợp với dâu tây tươi ngọt và lớp kem phô mai béo ngậy, mang đến hương vị đậm đà, lôi cuốn.", image: "img/img24.png", isNew: true },
        { id: 25, name: "Dâu tằm kem phô mai", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Hồng trà thơm mát kết hợp mứt dâu tằm chua ngọt, thạch quế hoa mềm mướt và kem phô mai béo, tạo nên hậu vị ngọt dịu.", image: "img/img25.jpg", isNew: true }
    ],
    'coffee': [
        { id: 26, name: "Jelly milk coffee", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Cà phê robusta đậm vị hòa quyện với sữa béo thơm, kết hợp thạch cà phê giòn ngọt, mang đến trải nghiệm sảng khoái.", image: "img/img26.png", isNew: true },
        { id: 27, name: "Cheese milk coffee", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Cà phê robusta đậm đà kết hợp với sữa béo và sương sáo mềm mịn, tạo nên thức uống thơm ngon, dễ thưởng thức.", image: "img/img27.jpg", isNew: true },
        { id: 28, name: "Phê sữa kem cheese", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Cà phê robusta đậm vị hòa quyện với sữa đặc béo và lớp kem cheese ngậy, mang đến trải nghiệm đậm đà, tuyệt vời.", image: "img/img28.jpg", isNew: true },
        { id: 29, name: "Phê sữa kem cheese", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Cà phê robusta đậm đà kết hợp sữa đặc và kem cheese béo ngậy, tạo nên thức uống thơm ngon, lôi cuốn.", image: "img/img29.jpg", isNew: true },
        { id: 30, name: "Ôlong sữa kem cafe trân châu sợi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long sữa béo thơm kết hợp trân châu sợi dẻo dai và lớp kem cà phê đậm đà, mang đến hương vị độc đáo.", image: "img/img30.jpg", isNew: true },
        { id: 31, name: "Ôlong sữa kem cafe trân châu sợi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long sữa thơm ngon kết hợp trân châu sợi dẻo và kem cà phê đậm vị, tạo nên thức uống hấp dẫn, cân bằng.", image: "img/img31.jpg", isNew: true },
        { id: 32, name: "Cafe kem trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Cà phê đậm đà kết hợp kem béo và trân châu hoàng kim dẻo dai, mang đến trải nghiệm thơm ngon tại cửa hàng.", image: "img/img32.png", isNew: true }
    ],
    'ice-cream': [
        { id: 33, name: "Kem ly vani dâu", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Kem vani thơm béo kết hợp với mứt dâu chua ngọt, mang đến trải nghiệm ngọt ngào, lý tưởng khi thưởng thức tại cửa hàng.", image: "img/img33.jpg", isNew: true },
        { id: 34, name: "Kem trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Kem béo ngậy kết hợp trân châu hoàng kim dẻo dai, mang đến hương vị độc đáo, ngon hơn khi thưởng thức tại cửa hàng.", image: "img/img34.jpg", isNew: true },
        { id: 35, name: "Kem trà sữa trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Kem trà sữa thơm ngon kết hợp trân châu hoàng kim dẻo dai, tạo nên món tráng miệng tuyệt hảo tại cửa hàng.", image: "img/img35.jpg", isNew: true },
        { id: 36, name: "Kem vani trà sữa trân châu hoàng kim", current_price: 25000, current_price_display: "25,000đ", original_price: "", description: "Kem vani béo ngậy kết hợp trà sữa và trân châu hoàng kim dẻo dai, mang đến trải nghiệm ngọt ngào tại cửa hàng.", image: "img/img36.jpg", isNew: true }
    ],
    'special': [
        { id: 37, name: "Xanh nhài mãng cầu", current_price: 25000, current_price_display: "25,000đ", original_price: "30,000đ", description: "Trà xanh nhài organic kết hợp mãng cầu chua ngọt và chanh vàng tươi mát, mang đến thức uống giải khát bổ dưỡng.", image: "img/img37.png", isNew: true },
        { id: 38, name: "Xanh nhài sữa tươi Toco", current_price: 30000, current_price_display: "30,000đ", original_price: "", description: "Trà xanh nhài thơm nhẹ kết hợp sữa tươi béo và trân châu hoàng kim dẻo dai, mang đến thức uống ngọt thơm, hấp dẫn.", image: "img/img38.png", isNew: true },
        { id: 39, name: "Xanh nhài sữa tươi Toco", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà xanh nhài organic hòa quyện sữa tươi nguyên kem và trân châu hoàng kim, tạo nên thức uống béo ngọt, lôi cuốn.", image: "img/img39.png", isNew: true },
        { id: 40, name: "Ôlong sữa trân châu ngũ cốc", current_price: 25000, current_price_display: "25,000đ", original_price: "38,000đ", description: "Trà ô long sữa béo thơm kết hợp trân châu ngũ cốc dẻo bùi từ khoai lang Đà Lạt, mang đến thức uống độc đáo, bổ dưỡng.", image: "img/img40.png", isNew: true },
        { id: 41, name: "Ôlong sữa tươi", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Sữa tươi nguyên kem kết hợp trà ô long khói thanh nhiệt, mang đến thức uống thơm nồng, tươi mát và giải khát.", image: "img/img41.png", isNew: true },
        { id: 42, name: "Ôlong sữa hạt dẻ hoàng kim", current_price: 30000, current_price_display: "30,000đ", original_price: "38,000đ", description: "Trà ô long sữa béo kết hợp hạt dẻ cao cấp và trân châu hoàng kim dẻo dai, mang đến thức uống thơm ngon, đậm đà.", image: "img/img42.png", isNew: true }
    ]
};

let selectedProduct = null;
let quantity = 1;
let sizeLevel = 'M';
let sugarLevel = '100% đường';
let iceLevel = '100% đá';
let cart = <?php echo json_encode($_SESSION['cart']); ?>;

// Hiển thị danh mục sản phẩm
function showCategory(category) {
    const categories = document.querySelectorAll('.category');
    const productList = document.getElementById('product-list');
    const categoryTitle = document.getElementById('category-title');

    categories.forEach(cat => cat.classList.remove('active'));
    document.querySelector(`.category[data-category="${category}"]`).classList.add('active');

    categoryTitle.innerHTML = `${document.querySelector(`.category[data-category="${category}"]`).childNodes[0].textContent.trim()} <span class="dropdown-arrow" id="arrow">+</span>`;
    const products = productsData[category] || [];
    let productHTML = '';

    products.forEach(product => {
        productHTML += `
            <div class="product">
                ${product.isNew ? '<span class="new-label">NEW</span>' : ''}
                <img src="${product.image}" alt="${product.name}">
                <h3>${product.name}</h3>
                <div class="price">
                    <span class="current-price">${product.current_price_display}</span>
                    ${product.original_price ? `<span class="original-price">${product.original_price}</span>` : ''}
                    <button class="add-btn" onclick="showProductDetails(${product.id})">+</button>
                </div>
            </div>
        `;
    });

    productList.innerHTML = productHTML;
    productList.classList.remove('hidden'); // Đảm bảo danh sách sản phẩm luôn hiển thị khi chọn danh mục
}

// Toggle dropdown sản phẩm
function toggleDropdown() {
    const arrow = document.getElementById('arrow');
    const list = document.getElementById('product-list');
    arrow.classList.toggle('rotate');
    list.classList.toggle('hidden');
}

// Hiển thị chi tiết sản phẩm
function showProductDetails(productId) {
    const allProducts = Object.values(productsData).flat();
    selectedProduct = allProducts.find(product => product.id === productId);
    if (selectedProduct) {
        document.getElementById('product-details').style.display = 'flex';
        document.getElementById('detail-image').src = selectedProduct.image || 'https://via.placeholder.com/150';
        document.getElementById('detail-name').textContent = selectedProduct.name;
        document.getElementById('detail-price').textContent = selectedProduct.current_price_display;
        document.getElementById('detail-original-price').textContent = selectedProduct.original_price;
        document.getElementById('detail-description').textContent = selectedProduct.description;
        quantity = 1;
        document.getElementById('quantity').textContent = quantity;

        resetOptions();
        updateTotalPrice();
    }
}

// Reset các tùy chọn về mặc định
function resetOptions() {
    document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.checked = radio.value === "M";
    });

    document.querySelectorAll('input[name="sugar"]').forEach(radio => {
        radio.checked = radio.value === "100";
    });

    document.querySelectorAll('input[name="ice"]').forEach(radio => {
        radio.checked = radio.value === "100";
    });

    document.querySelectorAll('input[name="topping"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    sizeLevel = 'M';
    sugarLevel = '100% đường';
    iceLevel = '100% đá';
}

// Cập nhật số lượng
function updateQuantity(change) {
    quantity = Math.max(1, quantity + change);
    document.getElementById('quantity').textContent = quantity;
    updateTotalPrice();
}

// Cập nhật tổng giá tiền
function updateTotalPrice() {
    if (!selectedProduct) return;

    let basePrice = selectedProduct.current_price;
    let toppingPrice = 0;

    const toppings = document.querySelectorAll('input[name="topping"]:checked');
    toppings.forEach(topping => {
        toppingPrice += parseInt(topping.getAttribute('data-price'));
    });

    let totalPrice = (basePrice + toppingPrice) * quantity;

    document.getElementById('detail-price').textContent = (basePrice + toppingPrice).toLocaleString('vi-VN') + 'đ';
    document.getElementById('total-price').textContent = totalPrice.toLocaleString('vi-VN') + 'đ';
}

// Xử lý sự kiện chọn size
document.querySelectorAll('input[name="size"]').forEach(radio => {
    radio.addEventListener('change', function() {
        sizeLevel = this.parentElement.textContent.trim();
        updateTotalPrice();
    });
});

// Xử lý sự kiện chọn đường
document.querySelectorAll('input[name="sugar"]').forEach(radio => {
    radio.addEventListener('change', function() {
        sugarLevel = this.parentElement.textContent.trim();
        updateTotalPrice();
    });
});

// Xử lý sự kiện chọn đá
document.querySelectorAll('input[name="ice"]').forEach(radio => {
    radio.addEventListener('change', function() {
        iceLevel = this.parentElement.textContent.trim();
        updateTotalPrice();
    });
});

// Thêm sản phẩm vào giỏ hàng
document.getElementById('add-to-cart-final').addEventListener('click', function() {
    if (!selectedProduct) return;

    let basePrice = selectedProduct.current_price;
    let toppingPrice = 0;
    const toppings = document.querySelectorAll('input[name="topping"]:checked');
    toppings.forEach(topping => {
        toppingPrice += parseInt(topping.getAttribute('data-price'));
    });

    const itemPrice = (basePrice + toppingPrice) * quantity;
    const cartItem = {
        id: selectedProduct.id,
        name: selectedProduct.name,
        quantity: quantity,
        price: basePrice + toppingPrice,
        totalPrice: itemPrice,
        sizeLevel: sizeLevel,
        sugarLevel: sugarLevel,
        iceLevel: iceLevel,
        toppings: getSelectedToppings(),
        image: selectedProduct.image || 'https://via.placeholder.com/150'
    };

    const existingItemIndex = cart.findIndex(item => item.id === cartItem.id && item.sizeLevel === cartItem.sizeLevel && item.sugarLevel === cartItem.sugarLevel && item.iceLevel === cartItem.iceLevel && item.toppings === cartItem.toppings);
    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity += cartItem.quantity;
        cart[existingItemIndex].totalPrice += cartItem.totalPrice;
    } else {
        cart.push(cartItem);
    }

    // Cập nhật PHP session qua AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    }).then(() => {
        updateCart();
        document.getElementById('product-details').style.display = 'none';
    });
});

// Cập nhật giao diện giỏ hàng
function updateCart() {
    const cartContent = document.getElementById('cart-content');
    const cartItemsContainer = cartContent.querySelector('.cart-items');
    const emptyCartMessage = cartContent.querySelector('.cart-empty');
    const cartTotalText = document.getElementById('cart-total-text');

    if (cart.length === 0) {
        emptyCartMessage.style.display = 'block';
        cartItemsContainer.style.display = 'none';
        cartTotalText.textContent = `x 0 = 0đ`;
    } else {
        emptyCartMessage.style.display = 'none';
        cartItemsContainer.style.display = 'block';

        let totalItems = 0;
        let totalPrice = 0;
        let cartHTML = '';

        cart.forEach((item, index) => {
            totalItems += item.quantity;
            totalPrice += item.totalPrice;
            cartHTML += `
                <div class="cart-item">
                    <div class="cart-item-details">
                        <strong>${item.name} (${item.sizeLevel || 'M'})</strong>
                        <div class="cart-item-options">
                            ${item.sugarLevel} đường, ${item.iceLevel} đá
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn minus" onclick="updateCartQuantity(${index}, -1)">−</button>
                            <span>${item.quantity}</span>
                            <button class="quantity-btn plus" onclick="updateCartQuantity(${index}, 1)">+</button>
                        </div>
                        <div class="cart-item-price">
                            ${item.price.toLocaleString('vi-VN')}đ x ${item.quantity} = ${item.totalPrice.toLocaleString('vi-VN')}đ
                        </div>
                    </div>
                    <button class="remove-btn" onclick="removeFromCart(${index})">X</button>
                </div>
            `;
        });

        cartItemsContainer.innerHTML = cartHTML;
        cartTotalText.textContent = `x ${totalItems} = ${totalPrice.toLocaleString('vi-VN')}đ`;
    }
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartQuantity(index, change) {
    cart[index].quantity = Math.max(1, cart[index].quantity + change);
    cart[index].totalPrice = cart[index].price * cart[index].quantity;

    // Cập nhật PHP session qua AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    }).then(() => {
        updateCart();
    });
}

// Xóa sản phẩm khỏi giỏ hàng
function removeFromCart(index) {
    cart.splice(index, 1);

    // Cập nhật PHP session qua AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    }).then(() => {
        updateCart();
    });
}

// Xóa toàn bộ giỏ hàng
function clearCart() {
    cart = [];

    // Cập nhật PHP session qua AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    }).then(() => {
        updateCart();
    });
}

// Xử lý thanh toán
function checkout() {
    if (cart.length === 0) {
        alert('Giỏ hàng của bạn đang trống!');
        return;
    }
    alert('Thanh toán thành công! Tổng cộng: ' + cart.reduce((sum, item) => sum + item.totalPrice, 0).toLocaleString('vi-VN') + 'đ');
    cart = [];

    // Cập nhật PHP session qua AJAX
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
    }).then(() => {
        updateCart();
    });
}

// Lấy danh sách topping đã chọn
function getSelectedToppings() {
    const toppings = document.querySelectorAll('input[name="topping"]:checked');
    return Array.from(toppings).map(topping => topping.nextElementSibling.textContent).join(', ') || 'Không có';
}

// Khởi tạo khi trang được tải
document.addEventListener('DOMContentLoaded', () => {
    showCategory('highlight'); // Hiển thị danh mục mặc định
    updateCart();
});
</script>
</body>
</html>
<?php include_once 'tatua-chou/footer.html' ?>
