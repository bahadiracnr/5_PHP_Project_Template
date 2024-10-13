<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', 'Dogu!19071881', 'car_dealership');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_car'])) {
        $model = $_POST['model'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $sql = "INSERT INTO cars (model, price, stock) VALUES ('$model', '$price', '$stock')";
        if ($conn->query($sql) === TRUE) {
            echo "Car added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['update_car'])) {
        $id = $_POST['id'];
        $model = $_POST['model'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $sql = "UPDATE cars SET model='$model', price='$price', stock='$stock' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            echo "Car updated successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$cars = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Cars</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Manage Cars</h1>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>

        <h2>Add New Car</h2>
        <form method="POST" action="">
            Model: <input type="text" name="model" required><br>
            Price: <input type="text" name="price" required><br>
            Stock: <input type="number" name="stock" required><br>
            <button type="submit" name="add_car" class="btn btn-success">Add Car</button>
        </form>

        <h2>Existing Cars</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($car = $cars->fetch_assoc()): ?>
                    <tr>
                        <td><?= $car['model'] ?></td>
                        <td>$<?= $car['price'] ?></td>
                        <td><?= $car['stock'] ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="id" value="<?= $car['id'] ?>">
                                Model: <input type="text" name="model" value="<?= $car['model'] ?>" required><br>
                                Price: <input type="text" name="price" value="<?= $car['price'] ?>" required><br>
                                Stock: <input type="number" name="stock" value="<?= $car['stock'] ?>" required><br>
                                <button type="submit" name="update_car" class="btn btn-warning">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
