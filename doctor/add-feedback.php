<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Doctor</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
    <?php


    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");



    if ($_POST) {
        $apponum = $_POST['apponum'];
        $pid = $_POST['pid'];
        $title = $_POST['title'];
        $docid = $_POST['docid'];
        $feedback = $_POST['feedback'];
    
        // Fixing the SQL syntax
        $sql = "INSERT INTO feedback (pid, docid, referenceId, patientFeedback, doctorFeedback)
                VALUES ('$pid', '$docid', '$apponum', NULL, '$feedback')";
    
        // Running the query
        $result = $database->query($sql);
    
        // Check for query success
        if ($result) {
            echo "Feedback submitted successfully.";
        } else {
            echo "Error: " . $database->error;
        }
    }
    
    

    header("location: feedback.php?action=add&error=".$error);
    ?>
    
   

</body>
</html>