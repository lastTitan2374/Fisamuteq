<?php
// Database connection details
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

// SQL query to fetch all fixed deposit entries without tds
$sql = "SELECT account_holder, bank_name, deposit_no, start_date, end_date, period, deposit_amount, interest, payout_type, payout_amount, maturity_amount, remarks FROM fixed_deposits";
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
