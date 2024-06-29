<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

if (isset($_GET['registration_no'])) {
    $registration_no = $_GET['registration_no'];

    $sql = "SELECT result.*, student.registration_no AS registration_no
            FROM result
            JOIN student ON result.registration_no_id = student.id
            WHERE student.registration_no = '$registration_no'";
$result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display student details
        $row = $result->fetch_assoc();
        
        // Display other relevant details
    } else {
        echo "No student details found for the given user ID.";
    }

    $conn->close();
} else {
    echo "No user ID provided.";
}
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
        <p class="card-text"><strong>Registration No:</strong> <?php echo $row['registration_no']; ?></p>
        <p class="card-text"><strong>Year/Semester:</strong> <?php echo $row['year_semester']; ?></p>
        <p class="card-text"><strong>Exam Month and Year:</strong> <?php echo $row['exam_month_year']; ?></p>
        <p class="card-text"><strong>Total Marks:</strong> <?php echo $row['total_marks']; ?></p>
        <p class="card-text"><strong>Obtained Marks:</strong> <?php echo $row['obtained_marks']; ?></p>
        <p class="card-text"><strong>SGPA:</strong> <?php echo $row['sgpa']; ?></p>
        <p class="card-text"><strong>Status:</strong>
         <?php
            if ($row['status'] == 1) {
              echo '<span style="color: green;">Pass</span>';
             } else {
                echo '<span style="color: red;">Fail</span>';
             }
           ?>
                        </p>
        <!-- Additional details as needed -->
      </div>
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