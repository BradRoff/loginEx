<?php
// Start session
session_start();

// Check if valid user is logged in
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
?>
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<link rel="stylesheet" href="stylesheet.css">
<body>
<head>
<fieldset id = "title">
    <h3>BCS350 Capstone project -- Database list -- Bradley Roff</h3> 
</fieldset>
</head>
<div id='link' >
<a href="HomePage.php" style="text-align: center; margin-top: 20px;">Return to HomePage</a>
</div> 
<div id = "results">
<?php

require_once 'cap_login.php';
$conn = new mysqli($host, $user, $pass, $data);
echo 'Welcome '.$username. ' , Here are the data base entries ordered by Titles!<br><br>';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Query to recive all records from gamestore, ordered by title
$query  = "SELECT * FROM GameStore ORDER BY Title ASC";
$result = $conn->query($query);


if ($result->num_rows > 0) {
    // output data to row while availible
   
    while ($row = $result->fetch_assoc()) {


        echo "Game Title: " . $row["Title"] . "<br>";
        echo "Publisher: " . $row["Publisher"] . "<br>";
        echo "Release Date: " . $row["ReleaseDate"] . "<br>";
        echo "Genre: " . $row["Genre"] . "<br>";
        echo "Rating: " . $row["Rating"] . "<br>";
        echo "Description: " . $row["Description"] . "<br>";
        echo "Price: $" . $row["Price"] . "<br>";
        echo "Current Stock: " . $row["StockQuantity"] . "<br><br>";
        

    }
} else {
    //if no data is found, show message
    echo "0 records in database.";
}


$conn->close();
?>
</div>
<style>
  
    </style>
</body>
</html>
<?php
} 
else {
    // User is not logged in, redirect to another page
    header("Location: login.php"); // redirects to login.php
    exit();
}
?>