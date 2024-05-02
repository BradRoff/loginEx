
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
<?php

// Connect to the database using the MySQLi class
require_once 'cap_login.php';
$conn = new mysqli($host, $user, $pass, $data);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop table if it exists, ignore error if it doesn't
$query = "DROP TABLE IF EXISTS GameStore";
if ($conn->query($query) === TRUE || $conn->errno == 1051) {
    echo "GameStore Table dropped successfully <br>";
} else {
    echo "Error dropping table: " . $conn->error;
}

// Create the GameStore table
$query = "CREATE TABLE IF NOT EXISTS GameStore (
              Title VARCHAR(100) PRIMARY KEY NOT NULL,
              Publisher VARCHAR(100) DEFAULT 'Unknown',
              ReleaseDate DATE DEFAULT NULL,
              Genre VARCHAR(50) DEFAULT 'Unknown',
              Rating DECIMAL(3,1) DEFAULT 0.0,
              Description TEXT,
              Price DECIMAL(10,2) DEFAULT 0.0,
              StockQuantity INT DEFAULT 0
          )";
if ($conn->query($query) === TRUE) {
    echo "GameStore Table created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Prepare INSERT statement
$stmt = $conn->prepare("INSERT INTO GameStore (Title, Publisher, ReleaseDate, Genre, Rating, Description, Price, StockQuantity) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

// Create an array to be bound to an insert statement for standard data
$valuesArray = array(
    array('The Witcher 3: Wild Hunt', 'CD Projekt', '2015-05-19', 'Action-RPG', 9.3, 'An open-world action RPG set in a fantasy universe, featuring a vast open world, engaging storylines, and immersive gameplay.', 29.99, 100),
    
    array('Mario Kart 8 Deluxe', 'Nintendo', '2017-04-28', 'Racing', 9.2, 'A port of the classic Mario Kart 8 on Switch including its previous DLCs!', 39.99, 60),
    array('Hades', 'Supergiant Games', '2020-09-17', 'Action Rougelike', 9.3, 'An action rougelike where you fight your way out of the underworld, making friends with many well-known Greek gods on the way.', 24.99, 20),
    array('Hollow Knight', 'Team Cherry', '2017-02-24', 'Metroidvania', 9.5, 'Explore twisting caverns, battle tainted creatures, and befriend bizarre bugs, all in a classic, hand-drawn 2D style.', 14.99, 20),
    array('Grand Theft Auto V', 'Rockstar Games', '2013-09-17', 'Action-Adventure', 9.5, 'An action-adventure game set in an open-world environment, allowing players to freely roam the fictional state of San Andreas.', 39.99, 150),
    array('The Legend of Zelda: Breath of the Wild', 'Nintendo', '2017-03-03', 'Action-Adventure', 9.7, 'An action-adventure game set in a large open world, featuring exploration, puzzle-solving, and combat elements.', 49.99, 80),
    array('Red Dead Redemption 2', 'Rockstar Games', '2018-10-26', 'Action-Adventure', 9.8, 'An action-adventure game set in the American Old West, featuring a vast open world, immersive narrative, and realistic gameplay mechanics.', 49.99, 120),
    array('Minecraft', 'Mojang Studios', '2009-05-17', 'Sandbox', 9.0, 'A sandbox video game that allows players to build and explore virtual worlds made up of blocks.', 19.99, 200)
);

$index = 0;
// Bind parameters and execute INSERT statement for each array
foreach ($valuesArray as $values) {
    // Bind parameters
    $stmt->bind_param('ssssdssi', $values[0], $values[1], $values[2], $values[3], $values[4], $values[5], $values[6], $values[7]);

    // Execute INSERT statement
    if ($stmt->execute() === TRUE) {
        $index++;
        echo "Record $index inserted successfully<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
}

// Close statement
$stmt->close();
$conn->close();
?>
