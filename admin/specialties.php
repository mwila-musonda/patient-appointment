<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Appointments</title>
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
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if ($_SESSION["user"] == "" || $_SESSION["usertype"] != 'a') {
            header("location: ../login.php");
            exit();
        }
    } else {
        header("location: ../login.php");
        exit();
    }

    // Import database
    include("../connection.php");
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0;margin:0;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">Webby</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><button class="logout-btn btn-primary-soft btn">Log out</button></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-dashbord">
                        <a href="index.php" class="non-style-link-menu">
                            <div><p class="menu-text">Dashboard</p></div>
                        </a>
                    </td>
                </tr>
                <!-- Add similar corrections for other menu items -->
                <!-- ... -->
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0; margin: 0; padding: 0; margin-top: 25px;">
                <tr>
                    <td width="13%">
                        <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding: 11px; margin-left: 20px; width: 125px;">Back</button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px; padding-left: 12px; font-weight: 600;">Specialties Management</p>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px; color: rgb(119, 119, 119); text-align: right;">Today's Date</p>
                        <p class="heading-sub12">
                            <?php
                            date_default_timezone_set('Asia/Kolkata');
                            $today = date('Y-m-d');
                            echo $today;
                            $list110 = $database->query("SELECT * FROM specialties;");
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex; justify-content: center; align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top: 10px; width: 100%;">
                        <p class="heading-main12" style="margin-left: 45px; font-size: 18px; color: rgb(49, 49, 49);">
                            All Specialties (<?php echo $list110->num_rows; ?>)
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">No</th>
                                            <th class="table-headin">Name of the Speciality</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $database->query("SELECT * FROM specialties;");
                                        if ($result->num_rows == 0) {
                                            echo '<tr>
                                                <td colspan="2" style="text-align: center; padding: 20px;">
                                                    <img src="../img/notfound.svg" width="25%">
                                                    <p class="heading-main12" style="font-size: 20px; color: rgb(49, 49, 49);">
                                                        We couldn\'t find anything related to your keywords!
                                                    </p>
                                                    <a href="appointment.php" class="non-style-link">
                                                        <button class="login-btn btn-primary-soft btn">
                                                            Show all Specialties
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>';
                                        } else {
                                            $i = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>
                                                    <td>' . $i . '</td>
                                                    <td style="font-weight: 600;">' . htmlspecialchars($row["sname"]) . '</td>
                                                </tr>';
                                                $i++;
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
