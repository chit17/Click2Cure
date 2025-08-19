<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6A64F1',
                        primaryhover: '#5A54E1',
                        btnice: '#DAD7FF',
                        btnnicetext: '#4C46C6',
                    },
                    animation: {
                        'transitionIn-Y-bottom': 'transitionIn-Y-bottom 0.5s ease-in-out',
                    },
                    keyframes: {
                        'transitionIn-Y-bottom': {
                            'from': { opacity: '0', transform: 'translateY(30px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        },
                    }
                }
            }
        }
    </script>
    <style type="text/css">
        .scroll {
            scrollbar-width: thin;
            scrollbar-color: #888 #F1F1F1;
        }
        .scroll::-webkit-scrollbar {
            width: 5px;
        }
        .scroll::-webkit-scrollbar-track {
            background: #F1F1F1;
        }
        .scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 12px;
        }
        .scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="font-sans">
    <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }
    }else{
        header("location: ../login.php");
    }
    
    include("../connection.php");
    $userrow = $database->query("select * from doctor where docemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["docid"];
    $username=$userfetch["docname"];
    
    $list110 = $database->query("select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid where doctor.docid=$userid");
    ?>
    
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar Menu -->
        <div class="w-full md:w-1/5 lg:w-1/6 border-r border-gray-200 bg-white shadow-sm">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden">
                        <img src="../img/user.png" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-primary font-medium text-lg truncate"><?php echo substr($username,0,13) ?>..</p>
                        <p class="text-indigo-300 text-sm truncate"><?php echo substr($useremail,0,22) ?></p>
                    </div>
                </div>
                <a href="../logout.php" class="mt-6 block">
                    <button class="w-full py-2 px-4 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors shadow-md">
                        Log out
                    </button>
                </a>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="index.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    Dashboard
                </a>
                <a href="appointment.php" class="block py-2 px-4 bg-primary text-white rounded-md transition-colors">
                    My Appointments
                </a>
                <a href="schedule.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    My Sessions
                </a>
                <a href="patient.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    My Patients
                </a>
                <a href="settings.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    Settings
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div class="flex items-center space-x-4">
                    <a href="appointment.php" class="inline-block">
                        <button class="bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </button>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800">Appointment Manager</h1>
                </div>
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="font-medium">
                        <?php 
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date('Y-m-d');
                        echo $today;
                        ?>
                    </p>
                    <button class="p-2 bg-gray-100 rounded-md">
                        <img src="../img/calendar.svg" alt="Calendar" class="w-5 h-5">
                    </button>
                </div>
            </div>
            
            <!-- Appointments Count -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800">My Appointments (<?php echo $list110->num_rows; ?>)</h2>
            </div>
            
            <!-- Filter Form -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form action="" method="post" class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-auto">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date:</label>
                        <input type="date" name="sheduledate" id="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div class="w-full md:w-auto mt-6 md:mt-0">
                        <input type="submit" name="filter" value="Filter" class="w-full md:w-auto px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors shadow-md cursor-pointer">
                    </div>
                </form>
            </div>
            
            <!-- Appointments Table -->
            <?php
            $sqlmain = "select appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid where doctor.docid=$userid";

            if($_POST){
                if(!empty($_POST["sheduledate"])){
                    $sheduledate=$_POST["sheduledate"];
                    $sqlmain.=" and schedule.scheduledate='$sheduledate' ";
                }
            }
            ?>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden animation transitionIn-Y-bottom">
                <div class="overflow-x-auto">
                    <div class="h-96 overflow-y-auto scroll">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $result= $database->query($sqlmain);

                                if($result->num_rows==0){
                                    echo '<tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <img src="../img/notfound.svg" alt="Not found" class="w-1/4 mb-4">
                                                <p class="text-lg text-gray-700 mb-4">We couldn\'t find anything related to your keywords!</p>
                                                <a href="appointment.php" class="inline-block bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors">
                                                    Show all Appointments
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                                } else {
                                    for ($x=0; $x<$result->num_rows;$x++){
                                        $row=$result->fetch_assoc();
                                        $appoid=$row["appoid"];
                                        $scheduleid=$row["scheduleid"];
                                        $title=$row["title"];
                                        $docname=$row["docname"];
                                        $scheduledate=$row["scheduledate"];
                                        $scheduletime=$row["scheduletime"];
                                        $pname=$row["pname"];
                                        $apponum=$row["apponum"];
                                        $appodate=$row["appodate"];
                                        echo '<tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">'.substr($pname,0,25).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-xl font-semibold text-btnnicetext">'.$apponum.'</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">'.substr($title,0,15).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500">'.substr($scheduledate,0,10).' @'.substr($scheduletime,0,5).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-500">'.$appodate.'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="?action=drop&id='.$appoid.'&name='.$pname.'&session='.$title.'&apponum='.$apponum.'" class="inline-block px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                                        Cancel
                                                    </a>
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
    </div>

    <?php
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        
        if($action=='drop'){
            $nameget=$_GET["name"];
            $session=$_GET["session"];
            $apponum=$_GET["apponum"];
            echo '
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full animation transitionIn-Y-bottom">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Are you sure?</h3>
                        <a href="appointment.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                    </div>
                    <div class="mb-4">
                        <p>You want to delete this record</p>
                        <div class="mt-2">
                            <p><b>Patient Name:</b> '.substr($nameget,0,40).'</p>
                            <p><b>Appointment number:</b> '.substr($apponum,0,40).'</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <a href="delete-appointment.php?id='.$id.'" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors">Yes</a>
                        <a href="appointment.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">No</a>
                    </div>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>