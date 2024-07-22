<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Rental Group Preferences</title>
    <style>
        body { font-family: Arial, sans-serif; }
        label, input, select, button { display: block; margin: 10px 0; }
        .container { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Update Rental Group Preferences</h2>

<?php
require 'db.php';

if (isset($_GET['group_id'])) {
    $groupId = htmlspecialchars($_GET['group_id']);

    $sql = "SELECT * FROM rental_group WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($group) {
        echo "<form action='process_update.php' method='post' class='container'>";
        echo "<input type='hidden' name='group_id' value='{$group['id']}' />";
        
        echo "<label for='rental_type'>Type of Accommodation:</label>";
        echo "<select name='rental_type' id='rental_type'>";
        $types = ['House', 'Apartment', 'Room'];
        foreach ($types as $type) {
            $selected = ($type === $group['rental_type']) ? 'selected' : '';
            echo "<option value='$type' $selected>$type</option>";
        }
        echo "</select>";
        
        echo "<label for='is_accessible'>Accessibility:</label>";
        echo "<select name='is_accessible' id='is_accessible'>";
        echo "<option value='1'" . ($group['is_accessible'] ? ' selected' : '') . ">Yes</option>";
        echo "<option value='0'" . (!$group['is_accessible'] ? ' selected' : '') . ">No</option>";
        echo "</select>";
        
        echo "<label for='rent_price'>Max Budget:</label>";
        echo "<input type='text' id='rent_price' name='rent_price' value='{$group['rent_price']}' />";
        
        echo "<label for='num_bedrooms'>Number of Bedrooms:</label>";
        echo "<input type='number' id='num_bedrooms' name='num_bedrooms' value='{$group['num_bedrooms']}' />";
        
        echo "<label for='num_bathrooms'>Number of Bathrooms:</label>";
        echo "<input type='number' id='num_bathrooms' name='num_bathrooms' value='{$group['num_bathrooms']}' />";
        
        echo "<label for='has_parking'>Parking Availability:</label>";
        echo "<select name='has_parking' id='has_parking'>";
        echo "<option value='1'" . ($group['has_parking'] ? ' selected' : '') . ">Yes</option>";
        echo "<option value='0'" . (!$group['has_parking'] ? ' selected' : '') . ">No</option>";
        echo "</select>";
        
        echo "<label for='laundry_type'>Laundry Type:</label>";
        echo "<select name='laundry_type' id='laundry_type'>";
        $laundryTypes = ['ensuite', 'shared'];
        foreach ($laundryTypes as $laundryType) {
            $selected = ($laundryType === $group['laundry_type']) ? 'selected' : '';
            echo "<option value='$laundryType' $selected>$laundryType</option>";
        }
        echo "</select>";
        
        echo "<button type='submit'>Update Preferences</button>";
        echo "</form>";
    } else {
        echo "Details for the selected rental group could not be found.";
    }
} else {
    echo "No rental group selected.";
}
?>

</body>
</html>