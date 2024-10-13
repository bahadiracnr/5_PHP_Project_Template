<?php
session_start();

// Hata raporlamayı etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısını kur
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "scmg";
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 'bilgiler' tablosunun var olup olmadığını kontrol et
$sql = "SHOW TABLES LIKE 'bilgiler'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Tablo yoksa oluştur
    $sql = "CREATE TABLE bilgiler (
        id INT AUTO_INCREMENT PRIMARY KEY,
        departure VARCHAR(255),
        arrival VARCHAR(255),
        date DATE,
        time TIME,
        seats INT,
        price DECIMAL(10, 2)
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Tablo başarıyla oluşturuldu.";
    } else {
        echo "Tablo oluşturulamadı: " . $conn->error;
    }
}

// Logout işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST isteği olduğunda bu blok çalışacak
    if (isset($_POST['add_flight'])) {
        // Yeni uçuş ekleme
        $departure = $_POST['departure'];
        $arrival = $_POST['arrival'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $seats = $_POST['seats'];
        $price = $_POST['price'];

        // Girdi verilerini kontrol et
        if (empty($departure) || empty($arrival) || empty($date) || empty($time) || empty($seats) || empty($price)) {
            echo "Tüm alanların doldurulması gerekiyor.";
        } else {
            $sql = "INSERT INTO bilgiler (departure, arrival, date, time, seats, price) VALUES ('$departure', '$arrival', '$date', '$time', '$seats', '$price')";
            if ($conn->query($sql) === TRUE) {
                echo "Yeni uçuş başarıyla eklendi.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } elseif (isset($_POST['update_flight'])) {
        // Mevcut uçuşu güncelleme
        $id = $_POST['id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $seats = $_POST['seats'];
        $price = $_POST['price'];

        $sql = "UPDATE bilgiler SET date='$date', time='$time', seats='$seats', price='$price' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Uçuş başarıyla güncellendi.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['delete_flight'])) {
        // Uçuşu silme
        $id = $_POST['id'];
        $sql = "DELETE FROM bilgiler WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Uçuş başarıyla silindi.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$result = $conn->query("SELECT * FROM bilgiler");

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ŞEVMEL | Uçuş Ekle</title>
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
      height:20%;
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
      margin-top: -45px; /* Logoyu yukarı hizalamak için negatif margin ekleyin */
    }
    .editable {
      display: block;
      width: 100%;
      border: none;
      background: transparent;
      outline: none;
    }
  </style>
</head>
<body>

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
        <li><a href="ucus.php">Uçuşlar</a></li>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<li><form action="" method="POST" style="display:inline-block;"><input type="hidden" name="logout" value="1"><button type="submit" class="btn btn-link" style="color: white; text-decoration: none;">Çıkış Yap</button></form></li>';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>

<br><br><br><br>

<div class="container">
  <h2>Uçuş Bilgileri</h2>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Kalkış</th>
        <th>İniş</th>
        <th>Tarih</th>
        <th>Saat</th>
        <th>Koltuk Sayısı</th>
        <th>Fiyat</th>
        <th>Düzenle</th>
        <th>Sil</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="post" action="">
          <td><input type="hidden" name="id" value="<?= $row['id'] ?>"><?= $row['id'] ?></td>
          <td><?= $row['departure'] ?></td>
          <td><?= $row['arrival'] ?></td>
          <td><input type="date" name="date" value="<?= $row['date'] ?>"></td>
          <td><input type="time" name="time" value="<?= $row['time'] ?>"></td>
          <td><input type="number" name="seats" value="<?= $row['seats'] ?>"></td>
          <td><input type="number" name="price" value="<?= $row['price'] ?>"></td>
          <td><button type="submit" name="update_flight" class="btn btn-primary">Kaydet</button></td>
          <td><button type="submit" name="delete_flight" class="btn btn-danger">Sil</button></td>
        </form>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h2>Yeni Uçuş Ekle</h2>
  <form method="post" action="">
    <div class="form-group">
      <label for="departure">Kalkış:</label>
      <input type="text" class="form-control" id="departure" name="departure">
    </div>
    <div class="form-group">
      <label for="arrival">İniş:</label>
      <input type="text" class="form-control" id="arrival" name="arrival">
    </div>
    <div class="form-group">
      <label for="date">Tarih:</label>
      <input type="date" class="form-control" id="date" name="date">
    </div>
    <div class="form-group">
      <label for="time">Saat:</label>
      <input type="time" class="form-control" id="time" name="time">
    </div>
    <div class="form-group">
      <label for="seats">Koltuk Sayısı:</label>
      <input type="number" class="form-control" id="seats" name="seats">
    </div>
    <div class="form-group">
      <label for="price">Fiyat:</label>
      <input type="number" class="form-control" id="price" name="price">
    </div>
    <button type="submit" name="add_flight" class="btn btn-success">Ekle</button>
  </form>
</div>

<footer class="container-fluid text-center">
  <p>&copy; ŞEVMEL || Tüm hakları saklıdır.</p>
</footer>

</body>
</html>
