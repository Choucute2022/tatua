<h2>Thêm người dùng</h2>
<?php if (isset($error) && $error) { ?>
    <div class="error-message"><?php echo $error; ?></div>
<?php } ?>
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required><br>
    <label>Mật khẩu:</label>
    <input type="password" name="password" required><br>
    <label>Vai trò:</label>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="user" selected>User</option>
    </select><br>
    <button type="submit" name="add_user">Thêm người dùng</button>
</form>
<a href="index.php?url=users">Quay lại danh sách người dùng</a>