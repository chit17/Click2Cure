<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .filter-container {
            animation: transitionIn-X 0.5s;
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

    include("../connection.php");
    $sqlmain = "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userfetch = $result->fetch_assoc();
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
                    <a href="settings.php" class="flex items-center p-2 text-gray-700 bg-[#6A64F1] text-white rounded-lg">
                        <span class="ml-2">Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="settings.php" class="bg-[#6A64F1] text-white py-2 px-4 rounded-lg hover:bg-[#5a55e0] transition duration-300">Back</a>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-semibold"><?php echo date('Y-m-d'); ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <center>
                    <table class="w-full">
                        <tr>
                            <td colspan="4">
                                <p class="text-2xl font-semibold mb-4">Settings</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-1/4">
                                <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="block">
                                    <div class="bg-gray-50 p-6 rounded-lg hover:bg-[#6A64F1] hover:text-white transition duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-[#9a95ec] p-4 rounded-lg">
                                                <img src="../img/icons/doctors-hover.svg" alt="Edit" class="w-8 h-8">
                                            </div>
                                            <div>
                                                <p class="text-xl font-semibold">Account Settings</p>
                                                <p class="text-sm text-gray-500">Edit your Account Details & Change Password</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-1/4">
                                <a href="?action=view&id=<?php echo $userid ?>" class="block">
                                    <div class="bg-gray-50 p-6 rounded-lg hover:bg-[#6A64F1] hover:text-white transition duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-[#9a95ec] p-4 rounded-lg">
                                                <img src="../img/icons/view-iceblue.svg" alt="View" class="w-8 h-8">
                                            </div>
                                            <div>
                                                <p class="text-xl font-semibold">View Account Details</p>
                                                <p class="text-sm text-gray-500">View Personal information About Your Account</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-1/4">
                                <a href="?action=drop&id=<?php echo $userid . '&name=' . $username ?>" class="block">
                                    <div class="bg-gray-50 p-6 rounded-lg hover:bg-[#6A64F1] hover:text-white transition duration-300">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-[#9a95ec] p-4 rounded-lg">
                                                <img src="../img/icons/patients-hover.svg" alt="Delete" class="w-8 h-8">
                                            </div>
                                            <div>
                                                <p class="text-xl font-semibold text-red-500">Delete Account</p>
                                                <p class="text-sm text-gray-500">Will Permanently Remove your Account</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    </table>
                </center>
            </div>
        </div>
    </div>

    <?php
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'drop') {
            $nameget = $_GET["name"];
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-2xl font-semibold mb-4">Are you sure?</h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content mb-4">
                            You want to delete Your Account<br>(' . substr($nameget, 0, 40) . ').
                        </div>
                        <div class="flex justify-center space-x-4">
                            <a href="delete-account.php?id=' . $id . '" class="bg-[#6A64F1] text-white py-2 px-4 rounded-lg hover:bg-[#5a55e0] transition duration-300">Yes</a>
                            <a href="settings.php" class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">No</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        } elseif ($action == 'view') {
            $sqlmain = "select * from patient where pid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $name = $row["pname"];
            $email = $row["pemail"];
            $address = $row["paddress"];
            $dob = $row["pdob"];
            $nic = $row['pnic'];
            $tele = $row['ptel'];
            echo '
            <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <center>
                        <h2 class="text-2xl font-semibold mb-4">View Details</h2>
                        <a class="close" href="settings.php">&times;</a>
                        <div class="content">
                            <table class="w-full">
                                <tr>
                                    <td class="py-2">Name:</td>
                                    <td class="py-2">' . $name . '</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Email:</td>
                                    <td class="py-2">' . $email . '</td>
                                </tr>
                                <tr>
                                    <td class="py-2">NIC:</td>
                                    <td class="py-2">' . $nic . '</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Telephone:</td>
                                    <td class="py-2">' . $tele . '</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Address:</td>
                                    <td class="py-2">' . $address . '</td>
                                </tr>
                                <tr>
                                    <td class="py-2">Date of Birth:</td>
                                    <td class="py-2">' . $dob . '</td>
                                </tr>
                            </table>
                        </div>
                        <div class="mt-6">
                            <a href="settings.php" class="bg-[#6A64F1] text-white py-2 px-4 rounded-lg hover:bg-[#5a55e0] transition duration-300">OK</a>
                        </div>
                    </center>
                </div>
            </div>
            ';
        } elseif ($action == 'edit') {
            $sqlmain = "select * from patient where pid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $name = $row["pname"];
            $email = $row["pemail"];
            $address = $row["paddress"];
            $nic = $row['pnic'];
            $tele = $row['ptel'];

            $error_1 = $_GET["error"];
            $errorlist = array(
                '1' => '<label class="text-red-500 text-center">Already have an account for this Email address.</label>',
                '2' => '<label class="text-red-500 text-center">Password Conformation Error! Reconform Password</label>',
                '3' => '<label class="text-red-500 text-center"></label>',
                '4' => "",
                '0' => '',
            );

            if ($error_1 != '4') {
                echo '
                <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <center>
                            <h2 class="text-2xl font-semibold mb-4">Edit User Account Details</h2>
                            <a class="close" href="settings.php">&times;</a>
                            <div class="content">
                                <form action="edit-user.php" method="POST" class="space-y-4">
                                    <input type="hidden" value="' . $id . '" name="id00">
                                    <input type="hidden" name="oldemail" value="' . $email . '">
                                    <div>
                                        <label class="block text-gray-700">Email:</label>
                                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg" value="' . $email . '" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">Name:</label>
                                        <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg" value="' . $name . '" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">NIC:</label>
                                        <input type="text" name="nic" class="w-full px-4 py-2 border rounded-lg" value="' . $nic . '" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">Telephone:</label>
                                        <input type="tel" name="Tele" class="w-full px-4 py-2 border rounded-lg" value="' . $tele . '" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">Address:</label>
                                        <input type="text" name="address" class="w-full px-4 py-2 border rounded-lg" value="' . $address . '" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">Password:</label>
                                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg" placeholder="Define a Password" required>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700">Confirm Password:</label>
                                        <input type="password" name="cpassword" class="w-full px-4 py-2 border rounded-lg" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="flex justify-center space-x-4">
                                        <input type="reset" value="Reset" class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">
                                        <input type="submit" value="Save" class="bg-[#6A64F1] text-white py-2 px-4 rounded-lg hover:bg-[#5a55e0] transition duration-300">
                                    </div>
                                </form>
                            </div>
                        </center>
                    </div>
                </div>
                ';
            } else {
                echo '
                <div id="popup1" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <center>
                            <h2 class="text-2xl font-semibold mb-4">Edit Successfully!</h2>
                            <a class="close" href="settings.php">&times;</a>
                            <div class="content mb-4">
                                If You change your email also Please logout and login again with your new email
                            </div>
                            <div class="flex justify-center space-x-4">
                                <a href="settings.php" class="bg-[#6A64F1] text-white py-2 px-4 rounded-lg hover:bg-[#5a55e0] transition duration-300">OK</a>
                                <a href="../logout.php" class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">Log out</a>
                            </div>
                        </center>
                    </div>
                </div>
                ';
            }
        }
    }
    ?>
</body>
</html>