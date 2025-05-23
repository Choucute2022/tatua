<?php
// Kết nối CSDL
$pdo = new PDO("mysql:host=localhost;dbname=duan;charset=utf8", "root", ""); // Thay đổi user/pass nếu cần
$currentYear = date('Y');

// Lấy dữ liệu doanh thu theo tháng
$doanhThuTheoThang = array_fill(1, 12, 0);
$stmt = $pdo->prepare("SELECT MONTH(created_at) AS thang, SUM(total_amount) AS tong FROM orders WHERE YEAR(created_at) = :year GROUP BY thang");
$stmt->execute(['year' => $currentYear]);

foreach ($stmt as $row) {
    $doanhThuTheoThang[(int)$row['thang']] = (int)$row['tong'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Biểu đồ doanh thu theo tháng</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #fffaf0;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #e67e22;
            margin-bottom: 40px;
        }

        .chart-container {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            background: #fff8e1;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <h2>📈 Biểu đồ doanh thu theo tháng - <?= $currentYear ?></h2>

    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const data = <?= json_encode(array_values($doanhThuTheoThang)) ?>;

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(243, 156, 18, 0.4)');
        gradient.addColorStop(1, 'rgba(243, 156, 18, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Array.from({ length: 12 }, (_, i) => `Tháng ${i + 1}`),
                datasets: [{
                    label: 'Doanh thu theo tháng (VND)',
                    data: data,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#f39c12',
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#e67e22'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `Doanh thu: ${ctx.parsed.y.toLocaleString('vi-VN')} ₫`
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
