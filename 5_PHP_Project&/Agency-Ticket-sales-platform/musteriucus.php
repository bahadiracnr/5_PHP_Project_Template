<?php
session_start();

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

// "Satın Al" butonuna tıklandığında seats değerini güncelle
if (isset($_POST['satinal'])) {
    $id = $_POST['id'];
    $sql = "UPDATE bilgiler SET seats = seats - 1 WHERE id = $id AND seats > 0";
    $conn->query($sql);
    
    // Koltuk seçimi sayfasına yönlendir
    header("Location: koltuksec.php?id=$id");
    exit();
}

// Fiyata göre sıralama butonuna tıklandığında
$orderBy = "";
if (isset($_POST['order_price'])) {
    $orderBy = "ORDER BY price ASC";
}

// bilgiler tablosundan verileri çek
$sql = "SELECT * FROM bilgiler $orderBy";
$result = $conn->query($sql);

// çıkış işlemi
if (isset($_POST['logout'])) {
    // Oturumu sonlandır
    session_unset(); // Tüm oturum değişkenlerini siler
    session_destroy(); // Oturumu sonlandırır

    // Kullanıcıyı bir sayfaya yönlendirme (örneğin ana sayfa)
    header("Location: home.php");
    exit(); // Yönlendirmeden sonra script'in devam etmesini engeller
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ŞEVMEL | Uçuşlar</title>
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
      margin-top: -45px;
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
  <h2>Uçuş Bilgileri</h2>
  <form method="post" action="">
    <button type="submit" name="order_price" class="btn btn-info">Fiyata Göre Sırala</button>
  </form>
  <br>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Uçuş No</th>
        <th>Kalkış</th>
        <th>Varış</th>
        <th>Tarih</th>
        <th>Saat</th>
        <th>Fiyat</th>
        <th>Satın Al</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>"; // Uçuş No olarak ID kullanıldı
              echo "<td>" . $row['departure'] . "</td>";
              echo "<td>" . $row['arrival'] . "</td>";
              echo "<td>" . $row['date'] . "</td>"; // Tarih eklendi
              echo "<td>" . $row['time'] . "</td>"; // Saat eklendi
              echo "<td>" . $row['price'] . "</td>";
              echo "<td>";
              if ($row['seats'] > 0) {
                  echo "<form method='post' action=''>
                          <input type='hidden' name='id' value='" . $row['id'] . "'>
                          <button type='submit' name='satinal' class='btn btn-success'>Satın Al</button>
                        </form>";
              } else {
                  echo "Uygun koltuk bulunmamaktadır";
              }
              echo "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='7'>Hiç uçuş bulunamadı.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<footer class="container-fluid text-center">
  <br>
  <br>
  <br>
  <br>
  <p>&copy; ŞEVMEL || Tüm hakları saklıdır.</p>
</footer>

</body>
</html>

<?php
// Veritabanı bağlantısını kapat
$conn->close();
?>
