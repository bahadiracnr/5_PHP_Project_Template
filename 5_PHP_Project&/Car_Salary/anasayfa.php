<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "car_dealership";

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select database
if (!$conn->select_db($database)) {
    die("Error selecting database: " . $conn->error);
}

// Select 10 recommended cars
$cars = $conn->query("SELECT * FROM cars LIMIT 10");
if (!$cars) {
    die("Error fetching cars: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ana Sayfa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .carousel-item img {
            width: 100%;
            height: 700px;
            object-fit: cover;
        }
        .recommended-cars .card {
            margin-bottom: 20px;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="anasayfa.php">Car Dealership</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="anasayfa.php">Ana Sayfa <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Araçlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Ara</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_cars.php">Manage Cars</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">Sepet</a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div id="carCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="image/s6.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="image/s7.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="image/s8.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container mt-5">
        <h2>Önerilen Araçlar</h2>
        <div class="row recommended-cars">
            <?php while ($car = $cars->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                      
                        <div class="card-body">
                            <h5 class="card-title"><?= $car['model'] ?></h5>
                            <p class="card-text">Price: $<?= $car['price'] ?></p>
                            <p class="card-text">Stock: <?= $car['stock'] ?></p>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer' && $car['stock'] > 0): ?>
                                <a href="index.php?add_to_cart=<?= $car['id'] ?>" class="btn btn-success">Add to Cart</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
