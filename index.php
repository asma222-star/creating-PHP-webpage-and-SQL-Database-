<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_data";

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);

// Connection error check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert user if form submitted
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];

    $stmt = $conn->prepare("INSERT INTO users (name, age) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $age);
    $stmt->execute();

    // Prevent form resubmission
    header("Location: index.php");
    exit();
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];

    $result = $conn->query("SELECT status FROM users WHERE id = $id");
    $row = $result->fetch_assoc();
    $new_status = ($row['status'] == 0) ? 1 : 0;

    $conn->query("UPDATE users SET status = $new_status WHERE id = $id");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    form {
      margin-bottom: 20px;
    }

    input[type="text"], input[type="number"] {
      padding: 5px;
      margin-right: 10px;
    }

    input[type="submit"] {
      padding: 5px 15px;
    }

    table {
      border-collapse: collapse;
      width: 60%;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #000;
      padding: 10px;
      text-align: center;
    }

    a {
      color: purple;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h2>User Information</h2>

  <form method="POST" action="">
    Name: <input type="text" name="name" required>
    Age: <input type="number" name="age" required>
    <input type="submit" name="submit" value="Submit">
  </form>

  <h3>Saved Users</h3>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Age</th>
      <th>Status</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM users");
    while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['age'] ?></td>
        <td>
          <?= $row['status'] ?> |
          <a href="?toggle=<?= $row['id'] ?>">Toggle</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

<
