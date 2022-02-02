<?php
/**
 * Created by PhpStorm.
 * User: RONEWA
 * Date: 4/17/2018
 * Time: 10:53 AM
 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>View Records</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="col-md-12">

<h1>View Records</h1>

<p><b>View All</b> | <button class="btn"><a href="view-paginated.php">View Paginated</a></button></p>

<?php
// connect to the database
include('connect-db.php');

// get the records from the database
if ($result = $mysqli->query("SELECT * FROM  contacts ORDER BY id"))
{
// display records if there are records to display
    if ($result->num_rows > 0)
    {
// display records in a table
        echo "<table class='table table-responsive table-hover'>";

// set table headers
        echo "<thead class='bg-primary'>
                <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th colspan='2'>Action</th>
                </tr>
              </thead>";

        while ($row = $result->fetch_object())
        {
// set up a row for each record
            echo "<tr>";
            echo "<td>" . $row->id . "</td>";
            echo "<td>" . $row->firstname . "</td>";
            echo "<td>" . $row->lastname . "</td>";
            echo "<td><button class='btn-outline-success btn'><a href='index.php?id=" . $row->id . "'>Edit</a></button></td>";
            echo "<td><button class='btn-outline-danger btn'><a href='delete.php?id=" . $row->id . "'>Delete</a></button></td>";
            echo "</tr>";
        }

        echo "</table>";
    }
// if there are no records in the database, display an alert message
    else
    {
        echo "No results to display!";
        echo "<br/><br/>";
    }
}
// show an error if there is an issue with the database query
else
{
    echo "Error: " . $mysqli->error;
    echo "<br/><br/>";
}

// close database connection
$mysqli->close();

?>

<button class="btn btn-outline-primary"><a href="index.php">Add New Record</a></button>
</div>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
