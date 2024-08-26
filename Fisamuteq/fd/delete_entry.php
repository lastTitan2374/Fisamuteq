<?php
$servername = "localhost";
$username = "Group5";
$password = "Zephiroth123#";
$dbname = "finance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$entry_id = $_POST['entry_id'];

$sql = "DELETE FROM fixed_deposits WHERE id='$entry_id'";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
