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
        <h3>BCS350 Capstone project -- Search Record -- Bradley Roff</h3>
    </fieldset>
    <div id = link>
        <a href="HomePage.php">Return to HomePage</a>
    </div>
</head>
<?php // delete.php
 require_once 'cap_login.php';
 $conn = new mysqli($host, $user, $pass, $data);

// Function to sanitize user input
function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

 if ($conn->connect_error) die("Fatal Error");

  if (isset($_POST['delete']) && isset($_POST['Id']))
  {
    $title = get_post($conn, 'Id');
    $query  = "DELETE FROM GameStore WHERE Title='$title'";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed<br><br>";
  }
  

  $query  = "SELECT * FROM GameStore ORDER BY Title ASC";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed");

  $rows = $result->num_rows;

  // Loop through the results and display them
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $row = $result->fetch_array(MYSQLI_NUM);

    $r0 = htmlspecialchars($row[0]);
    $r1 = htmlspecialchars($row[1]);
    $r2 = htmlspecialchars($row[2]);
    $r3 = htmlspecialchars($row[3]);
    $r4 = htmlspecialchars($row[4]);
    $r5 = htmlspecialchars($row[5]);
    $r6 = htmlspecialchars($row[6]);
    $r7 = htmlspecialchars($row[7]);
    
    // Output the game information
    echo "<div style='white-space: pre-wrap;'>";
    echo <<<_END
    
Game Title: $r0
Publisher: $r1
Release Date: $r2
Genre: $r3
Rating: $r4
Description: <span style="word-wrap: break-word; max-width: 500px;">$r5</span>Price: $$r6
Current Stock: $r7<form action='delete.php' method='post'> <input type='hidden' name='delete' value='yes'><input type='hidden' name='Id' value='$r0'>
<input type='submit' value='DELETE RECORD'></form>
_END;
  }

  $result->close();
  $conn->close();

} else {
    // User is not logged in, redirect to another page
    header("Location: index.php"); // redirects to login.php
    exit();
}
?>
</body>
</html>
