<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Rental Group Details</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-done { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Rental Group Details</h2>

<?php
require 'db.php';

if (isset($_GET['group_id'])) {
    $groupId = $_GET['group_id'];

    $sql = "SELECT rental_type, rent_price, is_accessible, laundry_type, num_bedrooms, num_bathrooms, has_parking FROM rental_group WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$groupId]);
    $detail = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($detail) {
        echo "<table>";
        echo "<tr><th>Type of Accommodation</th><td>{$detail['rental_type']}</td></tr>";
        echo "<tr><th>Max Budget</th><td>\${$detail['rent_price']}</td></tr>";
        echo "<tr><th>Accessibility</th><td>" . ($detail['is_accessible'] ? 'Yes' : 'No') . "</td></tr>";
        echo "<tr><th>Laundry</th><td>{$detail['laundry_type']}</td></tr>";
        echo "<tr><th>Number of Bedrooms</th><td>{$detail['num_bedrooms']}</td></tr>";
        echo "<tr><th>Number of Bathrooms</th><td>{$detail['num_bathrooms']}</td></tr>";
        echo "<tr><th>Parking</th><td>" . ($detail['has_parking'] ? 'Yes' : 'No') . "</td></tr>";
        echo "</table>";
        
        echo "<h3>Group Members</h3>";
        $peopleSql = "SELECT p.first_name, p.last_name FROM person p INNER JOIN renter r ON p.id = r.person_id WHERE r.rental_group_id = ?";
        $peopleStmt = $pdo->prepare($peopleSql);
        $peopleStmt->execute([$groupId]);
        echo "<ul>";
        while ($person = $peopleStmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>{$person['first_name']} {$person['last_name']}</li>";
        }
        echo "</ul>";
        
        echo "<a href='rental.php' class='btn-done'>Done</a>";
    } else {
        echo "Details for the selected rental group could not be found.";
    }
} else {
    echo "No rental group selected.";
}
?>

</body>
</html>