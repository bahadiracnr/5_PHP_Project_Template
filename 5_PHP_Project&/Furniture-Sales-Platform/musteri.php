<?php
$servername = "localhost";
$username = "root";
$password = "web364web";
$dbname = "cbaa";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// musteri tablosunu oluştur
$tableCheckQuery = "SHOW TABLES LIKE 'musteri'";
$result = $conn->query($tableCheckQuery);

if ($result->num_rows == 0) {
    $createTableQuery = "CREATE TABLE musteri (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(50) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($createTableQuery) === TRUE) {
        echo "Tablo 'musteri' başarıyla oluşturuldu.";
    } else {
        echo "Hata: " . $conn->error;
    }
}

// Kayıt işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "INSERT INTO musteri (email, password) VALUES ('$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "Kayıt başarılı!";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM musteri WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        header("Location: urun.php");
        exit();
    } else {
        echo "Geçersiz email veya şifre.";
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
        <button type="button" class="btn btn-outline-primary me-2" onclick="window.location.href='musteriGiris.php'">Giriş Yap / Kaydol</button>
        <button type="button" class="btn btn-outline-primary me-2" onclick="window.location.href='admin.php'">Personel Giriş</button>
        
      </div>
    </header>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Kayıt Ol</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="registerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Şifre</label>
                    <input type="password" class="form-control" id="registerPassword" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="register">Kayıt Ol</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Giriş Yap</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="loginEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Şifre</label>
                    <input type="password" class="form-control" id="loginPassword" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" name="login">Giriş Yap</button>
            </form>
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
