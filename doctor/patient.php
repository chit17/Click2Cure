<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients</title>
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

    $selecttype = "My";
    $current = "My patients Only";
    
    if($_POST){
        if(isset($_POST["search"])){
            $keyword=$_POST["search12"];
            $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
            $selecttype="Search";
        }
        
        if(isset($_POST["filter"])){
            if($_POST["showonly"]=='all'){
                $sqlmain= "select * from patient";
                $selecttype="All";
                $current="All patients";
            }else{
                $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                $selecttype="My";
                $current="My patients Only";
            }
        }
    }else{
        $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
        $selecttype="My";
    }
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
                <a href="appointment.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    My Appointments
                </a>
                <a href="schedule.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    My Sessions
                </a>
                <a href="patient.php" class="block py-2 px-4 bg-primary text-white rounded-md transition-colors">
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
                    <a href="patient.php" class="inline-block">
                        <button class="bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </button>
                    </a>
                    <form action="" method="post" class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-2">
                        <input type="search" name="search12" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary w-full md:w-64" placeholder="Search Patient name or Email" list="patient">
                        
                        <?php
                        echo '<datalist id="patient">';
                        $list11 = $database->query($sqlmain);
                        for ($y=0;$y<$list11->num_rows;$y++){
                            $row00=$list11->fetch_assoc();
                            $d=$row00["pname"];
                            $c=$row00["pemail"];
                            echo "<option value='$d'><br/>";
                            echo "<option value='$c'><br/>";
                        };
                        echo '</datalist>';
                        ?>
                        
                        <input type="submit" value="Search" name="search" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors shadow-md cursor-pointer">
                    </form>
                </div>
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <p class="text-sm text-gray-500">Today's Date</p>
                    <p class="font-medium">
                        <?php 
                        date_default_timezone_set('Asia/Kolkata');
                        $date = date('Y-m-d');
                        echo $date;
                        ?>
                    </p>
                    <button class="p-2 bg-gray-100 rounded-md">
                        <img src="../img/calendar.svg" alt="Calendar" class="w-5 h-5">
                    </button>
                </div>
            </div>
            
            <!-- Patients Count -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800"><?php echo $selecttype." Patients (".$list11->num_rows.")"; ?></h2>
            </div>
            
            <!-- Filter Form -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form action="" method="post" class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Show Details About:</label>
                        <select name="showonly" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="" disabled selected hidden><?php echo $current ?></option>
                            <option value="my">My Patients Only</option>
                            <option value="all">All Patients</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto mt-6 md:mt-0">
                        <input type="submit" name="filter" value="Filter" class="w-full md:w-auto px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors shadow-md cursor-pointer">
                    </div>
                </form>
            </div>
            
            <!-- Patients Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden animation transitionIn-Y-bottom">
                <div class="overflow-x-auto">
                    <div class="h-96 overflow-y-auto scroll">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telephone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $result = $database->query($sqlmain);
                                
                                if($result->num_rows==0){
                                    echo '<tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <img src="../img/notfound.svg" alt="Not found" class="w-1/4 mb-4">
                                                <p class="text-lg text-gray-700 mb-4">We couldn\'t find anything related to your keywords!</p>
                                                <a href="patient.php" class="inline-block bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors">
                                                    Show all Patients
                                                </a>
                                            </div>
                                        </td>
                                    </tr>';
                                } else {
                                    for ($x=0; $x<$result->num_rows;$x++){
                                        $row=$result->fetch_assoc();
                                        $pid=$row["pid"];
                                        $name=$row["pname"];
                                        $email=$row["pemail"];
                                        $nic=$row["pnic"];
                                        $dob=$row["pdob"];
                                        $tel=$row["ptel"];
                                        
                                        echo '<tr>
                                            <td class="px-6 py-4 whitespace-nowrap">'.substr($name,0,35).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">'.substr($nic,0,12).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">'.substr($tel,0,10).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">'.substr($email,0,20).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">'.substr($dob,0,10).'</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex justify-center">
                                                    <a href="?action=view&id='.$pid.'" class="inline-block px-4 py-2 bg-btnice text-btnnicetext rounded-md hover:bg-primary hover:text-white transition-colors">
                                                        View
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
        if($action=='view'){
            $sqlmain= "select * from patient where pid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            
            $name=$row["pname"];
            $email=$row["pemail"];
            $nic=$row["pnic"];
            $dob=$row["pdob"];
            $tele=$row["ptel"];
            $address=$row["paddress"];
            
            echo '
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full animation transitionIn-Y-bottom">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">View Patient Details</h3>
                        <a href="patient.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient ID:</label>
                            <p class="mt-1 font-medium">P-'.$id.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name:</label>
                            <p class="mt-1">'.$name.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email:</label>
                            <p class="mt-1">'.$email.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIC:</label>
                            <p class="mt-1">'.$nic.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telephone:</label>
                            <p class="mt-1">'.$tele.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address:</label>
                            <p class="mt-1">'.$address.'</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth:</label>
                            <p class="mt-1">'.$dob.'</p>
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <a href="patient.php" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors">
                                OK
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
    ?>
</body>
</html>