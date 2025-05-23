<?php

$act = $_GET['act'] ?? '';

    if ($act == 'add' || $act == 'edit') {
        $id = $_GET['id'] ?? '';
        $edit = false;
        $data = ['no_meja' => '', 'tanggal' => '', 'id_user' => '', 'keterangan' => '', 'status_order' => 'Belum'];

        if ($id) {
            $result = mysqli_query($conn, "SELECT * FROM `order` WHERE id_order='$id'");
            $data = mysqli_fetch_assoc($result);
            $edit = true;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $no_meja = $_POST['no_meja'];
            $tanggal = $_POST['tanggal'];
            $id_user = $_POST['id_user'];
            $keterangan = $_POST['keterangan'];
            $status_order = $_POST['status_order'];

            if ($edit) {
                mysqli_query($conn, "UPDATE `order` SET no_meja='$no_meja', tanggal='$tanggal', id_user='$id_user', keterangan='$keterangan', status_order='$status_order' WHERE id_order='$id'") or die(mysqli_error($conn));
            } else {
                mysqli_query($conn, "INSERT INTO `order` (no_meja, tanggal, id_user, keterangan, status_order) VALUES ('$no_meja','$tanggal','$id_user','$keterangan','$status_order')") or die(mysqli_error($conn));
            }

            header("Location: order.php");
            exit;
        }
        } elseif ($act == 'delete') {
        $id = $_GET['id'] ?? '';
        if ($id) {
            mysqli_query($conn, "DELETE FROM `order` WHERE id_order='$id'") or die(mysqli_error($conn));
        }
        header("Location: order.php");
    } else {
    }