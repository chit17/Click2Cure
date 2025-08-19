<?php
session_start();
if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

// Import database connection
include("../connection.php");

// Define $today
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

// Fetch data from the database
$list110 = $database->query("SELECT * FROM schedule;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes transitionIn-Y-bottom {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .popup { animation: transitionIn-Y-bottom 0.5s; }
        .sub-table { animation: transitionIn-Y-bottom 0.5s; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-1/5 bg-white border-r border-gray-200 shadow-sm">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <img src="../img/user.png" alt="User" class="w-12 h-12 rounded-full">
                    <div>
                        <p class="text-lg font-medium text-purple-600">Administrator</p>
                        <p class="text-sm text-purple-400">admin@edoc.com</p>
                    </div>
                </div>
                <a href="../logout.php" class="mt-6 block w-full bg-purple-600 text-white text-center py-2 rounded-lg hover:bg-purple-700 transition duration-300">Log out</a>
            </div>
            <nav class="mt-6">
                <a href="index.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Dashboard</a>
                <a href="doctors.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Doctors</a>
                <a href="schedule.php" class="block py-2 px-4 text-purple-600 bg-purple-50">Schedule</a>
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Appointment</a>
                <a href="patient.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Patients</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="schedule.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                    <span>Back</span>
                </a>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="text-lg font-medium"><?php echo $today; ?></p>
                </div>
            </div>

            <!-- Add New Session Button -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-xl font-bold text-gray-800">Schedule a Session</p>
                <a href="?action=add-session&id=none&error=0" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                    <span>Add a Session</span>
                </a>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form action="" method="post" class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <label class="mr-2">Date:</label>
                        <input type="date" name="sheduledate" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                    </div>
                    <div class="flex items-center">
                        <label class="mr-2">Doctor:</label>
                        <select name="docid" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="" disabled selected hidden>Choose Doctor</option>
                            <?php
                            $list11 = $database->query("SELECT * FROM doctor ORDER BY docname ASC;");
                            for ($y = 0; $y < $list11->num_rows; $y++) {
                                $row00 = $list11->fetch_assoc();
                                $sn = $row00["docname"];
                                $id00 = $row00["docid"];
                                echo "<option value='$id00'>$sn</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="submit" name="filter" value="Filter" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                </form>
            </div>

            <!-- All Sessions Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-xl font-bold text-gray-800 mb-4">All Sessions (<?php echo $list110->num_rows; ?>)</p>
                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-purple-50">
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Session Title</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Doctor</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Scheduled Date & Time</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Max Patients</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Events</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_POST) {
                                $sqlpt1 = "";
                                if (!empty($_POST["sheduledate"])) {
                                    $sheduledate = $_POST["sheduledate"];
                                    $sqlpt1 = " schedule.scheduledate='$sheduledate' ";
                                }

                                $sqlpt2 = "";
                                if (!empty($_POST["docid"])) {
                                    $docid = $_POST["docid"];
                                    $sqlpt2 = " doctor.docid=$docid ";
                                }

                                $sqlmain = "SELECT schedule.scheduleid, schedule.title, doctor.docname, schedule.scheduledate, schedule.scheduletime, schedule.nop FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid ";
                                $sqllist = array($sqlpt1, $sqlpt2);
                                $sqlkeywords = array(" WHERE ", " AND ");
                                $key2 = 0;
                                foreach ($sqllist as $key) {
                                    if (!empty($key)) {
                                        $sqlmain .= $sqlkeywords[$key2] . $key;
                                        $key2++;
                                    }
                                }
                            } else {
                                $sqlmain = "SELECT schedule.scheduleid, schedule.title, doctor.docname, schedule.scheduledate, schedule.scheduletime, schedule.nop FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid ORDER BY schedule.scheduledate DESC";
                            }

                            $result = $database->query($sqlmain);

                            if ($result->num_rows == 0) {
                                echo '<tr><td colspan="5" class="text-center text-gray-500 py-4">No sessions found.</td></tr>';
                            } else {
                                for ($x = 0; $x < $result->num_rows; $x++) {
                                    $row = $result->fetch_assoc();
                                    $scheduleid = $row["scheduleid"];
                                    $title = $row["title"];
                                    $docname = $row["docname"];
                                    $scheduledate = $row["scheduledate"];
                                    $scheduletime = $row["scheduletime"];
                                    $nop = $row["nop"];
                                    echo '<tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">' . substr($title, 0, 30) . '</td>
                                        <td class="px-4 py-2">' . substr($docname, 0, 20) . '</td>
                                        <td class="px-4 py-2 text-center">' . substr($scheduledate, 0, 10) . ' ' . substr($scheduletime, 0, 5) . '</td>
                                        <td class="px-4 py-2 text-center">' . $nop . '</td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="?action=view&id=' . $scheduleid . '" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">View</a>
                                                <a href="?action=drop&id=' . $scheduleid . '&name=' . $title . '" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">Remove</a>
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
        if ($action == 'add-session') {
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <center>
                        <h2 class="text-xl font-bold mb-4">Add New Session</h2>
                        <form action="add-session.php" method="POST" class="space-y-4">
                            <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" placeholder="Session Title" required>
                            <select name="docid" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" required>
                                <option value="" disabled selected hidden>Choose Doctor</option>';
                                $list11 = $database->query("SELECT * FROM doctor ORDER BY docname ASC;");
                                for ($y = 0; $y < $list11->num_rows; $y++) {
                                    $row00 = $list11->fetch_assoc();
                                    $sn = $row00["docname"];
                                    $id00 = $row00["docid"];
                                    echo "<option value='$id00'>$sn</option>";
                                }
                                echo '
                            </select>
                            <input type="number" name="nop" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" placeholder="Max Patients" required>
                            <input type="date" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" min="' . date('Y-m-d') . '" required>
                            <input type="time" name="time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" required>
                            <div class="flex justify-center space-x-4">
                                <input type="reset" value="Reset" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                                <input type="submit" value="Add Session" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                            </div>
                        </form>
                    </center>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>