<?php
    include('connect-db.php');
    // Display form to user using a function with defaults of empty strings that we can pass info to.
    function renderForm($first = '', $last = '', $error = '', $id = '') { ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <!-- If id value is set, then we're editing a record. If id is not set, then we're adding a new record. -->
            <title>
            <?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?>
            </title>
        </head>
        <body>
            <h1><?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?></h1>
            <!-- Provide form validation. -->
            <?php if ($error != '') {
                echo "<div style='padding: 4px; border: 1px solid red; color: red;'>" . $error . "</div>";
            } ?>
            <form action="" method="post">
                <div>
                    <!-- If editing a record -->
                    <?php if ($id != '') { ?>
                    <!-- ensure that when the form gets submitted we can grab the id info. -->
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <p>ID: <?php echo $id; ?></p>
                    <?php } ?>
                    <!-- Display fields for editing/adding record. -->
                    <strong>First Name: *</strong> <input type="text" name="firstname" value="<?php echo $first; ?>" /><br>
                    <strong>Last Name: *</strong> <input type="text" name="lastname" value="<?php echo $last; ?>" />
                    <p>* required</p>
                    <input type="submit" name="submit" value="Submit" />
                </div>
            </form>
        </body>
        </html>
    <?php }

    // Process form to update/create record.
    if (isset($_GET['id'])) {
        // Check if our form been submitted.
        // Rather than getting the data from the URL with GET, we'll capture the data from the form itself with POST.
        if (isset($_POST['submit'])) {
            // Grab the data from the form & save it to the database.
            if (is_numeric($_POST['id'])) {
                $id = $_POST['id'];
                $firstname = htmlentities($_POST['firstname'], ENT_QUOTES);
                $lastname = htmlentities($_POST['lastname'], ENT_QUOTES);

                // Esure that the firstname & lastname fields are filled in.
                if ($firstname == '' || $lastname == '') {
                    $error = 'ERROR: Please fill in all required fields!';
                    renderForm($firstname, $lastname, $error);
                } else {
                    // Take filled in form fields & save into database.
                    if ($stmt = $mysqli->prepare("UPDATE players SET firstname = ?, lastname = ? WHERE id=?")) {
                        $stmt->bind_param("ssi", $firstname, $lastname, $id);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        echo "ERROR: could not prepare SQL statement.";
                    }
                    // Redirect user
                    header("Location: view.php");
                }
            } else {
                echo "Error!";
            }
        } else {
            // Otherwise display the form.
            // Pass id to edit a record.
            // We know already that id is set, but first check that it is of numeric type and has a value.
            if (is_numeric($_GET['id']) && $_GET['id'] > 0) {
                // Query database using prepared statements.
                $id = $_GET['id'];
                if ($stmt = $mysqli->prepare("SELECT * FROM players WHERE id=?")) {
                    // Replace "?" placeholder with actual data, that is an integer.
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    // Bind the result with what rows we want.
                    $stmt->bind_result($id, $firstname, $lastname);
                    $stmt->fetch();
                    // Display the form with the data fetched & errors to be NULL.
                    renderForm($firstname, $lastname, NULL, $id);
                    $stmt->close();
                } else {
                    echo "Error: could not prepare SQL statement";
                }
            } else {
                // If id is set, but incorrectly, send user back to list page
                header("Location: view.php");
            }
        }
    } else {
        // Create new record.
        if (isset($_POST['submit'])) {
            // If form is submitted, grab the data. Convert quotations to prevent SQL injection.
            $firstname = htmlentities($_POST['firstname'], ENT_QUOTES);
            $lastname = htmlentities($_POST['lastname'], ENT_QUOTES);

            // Check that user entered info for both first and last names
            if ($firstname == '' || $lastname == '') {
                // Display error message, and show the form again.
                $error = 'ERROR: Please fill in all required fields.';
                renderForm($firstname, $lastname, $error);
            } else {
                if ($stmt = $mysqli->prepare('INSERT players (firstname, lastname) VALUES (?, ?)')) {
                    // Bind the two strings.
                    $stmt->bind_param("ss", $firstname, $lastname);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo 'ERROR: Could not prepare SQL statement.';
                }

                header("Location: view.php");
            }
        } else {
            renderForm();
        }
    }
    $mysqli->close();
?>