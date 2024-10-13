<?php
$servername = "localhost";
$username = "root";
$password = "web363web";
$dbname = "cbaa";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Veritabanının var olup olmadığını kontrol et
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Veritabanı yoksa oluştur
    $sql = "CREATE DATABASE $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Veritabanı başarıyla oluşturuldu.";
    } else {
        echo "Veritabanı oluşturulamadı: " . $conn->error;
    }
}

// Veritabanına bağlan
$conn->select_db($dbname);

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

// Veritabanı bağlantısını kapat
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .card {
            width: 18rem;
        }
    </style>
</head>
<body>
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
      <div class="col-md-3 mb-2 mb-md-0">
        <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
          <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
          <span class="fs-4">BURCAN MOBİLYA</span>
        </a>
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <li><a href="indeks.php" class="nav-link px-2 link-secondary">Anasayfa</a></li>
        <li><a href="urun.php" class="nav-link px-2">Ürünler</a></li>
        <li><a href="#" class="nav-link px-2">İletişim</a></li>
        <li><a href="#" class="nav-link px-2">Hakkında</a></li>
      </ul>

      <div class="col-md-3 text-end">
        <button type="button" class="btn btn-outline-primary me-2" onclick="window.location.href='musteri.php'">Giriş Yap / Kaydol</button>
        <button type="button" class="btn btn-outline-primary me-2" onclick="window.location.href='admin.php'">Personel Giriş</button>
        
      </div>
    </header>
</div>

<div id="carouselExample" class="carousel slide">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="slide1.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="slide2.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="slide3.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Geri</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">İleri</span>
  </button>
</div>

<div class="card-container">
    <div class="card">
        <img src="card1.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Konfor</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>

    <div class="card">
        <img src="card2.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Kalite</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>

    <div class="card">
        <img src="card3.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Uygun Fiyatlar</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
</div>

<p class="text-center text-body-secondary">© 2024 Company, Inc</p>

<!-- Bootstrap JS kütüphanesi -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>