<?php
/**
 * Created by PhpStorm.
 * User: RONEWA
 * Date: 4/17/2018
 * Time: 11:23 AM
 */
?>
<?php
/*
Allows the user to both create new records and edit existing records
*/

// connect to the database
include("connect-db.php");

// creates the new/edit record form
// since this form is used multiple times in this file, I have made it a function that is easily reusable
function renderForm($first = '', $last ='', $error = '', $id = '')
{ ?>
    <!DOCTYPE HTML>
    <html lang="en">
    <head>
        <title>
            <?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">


    <h1><?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?></h1>

    <?php if ($error != '') {
        echo "<div style='padding:2px;background: cornsilk; border:1px solid salmon; border-radius: 2px; color:red'>" . $error
            . "</div>";
    } ?>

    <form action="" method="post" class="form-row">
        <div>
            <?php if ($id != '') { ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <p>ID: <?php echo $id; ?></p>
            <?php } ?>

            <div class="form-group">
                <!--<label>First Name: *</label>-->
                <input type="text" name="firstname" class="form-control form-control-lg" placeholder="First Name" value="<?php echo $first; ?>"/>
            </div>
            <div class="form-group">
                <!--<label>Last Name: *</label>-->
                <input type="text" name="lastname" class="form-control form-control-lg" placeholder="Last Name" value="<?php echo $last; ?>"/>
            </div>

            <!--<div class="form-group">
                <label>Cell Number: *</label>
                <input type="text" name="celNumber" class="form-control form-control-lg" placeholder="Cell Number" value="<?php echo $last; ?>"/>
            </div>-->

            <!--<div class="form-group">
                <label>Email Address: *</label>
                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email Address" value="<?php echo $last; ?>"/>

            </div>-->
            <!--<p>* required</p>-->
            <button type="submit" name="submit"  class="btn btn-outline-success" value="Submit">Submit</button>
            <button class="btn btn-outline-secondary"><a href="view.php"> View Records </a></button>

        </div>
    </form>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>

<?php }



/*

EDIT RECORD

*/
// if the 'id' variable is set in the URL, we know that we need to edit a record
if (isset($_GET['id']))
{
// if the form's submit button is clicked, we need to process the form
    if (isset($_POST['submit']))
    {
// make sure the 'id' in the URL is valid
        if (is_numeric($_POST['id']))
        {
// get variables from the URL/form
            $id = $_POST['id'];
            $firstname = htmlentities($_POST['firstname'], ENT_QUOTES);
            $lastname = htmlentities($_POST['lastname'], ENT_QUOTES);
           // $celNumber = htmlentities($_POST['celnumber'], ENT_QUOTES);
           // $email = htmlentities($_POST['email'], ENT_QUOTES);

// check that firstname , lastname cellNumber and email are both not empty
            if ($firstname == '' || $lastname == '')
            {
// if they are empty, show an error message and display the form
                $error = 'ERROR: Please fill in all required fields!';
                renderForm($firstname, $lastname, $error, $id);
            }
            else
            {
// if everything is fine, update the record in the database
                if ($stmt = $mysqli->prepare("UPDATE  contacts SET firstname = ?, lastname = ? WHERE id=?"))
                {
                    $stmt->bind_param("ssi", $firstname, $lastname, $id);
                    $stmt->execute();
                    $stmt->close();
                }
// show an error message if the query has an error
                else
                {
                    echo "ERROR: could not prepare SQL statement.";
                }

// redirect the user once the form is updated
                header("Location: view.php");
            }
        }
// if the 'id' variable is not valid, show an error message
        else
        {
            echo "Error!";
        }
    }
// if the form hasn't been submitted yet, get the info from the database and show the form
    else
    {
// make sure the 'id' value is valid
        if (is_numeric($_GET['id']) && $_GET['id'] > 0)
        {
// get 'id' from URL
            $id = $_GET['id'];

// get the record from the database
            if($stmt = $mysqli->prepare("SELECT * FROM  contacts WHERE id=?"))
            {
                $stmt->bind_param("i", $id);
                $stmt->execute();

                $stmt->bind_result($id, $firstname, $lastname);
                $stmt->fetch();

// show the form
                renderForm($firstname, $lastname, NULL, $id);

                $stmt->close();
            }
// show an error if the query has an error
            else
            {
                echo "Error: could not prepare SQL statement";
            }
        }
// if the 'id' value is not valid, redirect the user back to the view.php page
        else
        {
            header("Location: view.php");
        }
    }
}



/*

NEW RECORD

*/
// if the 'id' variable is not set in the URL, we must be creating a new record
else
{
// if the form's submit button is clicked, we need to process the form
    if (isset($_POST['submit']))
    {
// get the form data
        $firstname = htmlentities($_POST['firstname'], ENT_QUOTES);
        $lastname = htmlentities($_POST['lastname'], ENT_QUOTES);
        //$celNumber = htmlentities($_POST['celNumber'], ENT_QUOTES);
        //$email = htmlentities($_POST['email'], ENT_QUOTES);

// check that firstname and lastname are both not empty
        if ($firstname == '' || $lastname == '')
        {
// if they are empty, show an error message and display the form
            $error = 'ERROR: Please fill in all required fields!';
            renderForm($firstname, $lastname, $error);
        }
        else
        {
// insert the new record into the database
            if ($stmt = $mysqli->prepare("INSERT  contacts (firstname, lastname ) VALUES (?, ?)"))
            {
                $stmt->bind_param("ss", $firstname, $lastname);
                $stmt->execute();
                $stmt->close();
            }
// show an error if the query has an error
            else
            {
                echo "ERROR: Could not prepare SQL statement.";
            }

// redirec the user
            header("Location: index.php");
        }

    }
// if the form hasn't been submitted yet, show the form
    else
    {
        renderForm();
    }
}

// close the mysqli connection
$mysqli->close();
?>
