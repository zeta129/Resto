<?php
session_start();
include 'koneksi.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $menu = $_POST['menu'];
    $harga = (int)$_POST['harga'];
    $img = $_POST['img'];
    $qty = (int)$_POST['qty'];

    // Jika sudah ada, tambah qty
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['menu'] == $menu) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = [
            'menu' => $menu,
            'harga' => $harga,
            'img' => $img,
            'qty' => $qty
        ];
    }
    unset($item);

    // Redirect ke order.php agar user melihat keranjang
    header('Location: order.php');
    exit;
}

// Hapus item dari keranjang
if (isset($_GET['remove'])) {
    $remove = $_GET['remove'];
    unset($_SESSION['cart'][$remove]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: order.php');
    exit;
}

// Hitung total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html>
<head>  
    <title>Order Menu</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
<div class="order-menu">
    <h3>Keranjang Pesanan</h3>
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $i => $item): ?>
            <div class="order-item">
                <img src="<?php echo $item['img']; ?>" width="48">
                <div>
                    <span class="order-title"><?php echo htmlspecialchars($item['menu']); ?></span>
                    <span class="order-price">Rp<?php echo number_format($item['harga'], 0, ',', '.'); ?></span>
                    <span class="order-qty">x<?php echo $item['qty']; ?></span>
                </div>
                <a href="order.php?remove=<?php echo $i; ?>" style="color:red;font-weight:bold;margin-left:10px;">Hapus</a>
            </div>
        <?php endforeach; ?>
        <div class="order-summary">
            <div class="summary-row total">
                <span>Total</span>
                <span>Rp<?php echo number_format($total, 0, ',', '.'); ?></span>
            </div>
        </div>
        <form method="get" action="bayar.php">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit" class="order-now-btn">Bayar</button>
        </form>
    <?php else: ?>
        <div class="order-item">
            <span>Keranjang kosong.</span>
        </div>
    <?php endif; ?>
</div>
</body>
</html>