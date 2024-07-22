<?php
require_once "db.php";

$id = $firstName = $lastName = $phoneNum = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = test_input($_POST['id']);
    $firstName = test_input($_POST['first_name']);
    $lastName = test_input($_POST['last_name']);
    $phoneNum = test_input($_POST['phone_num']);

    if (empty($id) || empty($firstName) || empty($lastName) || empty($phoneNum)) {
        $error = "All fields are required.";
    } elseif (!preg_match("/^\d{1,5}$/", $id)) {
        $error = "Invalid ID format. Please enter a numeric value up to 5 digits.";
    } elseif (!preg_match("/^[a-zA-Z'-]+$/", $firstName) || !preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
        $error = "Invalid name format. Only letters, hyphens, and apostrophes are allowed.";
    } elseif (!preg_match("/^\d{10}$/", $phoneNum)) {
        $error = "Invalid phone number format. Please enter a 10-digit numeric value.";
    } else {
        $sql = "INSERT INTO person (id, first_name, last_name, phone_num) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$id, $firstName, $lastName, $phoneNum])) {
            header("Location: rental.php");
            exit();
        } else {
            $error = "Error: Could not insert the new person into the database.";
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Person</title>
</head>
<body>
    <h2>Add New Person</h2>
    <div>
        <?php if (!empty($error)) : ?>
            <div><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" maxlength="5" value="<?php echo htmlspecialchars($id); ?>" required><br>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required><br>

            <label for="phone_num">Phone Number:</label>
            <input type="text" id="phone_num" name="phone_num" maxlength="10" value="<?php echo htmlspecialchars($phoneNum); ?>" required><br>

            <input type="submit" value="Add Person">
        </form>
    </div>
</body>
</html>
