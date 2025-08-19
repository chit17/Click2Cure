<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                        'transitionIn-X': 'transitionIn-X 0.5s ease-in-out',
                        'transitionIn-Y-over': 'transitionIn-Y-over 0.5s ease-in-out',
                        'transitionIn-Y-bottom': 'transitionIn-Y-bottom 0.5s ease-in-out',
                    },
                    keyframes: {
                        'transitionIn-X': {
                            'from': { opacity: '0', transform: 'translateX(-10px)' },
                            'to': { opacity: '1', transform: 'translateX(0)' },
                        },
                        'transitionIn-Y-over': {
                            'from': { opacity: '0', transform: 'translateY(-10px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        },
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
    ?>
    
    <div class="flex flex-col md:flex-row h-screen">
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
                <a href="index.php" class="block py-2 px-4 bg-primary text-white rounded-md transition-colors">
                    Dashboard
                </a>
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
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
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="font-medium">
                        <?php 
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date('Y-m-d');
                        echo $today;

                        $patientrow = $database->query("select * from patient;");
                        $doctorrow = $database->query("select * from doctor;");
                        $appointmentrow = $database->query("select * from appointment where appodate>='$today';");
                        $schedulerow = $database->query("select * from schedule where scheduledate='$today';");
                        ?>
                    </p>
                    <button class="p-2 bg-gray-100 rounded-md">
                        <img src="../img/calendar.svg" alt="Calendar" class="w-5 h-5">
                    </button>
                </div>
            </div>
            
            <!-- Welcome Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 animation transitionIn-Y-over">
                <h3 class="text-lg font-medium text-gray-700">Welcome!</h3>
                <h1 class="text-3xl font-bold text-primary mt-2"><?php echo $username ?>.</h1>
                <p class="text-gray-600 mt-2 mb-4">
                    Thanks for joining with us. We are always trying to get you a complete service<br>
                    You can view your daily schedule, Reach Patients Appointment at home!
                </p>
                <a href="appointment.php" class="inline-block">
                    <button class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primaryhover transition-colors shadow-md">
                        View My Appointments
                    </button>
                </a>
            </div>
            
            <!-- Stats and Upcoming Sessions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Stats Section -->
                <div class="animation transitionIn-Y-bottom">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Status</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- All Doctors -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-primary"><?php echo $doctorrow->num_rows ?></p>
                                <p class="text-gray-600">All Doctors</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/doctors-hover.svg" alt="Doctors" class="w-6 h-6">
                            </div>
                        </div>
                        
                        <!-- All Patients -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-primary"><?php echo $patientrow->num_rows ?></p>
                                <p class="text-gray-600">All Patients</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/patients-hover.svg" alt="Patients" class="w-6 h-6">
                            </div>
                        </div>
                        
                        <!-- New Booking -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-primary"><?php echo $appointmentrow->num_rows ?></p>
                                <p class="text-gray-600">New Booking</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/book-hover.svg" alt="Bookings" class="w-6 h-6">
                            </div>
                        </div>
                        
                        <!-- Today Sessions -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="text-3xl font-bold text-primary"><?php echo $schedulerow->num_rows ?></p>
                                <p class="text-gray-600 text-sm">Today Sessions</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/session-iceblue.svg" alt="Sessions" class="w-6 h-6">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Sessions -->
                <div class="animation transitionIn-Y-bottom">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 px-4">Your Up Coming Sessions until Next week</h2>
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="h-64 overflow-y-auto scroll">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    $nextweek=date("Y-m-d",strtotime("+1 week"));
                                    $sqlmain= "select schedule.scheduleid,schedule.title,doctor.docname,schedule.scheduledate,schedule.scheduletime,schedule.nop from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and schedule.scheduledate<='$nextweek' order by schedule.scheduledate desc"; 
                                    $result= $database->query($sqlmain);
                
                                    if($result->num_rows==0){
                                        echo '<tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <img src="../img/notfound.svg" alt="Not found" class="w-1/4 mb-4">
                                                    <p class="text-lg text-gray-700 mb-4">We couldn\'t find anything related to your keywords!</p>
                                                    <a href="schedule.php" class="inline-block bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors">
                                                        Show all Sessions
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>';
                                    } else {
                                        for ($x=0; $x<$result->num_rows;$x++){
                                            $row=$result->fetch_assoc();
                                            $scheduleid=$row["scheduleid"];
                                            $title=$row["title"];
                                            $docname=$row["docname"];
                                            $scheduledate=$row["scheduledate"];
                                            $scheduletime=$row["scheduletime"];
                                            $nop=$row["nop"];
                                            echo '<tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">'.substr($title,0,30).'</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">'.substr($scheduledate,0,10).'</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">'.substr($scheduletime,0,5).'</td>
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
    </div>
</body>
</html>