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

// Koltuklar tablosunun var olup olmadığını kontrol et ve yoksa oluştur
$table_check_query = "SHOW TABLES LIKE 'koltuklar'";
$table_check_result = $conn->query($table_check_query);

if ($table_check_result->num_rows == 0) {
    $create_table_query = "CREATE TABLE koltuklar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ucus_id INT NOT NULL,
        koltuk_no INT NOT NULL,
        cinsiyet CHAR(1) NOT NULL
    )";
    
    if ($conn->query($create_table_query) === TRUE) {
        echo "Koltuklar tablosu başarıyla oluşturuldu.";
    } else {
        die("Tablo oluşturulurken hata: " . $conn->error);
    }
}

$id = $_GET['id'];
$mesaj = "";

// Koltuk sayısını `bilgiler` tablosundan al
$sql_ucus = "SELECT seats FROM bilgiler WHERE id = $id";
$result_ucus = $conn->query($sql_ucus);
$koltuk_sayisi = 0;
if ($result_ucus->num_rows > 0) {
    $row_ucus = $result_ucus->fetch_assoc();
    $koltuk_sayisi = $row_ucus['seats'];
}

// Koltuk seçim işlemi
if (isset($_POST['koltuk_sec'])) {
    $koltuk_no = $_POST['koltuk_no'];
    $cinsiyet = $_POST['cinsiyet'];
    
    $sql = "INSERT INTO koltuklar (ucus_id, koltuk_no, cinsiyet) VALUES ($id, '$koltuk_no', '$cinsiyet')";
    if ($conn->query($sql) === TRUE) {
        $sql_ucus = "SELECT * FROM bilgiler WHERE id = $id";
        $result_ucus = $conn->query($sql_ucus);
        if ($result_ucus->num_rows > 0) {
            $ucus = $result_ucus->fetch_assoc();
            $kalkis_yeri = $ucus['departure'];
            $kalkis_saati = $ucus['time'];
            $mesaj = "Sayın " . $_SESSION['user'] . ", " . $id . " id'li uçuşunuz için koltuk numaranız " . $koltuk_no . ". Kalkış yeri: " . $kalkis_yeri . ", Kalkış saati: " . $kalkis_saati . ". Güzel uçuşlar dileriz.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mevcut koltukları çek
$sql = "SELECT * FROM koltuklar WHERE ucus_id = $id";
$result = $conn->query($sql);
$koltuklar = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $koltuklar[$row['koltuk_no']] = $row['cinsiyet'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>ŞEVMEL | Koltuk Seçimi</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    .seat {
        width: 30px;
        height: 30px;
        margin: 5px;
        text-align: center;
        line-height: 30px;
        border: 1px solid #ccc;
        display: inline-block;
        cursor: pointer;
    }
    .male {
        background-color: blue;
        color: white;
    }
    .female {
        background-color: pink;
        color: white;
    }
    .available {
        background-color: white;
    }
    .selected {
        background-color: yellow;
    }
    .seat-row {
        display: flex;
        justify-content: space-between;
    }
    .left-column, .right-column {
        display: inline-block;
        vertical-align: top;
        width: 48%;
    }
    .middle-space {
        width: 4%;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Koltuk Seçimi</h2>
  <div class="seat-row">
    <div class="left-column">
      <?php
      $row_count = 0;
      $left_column_seats = ceil($koltuk_sayisi / 2);
      for ($i = 1; $i <= $left_column_seats; $i++) {
          $class = "available";
          if (isset($koltuklar[$i])) {
              if ($koltuklar[$i] == 'K') {
                  $class = "female";
              } else if ($koltuklar[$i] == 'E') {
                  $class = "male";
              }
          }
          echo "<div class='seat $class' data-seat='$i'>$i</div>";
          if (++$row_count % 3 == 0) {
              echo "<br>";
          }
      }
      ?>
    </div>
    <div class="middle-space"></div>
    <div class="right-column">
      <?php
      $row_count = 0;
      $right_column_start = $left_column_seats + 1;
      for ($i = $right_column_start; $i <= $koltuk_sayisi; $i++) {
          $class = "available";
          if (isset($koltuklar[$i])) {
              if ($koltuklar[$i] == 'K') {
                  $class = "female";
              } else if ($koltuklar[$i] == 'E') {
                  $class = "male";
              }
          }
          echo "<div class='seat $class' data-seat='$i'>$i</div>";
          if (++$row_count % 3 == 0) {
              echo "<br>";
          }
      }
      ?>
    </div>
  </div>
  <br>
  <form method="post" action="">
    <input type="hidden" id="koltuk_no" name="koltuk_no" value="">
    <div class="form-group">
      <label for="cinsiyet">Cinsiyet:</label>
      <select class="form-control" id="cinsiyet" name="cinsiyet">
        <option value="E">Erkek</option>
        <option value="K">Kadın</option>
      </select>
    </div>
    <button type="submit" name="koltuk_sec" class="btn btn-success">Koltuk Seç</button>
  </form>
</div>
<br>
<br>
<br>
<script>
$(document).ready(function(){
    $('.seat.available').click(function(){
        $('.seat').removeClass('selected');
        $(this).addClass('selected');
        $('#koltuk_no').val($(this).data('seat'));
    });
});
</script>

<?php if ($mesaj): ?>
<script>
    alert("<?php echo $mesaj; ?>");
    window.location.href = 'musteriucus.php';
</script>
<?php endif; ?>

</body>
</html>

<?php
// Veritabanı bağlantısını kapat
$conn->close();
?>
