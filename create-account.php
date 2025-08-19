<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom animations from the original CSS */
        @keyframes transitionIn-X {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .container {
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    session_start();
    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');
    $_SESSION["date"] = $date;

    include("connection.php");

    if ($_POST) {
        $result = $database->query("SELECT * FROM webuser");

        $fname = $_SESSION['personal']['fname'];
        $lname = $_SESSION['personal']['lname'];
        $name = $fname . " " . $lname;
        $address = $_SESSION['personal']['address'];
        $nic = $_SESSION['personal']['nic'];
        $dob = $_SESSION['personal']['dob'];
        $email = $_POST['newemail'];
        $tele = $_POST['tele'];
        $newpassword = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];

        if ($newpassword == $cpassword) {
            $sqlmain = "SELECT * FROM webuser WHERE email=?;";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $error = '<label for="promter" class="form-label text-red-500 text-center">Already have an account for this Email address.</label>';
            } else {
                $database->query("INSERT INTO patient(pemail, pname, ppassword, paddress, pnic, pdob, ptel) VALUES('$email', '$name', '$newpassword', '$address', '$nic', '$dob', '$tele');");
                $database->query("INSERT INTO webuser VALUES('$email', 'p')");

                $_SESSION["user"] = $email;
                $_SESSION["usertype"] = "p";
                $_SESSION["username"] = $fname;

                header('Location: patient/index.php');
                $error = '<label for="promter" class="form-label"></label>';
            }
        } else {
            $error = '<label for="promter" class="form-label text-red-500 text-center">Password Confirmation Error! Reconfirm Password</label>';
        }
    } else {
        $error = '<label for="promter" class="form-label"></label>';
    }
    ?>

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md container">
            <h1 class="text-3xl font-bold text-center mb-4">Let's Get Started</h1>
            <p class="text-gray-600 text-center mb-6">It's Okay, Now Create User Account.</p>

            <form action="" method="POST" class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="newemail" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="newemail" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]" placeholder="Email Address" required>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="tele" class="block text-sm font-medium text-gray-700">Mobile Number:</label>
                    <input type="tel" name="tele" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]" placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}">
                </div>

                <!-- New Password -->
                <div>
                    <label for="newpassword" class="block text-sm font-medium text-gray-700">Create New Password:</label>
                    <input type="password" name="newpassword" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]" placeholder="New Password" required>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="cpassword" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                    <input type="password" name="cpassword" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6A64F1] focus:border-[#6A64F1]" placeholder="Confirm Password" required>
                </div>

                <!-- Error Message -->
                <div class="text-center">
                    <?php echo $error; ?>
                </div>

                <!-- Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button type="reset" class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300">Reset</button>
                    <button type="submit" class="w-full bg-[#6A64F1] text-white px-4 py-2 rounded-lg hover:bg-[#5a55e0] transition duration-300">Sign Up</button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="text-gray-600">Already have an account? <a href="login.php" class="text-[#6A64F1] hover:underline">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>