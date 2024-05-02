<?php
// Start session
session_start();

// Check if valid user is logged in
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
?>
<html>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <fieldset id="title">
        <h3>BCS350 Capstone project -- Add new Record -- Bradley Roff</h3>
    </fieldset>
    <div id = link>
        <a href="HomePage.php">Return to HomePage</a>
    </div>
<?php 
// Function to sanitize user input
function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

// add.php
require_once 'cap_login.php';
$conn = new mysqli($host, $user, $pass, $data);

if ($conn->connect_error) die("Fatal Error");

//if the user clicks the submit record and all points are filled, this code runs
if (isset($_POST['Title'])   &&
    isset($_POST['Publisher'])    &&
    isset($_POST['ReleaseDate'])    &&
    isset($_POST['Genre'])    &&
    isset($_POST['Rating'])    &&
    isset($_POST['Description'])    &&
    isset($_POST['Price'])    &&
    isset($_POST['StockQuantity'])   )
{
    $Title   = htmlspecialchars(get_post($conn, 'Title'));
    $Publisher    = htmlspecialchars(get_post($conn, 'Publisher'));
    $ReleaseDate = htmlspecialchars(get_post($conn, 'ReleaseDate'));
    $Genre     = htmlspecialchars(get_post($conn, 'Genre'));
    $Rating     = htmlspecialchars(get_post($conn, 'Rating'));
    $Description     = htmlspecialchars(get_post($conn, 'Description'));
    $Price     = htmlspecialchars(get_post($conn, 'Price'));
    $StockQuantity     = htmlspecialchars(get_post($conn, 'StockQuantity'));
    
    // Check if the record already exists
    $check_query = "SELECT * FROM GameStore WHERE Title='$Title'";
    $check_result = $conn->query($check_query);
    if ($check_result->num_rows > 0) {
        echo "Record already exists. Please try again with a different set of values.";
    } else {
        // Insert the record
        $query = "INSERT INTO GameStore (Title, Publisher, ReleaseDate, Genre, Rating, Description, Price, StockQuantity) 
        VALUES ('$Title', '$Publisher', '$ReleaseDate', '$Genre', '$Rating', '$Description', '$Price', '$StockQuantity')";

        $result   = $conn->query($query);
        if (!$result) echo "INSERT failed<br><br>";
    }
}

// code setting up submit form
echo <<<_END
<form action="add.php" method="post"><pre>
<label for="Title">Game Title:</label> 
<input type="text" name="Title" placeholder='Minecraft'>
<label for="Publisher">Publisher:</label>
<input type="text" name="Publisher" placeholder='Mojang Studios'>
<label for="ReleaseDate">Release Date:</label>
<input type="text" name="ReleaseDate" placeholder='2009-05-17'>
<label for="Genre">Genre:</label>
<input type="text" name="Genre" placeholder='Sandbox'>
<label for="Rating">Rating:</label>
<input type="text" name="Rating" placeholder='9.0'>
<label for="Description">Description:</label>
<textarea name="Description" 
placeholder = 'A sandbox video game that allows players to build and explore virtual worlds made up of blocks.'></textarea>
<label for="Price">Price:</label>
<input type="text" name="Price" placeholder="19.99">
<label for="StockQuantity">Current Stock:</label>
<input type="text" name="StockQuantity" placeholder="200">
<input type="submit" value="ADD RECORD">
</pre></form>
_END;


$query  = "SELECT * FROM GameStore ORDER BY Title ASC";
$result = $conn->query($query);
if (!$result) die ("Database access failed");

$rows = $result->num_rows;


for ($j = 0 ; $j < $rows ; ++$j)
{


    // code for showing current records in database.
    echo "<div style='white-space: pre-wrap;'>";
    echo <<<_END
    
    Game Title: $r0
    Publisher: $r1
    Release Date: $r2
    Genre: $r3
    Rating: $r4
    Description: <span style="word-wrap: break-word; max-width: 500px;">$r5</span>
    Price: $$r6
    Current Stock: $r7

    _END;
}

$result->close();
$conn->close();


?>
</body>
</html>
<?php
} else {
    // User is not logged in, redirect to another page
    header("Location: login.php"); // redirects to login.php
    exit();
}
?>
