
<!DOCTYPE html>
<html>
<head>
    <title>BCS350 Capstone project -- Registration -- Bradley Roff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<link rel="stylesheet" href="stylesheet.css">

</head>
<body>
    <fieldset id="title">
        <h3>BCS350 Capstone project -- Registration -- Bradley Roff</h3> 
    </fieldset>
    <div id = link>
        <a href="index.php">Return</a>
    </div>
<?php
  // Start with the PHP code
  require_once 'cap_login.php'; // Assuming cap_login.php contains database connection parameters
  $conn = new mysqli($host, $user, $pass, $data);
  
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  

  
  
  $username = $email = $password = $password2  ="";
  $fail = "";
  $check = 1; 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']))
      $username = fix_string($_POST['username']);
      $sqlUsername = "Select * from users where username = '$username'";
      $resultUsername = $conn->query($sqlUsername);
    if (isset($_POST['password']))
      $password = fix_string($_POST['password']);
    if (isset($_POST['password2']))
      $password2 = fix_string($_POST['password2']);
    if (isset($_POST['email']))
      $email = fix_string($_POST['email']);
      $sqlEmail = "Select * from users where email = '$email'";
      $resultEmail = $conn->query($sqlEmail);

    $fail .= validate_username($username,$resultUsername, $check);
    $fail .= validate_password($password, $password2);
    $fail .= validate_email($email, $resultEmail, $check);

    if ($fail == "") {
      // Hash the password before storing it
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      
      // Use prepared statements to prevent SQL injection
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      
      if ($stmt->execute()) {
        echo 'Form data successfully validated and inserted into database: $username, $email. <a href="login.php">Login here!</a>';
      } else {
        echo "Error: " . $stmt->error;
      }
      
      $stmt->close();
    } else {
      echo "Validation failed:<br>" . $fail;
    }
  }

  // The PHP functions
  function validate_username($field,$resultUsername, $check) {
    if ($field == "") return "No Username was entered.<br>";
    else if ($resultUsername->num_rows > 0){ //checks if any data exists for said username
        $check == 0;
        return 'Warning, Username already exists. Please chose a different username.<br>
        Or if you are a returning user, <a href="login.php">Login here!</a>';
    }
    else if (strlen($field) < 5)
      return "Usernames must be at least 5 characters.<br>";
    else if (!preg_match("/^[a-zA-Z0-9_-]+$/", $field))
      return "Only letters, numbers, - and _ in usernames.<br>";
    return "";		
  }
  
  function validate_password($field, $field2) {
    if ($field == "") return "No Password was entered.<br>";
    else if ($field2 == "") return "No confirmation password was entered.<br>";
    else if (strlen($field) < 6)
      return "Passwords must be at least 6 characters.<br>";
    else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field))
      return "Passwords require 1 each of a-z, A-Z and 0-9.<br>";
    else if ($field != $field2) return "Password and confirmation password must match.<br>";
    return "";
  }
  
  function validate_email($field, $resultEmail, $check) {
    if ($field == "") return "No Email was entered.<br>";
    else if (!filter_var($field, FILTER_VALIDATE_EMAIL))
      return "The Email address is invalid.<br>";
    else if ($resultEmail->num_rows > 0){ //checks if any data exists for said email
        if ($check == 1){ 
        
        return '<br>Warning, Email is already being used. Please use a diffrent email.<br>';
        }
        else if ($check == 0){
            return '<br>Warning, Email is already being used. Please use a diffrent email.<br>
            Or if you are a returning user, <a href="login.php">Login here!</a>';
        }
    }
    return "";
  }
  
  function fix_string($string) {
    return htmlspecialchars($string);
  }
?>
<script>
function validate(form) {
    var username = form.username.value.trim();
    var password = form.password.value.trim();
    var password2 = form.password2.value.trim();
    var email = form.email.value.trim();

    var error = "";

    if (username === "") {
        error += "Username cannot be empty.\n";
    } else if (username.length < 5) {
        error += "Username must be at least 5 characters.\n";
    }

    if (password === "") {
        error += "Password cannot be empty.\n";
    } else if (password.length < 6) {
        error += "Password must be at least 6 characters.\n";
    }

    if (password2 === "") {
        error += "Confirm Password cannot be empty.\n";
    } else if (password !== password2) {
        error += "Password and Confirm Password must match.\n";
    }

    if (email === "") {
        error += "Email cannot be empty.\n";
    } else if (!isValidEmail(email)) {
        error += "Invalid email format.\n";
    }

    if (error !== "") {
        alert("Validation failed:\n" + error);
        return false;
    }

    return true;
}

function isValidEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
</script>

    <table >
      <th >Signup Form</th>

      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onSubmit="return validate(this)">
        <tr><td>Username</td>
          <td><input id = "box" type="text" maxlength="16" name="username" value="<?php echo $username; ?>">
        </td></tr><tr><td>Password</td>
          <td><input type="password" maxlength="12" name="password" value="<?php echo $password; ?>">
        </td></tr><tr><td>Confirm Password:</td>
          <td><input id = "box" type="password" maxlength="12" name="password2" value="<?php echo $password2; ?>">
        </td></tr><tr><td>Email</td>
          <td><input type="text" maxlength="64" name="email" value="<?php echo $email; ?>">
          <div id = link>
          <input type="submit" value="Signup">
          
          </div>

        
      </form>
    </table>
    
  </body>
</html>
