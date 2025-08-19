<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animations from the original CSS */
        @keyframes transitionIn-Y-over {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        .animate-transitionIn-Y-over {
            animation: transitionIn-Y-over 0.5s;
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
    $userrow = $stmt->get_result();
    $userfetch = $userrow->fetch_assoc();

    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    // Fetch data for dashboard
    $today = date('Y-m-d');
    $patientrow = $database->query("SELECT * FROM patient");
    $doctorrow = $database->query("SELECT * FROM doctor");
    $appointmentrow = $database->query("SELECT * FROM appointment WHERE appodate>='$today'");
    $schedulerow = $database->query("SELECT * FROM schedule WHERE scheduledate='$today'");
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
                <h1 class="text-2xl font-bold">Home</h1>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-semibold"><?php echo $today; ?></p>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-2">Welcome!</h3>
                <h1 class="text-3xl font-bold mb-4"><?php echo $username; ?>.</h1>
                <p class="text-gray-600 mb-4">
                    Haven't any idea about doctors? No problem, let's jump to 
                    <a href="doctors.php" class="text-[#6A64F1] hover:underline"><b>"All Doctors"</b></a> section or 
                    <a href="schedule.php" class="text-[#6A64F1] hover:underline"><b>"Sessions"</b></a>.<br>
                    Track your past and future appointments history.<br>
                    Also, find out the expected arrival time of your doctor or medical consultant.
                </p>
                <h3 class="text-xl font-semibold mb-4">Channel a Doctor Here</h3>
                <form action="schedule.php" method="post" class="flex flex-col md:flex-row gap-4">
                    <input type="search" name="search" class="input-text flex-1" placeholder="Search Doctor and We will Find The Session Available" list="doctors">
                    <datalist id="doctors">
                        <?php
                        $list11 = $database->query("SELECT docname, docemail FROM doctor");
                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];
                            echo "<option value='$d'></option>";
                        }
                        ?>
                    </datalist>
                    <button type="submit" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Search</button>
                </form>
            </div>

            <!-- Status Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-xl font-semibold mb-4">Status</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="dashboard-item p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold"><?php echo $doctorrow->num_rows; ?></p>
                                <p class="text-gray-600">All Doctors</p>
                            </div>
                            <div class="dashboard-icon bg-[#9a95ec] p-3 rounded-full">
                                <img src="../img/icons/doctors-hover.svg" alt="Doctors" class="w-8 h-8">
                            </div>
                        </div>
                        <div class="dashboard-item p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold"><?php echo $patientrow->num_rows; ?></p>
                                <p class="text-gray-600">All Patients</p>
                            </div>
                            <div class="dashboard-icon bg-[#9a95ec] p-3 rounded-full">
                                <img src="../img/icons/patients-hover.svg" alt="Patients" class="w-8 h-8">
                            </div>
                        </div>
                        <div class="dashboard-item p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold"><?php echo $appointmentrow->num_rows; ?></p>
                                <p class="text-gray-600">New Bookings</p>
                            </div>
                            <div class="dashboard-icon bg-[#9a95ec] p-3 rounded-full">
                                <img src="../img/icons/book-hover.svg" alt="Bookings" class="w-8 h-8">
                            </div>
                        </div>
                        <div class="dashboard-item p-4 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold"><?php echo $schedulerow->num_rows; ?></p>
                                <p class="text-gray-600">Today's Sessions</p>
                            </div>
                            <div class="dashboard-icon bg-[#9a95ec] p-3 rounded-full">
                                <img src="../img/icons/session-iceblue.svg" alt="Sessions" class="w-8 h-8">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Bookings Section -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-xl font-semibold mb-4">Your Upcoming Bookings</p>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left p-2">Appointment Number</th>
                                    <th class="text-left p-2">Session Title</th>
                                    <th class="text-left p-2">Doctor</th>
                                    <th class="text-left p-2">Scheduled Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nextweek = date("Y-m-d", strtotime("+1 week"));
                                $sqlmain = "SELECT * FROM schedule INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN doctor ON schedule.docid=doctor.docid WHERE patient.pid=$userid AND schedule.scheduledate>='$today' ORDER BY schedule.scheduledate ASC";
                                $result = $database->query($sqlmain);

                                if ($result->num_rows == 0) {
                                    echo '<tr>
                                        <td colspan="4" class="text-center py-4">
                                            <img src="../img/notfound.svg" alt="Not Found" class="w-1/4 mx-auto">
                                            <p class="text-gray-600 mt-4">Nothing to show here!</p>
                                            <a href="schedule.php" class="inline-block mt-4 bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Channel a Doctor</a>
                                        </td>
                                    </tr>';
                                } else {
                                    for ($x = 0; $x < $result->num_rows; $x++) {
                                        $row = $result->fetch_assoc();
                                        $scheduleid = $row["scheduleid"];
                                        $title = $row["title"];
                                        $apponum = $row["apponum"];
                                        $docname = $row["docname"];
                                        $scheduledate = $row["scheduledate"];
                                        $scheduletime = $row["scheduletime"];

                                        echo '<tr>
                                            <td class="p-2">' . $apponum . '</td>
                                            <td class="p-2">' . substr($title, 0, 30) . '</td>
                                            <td class="p-2">' . substr($docname, 0, 20) . '</td>
                                            <td class="p-2">' . substr($scheduledate, 0, 10) . ' ' . substr($scheduletime, 0, 5) . '</td>
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
    </div>
</body>
</html>