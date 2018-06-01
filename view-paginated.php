<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Records</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>View Records</h1>
    <?php
        // Connect to the database.
        include('connect-db.php');
        // Set number of results to show per page.
        $per_page = 3;

        // Figure out the total pages in the database.
        if ($result = $mysqli->query("SELECT * FROM players ORDER BY id")) {
            if ($result->num_rows != 0) {
                // Collect the total number of rows in database.
                $total_results = $result->num_rows;
                // Total number of pages rounded up
                $total_pages = ceil($total_results / $per_page);
                // Specify which records to display.
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                    // Grab the data needed.
                    $show_page = $_GET['page'];
                    if ($show_page > 0 && $show_page <= $total_pages) {
                        // NOTE: first page is page 0 not 1.
                        $start = ($show_page - 1) * $per_page;
                        $end = $start + $per_page;
                    } else {
                        // In case "page" is not set in the URL it will default & show records 0 through 2.
                        $start = 0;
                        $end = $per_page;
                    }
                } else {
                    $start = 0;
                    $end = $per_page;
                }
                // Display the pagination.
                echo "<p><a href='view.php'>View All</a> | <b>View Page: </b> ";
                for ($i = 1; $i <= $total_pages; $i++) {
                    // Remove the link if on the current page
                    if (isset($_GET['page']) && $_GET['page'] == $i) {
                        echo $i . " ";
                    } else {
                        echo "<a href='view-paginated.php?page=$i'>$i</a> ";
                    }
                }
            echo "</p>";

            // Display data in a table.
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>ID</th> <th>First Name</th> <th>Last Name</th> <th></th> <th></th></tr>";

            // Loop through results of database query, displaying them in the table.
            for ($i = $start; $i < $end; $i++) {
                // Make sure that PHP doesn't try to show results that don't exist
                if ($i == $total_results) {
                    break;
                }

                // Find specific row
                $result->data_seek($i);
                $row = $result->fetch_row();

                // Echo out the contents of each row into a table
                echo "<tr>";
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td>' . $row[2] . '</td>';
                echo '<td><a href="records.php?id=' . $row[0] . '">Edit</a></td>';
                echo '<td><a href="delete.php?id=' . $row[0] . '">Delete</a></td>';
                echo "</tr>";
            }

            // Close table
            echo "</table>";
        } else {
            echo "no results to display!";
        }
    }
    // Error with query
    else {
        echo "Error: " . $mysqli->error;
    }

        // Close database.
        $mysqli->close();
    ?>

    <a href="records.php">Add New Record</a>
</body>
</html>