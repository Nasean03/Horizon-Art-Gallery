<?php

$pagetitle = "Delete Artists";


require_once '../connect.php';

if (isset($_GET['artistID'])) {
    $artistID = mysqli_real_escape_string($DBCONN, $_GET['artistID']);

    $checkQuery = "SELECT * FROM artist WHERE artistID = '$artistID'";
    $checkResult = mysqli_query($DBCONN, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $deleteQuery = "DELETE FROM artist WHERE artistID = '$artistID'";
        if (mysqli_query($DBCONN, $deleteQuery)) {
            echo "<script>alert('Artist deleted successfully.'); window.location.href='search_artists.php';</script>";
        } else {
            echo "Error deleting record: " . mysqli_error($DBCONN);
        }
    } else {
        echo "Artist not found.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($DBCONN);

?>
