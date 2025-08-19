<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animations from the original CSS */
        @keyframes transitionIn-Y-bottom {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-transitionIn-Y-bottom {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    // Import database
    include("../connection.php");
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];
    ?>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <div class="bg-white w-full md:w-1/4 lg:w-1/5 p-4 border-r border-gray-200">
            <div class="profile-container text-center mb-6">
                <img src="../img/user.png" alt="Profile" class="w-20 h-20 rounded-full mx-auto">
                <p class="profile-title mt-2 text-xl font-semibold"><?php echo substr($username, 0, 13) ?>..</p>
                <p class="profile-subtitle text-gray-600"><?php echo substr($useremail, 0, 22) ?></p>
                <a href="../logout.php" class="mt-4 inline-block bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Log out</a>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="index.php" class="flex items-center p-2 text-gray-700 hover:bg-[#6A64F1] hover:text-white rounded-lg transition duration-300">
                        <span class="ml-2">Home</span>
                    </a>
                </li>
                <li>
                    <a href="doctors.php" class="flex items-center p-2 text-gray-700 hover:bg-[#6A64F1] hover:text-white rounded-lg transition duration-300">
                        <span class="ml-2">All Doctors</span>
                    </a>
                </li>
                <li>
                    <a href="schedule.php" class="flex items-center p-2 text-gray-700 hover:bg-[#6A64F1] hover:text-white rounded-lg transition duration-300">
                        <span class="ml-2">Scheduled Sessions</span>
                    </a>
                </li>
                <li>
                    <a href="appointment.php" class="flex items-center p-2 text-gray-700 hover:bg-[#6A64F1] hover:text-white rounded-lg transition duration-300">
                        <span class="ml-2">My Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="flex items-center p-2 text-gray-700 hover:bg-[#6A64F1] hover:text-white rounded-lg transition duration-300">
                        <span class="ml-2">Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="doctors.php" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Back</a>
                <form action="" method="post" class="flex flex-col md:flex-row gap-4">
                    <input type="search" name="search" class="input-text flex-1" placeholder="Search Doctor name or Email" list="doctors">
                    <datalist id="doctors">
                        <?php
                        $list11 = $database->query("SELECT docname, docemail FROM doctor");
                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];
                            $c = $row00["docemail"];
                            echo "<option value='$d'></option>";
                            echo "<option value='$c'></option>";
                        }
                        ?>
                    </datalist>
                    <button type="submit" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Search</button>
                </form>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-semibold"><?php echo date('Y-m-d'); ?></p>
                </div>
            </div>

            <!-- Doctors Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-xl font-semibold mb-4">All Doctors (<?php echo $list11->num_rows; ?>)</p>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Doctor Name</th>
                                <th class="text-left p-2">Email</th>
                                <th class="text-left p-2">Specialties</th>
                                <th class="text-left p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_POST) {
                                $keyword = $_POST["search"];
                                $sqlmain = "SELECT * FROM doctor WHERE docemail='$keyword' OR docname='$keyword' OR docname LIKE '$keyword%' OR docname LIKE '%$keyword' OR docname LIKE '%$keyword%'";
                            } else {
                                $sqlmain = "SELECT * FROM doctor ORDER BY docid DESC";
                            }

                            $result = $database->query($sqlmain);

                            if ($result->num_rows == 0) {
                                echo '<tr>
                                    <td colspan="4" class="text-center py-4">
                                        <img src="../img/notfound.svg" alt="Not Found" class="w-1/4 mx-auto">
                                        <p class="text-gray-600 mt-4">We couldn\'t find anything related to your keywords!</p>
                                        <a href="doctors.php" class="inline-block mt-4 bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Show all Doctors</a>
                                    </td>
                                </tr>';
                            } else {
                                for ($x = 0; $x < $result->num_rows; $x++) {
                                    $row = $result->fetch_assoc();
                                    $docid = $row["docid"];
                                    $name = $row["docname"];
                                    $email = $row["docemail"];
                                    $spe = $row["specialties"];
                                    $spcil_res = $database->query("SELECT sname FROM specialties WHERE id='$spe'");
                                    $spcil_array = $spcil_res->fetch_assoc();
                                    $spcil_name = $spcil_array["sname"];

                                    echo '<tr>
                                        <td class="p-2">' . substr($name, 0, 30) . '</td>
                                        <td class="p-2">' . substr($email, 0, 20) . '</td>
                                        <td class="p-2">' . substr($spcil_name, 0, 20) . '</td>
                                        <td class="p-2">
                                            <div class="flex gap-2">
                                                <a href="?action=view&id=' . $docid . '" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">View</a>
                                                <a href="?action=session&id=' . $docid . '&name=' . $name . '" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Sessions</a>
                                            </div>
                                        </td>
                                    </tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup for Actions -->
    <?php
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'drop') {
            $nameget = $_GET["name"];
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-xl font-bold mb-4">Are you sure?</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>(' . substr($nameget, 0, 40) . ').
                        </div>
                        <div class="flex justify-center mt-4">
                            <a href="delete-doctor.php?id=' . $id . '" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300 mr-2">Yes</a>
                            <a href="doctors.php" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">No</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        } elseif ($action == 'view') {
            $sqlmain = "SELECT * FROM doctor WHERE docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $name = $row["docname"];
            $email = $row["docemail"];
            $spe = $row["specialties"];
            $spcil_res = $database->query("SELECT sname FROM specialties WHERE id='$spe'");
            $spcil_array = $spcil_res->fetch_assoc();
            $spcil_name = $spcil_array["sname"];
            $nic = $row['docnic'];
            $tele = $row['doctel'];

            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-xl font-bold mb-4">View Details</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            <table class="w-full">
                                <tr>
                                    <td class="p-2">Name:</td>
                                    <td class="p-2">' . $name . '</td>
                                </tr>
                                <tr>
                                    <td class="p-2">Email:</td>
                                    <td class="p-2">' . $email . '</td>
                                </tr>
                                <tr>
                                    <td class="p-2">NIC:</td>
                                    <td class="p-2">' . $nic . '</td>
                                </tr>
                                <tr>
                                    <td class="p-2">Telephone:</td>
                                    <td class="p-2">' . $tele . '</td>
                                </tr>
                                <tr>
                                    <td class="p-2">Specialties:</td>
                                    <td class="p-2">' . $spcil_name . '</td>
                                </tr>
                            </table>
                        </div>
                        <div class="flex justify-center mt-4">
                            <a href="doctors.php" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">OK</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        } elseif ($action == 'session') {
            $name = $_GET["name"];
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-xl font-bold mb-4">Redirect to Doctor\'s Sessions?</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            You want to view all sessions by <br>(' . substr($name, 0, 40) . ').
                        </div>
                        <form action="schedule.php" method="post" class="flex justify-center mt-4">
                            <input type="hidden" name="search" value="' . $name . '">
                            <button type="submit" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Yes</button>
                        </form>
                    </center>
                </div>
            </div>
            ';
        }
    }
    ?>
</body>
</html>