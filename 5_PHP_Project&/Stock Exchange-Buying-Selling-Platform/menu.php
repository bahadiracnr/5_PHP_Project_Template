<!-- menu.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Taşpa Yatırım</title>
</head>
<body>
    <div id="menu">
        <div id="logo">
            <a href="index.php"><img src="jpg/logo.jpg" alt="Logo" class="logo"></a>
            <div id="logo-text">Taşpa Yatırım</div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php"><i class="icon icon-home"></i>Anasayfa</a></li>
                <li><a href="hakkimizda.php"><i class="icon icon-info"></i>Hakkımızda</a></li>
                <li><a href="egitim.php"><i class="icon icon-book"></i>Eğitimler</a></li>
                <li><a href="iletisim.php"><i class="icon icon-mail"></i>İletişim</a></li>
                <li><a href="sepet.php"><i class="icon icon-cart"></i>Sepet </a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
