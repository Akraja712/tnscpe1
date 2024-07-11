<?php
session_start();
require 'db.php'; // Assuming this file contains database connection logic

$errorMsgStudent = ''; // Initialize student login error message variable
$errorMsgCenter = ''; // Initialize center login error message variable
// Center Login Handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['center_login'])) {
    $center_username = isset($_POST['center_username']) ? $_POST['center_username'] : '';
    $center_password = isset($_POST['center_password']) ? $_POST['center_password'] : '';

    if (!empty($center_username) && !empty($center_password)) {
        // Prepare SQL query to fetch center details (sanitize inputs properly in actual implementation)
        $sql_query = "SELECT center_code, password FROM center WHERE center_code='$center_username'";
        $result = $conn->query($sql_query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $correct_username = $row['center_code'];
            $correct_password = $row['password'];

            // Verify password
            if ($center_password === $correct_password) {
                // Set session variables
                $_SESSION['center_code'] = $correct_username; // Store center code in session
                $_SESSION['center_loggedin'] = true; // Example session variable for center login

                // Redirect to center dashboard
                header("Location: center.php?center_code=$correct_username");
                exit();
            } else {
                $errorMsgCenter = "Incorrect password";
            }
        } else {
            $errorMsgCenter = "Invalid username";
        }
    } else {
        $errorMsgCenter = "Please provide both username and password";
    }
}

$conn->close(); // Close database connection

// Include HTML or display forms here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Center Login Page</title>
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
            font-family: 'Poppins', sans-serif;
        }

        .container-fluid {
            background: url('image/global.avif') no-repeat center center/cover;
        }

        .login-box {
            background: transparent;
            border-radius: 25px; /* Adjust border-radius to increase the curve */
            border: 2px solid white; /* White border around the box */
            padding: 30px;
            position: relative; /* Added positioning for child elements */
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
        .title-text {
            position: absolute;
            top: 10px; /* Adjust top position as needed */
            left: 50px; /* Adjust right position as needed */
            color: white;
            font-size: 25px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="container-fluid vh-100 d-flex justify-content-end align-items-center">
        <div class="row w-100">
            <div class="col-md-4 offset-md-7">
                <div class="login-box shadow">
                    <h2 class="text-center mb-4">Login</h2>
                    <form method="POST" action="center-login.php">
                    <input type="hidden" name="center_login" value="1">
                    <div class="form-group">
                            <label for="center_username">Username</label>
                            <input type="text" class="form-control" id="center_username" name="center_username" required>
                        </div>
                        <div class="form-group">
                            <label for="center_password">Password</label>
                            <input type="password" class="form-control" id="center_password" name="center_password" required>
                        </div>
                        <button type="submit" style="background-color:#43c5f4; border: 2px solid white; /* White border */" class="btn btn-dark btn-block mt-4">Login</button>
                        <?php if (!empty($errorMsgCenter)) : ?>
                            <div class="alert alert-danger mt-4" role="alert" id="error-message">
                                <?php echo $errorMsgCenter; ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
                <br>
                <center><p style="color: white;">OR</p></center>
                <div class="login-info-container">
                    <a href="index.php" class="login-info">Student login</a>
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
