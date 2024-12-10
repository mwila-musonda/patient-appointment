<?php
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

include("../connection.php");

// Fetch user details
$userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Doctors</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <!-- Profile Section -->
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td>
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Navigation -->
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0; margin-top: 25px;">
                <tr>
                    <td width="13%">
                        <a href="doctors.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding: 11px; margin-left: 20px; width: 125px;">Back</button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">
                            <?php
                            echo '<datalist id="doctors">';
                            $list11 = $database->query("SELECT docname, docemail FROM doctor");
                            while ($row = $list11->fetch_assoc()) {
                                echo "<option value='" . $row["docname"] . "'>";
                                echo "<option value='" . $row["docemail"] . "'>";
                            }
                            echo '</datalist>';
                            ?>
                            <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding: 10px 25px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px; color: rgb(119, 119, 119);">Today's Date</p>
                        <p class="heading-sub12"><?php echo date('Y-m-d'); ?></p>
                    </td>
                    <td width="10%">
                        <button class="btn-label"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top: 10px;">
                        <p class="heading-main12" style="margin-left: 45px; font-size: 18px;">All Doctors</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th>Doctor Name</th>
                                            <th>NoPs</th>
                                            <th>Speciality</th>
                                            <th>Theme</th>
                                            <th>Select Date & Apply</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
            $sqlmain = "SELECT d.specialties, MAX(d.docid) AS docid, MAX(d.docname) AS docname, 
                        MAX(s.title) AS title, MAX(s2.sname) as sname, MAX(s.scheduleid) as scheduleid,MAX(s.nop) as nop
                        FROM doctor d
                        LEFT JOIN schedule s ON s.docid = d.docid
                        LEFT JOIN specialties s2 ON s2.id = d.specialties
                        WHERE s.docid IS NOT NULL AND s.title IS NOT NULL
                        GROUP BY d.specialties";

                $result = $database->query($sqlmain);
              
                if ($result->num_rows == 0) {
                    echo '<tr><td colspan="5">No results found!</td></tr>';
                } else {
                while ($row = $result->fetch_assoc()) {
                    //var_dump($row);die();
                    echo "<tr>
                        <td style='text-align: center; vertical-align: middle;'>{$row["docname"]}</td>
                        <td style='text-align: center; vertical-align: middle;'>{$row["nop"]}</td>
                        <td style='text-align: center; vertical-align: middle;'>{$row["sname"]}</td>
                        <td style='text-align: center; vertical-align: middle;'>{$row["title"]}</td>
                        <td style='text-align: center; vertical-align: middle;'>
                            <form method='post' style='display: flex; align-items: center; justify-content: center; gap: 10px;'>
                                <input type='hidden' name='scheduleid' value='{$row["scheduleid"]}'>
                                <input type='date' name='dofapp[]' required style='padding: 5px; border: 1px solid #ccc; border-radius: 5px;' class='dofapp'
                                onkeydown='return false;'>
                            <button type='submit' name='submit' class='btn-primary-soft' 
                                style='padding: 5px 15px; border: none; background-color: #4CAF50; color: white; border-radius: 5px; cursor: pointer;'>
                                Apply
                            </button>

                            </form>
                        </td>
                      
                    </tr>";
            }
        }
        function generateUniqueNumber($database,$min, $max) {
            do {
                $randomNumber = rand($min, $max);
                $checkQuery = "SELECT COUNT(*) as count FROM appointment WHERE apponum = $randomNumber";
                $result = $database->query($checkQuery);
                $row = mysqli_fetch_assoc($result);
            } while ($row['count'] > 0);
            return $randomNumber;
        }
    
        if (isset($_POST["submit"])) {
            $scheduleid = $_POST["scheduleid"];
            $dofapps = $_POST["dofapp"]; // This is now an array
            
            // Get the current `nop` for the selected schedule
            $esql = "SELECT nop FROM hospital.schedule WHERE scheduleid = '$scheduleid'";
            $run = $database->query($esql);
            if ($run && $row = $run->fetch_assoc()) {
                $currentNop = $row['nop'];
        
                // Check if `nop` is 0 or negative
                if ($currentNop <= 0) {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Fully Booked',
                            text: 'The doctor is fully booked for this session. Please try later.'
                        });
                    </script>";
                } else {
                    foreach ($dofapps as $dofapp) {
                        $uniqueNumber = generateUniqueNumber($database, 10, 100);
                        
                        // Insert each appointment
                        $sql = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) 
                                VALUES ('$userid', '$uniqueNumber', '$scheduleid', '$dofapp')";
        
                        if (!$database->query($sql)) {
                            echo "Error: " . $database->error;
                        } else {
                            // Reduce the number of patients (nop) in the schedule
                            $reduceNop = $currentNop - 1;
                            $updateSql = "UPDATE hospital.schedule SET nop = '$reduceNop' WHERE scheduleid = '$scheduleid'";
                            $database->query($updateSql);
                            
                            // Success message with SweetAlert
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Appointment successfully created!'
                                }).then(() => {
                                    window.location.href = 'appointment.php';
                                });
                            </script>";
                        }
        
                        // Update `currentNop` for subsequent iterations
                        $currentNop--;
                        if ($currentNop <= 0) {
                            break;
                        }
                    }
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to fetch schedule information. Please try again later.'
                    });
                </script>";
            }
        }
        
        
    ?>    
</tbody>

                                </table>
                            </div>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInputs = document.querySelectorAll('.dofapp'); // Select all date inputs
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based, so add 1
        const day = String(today.getDate()).padStart(2, '0'); // Pad single-digit days with a leading zero
        const formattedDate = `${year}-${month}-${day}`; // Format as YYYY-MM-DD

        dateInputs.forEach(input => {
            input.min = formattedDate; // Set the min attribute for each input
        });
    });
</script>