<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['group_id'])) {
    $groupId = $_POST['group_id'];
    $rentalType = filter_input(INPUT_POST, 'rental_type', FILTER_SANITIZE_STRING);
    $isAccessible = filter_input(INPUT_POST, 'is_accessible', FILTER_VALIDATE_INT);
    $rentPrice = filter_input(INPUT_POST, 'rent_price', FILTER_VALIDATE_FLOAT);
    $numBedrooms = filter_input(INPUT_POST, 'num_bedrooms', FILTER_VALIDATE_INT);
    $numBathrooms = filter_input(INPUT_POST, 'num_bathrooms', FILTER_VALIDATE_INT);
    $hasParking = filter_input(INPUT_POST, 'has_parking', FILTER_VALIDATE_INT);
    $laundryType = filter_input(INPUT_POST, 'laundry_type', FILTER_SANITIZE_STRING);
    
    $sql = "UPDATE rental_group SET rental_type = ?, is_accessible = ?, rent_price = ?, num_bedrooms = ?, num_bathrooms = ?, has_parking = ?, laundry_type = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$rentalType, $isAccessible, $rentPrice, $numBedrooms, $numBathrooms, $hasParking, $laundryType, $groupId])) {
        header("Location: rental.php?success=1");
        exit;
    } else {
        header("Location: rental.php?error=1");
        exit;
    }
} else {
    echo "Invalid request.";
}
?>