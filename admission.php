<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

// Initialize variables for pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$records_per_page = 10; // Number of records to display per page

// Calculate the starting record for the query based on pagination
$start_from = ($page - 1) * $records_per_page;

// Fetch center_name from center table based on center_code
$center_code = isset($_GET['center_code']) ? $_GET['center_code'] : '';
$center_code = mysqli_real_escape_string($conn, $center_code);

$sql_center = "SELECT center_name, id FROM center WHERE center_code = '$center_code'";
$result_center = $conn->query($sql_center);

if ($result_center && $result_center->num_rows > 0) {
    $row_center = $result_center->fetch_assoc();
    $center_name = htmlspecialchars($row_center['center_name']);
    $center_id = $row_center['id'];

    // Fetch admission details from the database with category name and center name
    $sql = "SELECT a.*, c.name AS category_name, ce.center_name
            FROM admission a
            LEFT JOIN category c ON a.category_id = c.id
            LEFT JOIN center ce ON a.center_id = ce.id
            WHERE a.center_id = $center_id
            ORDER BY a.id DESC
            LIMIT $start_from, $records_per_page";

    $result = $conn->query($sql);

    // Check if query was successful
    if ($result && $result->num_rows > 0) {
        // Admission details found
        // Fetch all rows and store in an array
        $admissions = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // No admission details found
        $admissions = [];
    }

    // Count total number of records for pagination
    $total_records_sql = "SELECT COUNT(*) AS total_records FROM admission WHERE center_id = $center_id";
    $total_records_result = $conn->query($total_records_sql);

    if ($total_records_result) {
        $total_records = $total_records_result->fetch_assoc()['total_records'];
        // Calculate total pages
        $total_pages = ceil($total_records / $records_per_page);
    } else {
        $total_records = 0;
        $total_pages = 1; // Default to 1 page if no records or error
    }
} else {
    // Center not found or invalid center_code
    $center_name = ''; // Set default center name or handle as needed
    $center_id = 0; // Set default center id or handle as needed
    $admissions = [];
    $total_records = 0;
    $total_pages = 1;
}

// Define current URL with pagination parameter
$url = $_SERVER['PHP_SELF'] . "?center_code=$center_code";

// Close the database connection
$conn->close();

// Check if redirected with success parameter and display success message
$status = isset($_GET['status']) ? $_GET['status'] : '';
$success_message = '';

if ($status === 'success' && isset($_SESSION['admission_added']) && $_SESSION['admission_added']) {
    $success_message = 'New admission record added successfully!';
    // Unset the session variable to prevent displaying the message on subsequent page loads
    unset($_SESSION['admission_added']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admission Details</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <!-- Font Awesome CSS for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Bootstrap Table CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.3/bootstrap-table.min.css">
  <!-- Google Fonts - Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Custom CSS for the student details page */    #sidebar {
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
      .btn-admission {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      cursor: pointer;
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
  <?php if (!empty($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $success_message; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>


    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col">
            Admission Details
          </div>
          <div class="col text-right">
            <a href="add_admission.php?center_code=<?php echo $_SESSION['center_code']; ?>" class="btn btn-admission">New Admission</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-container">
          <table id="admissionTable" data-toggle="table" data-pagination="true" data-search="true" data-sortable="true">
            <thead>
              <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Candidate Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Center Name</th>
                <th>Employeed</th>
                <th>Action</th> <!-- New column for Edit action -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($admissions as $admission): ?>
                <tr>
                  <td><?php echo $admission['id']; ?></td>
                  <td><img src="https://tnscpe.graymatterworks.com/admin/<?php echo $admission['image']; ?>" class="img-thumbnail" style="max-width: 100px;"></td>
                  <td><?php echo $admission['candidate_name']; ?></td>
                  <td><?php echo $admission['dob']; ?></td>
                  <td><?php echo $admission['gender']; ?></td>
                  <td><?php echo $admission['center_name']; ?></td>
                  <td>
                    <?php
                    // Assuming $admission is your array containing data from the database
                    $employeed = $admission['employeed']; // Get the 'employeed' value from $admission

                    if ($employeed == 1) {
                        echo '<span style="color: green;">Yes</span>';
                    } else {
                        echo '<span style="color: red;">No</span>';
                    }
                    ?>
                  </td>
                  <td>
                  <a href="edit_admission.php?admission_id=<?php echo $admission['id']; ?>&center_code=<?php echo $_SESSION['center_code']; ?>" class="btn btn-primary">View & Edit</a>

                        </td> <!-- Edit button with link to edit.php -->
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Pagination -->
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
      <!-- Previous Page Link -->
      <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
        <a class="page-link" href="<?php echo $url . '&page=' . ($page - 1); ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>

      <!-- Page Links -->
      <?php
      // Limit the number of displayed pages around the current page
      $num_links = 5; // Adjust this number based on your preference
      $start_page = max(1, $page - floor($num_links / 2));
      $end_page = min($total_pages, $start_page + $num_links - 1);

      for ($i = $start_page; $i <= $end_page; $i++) {
        echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="' . $url . '&page=' . $i . '">' . $i . '</a></li>';
      }
      ?>

      <!-- Next Page Link -->
      <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
        <a class="page-link" href="<?php echo $url . '&page=' . ($page + 1); ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <!-- Bootstrap Table JS -->
  <script>
  function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
  }
</script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.18.3/bootstrap-table.min.js"></script>
</body>
</html>
