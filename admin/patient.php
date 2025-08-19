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
$date = date('Y-m-d');

// Fetch data from the database
$list11 = $database->query("SELECT pname, pemail FROM patient;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
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
                <a href="schedule.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Schedule</a>
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Appointment</a>
                <a href="patient.php" class="block py-2 px-4 text-purple-600 bg-purple-50">Patients</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="patient.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                    <span>Back</span>
                </a>
                <form action="" method="post" class="flex-grow max-w-2xl ml-4">
                    <input type="search" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" placeholder="Search Patient name or Email" list="patient">
                    <datalist id="patient">
                        <?php
                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["pname"];
                            $c = $row00["pemail"];
                            echo "<option value='$d'></option>";
                            echo "<option value='$c'></option>";
                        }
                        ?>
                    </datalist>
                    <input type="submit" value="Search" class="mt-2 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                </form>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="text-lg font-medium"><?php echo $date; ?></p>
                </div>
            </div>

            <!-- All Patients Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-xl font-bold text-gray-800 mb-4">All Patients (<?php echo $list11->num_rows; ?>)</p>
                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-purple-50">
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Name</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">NIC</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Telephone</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Email</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Date of Birth</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Events</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($_POST) {
                                $keyword = $_POST["search"];
                                $sqlmain = "SELECT * FROM patient WHERE pemail='$keyword' OR pname='$keyword' OR pname LIKE '$keyword%' OR pname LIKE '%$keyword' OR pname LIKE '%$keyword%'";
                            } else {
                                $sqlmain = "SELECT * FROM patient ORDER BY pid DESC";
                            }

                            $result = $database->query($sqlmain);

                            if ($result->num_rows == 0) {
                                echo '<tr><td colspan="6" class="text-center text-gray-500 py-4">No patients found.</td></tr>';
                            } else {
                                for ($x = 0; $x < $result->num_rows; $x++) {
                                    $row = $result->fetch_assoc();
                                    $pid = $row["pid"];
                                    $name = $row["pname"];
                                    $email = $row["pemail"];
                                    $nic = $row["pnic"];
                                    $dob = $row["pdob"];
                                    $tel = $row["ptel"];
                                    echo '<tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">' . substr($name, 0, 35) . '</td>
                                        <td class="px-4 py-2">' . substr($nic, 0, 12) . '</td>
                                        <td class="px-4 py-2">' . substr($tel, 0, 10) . '</td>
                                        <td class="px-4 py-2">' . substr($email, 0, 20) . '</td>
                                        <td class="px-4 py-2">' . substr($dob, 0, 10) . '</td>
                                        <td class="px-4 py-2">
                                            <div class="flex justify-center">
                                                <a href="?action=view&id=' . $pid . '" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">View</a>
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

    <!-- Popup for Viewing Patient Details -->
    <?php
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'view') {
            $sqlmain = "SELECT * FROM patient WHERE pid='$id'";
            $result = $database->query($sqlmain);
            $row = $result->fetch_assoc();
            $name = $row["pname"];
            $email = $row["pemail"];
            $nic = $row["pnic"];
            $dob = $row["pdob"];
            $tele = $row["ptel"];
            $address = $row["paddress"];
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-1/3">
                    <center>
                        <h2 class="text-xl font-bold mb-4">View Patient Details</h2>
                        <div class="text-left">
                            <p><strong>Patient ID:</strong> P-' . $id . '</p>
                            <p><strong>Name:</strong> ' . $name . '</p>
                            <p><strong>Email:</strong> ' . $email . '</p>
                            <p><strong>NIC:</strong> ' . $nic . '</p>
                            <p><strong>Telephone:</strong> ' . $tele . '</p>
                            <p><strong>Address:</strong> ' . $address . '</p>
                            <p><strong>Date of Birth:</strong> ' . $dob . '</p>
                        </div>
                        <a href="patient.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 mt-4">OK</a>
                    </center>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>