<?php
$servername = "localhost";
$username = "root";
$password = "web364web";
$dbname = "cbaa";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Satın alma işlemi
if (isset($_GET['buy'])) {
    $id = $_GET['buy'];
    $buyQuery = "UPDATE ekler SET stok = stok - 1 WHERE id = $id AND stok > 0";
    $conn->query($buyQuery);
}

// Sıralama işlemi
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$sortQuery = "SELECT * FROM ekler ORDER BY fiyat $order";
$products = $conn->query($sortQuery);

// Veritabanı bağlantısını kapat
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ürünler</title>
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

<div class="container mt-5">
    <div class="d-flex justify-content-end mb-3">
        <a href="urun.php?order=ASC" class="btn btn-secondary me-2">Fiyat (Artan)</a>
        <a href="urun.php?order=DESC" class="btn btn-secondary">Fiyat (Azalan)</a>
    </div>
    <div class="row">
        <?php
        if ($products->num_rows > 0) {
            while ($row = $products->fetch_assoc()) {
                echo "<div class='col-md-4'>
                    <div class='card mb-4'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row['urun_adi']}</h5>
                            <p class='card-text'>Fiyat: {$row['fiyat']} TL</p>
                            <p class='card-text'>Stok: {$row['stok']}</p>
                            <a href='urun.php?buy={$row['id']}' class='btn btn-primary'>Satın Al</a>
                        </div>
                    </div>
                </div>";
            }
        } else {
            echo "<p class='text-center'>Hiç ürün eklenmemiş.</p>";
        }
        ?>
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
