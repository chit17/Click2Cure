<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
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
    $sqlmain = "SELECT * FROM patient WHERE pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userfetch = $result->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    // Fetch appointments
    $sqlmain = "SELECT appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate FROM schedule INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN doctor ON schedule.docid=doctor.docid WHERE patient.pid=$userid";

    if ($_POST) {
        if (!empty($_POST["sheduledate"])) {
            $sheduledate = $_POST["sheduledate"];
            $sqlmain .= " AND schedule.scheduledate='$sheduledate'";
        }
    }

    $sqlmain .= " ORDER BY appointment.appodate ASC";
    $result = $database->query($sqlmain);
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
                <a href="appointment.php" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Back</a>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-semibold"><?php echo date('Y-m-d'); ?></p>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <p class="text-xl font-semibold mb-4">Filter Appointments</p>
                <form action="" method="post" class="flex flex-col md:flex-row gap-4">
                    <input type="date" name="sheduledate" class="input-text flex-1">
                    <button type="submit" name="filter" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Filter</button>
                </form>
            </div>

            <!-- Appointments Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-xl font-semibold mb-4">My Bookings (<?php echo $result->num_rows; ?>)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    if ($result->num_rows == 0) {
                        echo '<div class="col-span-3 text-center">
                            <img src="../img/notfound.svg" alt="Not Found" class="w-1/4 mx-auto">
                            <p class="text-gray-600 mt-4">We couldn\'t find anything related to your keywords!</p>
                            <a href="appointment.php" class="inline-block mt-4 bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Show all Appointments</a>
                        </div>';
                    } else {
                        for ($x = 0; $x < $result->num_rows; $x++) {
                            $row = $result->fetch_assoc();
                            $appoid = $row["appoid"];
                            $title = $row["title"];
                            $docname = $row["docname"];
                            $scheduledate = $row["scheduledate"];
                            $scheduletime = $row["scheduletime"];
                            $apponum = $row["apponum"];
                            $appodate = $row["appodate"];

                            echo '<div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="h3-search text-gray-600">Booking Date: ' . substr($appodate, 0, 30) . '</div>
                                <div class="h3-search text-gray-600">Reference Number: OC-000-' . $appoid . '</div>
                                <div class="h1-search text-xl font-semibold">' . substr($title, 0, 21) . '</div>
                                <div class="h3-search text-gray-600">Appointment Number: <span class="h1-search">0' . $apponum . '</span></div>
                                <div class="h3-search text-gray-600">' . substr($docname, 0, 30) . '</div>
                                <div class="h4-search text-gray-600">Scheduled Date: ' . $scheduledate . '<br>Starts: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)</div>
                                <a href="?action=drop&id=' . $appoid . '&title=' . $title . '&doc=' . $docname . '" class="inline-block mt-4 bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300 w-full text-center">Cancel Booking</a>
                            </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Popup for Actions -->
    <?php
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'booking-added') {
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-xl font-bold mb-4">Booking Successful</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            Your Appointment number is ' . $id . '.<br><br>
                        </div>
                        <div class="flex justify-center mt-4">
                            <a href="appointment.php" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">OK</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        } elseif ($action == 'drop') {
            $title = $_GET["title"];
            $docname = $_GET["doc"];

            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-xl font-bold mb-4">Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to Cancel this Appointment?<br><br>
                            Session Name: &nbsp;<b>' . substr($title, 0, 40) . '</b><br>
                            Doctor name&nbsp; : <b>' . substr($docname, 0, 40) . '</b><br><br>
                        </div>
                        <div class="flex justify-center mt-4">
                            <a href="delete-appointment.php?id=' . $id . '" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300 mr-2">Yes</a>
                            <a href="appointment.php" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">No</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        }
    }
    ?>
</body>
</html>