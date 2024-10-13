<?php
session_start();
include('db.php');

// Ürün silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['urun_id'])) {
    $urun_id = $_POST['urun_id'];

    if (is_array($urun_id)) {
        $urun_id = $urun_id[0];
    }

    if (isset($_SESSION['sepet']) && is_array($_SESSION['sepet'])) {
        foreach ($_SESSION['sepet'] as $key => $value) {
            if ((string)$value === (string)$urun_id) {
                unset($_SESSION['sepet'][$key]);
                $_SESSION['sepet'] = array_values($_SESSION['sepet']); // Diziyi yeniden indeksle
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container" id="sepet">
        <h2>Sepetiniz</h2>
        <?php
        // Sepet boşsa
        if (empty($_SESSION['sepet'])) {
            echo "<p>Sepetiniz boş.</p>";
        } else {
            $urunler = $_SESSION['sepet'];
            echo '<ul>';
            foreach ($urunler as $urun_id) {
                $urun_id = intval($urun_id);  // Güvenlik için ID'yi tamsayıya dönüştür
                $query = "SELECT * FROM egitim WHERE id = $urun_id";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        $urun = mysqli_fetch_assoc($result);
                        echo '<li>';
                        echo '<div class="urun">';
                        echo '<h3>'.$urun['baslik'].'</h3>';
                        echo '<p>'.$urun['aciklama'].'</p>';
                        echo '<p>Fiyat: '.$urun['fiyat'].' TL</p>';
                        echo '<p>Kontenjan: '.$urun['kontenjan'].'</p>';
                        echo '<form method="POST" action="sepet.php">';
                        echo '<input type="hidden" name="urun_id" value="'.$urun_id.'">';
                        echo '<button type="submit" class="delete-button">Sil</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</li>';
                    } else {
                        echo "<p>Ürün bulunamadı. Sorgu: $query</p>";
                    }
                } else {
                    echo "<p>Sorgu başarısız: " . mysqli_error($conn) . "</p>";
                }
            }
            echo '</ul>';
            echo '<form method="POST" action="odeme.php">';
            echo '<button type="submit" class="satin-al-button">Satın Al</button>';
            echo '</form>';
        }
        ?>
    </div>
