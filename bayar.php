<?php
// filepath: c:\xampp\htdocs\Resto\bayar.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data dari GET (misal total bayar)
$total = isset($_GET['total']) ? (int)$_GET['total'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <link rel="stylesheet" href="bayar.css">
</head>
<body>
<div class="bayar-container">
    <h2>Pembayaran</h2>
    <form method="post" action="">
        <div class="form-row">
            <label>Total Bayar</label>
            <input type="text" name="total" value="Rp<?php echo number_format($total, 0, ',', '.'); ?>" readonly>
        </div>
        <div class="form-row">
            <label>Uang Diterima</label>
            <input type="number" name="uang_diterima" id="uang_diterima" min="<?php echo $total; ?>" required oninput="hitungKembalian()">
        </div>
        <div class="form-row">
            <label>Kembalian</label>
            <input type="text" name="kembalian" id="kembalian" readonly>
        </div>
        <button type="submit" class="btn-bayar">Bayar</button>
    </form>
</div>
<script>
function hitungKembalian() {
    var total = <?php echo $total; ?>;
    var diterima = parseInt(document.getElementById('uang_diterima').value) || 0;
    var kembali = diterima - total;
    document.getElementById('kembalian').value = kembali > 0 ? "Rp" + kembali.toLocaleString('id-ID') : "Rp0";
}
</script>
</body>
</html>