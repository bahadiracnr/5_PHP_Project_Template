<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "DGBC";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['odeme'])) {
    foreach ($_SESSION['sepet'] as $urun_id => $adet) {
        $sql = "UPDATE urunler SET stok = stok - $adet WHERE id = $urun_id";
        $conn->query($sql);
    }
    $_SESSION['sepet'] = array();
    header("Location: sepet.php?success=1");
    exit;
}
if (isset($_GET['sil'])) {
    $urun_id = $_GET['sil'];
    if (isset($_SESSION['sepet'][$urun_id])) {
        unset($_SESSION['sepet'][$urun_id]);
    }
    header("Location: sepet.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Sepet</title>
    <link rel="icon" type="image/x-icon" href="img/29xX798x.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column h-100 bg-light">
<main class="flex-shrink-0">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container px-5">
            <a class="navbar-brand" href="index.php"><span class="fw-bolder text-primary"><img src="img/logo.png" width="60px" height="60px" /></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                    <li class="nav-item"><a class="nav-link" href="index.php">Ana Sayfa</a></li>
                    <li class="nav-item"><a class="nav-link" href="urunler.php">Urunler</a></li>
                    <li class="nav-item"><a class="nav-link" href="iletisim.php">Iletisim</a></li>
                    <li class="nav-item"><a class="nav-link" href="sepet.php">Sepet</a></li>
                    <?php if (isset($_SESSION['girildi'])): ?>
                        <?php if ($_SESSION['role'] == 'personel'): ?>
                            <li class="nav-item"><a class="nav-link" href="personelana.php">Ürün Ekle</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="cikis.php">Çıkış Yap</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Sepet Section -->
    <section class="py-5">
        <div class="container px-5 mb-5">
            <h1 class="display-5 fw-bolder mb-4"><span class="text-gradient d-inline">Sepet</span></h1>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    Ödeme başarıyla gerçekleştirildi!
                </div>
            <?php endif; ?>
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-11 col-xl-9 col-xxl-8">
                    <div class="row gx-5">
                    <?php
                    if (isset($_SESSION['sepet']) && !empty($_SESSION['sepet'])) {
                        $toplam = 0;
                        foreach ($_SESSION['sepet'] as $urun_id => $adet) {
                            $sql = "SELECT * FROM urunler WHERE id = $urun_id";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $toplam += $row["fiyati"] * $adet;
                                echo '<div class="col-md-4 mb-5">';
                                echo '<div class="card h-100 shadow border-0">';
                                echo '    <div class="card-body p-4">';
                                echo '        <h5 class="card-title fw-bolder">' . $row["adi"] . '</h5>';
                                echo '        <p class="card-text">Fiyat: ' . $row["fiyati"] . ' TL</p>';
                                echo '        <p class="card-text">Adet: ' . $adet . '</p>';
                                echo '        <p class="card-text">Toplam: ' . ($row["fiyati"] * $adet) . ' TL</p>';
                                echo '    </div>';
                                echo '    <div class="card-footer p-4 pt-0 bg-transparent border-top-0">';
                                echo '        <div class="d-grid"><a class="btn btn-outline-dark mt-auto" href="sepet.php?sil=' . $row["id"] . '">Sepetten Kaldır</a></div>';
                                echo '    </div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                        echo '<div class="col-12">';
                        echo '<h3>Toplam Fiyat: ' . $toplam . ' TL</h3>';
                        echo '<a class="btn btn-success" href="sepet.php?odeme=1">Ödeme Yap</a>';
                        echo '</div>';
                    } else {
                        echo '<p class="text-center">Sepetiniz boş.</p>';
                    }
                    // Veritabanı bağlantısını kapat
                    $conn->close();
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Footer-->
<footer class="bg-white py-4 mt-auto">
    <div class="container px-5">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row">
            <div class="col-auto"><div class="small m-0">Copyright &copy; Dart Vader 2024</div></div>
            <div class="col-auto">
                <a class="small" href="#!">Hakkimizda</a>
                <span class="mx-1">&middot;</span>
                <a class="small" href="#!">Sartlar</a>
                <span class="mx-1">&middot;</span>
                <a class="small" href="iletisim.php">Iletisim</a>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
