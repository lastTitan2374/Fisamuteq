<?php
// Database connection parameters
$servername = "localhost";
$username = "Group5";
$password = "Zephiroth123#";
$dbname = "finance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if account_holder parameter is set
if (isset($_POST['account_holder'])) {
    // Prepare SQL statement
    $sql = "SELECT * FROM fixed_deposits WHERE account_holder = ?";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $account_holder);

    // Set parameter
    $account_holder = $_POST['account_holder'];

    // Execute statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["account_holder"] . "</td>";
            echo "<td>" . $row["bank_name"] . "</td>";
            echo "<td>" . $row["deposit_no"] . "</td>";
            echo "<td>" . $row["start_date"] . "</td>";
            echo "<td>" . $row["end_date"] . "</td>";
            echo "<td>" . $row["deposit_amount"] . "</td>";
            echo "<td>" . $row["maturity_amount"] . "</td>";
            echo "<td>" . $row["payout_date"] . "</td>";
            echo "<td>" . $row["payout_amount"] . "</td>";
            echo "<td>" . $row["remarks"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No fixed deposits found for this account holder.</td></tr>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "<tr><td colspan='10'>Please select an account holder.</td></tr>";
}
?>
