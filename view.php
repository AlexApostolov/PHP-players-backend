<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Players</title>
</head>
<body>
    <h1>View Players</h1>

    <?php
    // Open connection with the database using the DB settings file.
    include('connect-db.php');

    // Make the query to get the data for the view.
    if ($result = $mysqli->query('SELECT * FROM players ORDER BY id')) {
        // Check if there are results to display. Include logic if there isn't.
        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='10'>";
            echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th></th><th></th></tr>';

            // Display the results from the DB. Loop over a fetched object to save each row.
            while ($row = $result->fetch_object()) {
                echo "<tr>";
                echo "<td>" . $row->id . "</td>";
                echo "<td>" . $row->firstname . "</td>";
                echo "<td>" . $row->lastname . "</td>";
                echo "<td><a href='records.php?id=" . $row->id . "'>Edit</a></a></td>";
                echo "<td><a href='delete.php?id=" . $row->id . "'>Delete</a></a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo 'No results to display!';
        }
    } else {
        echo 'Error: ' . $mysqli->error;
    }

    // Close the connection.
    $mysqli->close();

    ?>

    <a href="records.php">Add New Record</a>
</body>
</html>