<?php

$pagetitle = "Search Artists";

include '../other/artist_header.php';
include '../other/artist_menu.php';
?>

<h3 align="center">Search for Artists</h3>
<div class="formtable">
    <form action="search_artists.php" method="post">
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
                <td id="label">Date of Birth Range:</td>
                <td>
                    <input type="text" id="textfield" name="firstdate" size="30" maxlength="30" placeholder="YYYY-MM-DD">
                </td>
                <td>to</td>
                <td>
                    <input type="text" id="textfield" name="seconddate" size="30" maxlength="30" placeholder="YYYY-MM-DD">
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
                        //mysqli_close($DBCONN);
                        ?>
                    </select>
                </td>
            </tr> 

            <tr class="submit_buttons">
                <td colspan="2" align="center">
                    <br>
                    <input type="submit" value="Submit" name="submit_button" id="btn">
                    <input type="reset" value="Reset" name="reset_button" id="btn">
                    <input type="hidden" name="submitted" value="TRUE">
                </td>
            
        </table>
    </form>
</div>

<?php
if(isset($_POST['submitted'])) {
    require_once '../connect.php';

    function escape_data($data, $DBCONN) {
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        return mysqli_real_escape_string($DBCONN, trim($data));
    }
    $errors = [];

    $query = "SELECT artistID, fname, lname, dob, nationality, contactinfo, biography, personallinks, staffID FROM artist WHERE 1=1";

    // Add conditions based on user input
    if (!empty($_POST['firstdate']) && !empty($_POST['seconddate']))
    {
        $firstdate = escape_data($_POST['firstdate'], $DBCONN);
        $seconddate = escape_data($_POST['seconddate'], $DBCONN);
        $query .= " AND dob BETWEEN '$firstdate' AND '$seconddate'";
    }
    else if (!empty($_POST['firstdate']) && empty($_POST['seconddate'])) // this searches between the initial date and the highest date in the clinic table. only works when the second date is empty
    {
        $firstdate = escape_data($_POST['firstdate'], $DBCONN);
        
        $seconddate = "SELECT MAX(dob) from artist;";
        $seconddateresult = mysqli_query($DBCONN,$seconddate);
        $maxdate = mysqli_fetch_assoc($seconddateresult);
        $maxdateresult = $maxdate['MAX(dob)'];
        $query .= " AND dob BETWEEN '$firstdate' AND '$maxdateresult'";
    } 
    else if (empty($_POST['firstdate']) && !empty($_POST['seconddate'])) //opposite of the previous else if statement.
    {
        $seconddate = escape_data($_POST['seconddate'], $DBCONN);
        
        $firstdate = "SELECT MIN(dob) from artist;";
        $firstdateresult = mysqli_query($DBCONN,$firstdate);
        $mindate = mysqli_fetch_assoc($firstdateresult);
        $mindateresult = $mindate['MIN(dob)'];
        $query .= " AND dob BETWEEN '$mindateresult' AND '$seconddate'";
    }
    else 
    {
        $errors[] = 'A minimum of one date is required';
    }

      if (!empty($_POST['staffID'])) 
    {
        $staffid = escape_data($_POST['staffID'], $DBCONN);
        $query .= " AND staffID = '$staffid'";
    }

    if (!empty($_POST['nationality'])) 
    {
        $nationality = escape_data($_POST['nationality'], $DBCONN);
        $query .= " AND nationality = '$nationality'";
    }

    $query .= " ORDER BY artistID ASC"; // sorts the dates so it's easier to read. 

// Execute the query
    $result = mysqli_query($DBCONN, $query);

    if(empty($errors))
    {
        if ($result) 
        {
            if (mysqli_num_rows($result) > 0)
            {
                // Display the results
                echo '<table border="1">';
                echo '<tr><th>Artist ID</th><th>First Name</th><th>Last Name</th><th>Date of Birth</th><th>Nationality</th><th>Contact Info</th><th>Biography</th><th>Personal Links</th><th>StaffID</th><th>Delete</th></tr>';

                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
                {
                    echo '<tr>';
                    echo '<td>' . $row['artistID'] . '</td>';
                    echo '<td>' . $row['fname'] . '</td>';
                    echo '<td>' . $row['lname'] . '</td>';
                    echo '<td>' . $row['dob'] . '</td>';
                    echo '<td>' . $row['nationality'] . '</td>';
                    echo '<td>' . $row['contactinfo'] . '</td>';
                     echo '<td>' . $row['biography'] . '</td>';
                    echo '<td>' . $row['personallinks'] . '</td>';
                    echo '<td>' . $row['staffID'] . '</td>';
                    echo '<td><a href="remove_artists.php?artistID=' . $row['artistID'] . '">Delete</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            else 
            {
                echo '<h4>No Records matching your search</h4>';
            }
        } 
        else 
        { 
            if (mysqli_num_rows($result) == 0)
            {
                echo 'Error executing query: ' . mysqli_error($DBCONN);
            }
        }
    }
    else 
    {
        echo '<h1 id="mainhead">Error!</h1><br>';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br>";
        }
    }
    

    mysqli_close($DBCONN);
}
?>
