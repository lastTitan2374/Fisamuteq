<?php
// Connect to your database (replace with your actual database credentials)
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

// Get the report month from the query string (ensure it's properly sanitized)
$reportMonth = $_GET['reportMonth'];

// Prepare SQL query to fetch data
$sql = "SELECT account_holder, bank_name, account_number, 
               SUM(income) AS total_income, 
               SUM(expenses) AS total_expenses
        FROM savings_deposit
        WHERE MONTH(transaction_date) = MONTH('$reportMonth') 
              AND YEAR(transaction_date) = YEAR('$reportMonth')
        GROUP BY account_holder, bank_name, account_number";

$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Initialize an array to store results
    $data = array();

    // Fetch each row and store in $data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return data as JSON
    echo json_encode($data);
} else {
    // Return an empty array if no results found
    echo json_encode(array());
}

// Close connection
$conn->close();
?>