<?php
require_once "views/layouts/header.php";

// Kết nối database
$connection = new mysqli("localhost", "root", "", "shoestore_db");
if ($connection->connect_error) {
    die("Kết nối thất bại: " . $connection->connect_error);
}

$today = date('Y-m-d');
$currentYear = date('Y');

// Truy vấn thống kê
$statsSql = "
    SELECT 
        SUM(CASE WHEN DATE(created_at) = ? THEN total ELSE 0 END) AS doanh_thu_hom_nay,
        COUNT(*) AS tong_don_hang,
        COUNT(DISTINCT user_id) AS tong_khach
    FROM orders
    WHERE status = 'completed' AND YEAR(created_at) = ?
";
$stmt = $connection->prepare($statsSql);
$stmt->bind_param("ss", $today, $currentYear);
$stmt->execute();
$statResult = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Doanh thu từng tháng
$chartSql = "
    SELECT MONTH(created_at) AS thang, SUM(total) AS doanh_thu
    FROM orders
    WHERE YEAR(created_at) = ? AND status = 'completed'
    GROUP BY thang
";
$stmt = $connection->prepare($chartSql);
$stmt->bind_param("s", $currentYear);
$stmt->execute();
$chartResult = $stmt->get_result();

$doanhThuTheoThang = array_fill(1, 12, 0);
while ($row = $chartResult->fetch_assoc()) {
    $doanhThuTheoThang[(int)$row['thang']] = (int)$row['doanh_thu'];
}
$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan doanh thu</title>
    <style>
        /* Font import */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            color: #1a202c;
            background: linear-gradient(135deg, #f0f2f5 0%, #e9ecef 100%);
            transition: background-color 0.3s ease;
        }

        .dashboard-container {
            max-width: 1280px;
            margin: 40px auto;
            padding: 32px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .dashboard-container:hover {
            transform: translateY(-4px);
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 32px;
            color: #2d3748;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        .stat-card {
            padding: 20px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card .icon {
            font-size: 24px;
            opacity: 0.3;
            position: absolute;
            right: 16px;
            top: 16px;
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        /* Chart container */
        .chart-container {
            padding: 24px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            margin-top: 32px;
        }

        /* Filter */
        .filter-container {
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-container label {
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
        }

        .filter-container select {
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f7fafc;
            font-size: 14px;
            color: #2d3748;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .filter-container select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 20px;
                padding: 20px;
            }

            .stat-cards {
                grid-template-columns: 1fr;
            }

            .stat-card {
                min-width: 100%;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2><span>📈</span> Tổng quan doanh thu - <?= $currentYear ?></h2>

    <!-- Filter Time -->
    <div class="filter-container">
        <label for="time-filter">Chọn thời gian:</label>
        <select id="time-filter" onchange="window.location.href='?year='+this.value">
            <?php for ($year = 2025; $year >= 2019; $year--): ?>
                <option value="<?= $year ?>" <?= $currentYear == $year ? 'selected' : '' ?>><?= $year ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <!-- Cards thống kê -->
    <div class="stat-cards">
        <div class="stat-card">
            <span class="icon">💰</span>
            <h3>Doanh thu hôm nay</h3>
            <p>$<?= number_format($statResult['doanh_thu_hom_nay'] ?? 0) ?></p>
        </div>
        <div class="stat-card">
            <span class="icon">📦</span>
            <h3>Tổng đơn hàng năm</h3>
            <p><?= number_format($statResult['tong_don_hang']) ?></p>
        </div>
        <div class="stat-card">
            <span class="icon">👥</span>
            <h3>Số khách hàng</h3>
            <p><?= number_format($statResult['tong_khach']) ?></p>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="chart-container">
        <canvas id="doanhThuThangChart"></canvas>
    </div>
</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('doanhThuThangChart').getContext('2d');
    const data = <?= json_encode(array_values($doanhThuTheoThang)) ?>;

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({ length: 12 }, (_, i) => `Tháng ${i + 1}`),
            datasets: [{
                label: 'Doanh thu theo tháng (USD)',
                data: data,
                fill: true,
                backgroundColor: gradient,
                borderColor: '#3b82f6',
                pointBackgroundColor: '#3b82f6',
                pointHoverBackgroundColor: '#1e40af',
                pointHoverBorderColor: '#fff',
                tension: 0.4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 14, family: 'Inter', weight: '500' },
                        color: '#2d3748'
                    }
                },
                tooltip: {
                    backgroundColor: '#2d3748',
                    titleFont: { family: 'Inter', size: 14, weight: '600' },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: context => `Doanh thu: $${context.parsed.y.toLocaleString('en-US')}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    ticks: {
                        font: { family: 'Inter', size: 12 },
                        color: '#4a5568',
                        callback: value => `$${value.toLocaleString('en-US')}`
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 12 },
                        color: '#4a5568'
                    }
                }
            }
        }
    });
</script>

<?php require_once "views/layouts/footer.php"; ?>
</body>
</html>