<?php

?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<head>
    <title>BCS350 Capstone project -- Registration -- Bradley Roff</title>
</head>
<body>
    <fieldset id="title">
        <h3>BCS350 Capstone project -- Registration -- Bradley Roff</h3> 
    </fieldset>
   <?php
 require_once 'cap_login.php';
 $conn = new mysqli($host, $user, $pass, $data);

/* think this isnt needed as it is done in setup DB
 //create table user table if it doesn't exist. 
$query = "CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(32) NOT NULL PRIMARY KEY,
    email VARCHAR(32) NOT NULL,
    password VARCHAR(225) NOT NULL
    
)";
if ($conn->query($query) === TRUE) {
echo "Users Table created successfully<br>";
} else {
echo "Error creating table: " . $conn->error;
}
*/
if (!empty($_POST['Username']) && !empty($_POST['Email']) && !empty($_POST['Password']) && !empty($_POST['Confirm']) ) //tests that each field is not empty
{
    $Username = htmlspecialchars($_POST['Username']);
    $Email = htmlspecialchars($_POST['Email']);
    $Password = htmlspecialchars($_POST['Password']);
    $ConfirmPassword = htmlspecialchars($_POST['Confirm']);
    if ($Password == $ConfirmPassword )
    {
        //sql command to check if username already exists in users database
        $sqlUsername = "Select * from users where username = '$Username'";
        $sqlEmail = "Select * from users where email = '$Email'";
        $resultUsername = $conn->query($sqlUsername);
        $resultEmail = $conn->query($sqlEmail);
        if ($resultUsername->num_rows > 0){ //checks if any data exists for said username
            echo 'Warning, Username already exists. Please chose a different username.<br>
            Or if you are a returning user, <a href="login.php">Login here!</a>';
        }
        elseif ($resultEmail->num_rows > 0){ //checks if any data exists for said email
            echo 'Warning, Email already exists. Please use a diffrnet email.<br>
            Or if you are a returning user, <a href="login.php">Login here!</a>';
        }
        elseif (! filter_var($Email, FILTER_VALIDATE_EMAIL))
        {
            echo 'Warning, please enter valid email adress';
        }
        else{ //if all requirements are fufiled, salt/hash password and enter into users database
            $Hash     = password_hash($Password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) 
            VALUES ('$Username', '$Email', '$Hash')";
            
          
            if ($conn->query($query) === TRUE) {
                echo 'User '. $Username . ' succsesfully added to users table, please <a href="login.php">Login here!</a> ';
            } else {
                echo "Error: " . $query . "<br>" . $conn->error;
            }

        }

    }
    else { //case that passwords dont match
        echo "Warning, Passwords do not match";

    }
}
else{ //error if all fields are not filled.
    echo "please fill all fields";
}

// code setting up registration form
echo <<<_END
<form action="registration.php" method="post"><pre>
<label for="Username">Username:</label> 
<input type="text" name="Username" >
<label for="Email">Email:</label> 
<input type="text" name="Email" >
<label for="Password">Password:</label> 
<input type="password" name="Password">
<label for="Confirm">Confirm Password:</label> 
<input type="password" name = "Confirm">
            <input type="submit" value="Register user">
  
</pre></form>
_END;
   ?>
   
</body>
</html>
<?php

?>
