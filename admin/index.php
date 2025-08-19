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
$doctorrow = $database->query("SELECT * FROM doctor;");
$patientrow = $database->query("SELECT * FROM patient;");
$appointmentrow = $database->query("SELECT * FROM appointment WHERE appodate >= '$today';");
$schedulerow = $database->query("SELECT * FROM schedule WHERE scheduledate = '$today';");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes transitionIn-Y-over {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes transitionIn-Y-bottom {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dashbord-tables { animation: transitionIn-Y-over 0.5s; }
        .filter-container { animation: transitionIn-Y-bottom 0.5s; }
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
                <a href="patient.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition duration-300">Patients</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="w-4/5 p-6">
            <div class="flex justify-between items-center mb-6">
                <form action="doctors.php" method="post" class="flex-grow max-w-2xl">
                    <input type="search" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500" placeholder="Search Doctor name or Email">
                    <input type="submit" value="Search" class="mt-2 bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">
                </form>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="text-lg font-medium"><?php echo $today; ?></p>
                </div>
            </div>

            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $doctorrow->num_rows; ?></p>
                        <p class="text-gray-600">Doctors</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                        <img src="../img/icons/doctors-hover.svg" alt="Doctors" class="w-6 h-6">
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $patientrow->num_rows; ?></p>
                        <p class="text-gray-600">Patients</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                        <img src="../img/icons/patients-hover.svg" alt="Patients" class="w-6 h-6">
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $appointmentrow->num_rows; ?></p>
                        <p class="text-gray-600">New Booking</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                        <img src="../img/icons/book-hover.svg" alt="Bookings" class="w-6 h-6">
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-center">
                    <div>
                        <p class="text-2xl font-bold text-purple-600"><?php echo $schedulerow->num_rows; ?></p>
                        <p class="text-gray-600">Today Sessions</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                        <img src="../img/icons/session-iceblue.svg" alt="Sessions" class="w-6 h-6">
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments and Sessions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-purple-600 mb-4">Upcoming Appointments</h2>
                    <div class="overflow-auto h-48">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-sm text-gray-500">Appointment Number</th>
                                    <th class="text-left text-sm text-gray-500">Patient Name</th>
                                    <th class="text-left text-sm text-gray-500">Doctor</th>
                                    <th class="text-left text-sm text-gray-500">Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nextweek = date("Y-m-d", strtotime("+1 week"));
                                $sqlmain = "SELECT appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate FROM schedule INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduledate >= '$today' AND schedule.scheduledate <= '$nextweek' ORDER BY schedule.scheduledate DESC";
                                $result = $database->query($sqlmain);
                                if ($result->num_rows == 0) {
                                    echo '<tr><td colspan="4" class="text-center text-gray-500 py-4">No appointments found.</td></tr>';
                                } else {
                                    for ($x = 0; $x < $result->num_rows; $x++) {
                                        $row = $result->fetch_assoc();
                                        echo '<tr class="border-b border-gray-200">
                                            <td class="py-2 text-purple-600">' . $row["apponum"] . '</td>
                                            <td class="py-2">' . substr($row["pname"], 0, 25) . '</td>
                                            <td class="py-2">' . substr($row["docname"], 0, 25) . '</td>
                                            <td class="py-2">' . substr($row["title"], 0, 15) . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="appointment.php" class="mt-4 block text-center bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">Show all Appointments</a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-purple-600 mb-4">Upcoming Sessions</h2>
                    <div class="overflow-auto h-48">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left text-sm text-gray-500">Session Title</th>
                                    <th class="text-left text-sm text-gray-500">Doctor</th>
                                    <th class="text-left text-sm text-gray-500">Scheduled Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlmain = "SELECT schedule.scheduleid, schedule.title, doctor.docname, schedule.scheduledate, schedule.scheduletime, schedule.nop FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduledate >= '$today' AND schedule.scheduledate <= '$nextweek' ORDER BY schedule.scheduledate DESC";
                                $result = $database->query($sqlmain);
                                if ($result->num_rows == 0) {
                                    echo '<tr><td colspan="3" class="text-center text-gray-500 py-4">No sessions found.</td></tr>';
                                } else {
                                    for ($x = 0; $x < $result->num_rows; $x++) {
                                        $row = $result->fetch_assoc();
                                        echo '<tr class="border-b border-gray-200">
                                            <td class="py-2">' . substr($row["title"], 0, 30) . '</td>
                                            <td class="py-2">' . substr($row["docname"], 0, 20) . '</td>
                                            <td class="py-2 text-center">' . substr($row["scheduledate"], 0, 10) . ' ' . substr($row["scheduletime"], 0, 5) . '</td>
                                        </tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="schedule.php" class="mt-4 block text-center bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300">Show all Sessions</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>