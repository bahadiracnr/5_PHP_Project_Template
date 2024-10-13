<?php
$servername = "localhost";
$username = "root";
$password = "web363web";
$dbname = "cbaa";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// ekler tablosunu oluştur
$tableCheckQuery = "SHOW TABLES LIKE 'ekler'";
$result = $conn->query($tableCheckQuery);

if ($result->num_rows == 0) {
    $createTableQuery = "CREATE TABLE ekler (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        urun_adi VARCHAR(100) NOT NULL,
        fiyat DECIMAL(10, 2) NOT NULL,
        stok INT(6) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tablo 'ekler' başarıyla oluşturuldu.";
    } else {
        echo "Hata: " . $conn->error;
    }
}

// Ürün ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    
    $sql = "INSERT INTO ekler (urun_adi, fiyat, stok) VALUES ('$urun_adi', '$fiyat', '$stok')";
    if ($conn->query($sql) === TRUE) {
        echo "Ürün başarıyla eklendi!";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Ürün güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    
    $sql = "UPDATE ekler SET urun_adi='$urun_adi', fiyat='$fiyat', stok='$stok' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Ürün başarıyla güncellendi!";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Ürün silme işlemi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM ekler WHERE id=$id";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "Ürün başarıyla silindi!";
    } else {
        echo "Hata: " . $conn->error;
    }
}

// Ürünleri listeleme
$selectQuery = "SELECT * FROM ekler";
$products = $conn->query($selectQuery);

// Veritabanı bağlantısını kapat
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Ana Sayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
          <span class="fs-4">Admin Paneli</span>
        </a>
      </div>

      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-outline-primary me-2" onclick="window.location.href='indeks.php'">Ana Sayfa</button>
      </div>
    </header>
</div>

<div class="container">
    <h2 class="text-center">Ürün Ekle</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="urun_adi" class="form-label">Ürün Adı</label>
            <input type="text" class="form-control" id="urun_adi" name="urun_adi" required>
        </div>
        <div class="mb-3">
            <label for="fiyat" class="form-label">Fiyat</label>
            <input type="number" step="0.01" class="form-control" id="fiyat" name="fiyat" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add_product">Ürün Ekle</button>
    </form>
</div>

<div class="container mt-5">
    <h2 class="text-center">Eklenen Ürünler</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($products->num_rows > 0) {
                while ($row = $products->fetch_assoc()) {
                    echo "<tr>
                        <form method='post' action=''>
                            <td>{$row['id']}</td>
                            <td><input type='text' class='form-control' name='urun_adi' value='{$row['urun_adi']}'></td>
                            <td><input type='number' step='0.01' class='form-control' name='fiyat' value='{$row['fiyat']}'></td>
                            <td><input type='number' class='form-control' name='stok' value='{$row['stok']}'></td>
                            <td>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='update_product' class='btn btn-success'>Güncelle</button>
                                <a href='adminana.php?delete={$row['id']}' class='btn btn-danger'>Sil</a>
                            </td>
                        </form>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Hiç ürün eklenmemiş.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<p class="text-center text-body-secondary">© 2024 Company, Inc</p>

<!-- Bootstrap JS kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
