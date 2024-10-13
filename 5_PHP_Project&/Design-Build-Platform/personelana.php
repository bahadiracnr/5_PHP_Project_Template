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
            margin-bottom: 20px;
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
    die("Connection failed: " . $conn->connect_error);
}
$message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['urun_ekle'])) {
    $adi = test_input($_POST['adi']);
    $fiyati = test_input($_POST['fiyati']);
    $stok = test_input($_POST['stok']);
    $kategori = test_input($_POST['kategori']);
    $sql = "INSERT INTO urunler (adi, fiyati, stok, kategori) VALUES ('$adi', '$fiyati', '$stok', '$kategori')";
    if ($conn->query($sql) === TRUE) {
        $message = "Ürün başarıyla eklendi.";
    } else {
        $message = "Ürün eklenemedi: " . $conn->error;
    }
}
if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $sql = "DELETE FROM urunler WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Ürün başarıyla silindi.";
    } else {
        $message = "Ürün silinemedi: " . $conn->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['urun_guncelle'])) {
    $id = test_input($_POST['id']);
    $adi = test_input($_POST['adi']);
    $fiyati = test_input($_POST['fiyati']);
    $stok = test_input($_POST['stok']);
    $kategori = test_input($_POST['kategori']);
    $sql = "UPDATE urunler SET adi='$adi', fiyati='$fiyati', stok='$stok', kategori='$kategori' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Ürün başarıyla güncellendi.";
    } else {
        $message = "Ürün güncellenemedi: " . $conn->error;
    }
}
$sql = "SELECT * FROM urunler";
$result = $conn->query($sql);
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<main class="flex-shrink-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container px-5">
            <a class="navbar-brand" href="index.php"><span class="fw-bolder text-primary"><img src="img/logo.png" width="60px" height="60px" /></span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
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
                <div class="col-lg-8">
                    <div class="custom-card">
                        <h3 class="card-title text-center text-gradient">Ürün Ekle</h3>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="mb-3">
                                <label for="adi" class="form-label">Ürün Adı</label>
                                <input name="adi" type="text" class="form-control" id="adi" required>
                            </div>
                            <div class="mb-3">
                                <label for="fiyati" class="form-label">Ürün Fiyatı</label>
                                <input name="fiyati" type="text" class="form-control" id="fiyati" required>
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input name="stok" type="number" class="form-control" id="stok" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Ürün Kategorisi</label>
                                <input name="kategori" type="text" class="form-control" id="kategori" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn custom-btn" name="urun_ekle">Ürün Ekle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="gradient-background py-5">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-10">
                    <div class="custom-card">
                        <h3 class="card-title text-center text-gradient">Ürün Listesi</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Ürün Adı</th>
                                    <th scope="col">Fiyatı</th>
                                    <th scope="col">Stok</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                                <th scope="row"><?php echo $row["id"]; ?></th>
                                                <td><input type="text" name="adi" value="<?php echo $row["adi"]; ?>" class="form-control" required></td>
                                                <td><input type="text" name="fiyati" value="<?php echo $row["fiyati"]; ?>" class="form-control" required></td>
                                                <td><input type="number" name="stok" value="<?php echo $row["stok"]; ?>" class="form-control" required></td>
                                                <td><input type="text" name="kategori" value="<?php echo $row["kategori"]; ?>" class="form-control" required></td>
                                                <td>
                                                    <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm" name="urun_guncelle">Kaydet</button>
                                                    <a href="personelana.php?sil=<?php echo $row["id"]; ?>" class="btn btn-danger btn-sm">Sil</a>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Ürün bulunamadı.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
