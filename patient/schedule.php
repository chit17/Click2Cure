<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions</title>
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

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');

    $sqlmain = "SELECT * FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduledate>='$today' ORDER BY schedule.scheduledate ASC";
    $sqlpt1 = "";
    $insertkey = "";
    $q = '';
    $searchtype = "All";

    if ($_POST) {
        if (!empty($_POST["search"])) {
            $keyword = $_POST["search"];
            $sqlmain = "SELECT * FROM schedule INNER JOIN doctor ON schedule.docid=doctor.docid WHERE schedule.scheduledate>='$today' AND (doctor.docname='$keyword' OR doctor.docname LIKE '$keyword%' OR doctor.docname LIKE '%$keyword' OR doctor.docname LIKE '%$keyword%' OR schedule.title='$keyword' OR schedule.title LIKE '$keyword%' OR schedule.title LIKE '%$keyword' OR schedule.title LIKE '%$keyword%' OR schedule.scheduledate LIKE '$keyword%' OR schedule.scheduledate LIKE '%$keyword' OR schedule.scheduledate LIKE '%$keyword%' OR schedule.scheduledate='$keyword') ORDER BY schedule.scheduledate ASC";
            $insertkey = $keyword;
            $searchtype = "Search Result : ";
            $q = '"';
        }
    }

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
                <a href="schedule.php" class="bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Back</a>
                <form action="" method="post" class="flex flex-col md:flex-row gap-4">
                    <input type="search" name="search" class="input-text flex-1" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php echo $insertkey; ?>">
                    <datalist id="doctors">
                        <?php
                        $list11 = $database->query("SELECT DISTINCT * FROM doctor");
                        $list12 = $database->query("SELECT DISTINCT * FROM schedule GROUP BY title");

                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];
                            echo "<option value='$d'></option>";
                        }

                        for ($y = 0; $y < $list12->num_rows; $y++) {
                            $row00 = $list12->fetch_assoc();
                            $d = $row00["title"];
                            echo "<option value='$d'></option>";
                        }
                        ?>
                    </datalist>
                    <button type="submit" class="bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Search</button>
                </form>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-semibold"><?php echo $today; ?></p>
                </div>
            </div>

            <!-- Sessions Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-xl font-semibold mb-4"><?php echo $searchtype . " Sessions (" . $result->num_rows . ")"; ?></p>
                <p class="text-xl font-semibold mb-4"><?php echo $q . $insertkey . $q; ?></p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    if ($result->num_rows == 0) {
                        echo '<div class="col-span-3 text-center">
                            <img src="../img/notfound.svg" alt="Not Found" class="w-1/4 mx-auto">
                            <p class="text-gray-600 mt-4">We couldn\'t find anything related to your keywords!</p>
                            <a href="schedule.php" class="inline-block mt-4 bg-[#6A64F1] text-white px-6 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Show all Sessions</a>
                        </div>';
                    } else {
                        for ($x = 0; $x < $result->num_rows; $x++) {
                            $row = $result->fetch_assoc();
                            $scheduleid = $row["scheduleid"];
                            $title = $row["title"];
                            $docname = $row["docname"];
                            $scheduledate = $row["scheduledate"];
                            $scheduletime = $row["scheduletime"];

                            echo '<div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <div class="h1-search text-xl font-semibold">' . substr($title, 0, 21) . '</div>
                                <div class="h3-search text-gray-600">' . substr($docname, 0, 30) . '</div>
                                <div class="h4-search text-gray-600">' . $scheduledate . '<br>Starts: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)</div>
                                <a href="booking.php?id=' . $scheduleid . '" class="inline-block mt-4 bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300 w-full text-center">Book Now</a>
                            </div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>