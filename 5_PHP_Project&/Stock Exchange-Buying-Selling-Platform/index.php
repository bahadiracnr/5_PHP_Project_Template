<!-- index.php -->
<?php include 'menu.php';


$servername = "localhost";
$username = "root";
$password = "web364web";
$dbname = "taspa_yatirim";

// Veritabanı bağlantısını kur
$conn = new mysqli($servername, $username, $password);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Veritabanının var olup olmadığını kontrol et
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

 ?>

<section id="index-banner" style="background-image: url('jpg/img4.jpg');">
    <div id="black"></div>
    <div id="icerik">
        <h2>TAŞPA YATIRIM İLE GELECEĞE YATIRIM</h2>
        <hr width="300" align="left">
        <p>Teknolojik altyapımızla Türk sermaye piyasalarının yatırım kuruluşları arasında yer alıyoruz. Bugünün yatırım ihtiyaçlarına geleceğin çözümlerini şimdiden sunmayı hedefliyoruz. Son teknolojileri kullanan gelişmiş online Forex, Opsiyon, VİOP ve Borsa işlem platformlarımız sayesinde müşterilerimiz tüm anlık finansal verilere, piyasa analizlerine ve ihtiyaç duyulan diğer bilgilere hızlı ve güvenli bir şekilde ulaşıp işlem yapabilmekte, yaratıcı mobil iletişim kolaylıklarıyla zaman ve mekan sınırlarından kurtulmaktadır.</p>
    </div>
</section>

<section id="borsa-kuru">
    <div class="container">
        <h3>Borsa Kuru</h3>
        <p>Sitemizde güncel borsa kurlarını ve analizlerini bulabilirsiniz. Aşağıda borsa kurlarını görebilirsiniz.</p>
        <div class="borsa-kur-tablosu">
            <table>
                <thead>
                    <tr>
                        <th>Borsa</th>
                        <th>Alış</th>
                        <th>Satış</th>
                        <th>Değişim</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BIST 100</td>
                        <td>100.000</td>
                        <td>101.000</td>
                        <td>+1.00%</td>
                    </tr>
                    <tr>
                        <td>Dow Jones</td>
                        <td>30.000</td>
                        <td>30.100</td>
                        <td>+0.33%</td>
                    </tr>
                    <tr>
                        <td>Nasdaq</td>
                        <td>12.000</td>
                        <td>12.100</td>
                        <td>+0.83%</td>
                    </tr>
                    <tr>
                        <td>YAS Fonu</td>
                        <td>14.543420</td>
                        <td>14.543420</td>
                        <td>-0.54%</td>
                    </tr>
                    <tr>
                        <td>KOCHL Hisse</td>
                        <td>200.00</td>
                        <td>201.50</td>
                        <td>+0.75%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="viop">
    <div class="container">
        <h3>Borsada VİOP Nedir?</h3>
        <p>VİOP (Vadeli İşlem ve Opsiyon Piyasası), yatırımcıların ileri tarihlerde teslim edilmek üzere alım satım yapmalarını sağlayan bir piyasadır. VİOP işlemleri ile risk yönetimi yapabilir ve gelecekteki fiyat hareketlerinden kar elde edebilirsiniz.</p>
        <ul>
            <li><a href="#">Borsada VİOP nedir?</a></li>
            <li><a href="#">VİOP'ta Bilinmesi Gereken Temel Kavramlar Nelerdir?</a></li>
        </ul>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/VyS_4bKFhcU" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</section>

<section id="kaldirac">
    <div class="container">
        <h3>Kaldıraç İşlemleri</h3>
        <p>Kaldıraç işlemleri, yatırımcılara düşük miktarda sermaye ile yüksek tutarlı işlemler yapma imkanı sunar. Bu işlemler, potansiyel karı artırdığı gibi riskleri de artırmaktadır. Kaldıraç oranları, yatırımcının pozisyon büyüklüğünü ve marjin gereksinimlerini belirler.</p>
        <ul>
            <li><a href="#">Kaldıraç Nedir?</a></li>
            <li><a href="#">Kaldıraçlı İşlemlerde Dikkat Edilmesi Gerekenler</a></li>
        </ul>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/BhrO-FA7SUA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</section>



<?php include 'footer.php'; ?>
