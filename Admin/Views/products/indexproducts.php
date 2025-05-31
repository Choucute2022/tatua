<h2 style="text-align: center; margin-bottom: 30px;">📦 Quản lý sản phẩm</h2>

<style>
    .table-container {
        max-width: 1000px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .add-product-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 18px;
        background-color: #0984e3;
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.2s;
    }

    .add-product-btn:hover {
        background-color: #0864b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th, table td {
        text-align: center;
        padding: 16px 10px;
        border-bottom: 1px solid #eee;
    }

    table th {
        background-color: #f9fafb;
        color: #333;
        font-size: 15px;
    }

    table tr:hover {
        background-color: #f1f7ff;
    }

    .table-container img {
        max-width: 50px;
        max-height: 50px;
        border-radius: 4px;
    }

    .product-actions a {
        padding: 6px 10px;
        margin: 0 4px;
        font-size: 12px;
        text-decoration: none;
        border-radius: 6px;
        transition: 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .product-actions a i {
        margin-right: 4px;
    }

    .edit-btn {
        background-color: #00b894;
        color: white;
    }

    .edit-btn:hover {
        background-color: #019170;
    }

    .delete-btn {
        background-color: #d63031;
        color: white;
    }

    .delete-btn:hover {
        background-color: #b52b2c;
    }

    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        width: 20px;
        height: 20px;
        line-height: 20px;
        margin: 0 4px;
        font-size: 12px;
        border-radius: 50%;
        background-color: #f1f1f1;
        text-decoration: none;
        color: #333;
        transition: background-color 0.2s, color 0.2s;
    }

    .pagination a.active {
        background-color: #00b894;
        color: white;
        font-weight: bold;
    }

    .pagination a:hover {
        background-color: #dfe6e9;
    }

    @media (max-width: 600px) {
        .table-container {
            padding: 20px;
        }
        table th, table td {
            padding: 10px 5px;
            font-size: 13px;
        }
    }
</style>

<div class="table-container">
    <a href="index.php?url=products/add" class="add-product-btn">➕ Thêm sản phẩm mới</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Hình ảnh</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($products)) { ?>
        <tr>
            <td><?= $row['productid']; ?></td>
            <td>
                <?php if ($row['image']) { ?>
                    <img src="<?= htmlspecialchars($row['image']); ?>" alt="Product Image">
                <?php } else { ?>
                    <em>Không có hình</em>
                <?php } ?>
            </td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= number_format($row['price'], 2, '.', ',') ?> USD</td>
            <td class="product-actions">
                <a href="index.php?url=products/edit/<?= $row['productid']; ?>" class="edit-btn">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="index.php?url=products/delete/<?= $row['productid']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash-alt"></i> Xóa
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="index.php?url=products&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i; ?>
            </a>
        <?php } ?>
    </div>
</div>

<!-- Thêm FontAwesome cho icon -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
