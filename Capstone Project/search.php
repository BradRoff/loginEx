<?php
// Start session
session_start();

// Check if valid user is logged in
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>BCS350 Capstone project -- Search Record -- Bradley Roff</title>
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
    <form action="search.php" method="post">
        <br>
        Search <input type="text" name="search"><br>
        Column:
        <select name="column">
            <option value="Title">Title</option>
            <option value="Genre">Genre</option>
            <option value="Rating">Rating</option>
            <option value="Price" selected>Price</option><!-- Default selected option -->
        </select><br>
        <!--If price option is selected, allows the user to search for a range of prices
            from min to max price by whole numbers. Example, between 10 and 25$
        -->
        <?php if (isset($_POST['column']) && $_POST['column'] == 'Price'): ?>
            Price Range: $<input type="number" name="minPrice" step="1" value="0"> 
            to $<input type="number" name="maxPrice" step="1" value="10"><br>
        <?php endif; ?>
        <input type="submit" value="Search">
    </form>
    <div id="results">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && isset($_POST['column'])) {
            $search = htmlspecialchars($_POST['search']);
            $column = htmlspecialchars($_POST['column']);

            $servername = "localhost";
            $username = "usersp24";
            $password = "pwdsp24";
            $db = "bcs350sp24";
          
            $conn = new mysqli($servername, $username, $password, $db);

            if ($conn->connect_error){
                die("Connection failed: ". $conn->connect_error);
            }

            /* creates a query for searching a price range if the price, min, and max values are filled.
            */
    $whereClause = "";
    if (isset($_POST['minPrice']) && isset($_POST['maxPrice']) && $_POST['column'] == 'Price') {
    $minPrice = intval($_POST['minPrice']);
    $maxPrice = intval($_POST['maxPrice']);
    $whereClause = "AND Price BETWEEN $minPrice AND $maxPrice";
    }

            // Query statement for search
            // Replace column with the selected option from dropdown for search
            $sql = $conn->prepare("SELECT * FROM GameStore WHERE $column LIKE ? $whereClause");
            $searchTerm = "%$search%";
            $sql->bind_param("s", $searchTerm);
            $sql->execute();
            $result = $sql->get_result();

            // If the number of results is more than 0, fetch them, otherwise show 0 results
            if ($result->num_rows > 0){
                $recnum = 0;
                while($row = $result->fetch_assoc() ){
                    echo "<br>Title: ".$row["Title"]."<br>Publisher: ".$row["Publisher"].
                    "<br>Release Date: ". $row["ReleaseDate"]."<br>Genre: ".$row["Genre"]."<br>Rating: ".$row["Rating"].
                    "<br>Description: ".$row["Description"]."<br>Price: $".$row["Price"]."<br>Current Stock: ".$row["StockQuantity"].
                    "<br>";
                }
            } else {
                echo "<br>0 records found";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
<?php
} else {
    // User is not logged in, redirect to another page
    header("Location: index.php"); // redirects to login.php
    exit();
}
?>
