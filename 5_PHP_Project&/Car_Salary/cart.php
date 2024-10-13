<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', 'Dogu!19071881', 'car_dealership');

if (isset($_GET['remove'])) {
    $car_id = $_GET['remove'];
    unset($_SESSION['cart'][$car_id]);
    header('Location: cart.php');
    exit;
}

if (isset($_POST['purchase'])) {
    $user_id = $_SESSION['user_id'];
    $total_price = 0;

    foreach ($_SESSION['cart'] as $car) {
        $car_id = $car['id'];
        $quantity = $car['quantity'];
        $price = $car['price'];
        $total_price += $price * $quantity;

        // Update car stock
        $conn->query("UPDATE cars SET stock = stock - $quantity WHERE id = $car_id");

        // Insert transaction
        $conn->query("INSERT INTO transactions (user_id, car_id, quantity, total_price) VALUES ('$user_id', '$car_id', '$quantity', '$price * $quantity')");
    }

    $_SESSION['cart'] = array(); // Empty cart
    echo "Purchase successful! Total: $" . $total_price;
    header('Location: anasayfa.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sepet</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
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
        <h1>Sepet</h1>
        <a href="anasayfa.php" class="btn btn-primary">Back to Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $car): ?>
                    <tr>
                        <td><?= $car['model'] ?></td>
                        <td>$<?= $car['price'] ?></td>
                        <td><?= $car['quantity'] ?></td>
                        <td>$<?= $car['price'] * $car['quantity'] ?></td>
                        <td>
                            <a href="cart.php?remove=<?= $car['id'] ?>" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="form-container mt-4">
            <h2 class="form-title">Ödeme Bilgileri</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Ad Soyad</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label for="card_number">Kart Numarası</label>
                    <input type="text" class="form-control" name="card_number" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Son Kullanma Tarihi</label>
                    <input type="text" class="form-control" name="expiry_date" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" name="cvv" required>
                </div>
                <button type="submit" name="purchase" class="btn btn-success">Ödeme Yap</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
