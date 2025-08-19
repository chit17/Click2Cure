<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
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
                        'transitionIn-Y-over': 'transitionIn-Y-over 0.5s ease-in-out',
                        'transitionIn-X': 'transitionIn-X 0.5s ease-in-out',
                        'transitionIn-Y-bottom': 'transitionIn-Y-bottom 0.5s ease-in-out',
                    },
                    keyframes: {
                        'transitionIn-Y-over': {
                            'from': { opacity: '0', transform: 'translateY(-10px)' },
                            'to': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'transitionIn-X': {
                            'from': { opacity: '0', transform: 'translateX(-10px)' },
                            'to': { opacity: '1', transform: 'translateX(0)' },
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
                <a href="patient.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                    My Patients
                </a>
                <a href="settings.php" class="block py-2 px-4 bg-primary text-white rounded-md transition-colors">
                    Settings
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div class="flex items-center space-x-4">
                    <a href="settings.php" class="inline-block">
                        <button class="bg-btnice text-btnnicetext px-4 py-2 rounded-md hover:bg-primary hover:text-white transition-colors shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </button>
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-800">Settings</h1>
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
            
            <!-- Settings Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animation transitionIn-Y-over">
                <!-- Account Settings -->
                <a href="?action=edit&id=<?php echo $userid ?>&error=0" class="block">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/doctors-hover.svg" alt="Account" class="w-6 h-6">
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Account Settings</h3>
                                <p class="text-gray-600 text-sm mt-1">Edit your Account Details & Change Password</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- View Account -->
                <a href="?action=view&id=<?php echo $userid ?>" class="block">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/view-iceblue.svg" alt="View" class="w-6 h-6">
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">View Account Details</h3>
                                <p class="text-gray-600 text-sm mt-1">View Personal information About Your Account</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Delete Account -->
                <a href="?action=drop&id=<?php echo $userid.'&name='.$username ?>" class="block">
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <img src="../img/icons/patients-hover.svg" alt="Delete" class="w-6 h-6">
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-600">Delete Account</h3>
                                <p class="text-gray-600 text-sm mt-1">Will Permanently Remove your Account</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php 
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        
        if($action=='drop'){
            $nameget=$_GET["name"];
            echo '
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full animation transitionIn-Y-bottom">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Are you sure?</h3>
                        <a href="settings.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                    </div>
                    <div class="mb-4">
                        <p>You want to delete this record</p>
                        <p class="mt-2 font-medium">('.substr($nameget,0,40).')</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <a href="delete-doctor.php?id='.$id.'" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors">Yes</a>
                        <a href="settings.php" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">No</a>
                    </div>
                </div>
            </div>';
        } elseif($action=='view'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $spcil_res= $database->query("select sname from specialties where id='$spe'");
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];
            
            echo '
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full animation transitionIn-Y-bottom">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">View Account Details</h3>
                        <a href="settings.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                    </div>
                    
                    <div class="space-y-4">
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
                            <label class="block text-sm font-medium text-gray-700">Specialties:</label>
                            <p class="mt-1">'.$spcil_name.'</p>
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <a href="settings.php" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors">
                                OK
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
        } elseif($action=='edit'){
            $sqlmain= "select * from doctor where docid='$id'";
            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $name=$row["docname"];
            $email=$row["docemail"];
            $spe=$row["specialties"];
            
            $spcil_res= $database->query("select sname from specialties where id='$spe'");
            $spcil_array= $spcil_res->fetch_assoc();
            $spcil_name=$spcil_array["sname"];
            $nic=$row['docnic'];
            $tele=$row['doctel'];

            $error_1=$_GET["error"];
            $errorlist= array(
                '1'=>'<p class="text-red-500 text-center">Already have an account for this Email address.</p>',
                '2'=>'<p class="text-red-500 text-center">Password Conformation Error! Reconform Password</p>',
                '3'=>'<p class="text-red-500 text-center"></p>',
                '4'=>"",
                '0'=>'',
            );

            if($error_1!='4'){
                echo '
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full my-8 animation transitionIn-Y-bottom">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Edit Doctor Details</h3>
                            <a href="settings.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                        </div>
                        
                        <div class="space-y-4">
                            '.$errorlist[$error_1].'
                            <p class="text-gray-600">Doctor ID: '.$id.' (Auto Generated)</p>
                            
                            <form action="edit-doc.php" method="POST" class="space-y-4">
                                <input type="hidden" value="'.$id.'" name="id00">
                                <input type="hidden" name="oldemail" value="'.$email.'">
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Email Address" value="'.$email.'" required>
                                </div>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Doctor Name" value="'.$name.'" required>
                                </div>
                                
                                <div>
                                    <label for="nic" class="block text-sm font-medium text-gray-700">NIC:</label>
                                    <input type="text" name="nic" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="NIC Number" value="'.$nic.'" required>
                                </div>
                                
                                <div>
                                    <label for="Tele" class="block text-sm font-medium text-gray-700">Telephone:</label>
                                    <input type="tel" name="Tele" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Telephone Number" value="'.$tele.'" required>
                                </div>
                                
                                <div>
                                    <label for="spec" class="block text-sm font-medium text-gray-700">Choose specialty: (Current: '.$spcil_name.')</label>
                                    <select name="spec" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">';
                                        
                                        $list11 = $database->query("select * from specialties;");
                                        for ($y=0;$y<$list11->num_rows;$y++){
                                            $row00=$list11->fetch_assoc();
                                            $sn=$row00["sname"];
                                            $id00=$row00["id"];
                                            echo "<option value=".$id00.">$sn</option>";
                                        };
                                        
                                    echo '</select>
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Define a Password" required>
                                </div>
                                
                                <div>
                                    <label for="cpassword" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                                    <input type="password" name="cpassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Confirm Password" required>
                                </div>
                                
                                <div class="flex justify-end space-x-3 pt-4">
                                    <input type="reset" value="Reset" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors cursor-pointer">
                                    <input type="submit" value="Save" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors cursor-pointer">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
            } else {
                echo '
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full animation transitionIn-Y-bottom">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Edit Successfully!</h3>
                            <a href="settings.php" class="text-gray-500 hover:text-gray-700">&times;</a>
                        </div>
                        
                        <div class="mb-6">
                            <p>If You changed your email, please logout and login again with your new email</p>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="settings.php" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primaryhover transition-colors">OK</a>
                            <a href="../logout.php" class="px-4 py-2 bg-btnice text-btnnicetext rounded-md hover:bg-primary hover:text-white transition-colors">Log out</a>
                        </div>
                    </div>
                </div>';
            }
        }
    }
    ?>
</body>
</html>