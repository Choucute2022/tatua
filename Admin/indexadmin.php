<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", "");
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Doanh thu theo tháng
$doanhThuTheoThang = array_fill(1, 12, 0);
$stmt = $pdo->prepare("SELECT MONTH(created_at) AS thang, SUM(total_amount) AS tong FROM orders WHERE YEAR(created_at) = :year GROUP BY thang");
$stmt->execute(['year' => $currentYear]);
foreach ($stmt as $row) {
    $doanhThuTheoThang[(int)$row['thang']] = (int)$row['tong'];
}

// Thống kê nhanh
$statResult = [
    'doanh_thu_hom_nay' => 0,
    'tong_don_hang_nam' => 0,
    'so_khach_hang' => 0
];
$statResult['doanh_thu_hom_nay'] = (int)$pdo->query("SELECT SUM(total_amount) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE YEAR(created_at) = :year");
$stmt->execute(['year' => $currentYear]);
$statResult['tong_don_hang_nam'] = (int)$stmt->fetchColumn();
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT user_id) FROM orders WHERE YEAR(created_at) = :year");
$stmt->execute(['year' => $currentYear]);
$statResult['so_khach_hang'] = (int)$stmt->fetchColumn();

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Tatua Milktea</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #f39c12;
            --light-orange: #fcebd3;
            --dark-orange: #c87f0a;
            --bg: #fffdf8;
            --text: #4b2e1f;
            --white: #fff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        header {
            background: var(--white);
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        header h1 {
            color: var(--orange);
            font-size: 22px;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 240px;
            background: var(--light-orange);
            padding: 24px;
            border-right: 1px solid #ffd89f;
        }

        aside h2 {
            font-size: 16px;
            margin-bottom: 20px;
            color: var(--dark-orange);
        }

        aside a {
            display: block;
            padding: 12px 18px;
            margin-bottom: 10px;
            border-radius: 10px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        aside a:hover,
        aside a.active {
            background: var(--orange);
            color: var(--white);
            font-weight: bold;
        }

        main {
            flex: 1;
            padding: 32px;
        }

        .title {
            font-size: 24px;
            color: var(--dark-orange);
            margin-bottom: 24px;
        }

        .filter select {
            padding: 10px 18px;
            border-radius: 10px;
            border: 1px solid #f5d3a8;
            background: var(--white);
            font-size: 15px;
            margin-bottom: 24px;
        }

        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .card {
            background: linear-gradient(135deg, #f39c12, #f5b041);
            color: var(--white);
            flex: 1;
            min-width: 220px;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.1);
        }

        .card h3 {
            font-size: 14px;
            opacity: 0.9;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .card p {
            font-size: 26px;
            font-weight: bold;
        }

        .chart-container {
            background: var(--white);
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        }

        footer {
            text-align: center;
            padding: 18px;
            font-size: 14px;
            background: var(--white);
            border-top: 1px solid #eee;
            color: #a05e00;
        }

        @media (max-width: 768px) {
            .container { flex-direction: column; }
            aside { width: 100%; }
            .stats { flex-direction: column; }
        }
    </style>
</head>
<body>

<header>
    <h1>🔶 Trang quản trị</h1>
    <nav><a href="logout.php" style="text-decoration: none; color: var(--dark-orange); font-weight: bold;">Đăng xuất</a></nav>
</header>

<div class="container">
    <aside>
        <h2>Menu</h2>
        <a href="indexadmin.php" class="<?= $currentPage == 'indexadmin.php' ? 'active' : '' ?>">Tổng quan</a>
        <a href="indexorders.php" class="<?= $currentPage == 'indexorders.php' ? 'active' : '' ?>">Đơn hàng</a>
        <a href="indexproducts.php" class="<?= $currentPage == 'indexproducts.php' ? 'active' : '' ?>">Sản phẩm</a>
        <a href="indexuser.php" class="<?= $currentPage == 'indexuser.php' ? 'active' : '' ?>">Khách hàng</a>
        <a href="caidat.php" class="<?= $currentPage == 'caidat.php' ? 'active' : '' ?>">Cài đặt</a>
    </aside>

    <main>
        <div class="title">📊 Tổng quan doanh thu <?= $currentYear ?></div>

        <form method="GET" class="filter">
            <label for="year">Chọn năm:</label>
            <select name="year" id="year" onchange="this.form.submit()">
                <?php for ($y = date('Y'); $y >= 2019; $y--): ?>
                    <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>

        <div class="stats">
            <div class="card">
                <h3>Doanh thu hôm nay</h3>
                <p><?= number_format($statResult['doanh_thu_hom_nay'], 0, ',', '.') ?> ₫</p>
            </div>
            <div class="card">
                <h3>Tổng đơn hàng</h3>
                <p><?= $statResult['tong_don_hang_nam'] ?></p>
            </div>
            <div class="card">
                <h3>Số khách hàng</h3>
                <p><?= $statResult['so_khach_hang'] ?></p>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </main>
</div>

<footer>
    &copy; <?= date('Y') ?> - Dashboard quản trị bởi PTIT Dev | Giao diện chuyên nghiệp cam vàng
</footer>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const data = <?= json_encode(array_values($doanhThuTheoThang)) ?>;

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(243, 156, 18, 0.4)');
    gradient.addColorStop(1, 'rgba(243, 156, 18, 0.05)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Array.from({ length: 12 }, (_, i) => `Tháng ${i + 1}`),
            datasets: [{
                label: 'Doanh thu theo tháng (VND)',
                data: data,
                backgroundColor: gradient,
                borderColor: '#f39c12',
                borderWidth: 2,
                hoverBackgroundColor: '#f39c12'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `Doanh thu: ${ctx.parsed.y.toLocaleString('vi-VN')} ₫`
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: value => value.toLocaleString('vi-VN') + ' ₫'
                    }
                }
            }
        }
    });
</script>

</body>
</html>