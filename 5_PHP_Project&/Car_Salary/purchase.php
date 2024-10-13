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
            height: 400px;
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
    <?php include 'navbar.php'; ?>

    <div id="carCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="car1.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="car2.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="car3.jpg" alt="Third slide">
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
                        <img class="card-img-top" src="car_image.jpg" alt="Car image">
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
