<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Admin Paneli</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Ana Sayfa</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Admin Paneli</h2>
    <nav class="nav justify-content-center mb-4">
    </nav>

    <?php 
    // Veritabanı bağlantı bilgileri
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "taspa_yatirim";

    // Veritabanı bağlantısını kur
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Bağlantıyı kontrol et
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Tablonun var olup olmadığını kontrol et ve yoksa oluştur
    $sql = "CREATE TABLE IF NOT EXISTS egitim (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        baslik VARCHAR(255) NOT NULL,
        aciklama TEXT NOT NULL,
        fiyat DECIMAL(10, 2) NOT NULL,
        kontenjan INT NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error creating table: " . $conn->error . "</div>";
    }

    // Eğer tablo zaten mevcutsa, kontenjan sütunu ekleyin
    $sql = "ALTER TABLE egitim ADD COLUMN IF NOT EXISTS kontenjan INT NOT NULL AFTER fiyat";

    if ($conn->query($sql) === TRUE) {
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error modifying table: " . $conn->error . "</div>";
    }

    // Form verilerini işle
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['egitim_baslik'])) {
        $baslik = $_POST['egitim_baslik'];
        $aciklama = $_POST['egitim_aciklama'];
        $fiyat = $_POST['egitim_fiyat'];
        $kontenjan = $_POST['egitim_kontenjan'];

        $stmt = $conn->prepare("INSERT INTO egitim (baslik, aciklama, fiyat, kontenjan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $baslik, $aciklama, $fiyat, $kontenjan);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Yeni eğitim başarıyla eklendi.</div>";
            // Formun yeniden gönderilmesini önlemek için sayfayı yeniden yönlendir
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Eğitim eklenirken bir hata oluştu: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Silme işlemini gerçekleştir
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        $stmt = $conn->prepare("DELETE FROM egitim WHERE id = ?");
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Eğitim başarıyla silindi.</div>";
            // Silme işleminden sonra da sayfayı yeniden yönlendir
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Eğitim silinirken bir hata oluştu: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Güncelleme işlemini gerçekleştir
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
        $update_id = $_POST['update_id'];
        $update_baslik = $_POST['update_baslik'];
        $update_aciklama = $_POST['update_aciklama'];
        $update_fiyat = $_POST['update_fiyat'];
        $update_kontenjan = $_POST['update_kontenjan'];

        $stmt = $conn->prepare("UPDATE egitim SET baslik = ?, aciklama = ?, fiyat = ?, kontenjan = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $update_baslik, $update_aciklama, $update_fiyat, $update_kontenjan, $update_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success' role='alert'>Eğitim başarıyla güncellendi.</div>";
            // Güncelleme işleminden sonra sayfayı yeniden yönlendir
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Eğitim güncellenirken bir hata oluştu: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    // Eklenen verileri tabloya çek
    $sql = "SELECT id, baslik, aciklama, fiyat, kontenjan, reg_date FROM egitim";
    $result = $conn->query($sql);
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="egitim-baslik">Başlık:</label>
            <input type="text" class="form-control" id="egitim-baslik" name="egitim_baslik" required>
        </div>
        <div class="form-group">
            <label for="egitim-aciklama">Açıklama:</label>
            <textarea class="form-control" id="egitim-aciklama" name="egitim_aciklama" required></textarea>
        </div>
        <div class="form-group">
            <label for="egitim-fiyat">Fiyat:</label>
            <input type="text" class="form-control" id="egitim-fiyat" name="egitim_fiyat" required>
        </div>
        <div class="form-group">
            <label for="egitim-kontenjan">Kontenjan:</label>
            <input type="number" class="form-control" id="egitim-kontenjan" name="egitim_kontenjan" required>
        </div>
        <button type="submit" class="btn btn-primary">Eğitim Ekle</button>
    </form>

    <h3 class="mt-5">Eklenen Eğitimler</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Açıklama</th>
                <th>Fiyat</th>
                <th>Kontenjan</th>
                <th>Eklenme Tarihi</th>
                <th>Güncelle</th>
                <th>Sil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                                <td>" . $row["id"] . "</td>
                                <td><input type='text' name='update_baslik' value='" . $row["baslik"] . "' class='form-control'></td>
                                <td><input type='text' name='update_aciklama' value='" . $row["aciklama"] . "' class='form-control'></td>
                                <td><input type='text' name='update_fiyat' value='" . $row["fiyat"] . "' class='form-control'></td>
                                <td><input type='number' name='update_kontenjan' value='" . $row["kontenjan"] . "' class='form-control'></td>
                                <td>" . $row["reg_date"] . "</td>
                                <td>
                                    <input type='hidden' name='update_id' value='" . $row["id"] . "'>
                                    <button type='submit' class='btn btn-success btn-sm'>Güncelle</button>
                                </td>
                            </form>
                            <td>
                                <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                                    <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Sil</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Kayıt bulunamadı</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
