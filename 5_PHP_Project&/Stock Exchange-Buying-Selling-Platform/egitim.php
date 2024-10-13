<?php
session_start();
include('db.php');

// Eğitim ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['egitim_id'])) {
    $egitim_id = $_POST['egitim_id'];

    // Eğitim var mı kontrol et
    $result = mysqli_query($conn, "SELECT * FROM egitim WHERE id='$egitim_id'");
    $egitim = mysqli_fetch_assoc($result);

    if ($egitim) {
        if (!isset($_SESSION['sepet'])) {
            $_SESSION['sepet'] = array();
        }
        // Eğitim ID'sini sepete ekle
        $_SESSION['sepet'][] = $egitim_id;
    }
}

// Eğitimleri sıralama
$order_by = "";
if (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') {
    $order_by = "ORDER BY fiyat DESC";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eğitimler</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div id="egitimler" class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Eğitimler</h3>
            <a href="egitim.php?sort=price_desc" class="btn btn-primary">Fiyata Göre Azalan</a>
        </div>
        <div class="egitim-listesi">
            <?php
            $query = "SELECT * FROM egitim $order_by";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>";
                echo "<h3>" . $row['baslik'] . "</h3>";
                echo "<p>" . $row['aciklama'] . "</p>";
                echo "<p><strong>Fiyat:</strong> " . $row['fiyat'] . " TL</p>";
                echo "<p><strong>Kontenjan:</strong> " . $row['kontenjan'] . "</p>";
                echo "<form action='egitim.php' method='post'>";
                echo "<input type='hidden' name='egitim_id' value='" . $row['id'] . "'>";
                echo "<button type='submit'>Ekle</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
