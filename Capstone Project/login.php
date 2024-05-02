
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'cap_login.php';
    $conn = new mysqli($host, $user, $pass, $data);

    if ($conn->connect_error) {
        die("Fatal Error");
    }

    $username = mysql_entities_fix_string($conn, $_POST['username']);
    $password = mysql_entities_fix_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if (!$result) {
        die("User not found");
    } elseif ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();

        if (password_verify($password, $row[2])) {
            $_SESSION['username'] = $row[0];
            header("Location: homepage.php");
            exit;
        } else {
            $error = "Invalid username/password combination";
        }
    } else {
        $error = "Invalid username/password combination";
    }

    $conn->close();
}

function mysql_entities_fix_string($conn, $string)
{
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string)
{
    if (get_magic_quotes_gpc()) {
        $string = stripslashes($string);
    }
    return $conn->real_escape_string($string);
}
?>

<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<link rel="stylesheet" href="stylesheet.css">

</head>
<body>
  <h1>Welcome, Please enter your credentials to Log-In</h1>
  <div id = link>
        <a href="index.php">Return</a>
    </div>
  <?php if(isset($error)) { ?>
    <p style="color: red;"><?php echo $error; ?></p>
  <?php } ?>
  
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    
    <input type="submit" value="Login">

  </form>
</body>
</html>
