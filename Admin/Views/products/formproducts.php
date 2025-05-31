<h2><?= isset($product) ? '✏️ Sửa sản phẩm' : '➕ Thêm sản phẩm' ?></h2>

<style>
    .form-container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        font-family: 'Segoe UI', sans-serif;
    }

    .form-container label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
        color: #2d3436;
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container input[type="file"] {
        width: 100%;
        padding: 10px 14px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: border 0.2s;
        font-size: 10px;
    }

    .form-container input:focus {
        border-color: #0984e3;
        outline: none;
    }

    .form-container button {
        width: 100%;
        padding: 12px 0;
        background: #00b894;
        color: white;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .form-container button:hover {
        background: #019170;
    }

    .form-container a {
        display: inline-block;
        margin-top: 40px;
        text-align: center;
        width: 100%;
        color: #3498db;
        text-decoration: none;
    }

    .form-container a:hover {
        text-decoration: underline;
    }

    .form-container img {
        display: block;
        max-width: 150px;
        margin-top: -8px;
        border-radius: 6px;
    }

    .form-container .image-preview {
        margin-bottom: 30px;
    }
</style>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" 
            value="<?= isset($product) ? htmlspecialchars($product['name']) : '' ?>" 
            required>

        <label for="price">Giá (USD):</label>
        <input type="number" id="price" name="price" step="0.01" min="0" 
            value="<?= isset($product) ? htmlspecialchars($product['price']) : '' ?>" 
            required>

        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" accept="image/*" <?= isset($product) ? '' : 'required' ?>>

        <?php if (isset($product) && !empty($product['image'])): ?>
            <div class="image-preview">
                <p>Hình ảnh hiện tại:</p>
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Hình sản phẩm">
            </div>
        <?php endif; ?>

        <button type="submit" name="<?= isset($product) ? 'update_product' : 'add_product' ?>">
            <?= isset($product) ? '💾 Cập nhật sản phẩm' : '📦 Thêm sản phẩm' ?>
        </button>
    </form>

    <a href="index.php?url=products">← Quay lại danh sách sản phẩm</a>
</div>
