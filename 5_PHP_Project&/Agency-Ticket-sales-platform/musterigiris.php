<!DOCTYPE html>
<html lang="en">
<head>
  <title>Müşteri ve Personel Kayıt ve Giriş</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    body {
      font: 350 15px Lato, sans-serif;
      line-height: 1.8;
      color: #828282;
    }
    h2 {
      font-size: 24px;
      text-transform: uppercase;
      color: #12585c;
      font-weight: 600;
      margin-bottom: 30px;
    }
    .container {
      padding: 60px 50px;
    }
    .navbar {
      margin-bottom: 0;
      background-color: #312DEB;
      z-index: 9999;
      border: 0;
      font-size: 12px !important;
      line-height: 1.42857143 !important;
      letter-spacing: 4px;
      border-radius: 0;
      font-family: Montserrat, sans-serif;
    }
    .navbar li a, .navbar .navbar-brand {
      color: #fff !important;
    }
    .navbar-nav li a:hover, .navbar-nav li.active a {
      color: #312DEB !important;
      background-color: #fff !important;
    }
    .navbar-default .navbar-toggle {
      border-color: transparent;
      color: #fff !important;
    }
    footer .glyphicon {
      font-size: 10px;
      margin-bottom: 20px;
      color: #312DEB;
    }
    .navbar-brand img {
      height: 100px;
      width: 100px;
      margin-top: -40px;
    }
  </style>
</head>
<body>

<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "scmg";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Müşteri ve Admin tablolarının var olup olmadığını kontrol et
$tables = ["musteri", "admin"];
foreach ($tables as $table) {
    $sql = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Tablo yoksa oluştur
        $sql = "CREATE TABLE $table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ad VARCHAR(255),
            soyad VARCHAR(255),
            email VARCHAR(255) UNIQUE,
            sifre VARCHAR(255)
        )";

        if ($conn->query($sql) === TRUE) {
            // Tablo oluşturuldu
        } else {
            echo "Veritabanı oluşturulamadı: " . $conn->error;
        }
    }
}

$feedback = "";

// Kayıt işlemi
if (isset($_POST['register'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_BCRYPT);
    $userType = $_POST['user_type'];

    $table = $userType == 'admin' ? 'admin' : 'musteri';
    $sql = "INSERT INTO $table (ad, soyad, email, sifre) VALUES ('$ad', '$soyad', '$email', '$sifre')";
    if ($conn->query($sql) === TRUE) {
        $feedback = "<div class='alert alert-success'>Sayın $ad $soyad, kaydınız başarıyla oluşturuldu.</div>";
    } else {
        $feedback = "<div class='alert alert-danger'>Kayıt sırasında bir hata oluştu: " . $conn->error . "</div>";
    }
}

// Giriş işlemi
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $userType = $_POST['user_type'];

    $table = $userType == 'admin' ? 'admin' : 'musteri';
    $sql = "SELECT * FROM $table WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($sifre, $row['sifre'])) {
            $_SESSION['user'] = $row['ad'] . " " . $row['soyad'];
            $redirectPage = $userType == 'admin' ? 'ucus.php' : 'musteriucus.php';
            header("Location: $redirectPage");
            exit();
        } else {
            $feedback = "<div class='alert alert-danger'>Hatalı şifre.</div>";
        }
    } else {
        $feedback = "<div class='alert alert-danger'>Böyle bir kayıt bulunamadı.</div>";
    }
}

// Çıkış işlemi
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="home.php"><img src="images/logo1.jpg" alt="Logo"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="home.php">Anasayfa</a></li>
        <li><a href="musteriucus.php">Uçuşlar</a></li>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<li><form action="" method="POST" style="display:inline-block;"><button type="submit" name="logout" class="btn btn-link" style="color: white; text-decoration: none;">Çıkış Yap</button></form></li>';
        } else {
            echo '<li><a href="musterigiris.php">Giriş Yap / Kayıt Ol</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>

<br><br><br><br>

<div class="container">
  <?php if ($feedback != "") echo $feedback; ?>
  <div class="row">
    <div class="col-md-6">
      <h2>Müşteri Kayıt Formu</h2>
      <form action="" method="POST">
        <div class="form-group">
          <label for="ad">Ad:</label>
          <input type="text" class="form-control" id="ad" name="ad" required>
        </div>
        <div class="form-group">
          <label for="soyad">Soyad:</label>
          <input type="text" class="form-control" id="soyad" name="soyad" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="sifre">Şifre:</label>
          <input type="password" class="form-control" id="sifre" name="sifre" required>
        </div>
        <input type="hidden" name="user_type" value="musteri">
        <button type="submit" name="register" class="btn btn-primary">Kayıt Ol</button>
      </form>
    </div>
    <div class="col-md-6">
      <h2>Müşteri Giriş Formu</h2>
      <form action="" method="POST">
        <div class="form-group">
          <label for="loginEmail">Email:</label>
          <input type="email" class="form-control" id="loginEmail" name="email" required>
        </div>
        <div class="form-group">
          <label for="loginSifre">Şifre:</label>
          <input type="password" class="form-control" id="loginSifre" name="sifre" required>
        </div>
        <input type="hidden" name="user_type" value="musteri">
        <button type="submit" name="login" class="btn btn-primary">Giriş Yap</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <h2>Personel Kayıt Formu</h2>
      <form action="" method="POST">
        <div class="form-group">
          <label for="registerAd">Ad:</label>
          <input type="text" class="form-control" id="registerAd" name="ad" required>
        </div>
        <div class="form-group">
          <label for="registerSoyad">Soyad:</label>
          <input type="text" class="form-control" id="registerSoyad" name="soyad" required>
        </div>
        <div class="form-group">
          <label for="registerEmail">Email:</label>
          <input type="email" class="form-control" id="registerEmail" name="email" required>
        </div>
        <div class="form-group">
          <label for="registerSifre">Şifre:</label>
          <input type="password" class="form-control" id="registerSifre" name="sifre" required>
        </div>
        <input type="hidden" name="user_type" value="admin">
        <button type="submit" name="register" class="btn btn-success">Kayıt Ol</button>
      </form>
    </div>
    <div class="col-md-6">
      <h2>Personel Giriş Formu</h2>
      <form action="" method="POST">
        <div class="form-group">
          <label for="loginEmail">Email:</label>
          <input type="email" class="form-control" id="loginEmail" name="email" required>
        </div>
        <div class="form-group">
          <label for="loginSifre">Şifre:</label>
          <input type="password" class="form-control" id="loginSifre" name="sifre" required>
        </div>
        <input type="hidden" name="user_type" value="admin">
        <button type="submit" name="login" class="btn btn-primary">Giriş Yap</button>
      </form>
    </div>
  </div>
</div>

<footer class="container-fluid text-center">
  <p>&copy; ŞEVMEL || Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
