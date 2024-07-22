<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Queen's Rental Portal</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .navbar { 
            display: flex; 
            justify-content: center; 
            background-color: #333; 
            overflow: hidden; 
        }
        .navbar a { 
            display: block; 
            color: #f2f2f2; 
            text-align: center; 
            padding: 14px 20px; 
            text-decoration: none; 
        }
        .navbar a:hover { background-color: #ddd; color: black; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .centered { text-align: center; margin-top: 40px; 
        }
        .add-person-button {
            margin-bottom: 20px;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-person-button:hover {
            background-color: #f1c40f;
        }
    </style>
</head>
<body>

<div class="centered">
    <h1>Queen's Rental Portal</h1>
</div>

<div class="centered">
    <img src="queensu.png" alt="Queens Flag" style="width: 150px; height: auto;">
</div>

<div class="centered">
    <a href="new-person.php" class="add-person-button">Add New Person</a>
</div>

<div class="centered">
    <h2>Rental Properties</h2>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Owner(s)</th>
        <th>Manager</th>
    </tr>
    <?php
    require 'db.php';

    $sql = "SELECT r.id, GROUP_CONCAT(DISTINCT CONCAT(p.first_name, ' ', p.last_name) SEPARATOR ', ') AS owners, CONCAT(pm.first_name, ' ', pm.last_name) AS manager
            FROM rental r
            LEFT JOIN owns_rental ow ON r.id = ow.rental_id
            LEFT JOIN person p ON ow.owner_id = p.id
            LEFT JOIN manages_rental mr ON r.id = mr.rental_id
            LEFT JOIN rental_manager rm ON mr.manager_id = rm.person_id
            LEFT JOIN person pm ON rm.person_id = pm.id
            GROUP BY r.id";

    foreach ($pdo->query($sql) as $row) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['owners']) . "</td>";
        echo "<td>" . htmlspecialchars($row['manager']) ?? 'None' . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<div class="centered">
    <h2>Average Rent by Type</h2>
</div>

<?php
$sql = "SELECT rental_type, AVG(rent) as average_rent FROM rental GROUP BY rental_type";
$stmt = $pdo->query($sql);
echo "<table><tr><th>Type</th><th>Average Rent</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>" . htmlspecialchars($row['rental_type']) . "</td><td>" . number_format($row['average_rent'], 2) . "</td></tr>";
}
echo "</table>";
?>

<div class="centered">
    <h2>Rental Group Listings</h2>
</div>

<table>
    <tr>
        <th>Group ID</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT id FROM rental_group";
    foreach ($pdo->query($sql) as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><a href='view_details.php?group_id={$row['id']}'>View Details</a> | <a href='update_preferences.php?group_id={$row['id']}'>Update Preferences</a></td>";
        echo "</tr>";
    }
    ?>
</table>

<div class="centered">
    <footer>
        <p>2024 Queen's Rental Portal. No Rights Reserved.</p>
    </footer>
</div>

</body>
</html>