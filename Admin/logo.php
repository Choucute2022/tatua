<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Logo 3D Tatua Milktea</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap');

    body {
      background: linear-gradient(135deg, #fff3e0, #ffe0b2);
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo-3d {
      font-family: 'Montserrat', sans-serif;
      font-size: 64px;
      font-weight: 800;
      color: #FFA726;
      letter-spacing: 2px;
      position: relative;
      transform-style: preserve-3d;
      perspective: 1000px;
      cursor: pointer;
      transition: transform 0.6s ease, text-shadow 0.6s ease;
      text-shadow:
        1px 1px 0 #ef6c00,
        2px 2px 0 #ef6c00,
        3px 3px 0 #ef6c00,
        4px 4px 10px rgba(0,0,0,0.15);
    }

    .logo-3d:hover {
      transform: rotateX(8deg) rotateY(20deg) scale(1.03);
      text-shadow:
        1px 1px 0 #ef6c00,
        2px 2px 0 #ef6c00,
        3px 3px 0 #ef6c00,
        4px 4px 15px rgba(0,0,0,0.3);
    }

    /* Optional glowing border */
    .logo-3d::after {
      content: '';
      position: absolute;
      top: -10px;
      left: -10px;
      right: -10px;
      bottom: -10px;
      border: 3px solid rgba(255, 167, 38, 0.4);
      border-radius: 12px;
      box-shadow: 0 0 30px rgba(255, 152, 0, 0.3);
      z-index: -1;
    }
  </style>
</head>
<body>
  <div class="logo-3d">Tatua Milktea</div>
</body>
</html>
