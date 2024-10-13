<?php include 'menu.php'; ?>
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim Formu</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>



<div class="container mt-5">
    <h2 class="text-center">Bize Ulaşın</h2>
    
    <?php
    include 'db.php';

    // Tabloyu oluştur, eğer yoksa
    $sql = "CREATE TABLE IF NOT EXISTS taspa_iletisim (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        ad VARCHAR(50) NOT NULL,
        soyad VARCHAR(50) NOT NULL,
        `e-mail` VARCHAR(50) NOT NULL,
        telefon VARCHAR(20) NOT NULL,
        konu VARCHAR(100) NOT NULL,
        mesaj TEXT NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) !== TRUE) {
        echo "Error creating table: " . $conn->error;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ad = $_POST['ad'];
        $soyad = $_POST['soyad'];
        $email = $_POST['email'];
        $telefon = $_POST['telefon'];
        $konu = $_POST['konu'];
        $mesaj = $_POST['mesaj'];

        $sql = "INSERT INTO taspa_iletisim (ad, soyad, `e-mail`, telefon, konu, mesaj) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $ad, $soyad, $email, $telefon, $konu, $mesaj);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Mesajınız başarıyla gönderildi.</div>";
            echo "<div class='card mt-3'>
                    <div class='card-header'>Gönderdiğiniz Mesaj</div>
                    <div class='card-body'>
                        <p><strong>Ad:</strong> $ad</p>
                        <p><strong>Soyad:</strong> $soyad</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Telefon:</strong> $telefon</p>
                        <p><strong>Konu:</strong> $konu</p>
                        <p><strong>Mesaj:</strong> $mesaj</p>
                    </div>
                  </div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Hata: " . $stmt->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
            <label for="telefon">Telefon:</label>
            <input type="text" class="form-control" id="telefon" name="telefon" required>
        </div>
        <div class="form-group">
            <label for="konu">Konu:</label>
            <input type="text" class="form-control" id="konu" name="konu" required>
        </div>
        <div class="form-group">
            <label for="mesaj">Mesaj:</label>
            <textarea class="form-control" id="mesaj" name="mesaj" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


<?php include 'footer.php'; ?>
