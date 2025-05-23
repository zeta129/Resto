<?php
session_start();
include 'koneksi.php';

// Ambil kategori dari URL, default 'all'
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'all';

// Mapping kategori ke WHERE SQL
$where = '';
if ($kategori == 'burger') {
    $where = "AND nama_masakan LIKE '%burger%'";
} elseif ($kategori == 'drinks') {
    $where = "AND (
        nama_masakan LIKE '%cappuccino%' OR
        nama_masakan LIKE '%matcha%' OR
        nama_masakan LIKE '%lemon%' OR
        nama_masakan LIKE '%tea%' OR
        nama_masakan LIKE '%mineral%' OR
        nama_masakan LIKE '%water%'
    )";
}

// Query data masakan sesuai kategori
$sql = "SELECT id_masakan, nama_masakan, harga FROM masakan WHERE 1 $where";
$result = $conn->query($sql);

$masakan = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $masakan[$row['id_masakan']] = $row;
    }
}

// Food cards data
$foods = array(
    2 => array('menu' => 'Origin Burger', 'img' => 'https://img.icons8.com/color/96/hamburger.png'),
    3 => array('menu' => 'Bacon Burger', 'img' => 'https://img.icons8.com/color/96/hamburger.png'),
    4 => array('menu' => 'Beef Burger', 'img' => 'https://img.icons8.com/color/96/hamburger.png'),
    5 => array('menu' => 'Triple Burger', 'img' => 'https://img.icons8.com/color/96/hamburger.png', 'new' => true),
    6 => array('menu' => 'Matcha Latte', 'img' => 'https://img.icons8.com/color/96/matcha.png'),
    7 => array('menu' => 'Cappuccino', 'img' => 'https://img.icons8.com/color/96/coffee-to-go.png'),
    8 => array('menu' => 'Lemon Tea', 'img' => 'https://img.icons8.com/color/96/tea.png'),
    9 => array('menu' => 'Mineral Water', 'img' => 'https://img.icons8.com/color/96/water-bottle.png')
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Resto</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="logo">
            <img src="https://img.icons8.com/fluency/48/restaurant.png" alt="Logo">
        </div>
        <nav>
            <a href="#" class="active"><span>🏠</span> Home</a>
            <a href="pesanan.php"><span>🧾</span> Pesanan</a>
        </nav>
        <a href="logout.php" class="logout">Log Out</a>
    </div>
    <div class="main-content" id="mainContent">
        <div class="menu-category">
            <h2>Menu <span>Category</span></h2>
            <div class="categories">
                <a href="?kategori=all" class="cat-btn<?php if($kategori=='all') echo ' active'; ?>">
                    <img src="https://img.icons8.com/ios-filled/32/fire-element.png"/>
                    <span>ALL</span>
                </a>
                <a href="?kategori=burger" class="cat-btn<?php if($kategori=='burger') echo ' active'; ?>">
                    <img src="https://img.icons8.com/ios-filled/32/hamburger.png"/>
                    <span>Burger</span>
                </a>
                <a href="?kategori=drinks" class="cat-btn<?php if($kategori=='drinks') echo ' active'; ?>">
                    <img src="https://img.icons8.com/ios-filled/32/coffee.png"/>
                    <span>Drinks</span>
                </a>
            </div>
        </div>
        <div class="choose-order">
            <h3>Choose <span>Order</span></h3>
            <div class="food-list">
            <?php
            foreach ($foods as $id => $food) {
                if (!isset($masakan[$id])) continue;
                $nama = htmlspecialchars($masakan[$id]['nama_masakan']);
                $harga = htmlspecialchars($masakan[$id]['harga']);
                $newBadge = isset($food['new']) ? '<span class="new-badge">New</span>' : '';
                $newClass = isset($food['new']) ? ' new' : '';
                ?>
                <div class="food-card interact<?php echo $newClass; ?>" data-menu="<?php echo $nama; ?>">
                    <img src="<?php echo $food['img']; ?>"/>
                    <div class="food-info">
                        <span class="food-title"><?php echo $nama; ?></span>
                        <span class="food-price">Rp. <?php echo $harga; ?></span>
                        <?php echo $newBadge; ?>
                    </div>
                    <form method="post" action="" class="add-cart-form">
                        <input type="hidden" name="menu" value="<?php echo htmlspecialchars($food['menu']); ?>">
                        <input type="hidden" name="harga" value="<?php echo (int)$masakan[$id]['harga']; ?>">
                        <input type="hidden" name="img" value="<?php echo $food['img']; ?>">
                        <input type="number" name="qty" value="1" min="1" class="qty-input">
                        <button type="submit" name="add_to_cart" class="order-now-btn">Tambah</button>
                    </form>
                </div>
                <?php
            }
            ?>
            </div>
        </div>
    </div>
    <div class="cart-sidebar">
        <h3>🛒 Keranjang</h3>
        <?php
        // Keranjang session
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $cart = $_SESSION['cart'];

        // Tambah/kurangi qty dari GET (opsional, bisa pakai AJAX/POST di implementasi nyata)
        if (isset($_GET['cart_action']) && isset($_GET['cart_id'])) {
            $cid = $_GET['cart_id'];
            if ($_GET['cart_action'] == 'plus') {
                if (isset($cart[$cid])) $cart[$cid]['qty']++;
            } elseif ($_GET['cart_action'] == 'min') {
                if (isset($cart[$cid]) && $cart[$cid]['qty'] > 1) $cart[$cid]['qty']--;
            } elseif ($_GET['cart_action'] == 'del') {
                unset($cart[$cid]);
            }
            $_SESSION['cart'] = $cart;
            header("Location: dashboard.php?kategori=$kategori");
            exit;
        }

        if (empty($cart)) {
            echo "<p style='color:#888;'>Keranjang kosong.</p>";
        } else {
            $total = 0;
            echo "<ul style='list-style:none;padding:0;'>";
            foreach ($cart as $cid => $item) {
                $subtotal = $item['harga'] * $item['qty'];
                $total += $subtotal;
                echo "<li style='margin-bottom:16px;border-bottom:1px solid #eee;padding-bottom:8px;'>";
                echo "<div style='display:flex;align-items:center;gap:10px;'>";
                echo "<img src='".htmlspecialchars($item['img'])."' width='36' height='36' style='border-radius:8px;background:#f7f7f7;'>";
                echo "<div style='flex:1;'>";
                echo "<div style='font-weight:600;'>".htmlspecialchars($item['menu'])."</div>";
                echo "<div style='font-size:13px;color:#888;'>Rp. ".number_format($item['harga'])." x {$item['qty']}</div>";
                echo "</div>";
                echo "<div>";
                echo "<a href='?cart_action=min&cart_id=$cid&kategori=$kategori' style='padding:2px 8px;text-decoration:none;'>-</a>";
                echo "<span style='margin:0 4px;'>{$item['qty']}</span>";
                echo "<a href='?cart_action=plus&cart_id=$cid&kategori=$kategori' style='padding:2px 8px;text-decoration:none;'>+</a>";
                echo "</div>";
                echo "<a href='?cart_action=del&cart_id=$cid&kategori=$kategori' style='color:red;margin-left:8px;text-decoration:none;' title='Hapus'>&times;</a>";
                echo "</div>";
                echo "</li>";
            }
            echo "</ul>";
            echo "<div style='font-weight:700;font-size:16px;margin-top:8px;'>Total: Rp. ".number_format($total)."</div>";
            echo "<form method='post' action='order.php' style='margin-top:16px;'>";
            echo "<button type='submit' name='checkout' class='order-now-btn' style='width:100%;'>Checkout</button>";
            echo "</form>";
        }
        ?>
    </div>
</div>
<style>
.cart-sidebar {font-family:'Montserrat',sans-serif;}
@media (max-width:900px) {
    .cart-sidebar {position:static;width:100%;height:auto;box-shadow:none;}
}
body {padding-right:320px;}
@media (max-width:900px) {
    body {padding-right:0;}
}
</style>
<script>
// Tangkap submit form tambah ke keranjang
document.querySelectorAll('.add-cart-form').forEach(function(form){
    form.addEventListener('submit', function(e){
        e.preventDefault();
        var data = new FormData(form);
        fetch('add_to_cart.php', {
            method: 'POST',
            body: data
        }).then(function(){ location.reload(); });
    });
});
</script>
</body>
</html>
