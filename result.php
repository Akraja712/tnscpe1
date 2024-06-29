<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Initialize variables for form submission and result display
$registration_no = '';
$year_semester = '';
$resultData = null;
$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['registration_no']) && isset($_GET['year_semester'])) {
        $registration_no = $_GET['registration_no'];
        $year_semester = $_GET['year_semester'];

        // Validate registration_no as numeric (assuming it's an integer)
        if (!is_numeric($registration_no)) {
            $errorMsg = "Invalid registration number.";
        } else {
            // Query to fetch result based on registration number and year_semester
            $sql = "SELECT result.*, student.registration_no AS registration_no
                    FROM result
                    JOIN student ON result.registration_no_id = student.id
                    WHERE student.registration_no = '$registration_no'
                    AND result.year_semester = '$year_semester'";
            $result = $conn->query($sql);

            if ($result === false) {
                $errorMsg = "Database error: " . $conn->error;
            } elseif ($result->num_rows > 0) {
                // Redirect to result_details.php with matched parameters
                header("Location: result_details.php?registration_no=$registration_no&year_semester=$year_semester");
                exit();
            } else {
                // Check if registration number exists
                $checkStudentSql = "SELECT * FROM student WHERE registration_no = '$registration_no'";
                $studentResult = $conn->query($checkStudentSql);
                if ($studentResult->num_rows == 0) {
                    $errorMsg = "Invalid registration number.";
                } else {
                    $errorMsg = "No data found for the student in year semester $year_semester.";
                }
            }
        }
    } elseif (empty($_GET['registration_no']) && empty($_GET['year_semester'])) {
        // No parameters provided, do nothing or show a message
    } else {
        $errorMsg = "Please provide both registration number and semester.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Details</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <!-- Font Awesome CSS for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Google Fonts - Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Additional custom styles */
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100%;
     
      }
      body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }
      .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background-color: #343a40;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      padding: 10px 20px;
    }
    .card-body {
      padding: 20px;
    }
    .container {
      padding: 20px;
      max-width: 100%;
    }
    @media (max-width: 768px) {
      #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100%;
        transform: translateX(-250px);
        transition: transform 0.3s ease-in-out;
        z-index: 1050; /* Ensure it's above other content */
      }
      #sidebar.open {
        transform: translateX(0);
      }
      .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040; /* Just below the sidebar */
      }
      .overlay.show {
        display: block;
      }
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Overlay for sidebar -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
      <div class="sidebar-sticky">
        <div class="navbar-brand d-flex align-items-center">
          <img src="image/logo.jpeg" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%;">
          <span class="ml-2" style="color: white;">TNSCPE</span>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link text-white" href="student_details.php?registration_no=<?php echo $_SESSION['registration_no']; ?>">
              <i class="fas fa-user"></i> Student Profile
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="result.php">
              <i class="fas fa-poll"></i> Result
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="pdf.php">
              <i class="fas fa-file-pdf"></i> PDF
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="logout.php">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main content -->
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
      <!-- Menu toggle button for mobile -->
      <div class="d-flex justify-content-between align-items-center bg-dark p-3 d-md-none">
        <div class="d-flex align-items-center">
          <img src="image/logo.jpeg" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%;">
          <span class="ml-2" style="color: white;">TNSCPE</span>
        </div>
        <button class="btn btn-dark" type="button" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
      </div>

      <div class="container mt-3">
        <div class="card">
          <div class="card-header bg-dark text-white">
            Student Result
          </div>
          <div class="card-body">
            <center><h4 class="card-title">Result</h4></center>
            <br>
            
            <!-- Form to input registration number and select semester -->
            <form method="get" action="">
              <div class="form-group">
                <label for="registration_no">Registration Number</label>
                <input type="text" class="form-control" id="registration_no" name="registration_no" required>
              </div>
              <div class="form-group">
                <label for="year_semester">Semester</label>
                <select class="form-control" id="year_semester" name="year_semester" required>
                  <option value="">Select Semester</option>
                  <?php
                  // Fetch distinct semesters from the result table
                  require 'db.php';
                  $sql = "SELECT DISTINCT year_semester FROM result";
                  $result = $conn->query($sql);
                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<option value='{$row['year_semester']}'>{$row['year_semester']}</option>";
                      }
                  }
                  $conn->close();
                  ?>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Display error message if any -->
            <?php if (!empty($errorMsg)) : ?>
                <div class="alert alert-danger mt-4" role="alert">
                    <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


<!-- JavaScript for sidebar toggle -->
<script>
  function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
  }
</script>

</body>
</html>
