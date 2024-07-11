<?php
session_start();
require 'db.php'; // Assuming this file contains database connection logic

$errorMsgStudent = ''; // Initialize student login error message variable
$errorMsgCenter = ''; // Initialize center login error message variable

// Student Login Handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_login'])) {
    $registration_no = isset($_POST['username']) ? $_POST['username'] : '';
    $dob = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($registration_no) && !empty($dob)) {
        // Format the dob into YYYY-MM-DD format for comparison with database
        $dob_formatted = date('Y-m-d', strtotime($dob));

        // Prepare SQL query to check student credentials (sanitize inputs properly in actual implementation)
        $sql_query = "SELECT id FROM student WHERE registration_no='$registration_no' AND dob='$dob_formatted'";
        $result = $conn->query($sql_query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['id'];

            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['registration_no'] = $registration_no; // Store registration number in session

            // Redirect to student details page
            header("Location: student_details.php?registration_no=$registration_no");
            exit();
        } else {
            $errorMsgStudent = "Invalid registration number or date of birth";
        }
    } else {
        $errorMsgStudent = "Please provide registration number and date of birth";
    }
}

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Bootstrap Table CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.3/bootstrap-table.min.css">
  <!-- Google Fonts - Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'poppins', sans-serif;
        }


        .container-fluid {
            background: url('image/global.avif') no-repeat center center/cover;
        
        
        }

        .login-box {
            background: transparent;
            border-radius: 25px; /* Adjust border-radius to increase the curve */
            border: 2px solid white; /* White border around the box */
            padding: 20px;
            position: relative; /* Added positioning for child elements */
            margin-top:50px;
        }

        label, h2 {
            color: white;
        }

        .form-control {
            background: transparent;
            border: none;
            border-bottom: 2px solid white;
            border-radius: 0;
            color: white;
            margin-top: 10px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: white;
        }

        .login-info-container {
            text-align: center; /* Center align the container */
            margin-top: 20px; /* Adjust margin as needed */
        }

        .login-info {
            color: white;
            background-color: #43c5f4;
            padding: 10px 20px;
            cursor: pointer; /* Add cursor pointer to indicate clickable */
            text-decoration: none; /* Remove default underline */
            display: inline-block; /* Ensure it behaves like a block-level element */
            border: 2px solid white; /* White border */
            border-radius: 5px; /* Rounded corners */
            transition: all 0.3s ease; /* Smooth transition for hover effect */
        }

        .login-info:hover {
            background-color: #43c5f4;
            border-color: #43c5f4;
            color: white;
        }


        /* Style for the white-colored paragraph */
        .white-text {
            color: #43c5f4;
            font-size: 12px;
        }
        .title-text {
    margin-top: -100vh; /* Adjust based on your design */
    color: white;
    font-size: 3vw; /* Responsive font size */
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

        .alert {
    position: absolute;
    bottom: -30px; /* Adjust this value to position the error message */
    left: 50%;
    transform: translateX(-50%);
    width: 100%; /* Ensure it spans the full width of the parent */
    text-align: center; /* Center-align the error message text */
}
@media (max-width: 768px) {
    .title-text {
        margin-top: -90vh; /* Adjust for smaller screens */
        font-size: 6vw; /* Increase font size for smaller screens */
    }
}
       
    </style>
</head>
<body>
    <div class="container-fluid vh-100 d-flex justify-content-end align-items-center">
        <div class="row w-100">
            <div class="col-md-4 offset-md-7">
                <div class="login-box shadow">
                    <h2 class="text-center mb-4">Login</h2>
                    <form method="POST" action="index.php">
                        <input type="hidden" name="student_login" value="1">
                        <div class="form-group">
                            <label for="mobile">Register Number</label>
                            <input type="text" class="form-control" id="mobile" name="username" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="date" class="form-control" id="password" name="password" required>
                        </div>
                        <p class="white-text">Use Password as your DOB with (-) like DD-MM-YYYY</p>
                        <button type="submit" style="background-color:#43c5f4; border: 2px solid white; /* White border */" class="btn btn-dark btn-block mt-4">Login</button>
                        <?php if (!empty($errorMsgStudent)) : ?>
                            <div class="alert alert-danger mt-4" role="alert" id="error-message">
                                <?php echo $errorMsgStudent; ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
                
                <center><p style="color: white;">OR</p></center>
                <div class="login-info-container">
                    <a href="center-login.php" class="login-info">Center login</a>
              </div>
            </div>
        </div>
    </div>
    <div class="title-text">
            Tamilnadu State Council of Professional Education
        </div>
    <script>
        // JavaScript to hide error message on page reload
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 3000); // Adjust timeout as needed (3000 milliseconds = 3 seconds)
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
