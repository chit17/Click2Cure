<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }
    }else{
        header("location: ../login.php");
    }

    include("../connection.php");

    $sqlmain= "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();
    $result = $stmt->get_result();
    $userrow = $result->fetch_assoc();
    $userid= $userrow["pid"];
    $username=$userrow["pname"];

    date_default_timezone_set('Asia/Kolkata');
    $today = date('Y-m-d');
    ?>
    <div class="container mx-auto flex">
        <!-- Sidebar -->
        <div class="w-1/4 bg-white shadow-lg">
            <div class="p-6">
                <div class="flex items-center space-x-4">
                    <img src="../img/user.png" alt="User" class="w-16 h-16 rounded-full">
                    <div>
                        <p class="text-lg font-medium text-gray-800"><?php echo substr($username,0,13)  ?>..</p>
                        <p class="text-sm text-gray-500"><?php echo substr($useremail,0,22)  ?></p>
                    </div>
                </div>
                <a href="../logout.php" class="mt-6 block w-full bg-purple-600 text-white py-2 px-4 rounded-lg text-center hover:bg-purple-700 transition duration-300">Log out</a>
            </div>
            <div class="mt-6">
                <a href="index.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600">Home</a>
                <a href="doctors.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600">All Doctors</a>
                <a href="schedule.php" class="block py-2 px-4 bg-purple-50 text-purple-600">Scheduled Sessions</a>
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600">My Bookings</a>
                <a href="settings.php" class="block py-2 px-4 text-gray-700 hover:bg-purple-50 hover:text-purple-600">Settings</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <div class="flex justify-between items-center mb-6">
                <a href="schedule.php" class="bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">Back</a>
                <form action="schedule.php" method="post" class="flex space-x-4">
                    <input type="search" name="search" class="w-96 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors">
                    <datalist id="doctors">
                        <?php
                        $list11 = $database->query("select DISTINCT * from  doctor;");
                        $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;");

                        for ($y=0;$y<$list11->num_rows;$y++){
                            $row00=$list11->fetch_assoc();
                            $d=$row00["docname"];
                            echo "<option value='$d'><br/>";
                        };

                        for ($y=0;$y<$list12->num_rows;$y++){
                            $row00=$list12->fetch_assoc();
                            $d=$row00["title"];
                            echo "<option value='$d'><br/>";
                        };
                        ?>
                    </datalist>
                    <input type="Submit" value="Search" class="bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">
                </form>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Today's Date</p>
                    <p class="text-lg font-medium"><?php echo $today; ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <?php
                if(($_GET)){
                    if(isset($_GET["id"])){
                        $id=$_GET["id"];
                        $sqlmain= "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduleid=? order by schedule.scheduledate desc";
                        $stmt = $database->prepare($sqlmain);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row=$result->fetch_assoc();
                        $scheduleid=$row["scheduleid"];
                        $title=$row["title"];
                        $docname=$row["docname"];
                        $docemail=$row["docemail"];
                        $scheduledate=$row["scheduledate"];
                        $scheduletime=$row["scheduletime"];
                        $sql2="select * from appointment where scheduleid=$id";
                        $result12= $database->query($sql2);
                        $apponum=($result12->num_rows)+1;

                        echo '
                        <form action="booking-complete.php" method="post">
                            <input type="hidden" name="scheduleid" value="'.$scheduleid.'">
                            <input type="hidden" name="apponum" value="'.$apponum.'">
                            <input type="hidden" name="date" value="'.$today.'">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h2 class="text-2xl font-semibold mb-4">Session Details</h2>
                                    <p class="text-lg"><b>Doctor name:</b> '.$docname.'</p>
                                    <p class="text-lg"><b>Doctor Email:</b> '.$docemail.'</p>
                                    <p class="text-lg"><b>Session Title:</b> '.$title.'</p>
                                    <p class="text-lg"><b>Session Scheduled Date:</b> '.$scheduledate.'</p>
                                    <p class="text-lg"><b>Session Starts:</b> '.$scheduletime.'</p>
                                    <p class="text-lg"><b>Channeling fee:</b> LKR.2 000.00</p>
                                </div>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h2 class="text-2xl font-semibold mb-4 text-center">Your Appointment Number</h2>
                                    <div class="text-6xl font-bold text-center text-purple-600 bg-purple-100 py-8 rounded-lg">'.$apponum.'</div>
                                </div>
                            </div>
                            <div class="mt-6">
                                <input type="Submit" class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition duration-300" value="Book now" name="booknow">
                            </div>
                        </form>
                        ';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>