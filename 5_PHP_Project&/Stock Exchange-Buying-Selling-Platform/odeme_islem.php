<?php
session_start();
include('db.php'); // Veritabanı bağlantısını dahil edin

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ödeme bilgilerini işleme
    $odeme_turu = $_POST['odeme_turu'];
    
    if ($odeme_turu == 'kredi_karti') {
        $kart_numarasi = $_POST['kart_numarasi'];
        $ad_soyad = $_POST['ad_soyad'];
        $son_kullanma_tarihi = $_POST['son_kullanma_tarihi'];
        $cvv = $_POST['cvv'];
        // Kredi kartı bilgilerini işleyin (ödeme API'sine gönderme, doğrulama vb.)
    } elseif ($odeme_turu == 'banka_havalesi') {
        $hesap_sahibi = $_POST['hesap_sahibi'];
        $hesap_numarasi = $_POST['hesap_numarasi'];
        // Banka havalesi bilgilerini işleyin (ödeme API'sine gönderme, doğrulama vb.)
    }

    // Ödeme başarılıysa, sepetin kontenjanını güncelle
    if (isset($_SESSION['sepet'])) {
        foreach ($_SESSION['sepet'] as $urun_id) {
            $result = mysqli_query($conn, "SELECT kontenjan FROM egitim WHERE id='$urun_id'");
            $egitim = mysqli_fetch_assoc($result);

            if ($egitim && $egitim['kontenjan'] > 0) {
                $yeni_kontenjan = $egitim['kontenjan'] - 1;
                mysqli_query($conn, "UPDATE egitim SET kontenjan='$yeni_kontenjan' WHERE id='$urun_id'");
            }
        }
        // Sepeti boşalt
        unset($_SESSION['sepet']);
    }

    // Kullanıcıyı sepet sayfasına yönlendir
    echo "<script>alert('Ödeme işlemi başarılı!'); window.location.href='sepet.php';</script>";
    exit();
}
?>
