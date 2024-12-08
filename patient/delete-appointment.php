<?php
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit();
    }
} else {
    header("location: ../login.php");
    exit();
}

if ($_GET) {
    // Import database
    include("../connection.php");
    $id = $_GET["id"];
    $scheduleid = $_GET['scheduleid'];
    
    
    // Fetch the current number of appointments (nop)
    $esql = "SELECT nop FROM hospital.schedule WHERE scheduleid = '$scheduleid'";
    
    $run = $database->query($esql);
    
    if ($row = $run->fetch_assoc()) {
        // Increment nop
        $increaseNop = $row['nop'] + 1;
        
        // Update nop in the schedule table
        $updateSql = "UPDATE hospital.schedule SET nop = '$increaseNop' WHERE scheduleid = '$scheduleid'";
        $database->query($updateSql);
    }

    // Delete the appointment
    $sql = $database->query("DELETE FROM appointment WHERE appoid = '$id'");

    // Redirect to the appointment page
    header("location: appointment.php?message=Appointment+deleted+successfully");
    exit();
}
?>
