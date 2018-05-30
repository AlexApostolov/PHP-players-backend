<?php

    // Access to the DB
    include('connect-db.php');

    // Check if the "id" in the URL is set & has a value other than NULL, and it is numeric data not a text passed by the user.
    // $_GET is an associative array of variables passed to the current script via the URL parameters.
    if(isset($_GET ['id']) && is_numeric($_GET['id'])) {
        // Collect "id" value.
        $id = $_GET['id'];

        // Create a prepared statement from mysqli to delete a record--if valid.
        // The "?" placeholder separates the mysql logic from the data for additional security against sql injection by users.
        // Then the placeholder is set in the following line.
        if ($stmt = $mysqli->prepare("DELETE FROM players WHERE id = ? LIMIT 1")) {
            // If valid, clarify to mysql what "?" placeholder will equal:
            // First type of data (in this case "i" for integer), and second the value.
            // NOTE: For other type specification chars look at https://secure.php.net/manual/en/mysqli-stmt.bind-param.php
            $stmt->bind_param("i", $id);
            // Now that the param has been bound to the query, go ahead and execute the prepared statement.
            $stmt->execute();
            // Close out the statement.
            $stmt->close();
        } else {
            echo "ERROR: could not prepare SQL statement.";
        }

        // Close out the DB connection itself.
        $mysqli->close();
    } else {
        // Otherwise redirect the user.
        header("Location: view.php");
    }

?>