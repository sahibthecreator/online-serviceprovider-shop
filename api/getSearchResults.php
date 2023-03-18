<?php
include "connect.php";

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if (isset($_REQUEST["term"])) {
    // Prepare a select statement
    $sql = "SELECT * FROM Packages WHERE `Name` LIKE ? LIMIT 6";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);

        // Set parameters
        $param_term = '%' . $_REQUEST["term"] . '%';

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            // Check number of rows in the result set
            if (mysqli_num_rows($result) > 0) {
                // Fetch result rows as an associative array
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo "<div class='result'>
                    <a style = 'text-decoration: none; color:black' href ='product.php?id=" . $row['Id'] . "' >
                    <img src='img/" . $row["Name"] . "-icon.webp' />
                    <p>" . $row["Amount"] . " " . $row["Type"] . "</p>
                    </a></div>";
                }
                echo "<div class='result'>
                    <a style = 'text-decoration: none; color:black' href ='shop.php' >
                    <p>View all</p>
                    </a></div>";
            } else {
                echo "<div class='result'>
                <a style = 'text-decoration: none; color:black' href ='shop.php' >
                <p>Not found</p>
                </a></div>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// close connection
mysqli_close($conn);
