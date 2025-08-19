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
$list11 = $database->query("SELECT docname, docemail FROM doctor;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
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
                <a href="doctors.php" class="block py-2 px-4 text-purple-600 bg-purple-50">Doctors</a>
                <a href="schedule.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Schedule</a>
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Appointment</a>
                <a href="patient.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Patients</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="doctors.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                    <span>Back</span>
                </a>
                <form action="" method="post" class="flex-grow max-w-2xl ml-4">
                    <input type="search" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" placeholder="Search Doctor name or Email" list="doctors">
                    <datalist id="doctors">
                        <?php
                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];
                            $c = $row00["docemail"];
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

            <!-- Add New Doctor Button -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-xl font-bold text-gray-800">Add New Doctor</p>
                <a href="add-doctor.php" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center">
                    <span>Add New</span>
                </a>
            </div>

            <!-- All Doctors Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-xl font-bold text-gray-800 mb-4">All Doctors (<?php echo $list11->num_rows; ?>)</p>
                <div class="overflow-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-purple-50">
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Doctor Name</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Email</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Specialties</th>
                                <th class="px-4 py-2 text-left text-sm text-purple-600">Events</th>
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
                                echo '<tr><td colspan="4" class="text-center text-gray-500 py-4">No doctors found.</td></tr>';
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
                                    echo '<tr class="border-b border-gray-200">
                                        <td class="px-4 py-2">' . substr($name, 0, 30) . '</td>
                                        <td class="px-4 py-2">' . substr($email, 0, 20) . '</td>
                                        <td class="px-4 py-2">' . substr($spcil_name, 0, 20) . '</td>
                                        <td class="px-4 py-2">
                                            <div class="flex space-x-2">
                                                <a href="?action=edit&id=' . $docid . '&error=0" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">Edit</a>
                                                <a href="?action=view&id=' . $docid . '" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition duration-300">View</a>
                                                <a href="?action=drop&id=' . $docid . '&name=' . $name . '" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-300">Remove</a>
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
    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        $id = $_GET["id"] ?? null;

        if ($action === 'drop' && $id !== null) {
            $nameget = $_GET["name"] ?? "Unknown";
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6">
                    <center>
                        <h2 class="text-xl font-bold">Are you sure?</h2>
                        <p class="text-gray-600">You want to delete this record<br>(' . htmlspecialchars(substr($nameget, 0, 40)) . ').</p>
                        <a href="delete-doctor.php?id=' . $id . '" class="bg-purple-600 text-white px-6 py-2 rounded">Yes</a>
                        <a href="doctors.php" class="bg-gray-600 text-white px-6 py-2 rounded">No</a>
                    </center>
                </div>
            </div>';
        } elseif ($action === 'view' && $id !== null) {
            $sqlmain = "SELECT * FROM doctor WHERE docid='$id'";
            $result = $database->query($sqlmain);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row["docname"];
                $email = $row["docemail"];
                $nic = $row['docnic'] ?? "N/A";
                $tele = $row['doctel'] ?? "N/A";
                $spe = $row["specialties"];
                $spcil_res = $database->query("SELECT sname FROM specialties WHERE id='$spe'");
                $spcil_array = $spcil_res->fetch_assoc();
                $spcil_name = $spcil_array["sname"] ?? "N/A";

                echo '
                <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg p-6">
                        <center>
                            <h2 class="text-xl font-bold">View Details</h2>
                            <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
                            <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                            <p><strong>NIC:</strong> ' . htmlspecialchars($nic) . '</p>
                            <p><strong>Telephone:</strong> ' . htmlspecialchars($tele) . '</p>
                            <p><strong>Specialties:</strong> ' . htmlspecialchars($spcil_name) . '</p>
                            <a href="doctors.php" class="bg-purple-600 text-white px-6 py-2 rounded">OK</a>
                        </center>
                    </div>
                </div>';
            }
        }
    }
    ?>
</body>
</html>