<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        /* Background image animation */
        @keyframes backgroundTransition {

            0%,
            100% {
                background-image: url('img/bg-img/bg1.jpeg');
            }

            10% {
                background-image: url('img/bg-img/bg2.jpg');
            }

            20% {
                background-image: url('img/bg-img/bg3.jpg');
            }

            30% {
                background-image: url('img/bg-img/bg4.jpg');
            }

            40% {
                background-image: url('img/bg-img/bg5.jpg');
            }

            50% {
                background-image: url('img/bg-img/bg6.avif');
            }

            60% {
                background-image: url('img/bg-img/bg7.jpg');
            }

            70% {
                background-image: url('img/bg-img/bg8.avif');
            }

            80% {
                background-image: url('img/bg-img/bg9.jpg');
            }

            90% {
                background-image: url('img/bg-img/bg10.webp');
            }
        }

        .bg-slideshow {
            animation: backgroundTransition 50s infinite;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: background-image 1s ease-in-out;
        }
    </style>
</head>

<body class="bg-slideshow bg-fixed">
    <?php
    session_start();
    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"] = $date;

    include("connection.php");

    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];
        $error = '<label for="promter" class="form-label"></label>';

        $result = $database->query("SELECT * FROM webuser WHERE email='$email'");
        if ($result->num_rows == 1) {
            $utype = $result->fetch_assoc()['usertype'];
            if ($utype == 'p') {
                $checker = $database->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
                if ($checker->num_rows == 1) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'p';
                    header('location: patient/index.php');
                } else {
                    $error = '<label for="promter" class="form-label text-red-500 text-center">Wrong credentials: Invalid email or password</label>';
                }
            } elseif ($utype == 'a') {
                $checker = $database->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
                if ($checker->num_rows == 1) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'a';
                    header('location: admin/index.php');
                } else {
                    $error = '<label for="promter" class="form-label text-red-500 text-center">Wrong credentials: Invalid email or password</label>';
                }
            } elseif ($utype == 'd') {
                $checker = $database->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
                if ($checker->num_rows == 1) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'd';
                    header('location: doctor/index.php');
                } else {
                    $error = '<label for="promter" class="form-label text-red-500 text-center">Wrong credentials: Invalid email or password</label>';
                }
            }
        } else {
            $error = '<label for="promter" class="form-label text-red-500 text-center">We can\'t find any account for this email.</label>';
        }
    } else {
        $error = '<label for="promter" class="form-label">&nbsp;</label>';
    }
    ?>

    <div class="min-h-screen bg-gray-900 bg-opacity-50 bg-fixed flex flex-col">

        <nav class="w-full pt-5 px-4 sm:px-8 bg-white/5 backdrop-blur-md border-b border-white/10">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <a href="index.html" class="group">
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-4xl font-bold font-sans bg-clip-text text-transparent bg-gradient-to-r from-[#6A64F1] to-[#9D4DFB] group-hover:from-[#9D4DFB] group-hover:to-[#6A64F1] transition-all duration-500">
                            Click2Cure
                        </span>
                        <span
                            class="hidden sm:inline-block text-xs bg-gradient-to-r from-purple-500 to-blue-400 text-white px-2 py-1 rounded-full animate-pulse">
                            FAST TRACK HEALTHCARE
                        </span>
                    </div>
                </a>
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-4 sm:mt-0">
                    <a href="index2.html" class="no-underline">
                        <button
                            class="bg-[#6A64F1] text-white text-lg sm:text-xl text-center px-4 sm:px-6 py-2 sm:py-3 rounded-3xl hover:font-bold hover:scale-105 transition-all duration-300 animate-transitionIn-Y-over shadow-lg hover:shadow-xl w-full sm:w-auto">
                            CONTACT US
                        </button>
                    </a>
                    <a href="login.php" class="no-underline">
                        <button
                            class="bg-[#6A64F1] text-white text-lg sm:text-xl text-center px-4 sm:px-6 py-2 sm:py-3 rounded-3xl hover:font-bold hover:scale-105 transition-all duration-300 animate-transitionIn-Y-over shadow-lg hover:shadow-xl w-full sm:w-auto">
                            LOGIN
                        </button>
                    </a>
                    <a href="signup.php" class="no-underline">
                        <button
                            class="bg-[#6A64F1] text-white text-lg sm:text-xl text-center px-4 sm:px-6 py-2 sm:py-3 rounded-3xl hover:font-bold hover:scale-105 transition-all duration-300 animate-transitionIn-Y-over shadow-lg hover:shadow-xl w-full sm:w-auto">
                            REGISTER
                        </button>
                    </a>
                    <a href="About_Us.html" class="no-underline">
                        <button
                            class="bg-[#6A64F1] text-white text-lg sm:text-xl text-center px-4 sm:px-6 py-2 sm:py-3 rounded-3xl hover:font-bold hover:scale-105 transition-all duration-300 animate-transitionIn-Y-over shadow-lg hover:shadow-xl w-full sm:w-auto">ABOUT
                            US</button>
                    </a>
                </div>
            </div>
            <!-- Gap between Navigation and Text-to-Button Section -->
            <div class="mt-10 sm:mt-10"></div>
        </nav>

        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white/10 p-8 rounded-lg shadow-lg w-full max-w-md backdrop-blur-md border border-white/20">
                <h1 class="text-3xl font-bold text-center mb-4 text-white">Welcome Back!</h1>
                <p class="text-gray-400 text-center mb-6">Login with your details to continue</p>

                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label for="useremail" class="block text-sm font-medium text-white">Email:</label>
                        <input type="email" name="useremail"
                            class="mt-1 block w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-white/5 backdrop-blur-md text-white focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]"
                            placeholder="Email Address" required>
                    </div>

                    <div>
                        <label for="userpassword" class="block text-sm font-medium text-white">Password:</label>
                        <input type="password" name="userpassword"
                            class="mt-1 block w-full px-4 py-2 border border-gray-700 rounded-lg shadow-sm bg-white/5 backdrop-blur-md text-white focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]"
                            placeholder="Password" required>
                    </div>

                    <div class="text-center">
                        <?php echo $error; ?>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Login</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-gray-400">Don't have an account? <a href="signup.php"
                            class="text-[#6A64F1] hover:underline">Sign Up</a></p>
                </div>
            </div>
        </div>

        <footer class="mt-auto w-full py-8 px-4 sm:px-8 bg-gray-900/80 backdrop-blur-md border-t border-white/10">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <a href="index.html" class="group flex items-center space-x-2">
                            <span
                                class="text-2xl font-bold font-sans bg-clip-text text-transparent bg-gradient-to-r from-[#6A64F1] to-[#9D4DFB]">
                                Click2Cure
                            </span>
                        </a>
                        <p class="text-white/70 text-sm">
                            Your trusted digital healthcare platform connecting you with medical professionals
                            instantly.
                        </p>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-white font-semibold text-lg">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="index.html"
                                    class="text-white/70 hover:text-white transition-colors duration-300">Home</a></li>
                            <li><a href="About_Us.html"
                                    class="text-white/70 hover:text-white transition-colors duration-300">About Us</a>
                            </li>
                            <li><a href="index2.html"
                                    class="text-white/70 hover:text-white transition-colors duration-300">Contact</a>
                            </li>
                            <li><a href="login.php"
                                    class="text-white/70 hover:text-white transition-colors duration-300">Login</a></li>
                            <li><a href="signup.php"
                                    class="text-white/70 hover:text-white transition-colors duration-300">Register</a>
                            </li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-white font-semibold text-lg">Services</h3>
                        <ul class="space-y-2">
                            <li><a href="#"
                                    class="text-white/70 hover:text-white transition-colors duration-300">Instant
                                    Consultations</a></li>
                            <li><a href="#" class="text-white/70 hover:text-white transition-colors duration-300">Doctor
                                    Appointments</a></li>
                            <li><a href="#"
                                    class="text-white/70 hover:text-white transition-colors duration-300">E-Prescriptions</a>
                            </li>
                            <li><a href="#" class="text-white/70 hover:text-white transition-colors duration-300">Health
                                    Records</a></li>
                            <li><a href="#" class="text-white/70 hover:text-white transition-colors duration-300">24/7
                                    Support</a></li>
                        </ul>
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-white font-semibold text-lg">Contact Us</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-2">
                                <span class="text-white/70">123 Health Street, Medical City</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-white/70">+1 (555) 123-4567</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="text-white/70">support@click2cure.com</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-white/10 text-center text-white/50 text-sm">
                    <p>© 2023 Click2Cure. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>