<?php
session_start();
// Hanya admin (id_user = 1) yang boleh akses
if (!isset($_SESSION['id_user']) || $_SESSION['id_user'] = 1) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Tidak Ada Akses</title>
        <link rel="stylesheet" href="order.css">
        <link rel="stylesheet" href="dashboard.css">
        <style>
            .no-access {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 48px 0 32px 0;
                color: #ff4f8b;
                font-size: 1.3rem;
                font-weight: bold;
                background: #fff0f6;
                border-radius: 16px;
                margin: 40px 0 0 0;
                max-width: 400px;
                box-shadow: 0 2px 16px #ff4f8b22;
            }
            .no-access img {
                width: 80px;
                margin-bottom: 18px;
                opacity: 0.8;
            }
        </style>
    </head>
    <body>
        <p>Belum di isi apa apa 🤣 </p>
    </body>
    </html>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pesanan</title>
    <link rel="stylesheet" href="order.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .empty-order {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 0 32px 0;
            color: #ff4f8b;
            font-size: 1.3rem;
            font-weight: bold;
            background: #fff0f6;
            border-radius: 16px;
            margin: 40px 0 0 0;
            max-width: 400px;
            box-shadow: 0 2px 16px #ff4f8b22;
        }
        .empty-order img {
            width: 80px;
            margin-bottom: 18px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="logo">
            <img src="https://img.icons8.com/fluency/48/restaurant.png" alt="Logo">
        </div>
        <nav>
            <a href="dashboard.php"><span>🏠</span> Home</a>
            
        </nav>
        <a href="logout.php" class="logout">Log Out</a>
    </div>
    <div class="main-content">
        <h2>Pesanan</h2>
        <div class="empty-order">
            <img src="https://img.icons8.com/fluency/96/empty-box.png" alt="Empty">
            belum di isi apa apa :v
        </div>
    </div>
</div>
</body>
</html>