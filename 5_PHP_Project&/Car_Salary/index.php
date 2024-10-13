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

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($database);

// Create tables if they do not exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

$sql = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    car_id INT,
    quantity INT,
    total_price DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Initialize error message variable
$error_message = "";

// Handle login and registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $error_message = "Registration successful!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: anasayfa.php');
                exit;
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "No user found with that username.";
        }
    }
}

// Add to cart
if (isset($_GET['add_to_cart'])) {
    $car_id = $_GET['add_to_cart'];
    $car = $conn->query("SELECT * FROM cars WHERE id = $car_id")->fetch_assoc();
    if ($car['stock'] > 0) {
        $_SESSION['cart'][$car_id] = $car;
        $_SESSION['cart'][$car_id]['quantity'] = 1;
        header('Location: anasayfa.php');
        exit;
    } else {
        $error_message = "Car out of stock.";
    }
}

// Display available cars
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $cars = $conn->query("SELECT * FROM cars WHERE model LIKE '%$search_query%' LIMIT 10");
} else {
    $cars = $conn->query("SELECT * FROM cars LIMIT 10");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Dealership</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
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
    <div class="container">
        <h1 class="text-center">Car Dealership</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="manage_cars.php" class="btn btn-primary mb-3">Manage Cars</a>
            <?php else: ?>
                <a href="cart.php" class="btn btn-success mb-3">Sepet</a>
            <?php endif; ?>

            <form method="GET" action="" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Araba Modeli Ara" name="search" value="<?= htmlspecialchars($search_query) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">Ara</button>
                    </div>
                </div>
            </form>

            <h2>Available Cars</h2>
            <div class="row">
                <?php while ($car = $cars->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= $car['model'] ?></h5>
                                <p class="card-text">Price: $<?= $car['price'] ?></p>
                                <p class="card-text">Stock: <?= $car['stock'] ?></p>
                                <?php if ($_SESSION['role'] === 'customer' && $car['stock'] > 0): ?>
                                    <a href="index.php?add_to_cart=<?= $car['id'] ?>" class="btn btn-success">Add to Cart</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php if ($error_message): ?>
                        <div class="error-message"><?= $error_message ?></div>
                    <?php endif; ?>
                    <div class="form-container">
                        <h2 class="form-title">Login</h2>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                    <div class="form-container mt-4">
                        <h2 class="form-title">Register</h2>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="role">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" name="register" class="btn btn-success">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
