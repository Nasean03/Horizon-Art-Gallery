<?php
$pagetitle = "Update Artists";

include '../other/artist_header.php';
include '../other/artist_menu.php';


if (isset($_POST['submitted'])) 
{
    require_once '../connect.php'; // Ensure the connection is established

    // Create a function for escaping the data.
    function escape_data($data, $DBCONN) 
    {
        // if magic quotes is enabled strip the slashes
        if (ini_get('magic_quotes_gpc')) 
        {
            $data = stripslashes($data);
        }
        // escape what could be problematic characters
        return mysqli_real_escape_string($DBCONN, trim($data));
    } // End of function.

    $errors = [];

    // Initialize variables with default values
    $date = $artistid = $fname = $lname = $dob = $nationality = $contactinfo = $biography = $personallinks = $staffid = '';

    // Check for artist ID
    if (isset($_POST['artistID']) && !empty($_POST['artistID']))
    {
        $artistid = escape_data($_POST['artistID'], $DBCONN); // Assign and escape the artistID
    } 
    else 
    {
        $errors[] = 'Artist ID is required.';
    }   
 
    // Check for first name
    if (empty($_POST['fname'])) 
    {
        $errors[] = 'First name is required.';
    } 
    else 
    {
        $fname = escape_data($_POST['fname'], $DBCONN);
    }

    
    // Check for last name
    if (empty($_POST['lname'])) 
    {
        $errors[] = 'Last name is required.';
    } 
    else 
    {
        $lname = escape_data($_POST['lname'], $DBCONN);
    }

    // Check for date of birth
    if (empty($_POST['dob'])) 
    {
        $errors[] = 'Date of birth is required';
    } 
    else 
    {
        $dob = $_POST['dob'];
        $todays_date = date('Y-m-d'); // current date

        if ($dob > $todays_date) 
        {
            $errors[] = 'Invalid date. Date cannot be in the future.';
        }
        $dob = escape_data($_POST['dob'], $DBCONN);
    }

    // Check for nationality 
    if (empty($_POST['nationality'])) 
    {
        $errors[] = 'nationality is required.';
    } 
    else 
    {
        $nationality = escape_data($_POST['nationality'], $DBCONN);
    }

    // Check for contact information
    if (empty($_POST['contactinfo'])) 
    {
        $errors[] = 'Contact information is required.';
    } 
    else 
    {
        $contactinfo = escape_data($_POST['contactinfo'], $DBCONN);
    }

    // Check for biography 
    if (empty($_POST['biography'])) 
    {
        $biography = NULL;
    } 
    else 
    {
        $biography = escape_data($_POST['biography'], $DBCONN);
    }

    // Check for personal links
    if (empty($_POST['personallinks'])) 
    {
        $personallinks = NULL;
    } 
    else 
    {
        $personallinks = escape_data($_POST['personallinks'], $DBCONN);
    } 

    //check for staff ID
    if (isset($_POST['staffID']) && !empty($_POST['staffID'])) 
    {
        $staffid = escape_data($_POST['staffID'], $DBCONN); // Assign and escape the staffID
    } 
    else 
    {
        $errors[] = 'Staff ID is required.';
    }

    if (empty($errors)) 
    {
        $UPDATEQUERY = "UPDATE artist SET fname = '$fname', lname = '$lname', dob = '$dob', nationality = '$nationality', contactinfo = '$contactinfo', biography = '$biography', personallinks = '$personallinks', staffID = '$staffid' WHERE artistID = '$artistid'";
        $UPDATERESULT = mysqli_query($DBCONN, $UPDATEQUERY);

        if ($UPDATERESULT) {
             echo "<h1>Artist successfully updated.</h1>";
        } else {
             echo "Error updating artist: " . mysqli_error($DBCONN);
        }
    } 
    else 
    {
        echo '<h1 id="mainhead">Error!</h1><br>';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br>";
        }
    }
}
?>


<h3 align="center">Update Artist Records</h3>
<div class="formtable">
    <form action="update_artists.php" method="POST">
        <table>
                <td>Artist ID:</td>
                <td>
                    <select id="artistID" name="artistID">
                        <?php
                        require_once '../connect.php';

                        $QUERY = "SELECT artistID FROM artist";
                        $RESULT = mysqli_query($DBCONN, $QUERY);

                        echo "<option value='' selected>Select Artist ID</option>";

                        while ($ROW = mysqli_fetch_array($RESULT)) 
                        {
                            echo "<option value=".$ROW['artistID'].">".$ROW['artistID']."</option>";
                        }
                        //mysqli_close($DBCONN);
                        ?>
                    </select>
                </td>
           </tr>
             <tr>
                <td>First Name:</td>
                <td>
                    <input type="text" id="textfield" name="fname" size="20" maxlength="20">
                </td>
            </tr>
             <tr>
                <td>Last Name:</td>
                <td>
                    <input type="text" id="textfield" name="lname" size="20" maxlength="20">
                </td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>
                    <input type="text" id="textfield" name="dob" size="20" placeholder="YYYY-MM-DD">
                </td>
            </tr>
            <tr>
                <td>Nationality:</td>
                <td>
                    <select id="nationality" name="nationality">
                        <?php
                        require_once '../connect.php';

                        $QUERY = "SELECT DISTINCT nationality FROM artist";
                        $RESULT = mysqli_query($DBCONN, $QUERY);

                        echo "<option value='' selected>Select Nationality</option>";

                        while ($ROW = mysqli_fetch_array($RESULT)) 
                        {
                            echo "<option value=".$ROW['nationality'].">".$ROW['nationality']."</option>";
                        }
                        //mysqli_close($DBCONN);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Contact Info:</td>
                <td>
                    <input type="text" id="textfield" name="contactinfo" size="14" maxlength="14" placeholder="1-234-567-8900">
                </td>
            </tr>
            <tr>
                <td>Biography:</td>
                <td>
                    <input type="text" id="textfield" name="biography" size="300" maxlength="300">
                </td>
            </tr>
            <tr>
                <td>Personal Links:</td>
                <td>
                    <input type="text" id="textfield" name="personallinks" size="100" maxlength="100">
                </td>
            </tr>
            <tr>
               <td>Staff ID:</td>
                <td>
                    <select id="staffID" name="staffID">
                        <?php
                        require_once '../connect.php';

                        $QUERY = "SELECT DISTINCT artist.staffID FROM artist, staff WHERE artist.staffID = staff.staffID";
                        $RESULT = mysqli_query($DBCONN, $QUERY);

                        echo "<option value='' selected>Select Staff ID</option>";

                        while ($ROW = mysqli_fetch_array($RESULT)) 
                        {
                            echo "<option value=".$ROW['staffID'].">".$ROW['staffID']."</option>";
                        }
                        mysqli_close($DBCONN);
                        ?>
                    </select>
                </td>
            </tr> 
            <tr class="submit_buttons">
                <td colspan="2" align="center">
                    <br>
                    <input type="submit" value="Update" name="update_button" id="btn">
                    <input type="reset" value="Reset" name="reset_button" id="btn">
                    <input type="hidden" name="submitted" value="TRUE">
                </td>
            </tr>
        </table>
    </form>
</div>


