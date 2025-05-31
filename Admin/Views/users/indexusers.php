<h2 style="text-align: center; margin-bottom: 30px;">👤 Quản lý người dùng</h2>

<style>
    .user-container {
        max-width: 1000px;
        margin: 40px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .add-btn {
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

    .add-btn:hover {
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

    .delete-btn {
        background-color: #d63031;
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: 0.2s;
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
        .user-container {
            padding: 20px;
        }
        table th, table td {
            padding: 10px 5px;
            font-size: 13px;
        }
    }
</style>

<div class="user-container">
    <a href="index.php?url=users/add" class="add-btn">➕ Thêm người dùng mới</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($users)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['role']); ?></td>
            <td>
                <a href="index.php?url=users/delete/<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="index.php?url=users&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i; ?>
            </a>
        <?php } ?>
    </div>
</div>
