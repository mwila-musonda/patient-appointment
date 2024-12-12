<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Specialties Management</title>
    <style>
        .btn {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .btn-add {
            background-color: #2196F3;
            color: white;
            margin-bottom: 15px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
        }
        .modal-header {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: red;
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add new specialty
        if (isset($_POST['new_sname'])) {
            $new_sname = $_POST['new_sname'];
            $insert_query = "INSERT INTO hospital.specialties (id, sname) VALUES (NULL, '$new_sname')";
            $stmt = $database->query($insert_query);
            if($stmt){
                echo '  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Specialty Added Successfully!.',
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'specialties.php';
                    }
                });
            </script>";
                exit();
            }
           
        }
    
        // Edit specialty
        if (isset($_POST['edit_id'], $_POST['edit_sname'])) {
            $edit_id = $_POST['edit_id'];
            $edit_sname = $_POST['edit_sname'];
            $update_query = "UPDATE hospital.specialties SET sname = ? WHERE id = ?";
            $stmt = $database->prepare($update_query);
            $stmt->bind_param("si", $edit_sname, $edit_id);
            $stmt->execute();
            echo '  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Specialty Updated Successfully!.',
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'specialties.php';
                    }
                });
            </script>";
        }
    
        // Delete specialty
        if (isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];
            $delete_query = "DELETE FROM hospital.specialties WHERE id = ?";
            $stmt = $database->prepare($delete_query);
            $stmt->bind_param("i", $delete_id);
            $stmt->execute();
            header("location: specialties.php");
            exit();
        }
    }
    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">Webby</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-specialties">
                        <a href="specialties.php" class="non-style-link-menu"><div><p class="menu-text">Specialties</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient  menu-active menu-icon-patient-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>
                </tr><tr class="menu-row" >
                    <td class="menu-btn menu-icon-feedback">
                        <a href="feedback.php" class="non-style-link-menu"><div><p class="menu-text">Patient Feedback</p></div></a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body" style= "padding:20px;">
            <button class="btn btn-add"  style = "margin-top:8%;" onclick="openModal()">Add New Specialty</button>
            <table id="specialtiesTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name of the Specialty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $database->query("SELECT * FROM specialties;");
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                            <td>' . $i . '</td>
                            <td>' . htmlspecialchars($row["sname"]) . '</td>
                            <td>
                                <button  onclick="editSpecialty(' . $row['id'] . ', \'' . htmlspecialchars($row['sname'], ENT_QUOTES) . '\')">Edit</button>
                                <button onclick="deleteSpecialty(' . $row['id'] . ')">Delete</button>
                            </td>
                          </tr>';
                    
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for adding a new specialty -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-header">Add New Specialty</div>
            <form action="specialties.php" method="POST">
                <label for="new_sname">Specialty Name:</label>
                <input type="text" id="new_sname" name="new_sname" required style="width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc;">
                <button type="submit" class="btn btn-add">Save</button>
            </form>
        </div>
    </div>
    <!-- Modal for editing a specialty -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <div class="modal-header">Edit Specialty</div>
        <form action="specialties.php" method="POST">
            <input type="hidden" id="edit_id" name="edit_id">
            <label for="edit_sname">Specialty Name:</label>
            <input type="text" id="edit_sname" name="edit_sname" required style="width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc;">
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</div>

<!-- Modal for confirming delete -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <div class="modal-header">Confirm Delete</div>
        <p>Are you sure you want to delete this specialty?</p>
        <form action="specialties.php" method="POST">
            <input type="hidden" id="delete_id" name="delete_id">
            <button type="submit" class="btn">Yes, Delete</button>
            <button type="button" class="btn" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#specialtiesTable').DataTable();
        });

        function openModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function (event) {
            const modal = document.getElementById('addModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        function editSpecialty(id, name) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_sname').value = name;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function deleteSpecialty(id) {
    document.getElementById('delete_id').value = id;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

    </script>
</body>
</html>
