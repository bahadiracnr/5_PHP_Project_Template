<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "scmg";
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Bağlanti Kurulamadi: " . $conn->connect_error);
}
$sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    // Veritabanı yoksa oluştur
    $sql = "CREATE DATABASE $dbname";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Veritabanı oluşturulamadı: " . $conn->error;
    }
} else {
}
$conn->close();
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlanti Kurulamadi: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ŞEVMEL | Anasayfa</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    body {
      font: 400 15px Lato, sans-serif;
      line-height: 1.8;
      color: #555555;
      margin: 0;
      background-color: #f8f8f8;
    }

    h1, h2 {
      text-transform: uppercase;
      color: #333;
      font-weight: 700;
      margin-bottom: 30px;
    }

    h3 {
      text-transform: uppercase;
      color: #555;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .jumbotron {
      color: #fff;
      background: rgba(49, 45, 235, 0.8) url('images/ucak.jpg') no-repeat center center;
      background-size: cover;
      padding: 150px 25px;
      font-family: Montserrat, sans-serif;
      text-align: center;
    }

    .btn {
      background-color: #312DEB;
      color: #fff;
    }

    .container-fluid {
      padding: 60px 50px;
      background-color: rgba(255, 255, 255, 0.9);
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

    .meltem {
      margin-top: 4%;
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

    .navbar-brand img {
      height: 80px;
      width: 80px;
      margin-top: -10px;
    }

    footer .glyphicon {
      font-size: 20px;
      margin-bottom: 20px;
      color: #312DEB;
    }

    footer p {
      margin: 0;
    }

    .container-fluid.text-center {
      padding: 10px 10px;
    }

    .services {
      background-color: rgba(249, 249, 249, 0.9);
      padding: 50px 0;
    }

    .service {
      padding: 20px;
    }

    .featured-flights {
      background-color: rgba(255, 255, 255, 0.9);
      margin: 30px 0;
    }

    .team {
      background-color: rgba(241, 241, 241, 0.9);
      padding: 50px 0;
    }

    .team-member {
      padding: 20px;
    }

    .blog {
      background-color: rgba(245, 245, 245, 0.9);
      margin: 30px 0;
    }

    .blog-post {
      padding: 20px;
    }

    .row.content {
      height: 100%;
    }

    .sidenav {
      background-color: rgba(49, 45, 235, 0.8);
      color: white;
      padding: 15px;
      height: 100%;
      text-align: center; /* Align text center */
    }

    .sidenav a {
      color: white;
      text-decoration: none;
    }

    .sidenav a:hover {
      color: #312DEB;
    }

    /* New styles for added content */
    .recommended-destinations, .customer-reviews, .about-us, .special-offers, .travel-tips, .gallery {
      padding: 50px 0;
      text-align: center;
    }

    .recommended-destinations h2, .customer-reviews h2, .about-us h2, .special-offers h2, .travel-tips h2, .gallery h2 {
      color: #333;
      margin-bottom: 30px;
    }
  </style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

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
      <div class="meltem">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="home.php">Anasayfa</a></li>
          <li><a href="musterigiris.php">Giriş Yap / Kayıt Ol</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="jumbotron">
  <h1>ŞEVMEL Uçuşlar</h1>
  <p>En uygun uçak bileti fırsatları</p>
  <button class="btn btn-default btn-lg" onclick="window.location.href = 'musterigiris.php'">Şimdi Keşfet</button>

</div>
<!-- About Us -->
<div class="about-us">
  <div class="container">
    <h2>BİZ KİMİZ?</h2>
    <p>ŞEVMEL, Türkiye'nin önde gelen yurtiçi uçuş sağlayıcılarından biridir. 10 yılı aşkın süredir havacılık sektöründe faaliyet gösteren firmamız, geniş uçuş ağı, kaliteli hizmet anlayışı ve müşteri memnuniyeti odaklı yaklaşımı ile tanınmaktadır.

    Misyonumuz, müşterilerimize güvenli, konforlu ve zamanında uçuş deneyimi sunarak seyahatlerini keyifli bir hâle getirmektir. Müşteri memnuniyetini her zaman ön planda tutarak, ihtiyaçları en iyi şekilde karşılamak ve beklentileri aşmak için sürekli olarak çalışıyoruz.

    ŞEVMEL olarak, teknolojik yenilikleri yakından takip ediyor ve sürekli olarak hizmet kalitemizi artırmak için çalışıyoruz. Modern filomuz, uzman personelimiz ve güvenilir iş ortaklarımız ile birlikte, seyahat deneyimlerini mükemmelleştirmek için sürekli olarak çaba sarf ediyoruz.

    Vizyonumuz, havacılık sektöründe öncü bir marka olarak Türkiye'nin dört bir yanına kesintisiz ve kaliteli hizmet sunmaktır. Müşterilerimize en uygun fiyatlarla en iyi hizmeti sunmak ve seyahatlerini unutulmaz bir deneyime dönüştürmek için sürekli olarak çalışıyoruz.

    ŞEVMEL'e hoş geldiniz. Sizinle birlikte daha nice keyifli ve güvenli uçuşlara yelken açmayı dört gözle bekliyoruz.</p>
  </div>
</div>
<!-- Recommended Destinations -->
<div class="recommended-destinations">
  <div class="container">
    <h2>ÖNERİLEN DESTİNASYONLAR</h2>
    <div class="row">
      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
      <img src="images/istanbul.jpg" alt="İstanbul" style="width: 100%;">
        <h3>İSTANBUL</h3>
        <p>Boğaz manzarası eşliğinde tarih ve lezzetin buluştuğu şehir.</p>
      </div>

      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
        <img src="images/antalya.jpg" alt="Antalya" style="width: 100%;">
        <h3>ANTALYA</h3>
        <p>Harika plajları ve tarihi dokusuyla ünlü Akdeniz cenneti.</p>
      </div>

      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
        <img src="images/izmir.jpg" alt="İzmir" style="width: 100%;">
        <h3>İZMİR</h3>
        <p>Ege'nin incisi, sıcakkanlı insanları ve lezzetli yemekleriyle ünlü şehir.</p>
      </div>
    </div>
  </div>
</div>

<!-- Customer Reviews -->
<div class="customer-reviews">
  <div class="container">
    <h2>MÜŞTERİ YORUMLARI</h2>
    <div class="row">
      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
        <img src="images/kadın.png" alt="kadın" style="width: 100%;">
        <p>"ŞEVMEL ile seyahat etmek harikaydı, personel çok ilgiliydi!"</p>
      </div>
      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
        <img src="images/erkek.png" alt="erkek" style="width: 100%;">
        <p>"Uçuşlarımız zamanında ve konforluydu, herkese tavsiye ederim."</p>
      </div>
      <div class="col-sm-4 destination" style="margin-bottom: 30px;">
        <img src="images/kadın.png" alt="kadın" style="width: 100%;">
        <p>"ŞEVMEL'e her zaman güveniyorum, harika bir deneyimdi!"</p>
      </div>
    </div>
  </div>
</div>



<!-- Special Offers -->
<div class="special-offers">
  <div class="container">
    <h2>GÜNCEL KAMPANYALAR</h2>
    <div class="row">
      <div class="col-sm-4 col-sm-offset-4 destination" style="margin-bottom: 30px;">
        <img src="images/kampanya.jpg" alt="Güncel Kampanyalar" style="width: 100%;">
       
        <p>ŞEVMEL'in özel kampanyalarından yararlanarak harika indirimlerden faydalanın. Kaçırmayın!</p>
      </div>
    </div>
  </div>
</div>

<!-- Travel Tips -->
<div class="travel-tips">
  <div class="container">
    <h2>SEYAHAT İPUÇLARI</h2>
    <div class="row">
      <div class="col-sm-4 col-sm-offset-4 destination" style="margin-bottom: 30px;">
        <img src="images/seyahat.png" alt="Seyahat İpuçları" style="width: 100%;">
        <p>Havaalanında yapılması gerekenler, uçuş sırasında nelere dikkat edilmeli gibi faydalı bilgiler için seyahat ipuçlarına göz atın.</p>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript for scrolling -->
<script>
  function scrollToContent() {
    var yOffset = document.getElementById('myNavbar').offsetHeight;
    window.scrollTo({top: document.querySelector('.about-us').offsetTop - yOffset, behavior: 'smooth'});
  }
</script>
</body>
</html>
<!-- JavaScript for scrolling -->
<script>
  function scrollToContent() {
    var yOffset = document.getElementById('myNavbar').offsetHesight;
    window.scrollTo({top: document.querySelector('.about-us').offsetTop - yOffset, behavior: 'smooth'});
  }
</script>
</form>
</div>
<footer class="container-fluid text-center">
  <p>&copy; ŞEVMEL || Tüm hakları saklıdır. </p>
</footer>
</body>
</html>

