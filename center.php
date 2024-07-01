<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

if (isset($_GET['center_code'])) {
    $center_code = $_GET['center_code'];

    // Prepare SQL query (sanitize inputs properly in actual implementation)
    $sql = "SELECT * FROM center WHERE center_code = '$center_code'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch center details
        $row = $result->fetch_assoc();
        // Display other relevant details
    } else {
        echo "No Center details found for the given center code.";
    }

    $conn->close();
} else {
    echo "No center code provided.";
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
        <a class="nav-link" style="color: white;" href="center.php?center_code=<?php echo $_SESSION['center_code']; ?>">
            <i class="fas fa-building"></i> Center Profile
          </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" style="color: white;" href="admission.php?center_code=<?php echo $_SESSION['center_code']; ?>"><i class="fas fa-poll"></i> Admission</a>
       </li>
        <li class="nav-item">
          <a class="nav-link" style="color: white;" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
  <div class="container">
    <div class="card">
      <div class="card-header">
        Center Profile
      </div>
      <div class="card-body">
        <center><h4>Center Details</h4></center>
        <br>
        <img src="https://tnscpe.graymatterworks.com/admin/<?php echo $row['image']; ?>" class="card-img-top float-right img-fluid" alt="Center Image" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #fff;">
        <p class="card-text"><strong>Center Name:</strong> <?php echo $row['center_name']; ?></p>
        <p class="card-text"><strong>Center Code:</strong> <?php echo $row['center_code']; ?></p>
        <p class="card-text"><strong>Director Name:</strong> <?php echo $row['director_name']; ?></p>
        <p class="card-text"><strong>Mobile Number:</strong> <?php echo $row['mobile_number']; ?></p>
        <p class="card-text"><strong>Whatsapp Number:</strong> <?php echo $row['whatsapp_number']; ?></p>
        <p class="card-text"><strong>Email ID:</strong> <?php echo $row['email_id']; ?></p>
        <p class="card-text"><strong>Institude Address:</strong> <?php echo $row['institute_address']; ?></p>
        <p class="card-text"><strong>City:</strong> <?php echo $row['city']; ?></p>
        <p class="card-text"><strong>State:</strong> <?php echo $row['state']; ?></p>
        <p class="card-text"><strong>Country:</strong> <?php echo $row['country']; ?></p>
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
