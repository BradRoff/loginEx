<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="stylesheet.css">
<?php
    session_start(); 
    

    echo "Goodbye ". $_SESSION['username'] ; 
    destroy_session_and_data();
    die ("<p><a href='index.php'>Click here to return to index.</a></p>"); 
    function destroy_session_and_data()
    {
      $_SESSION = array();
      setcookie(session_name(), '', time() - 2592000, '/');
      session_destroy();
    }
?>
