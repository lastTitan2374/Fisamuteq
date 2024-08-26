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

// Prepare SQL statement to fetch specific fixed deposit details based on account holder
$sql = "SELECT id, account_holder, bank_name, deposit_no, start_date, end_date, deposit_amount, payout_type, interest, remarks FROM fixed_deposits";

$result = $conn->query($sql);

// Initialize an array to hold the data
$data = array();

// Fetch data from the database and store it in the array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data[] = array("message" => "No records found");
}

// Close the database connection
$conn->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
