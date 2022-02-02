<!DOCTYPE HTML>
<html>

<head>
    <title>View Records</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h1 class="page-header">View Records</h1>

                <?php
                // connect to the database
                include('connect-db.php');

                // number of results to show per page
                $per_page = 3;

                // figure out the total pages in the database
                if ($result = $mysqli->query("SELECT * FROM  contacts ORDER BY id")) {
                    if ($result->num_rows != 0) {
                        $total_results = $result->num_rows;
                        // ceil() returns the next highest integer value by rounding up value if necessary
                        $total_pages = ceil($total_results / $per_page);

                        // check if the 'page' variable is set in the URL (ex: view-paginated.php?page=1)
                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $show_page = $_GET['page'];

                            // make sure the $show_page value is valid
                            if ($show_page > 0 && $show_page <= $total_pages) {
                                $start = ($show_page - 1) * $per_page;
                                $end = $start + $per_page;
                            } else {
                                // error - show first set of results
                                $start = 0;
                                $end = $per_page;
                            }
                        } else {
                            // if page isn't set, show first set of results
                            $start = 0;
                            $end = $per_page;
                        }

                        // display pagination
                        echo "<p><button class='btn'><a href='view.php'>View All</a></button> | <b>View Page:</b> ";
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if (isset($_GET['page']) && $_GET['page'] == $i) {
                                echo $i . " ";
                            } else {
                                echo "<button class='btn'><a href='view-paginated.php?page=$i'>$i</a></button> ";
                            }
                        }
                        echo "</p>";

                        // display data in table
                        echo "<table class='table table-responsive table-hover'>";
                        echo "<thead class='bg-primary'>
                <tr>
                <th>ID</th>
                 <th>First Name</th>
                  <th>Last Name</th>
                  <th colspan='2'>Action</th>

</tr>
               </thead>";

                        // loop through results of database query, displaying them in the table
                        for ($i = $start; $i < $end; $i++) {
                            // make sure that PHP doesn't try to show results that don't exist
                            if ($i == $total_results) {
                                break;
                            }

                            // find specific row
                            $result->data_seek($i);
                            $row = $result->fetch_row();

                            // echo out the contents of each row into a table
                            echo "<tr>";
                            echo '<td>' . $row[0] . '</td>';
                            echo '<td>' . $row[1] . '</td>';
                            echo '<td>' . $row[2] . '</td>';
                            echo '<td><button class="btn btn-outline-success"><a href="index.php?id=' . $row[0] . '">Edit</a></button></td>';
                            echo '<td><button class="btn btn-outline-danger"><a href="delete.php?id=' . $row[0] . '">Delete</a></button></td>';
                            echo "</tr>";
                        }

                        // close table>
                        echo "</table>";
                    } else {
                        echo "No results to display!";
                        echo "<br/><br/>";
                    }
                }
                // error with the query
                else {
                    echo "Error: " . $mysqli->error;
                    echo "<br/><br/>";
                }

                // close database connection
                $mysqli->close();

                ?>

                <button class="btn btn-outline-primary btn-sm-center"><a href="index.php">Add New Record</a></button>
            </div>
        </div>
    </div>
    <style>
        .btn {
            margin-bottom: 10px;

        }
    </style>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>

</html>