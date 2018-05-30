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
        // Pass id to edit a record.
        renderForm(NULL, NULL, NULL, $_GET['id']);
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