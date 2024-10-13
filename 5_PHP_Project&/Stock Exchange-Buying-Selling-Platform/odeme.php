<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ödeme</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container" id="odeme">
        <h2>Ödeme Seçenekleri</h2>
        <form method="POST" action="odeme_islem.php">
            <h3>Kredi Kartı ile Ödeme</h3>
            <div class="form-group">
                <label for="kart_numarasi">Kart Numarası:</label>
                <input type="text" id="kart_numarasi" name="kart_numarasi" required>
            </div>
            <div class="form-group">
                <label for="ad_soyad">Kartın Üzerindeki Ad Soyad:</label>
                <input type="text" id="ad_soyad" name="ad_soyad" required>
            </div>
            <div class="form-group">
                <label for="son_kullanma_tarihi">Son Kullanma Tarihi:</label>
                <input type="text" id="son_kullanma_tarihi" name="son_kullanma_tarihi" placeholder="MM/YY" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
            </div>
            <button type="submit" name="odeme_turu" value="kredi_karti" class="submit-button">Kredi Kartı ile Öde</button>
        </form>

        <form method="POST" action="odeme_islem.php">
            <h3>Banka Havalesi ile Ödeme</h3>
            <div class="form-group">
                <label for="hesap_sahibi">Hesap Sahibinin Adı Soyadı:</label>
                <input type="text" id="hesap_sahibi" name="hesap_sahibi" required>
            </div>
            <div class="form-group">
                <label for="hesap_numarasi">Hesap Numarası:</label>
                <input type="text" id="hesap_numarasi" name="hesap_numarasi" required>
            </div>
            <button type="submit" name="odeme_turu" value="banka_havalesi" class="submit-button">Banka Havalesi ile Öde</button>
        </form>
    </div>
</body>
</html>
