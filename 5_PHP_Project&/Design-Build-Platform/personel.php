<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dart Vader</title>
    <link rel="icon" type="image/x-icon" href="img/29xX798x.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .custom-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 30px;
        }
        .custom-btn {
            background-color: #007bff;
            border: none;
            color: white;
        }
        .custom-btn:hover {
            background-color: #0056b3;
        }
        .gradient-background {
            background: linear-gradient(90deg, rgba(0, 123, 255, 1) 0%, rgba(255, 0, 150, 1) 100%);
            border-radius: 10px;
            padding: 30px;
        }
        .text-gradient {
            background: -webkit-linear-gradient(90deg, #007bff, #ff0096);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "DGBC";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlanti Kurulamadi: " . $conn->connect_error);
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$message = "";
$table = "personel";
$sql = "SHOW TABLES LIKE '$table'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    // Tablo yoksa oluştur
    $sql = "CREATE TABLE personel(
        personel_id VARCHAR(30) PRIMARY KEY,
        sifre VARCHAR(30) NOT NULL,
        p_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Tablo oluşturulamadı: " . $conn->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['giris'])) {
    $personel_id = test_input($_POST['personel_id']);
    $sifre = test_input($_POST['sifre']);
    $sql = "SELECT * FROM personel WHERE personel_id='$personel_id' AND sifre='$sifre'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['girildi'] = true;
        $_SESSION['role'] = 'personel'; 
        $_SESSION['personel_id'] = $personel_id;
        $_SESSION['sifre'] = $sifre;
        header("Location: personelana.php");
        exit;
    } else {
        $message = "Geçersiz personel id veya şifre";
    }
}
$conn->close();
?>

<main class="flex-shrink-0">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container px-5">
            <a class="navbar-brand" href="index.php"><span class="fw-bolder text-primary"><img src="img/logo.png" width="60px" height="60px" /></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle
navigation"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
<li class="nav-item"><a class="nav-link" href="index.php">Ana Sayfa</a></li>
<li class="nav-item"><a class="nav-link" href="urunler.php">Urunler</a></li>
<li class="nav-item"><a class="nav-link" href="iletisim.php">Iletisim</a></li>
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
<section class="gradient-background py-5">
<div class="container px-5">
<div class="row gx-5 justify-content-center">
<div class="col-lg-6">
<div class="custom-card">
<h3 class="card-title text-center text-gradient">Personel Girişi</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="mb-3">
                            <label for="personel_id" class="form-label">Personel ID</label>
                            <input name="personel_id" type="text" class="form-control" id="personel_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input name="sifre" type="password" class="form-control" id="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn custom-btn" name="giris">Giriş Yap</button>
                        </div>
                    </form>
                    <p class="text-danger mt-3"><?php echo $message; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
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
