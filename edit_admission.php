<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

$DOMAIN_URL = "https://tnscpe.graymatterworks.com/admin/";

// Function to fetch center name by center_code
function getCenterName($conn, $center_code) {
    $center_code = mysqli_real_escape_string($conn, $center_code);
    $sql = "SELECT center_name FROM center WHERE center_code = '$center_code'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return htmlspecialchars($row['center_name']);
    }
    return ''; // Return empty string if center_code not found
}

$center_code = $_GET['center_code'] ?? ''; // Get center_code from URL parameter
$center_name = getCenterName($conn, $center_code);

// Fetch center details including center_id
$sql_center = "SELECT id, center_name FROM center WHERE center_name = '$center_name'";
$result_center = $conn->query($sql_center);

if ($result_center && $result_center->num_rows > 0) {
    $row = $result_center->fetch_assoc();
    $center_id = $row['id'];
} else {
    $center_id = ''; // Handle case where center_id is not found
}


$sql_admission = "SELECT * FROM admission";
$result_admission = $conn->query($sql_admission);

// Check if there are any results
if ($result_admission->num_rows > 0) {
    // Fetch data from each row as an associative array
    $admission = $result_admission->fetch_assoc();
} else {
    // Handle case where no admission data is found
    $admission = array(); // Initialize an empty array or handle appropriately
}

if (isset($_POST['btnEdit'])) {
  // Sanitize and validate inputs
  $admission_id = mysqli_real_escape_string($conn, $_POST['admission_id']);
  $candidate_name = mysqli_real_escape_string($conn, $_POST['candidate_name']);
  $fathers_name = mysqli_real_escape_string($conn, $_POST['fathers_name']);
  $mothers_name = mysqli_real_escape_string($conn, $_POST['mothers_name']);
  $dob = mysqli_real_escape_string($conn, $_POST['dob']);
  $gender = mysqli_real_escape_string($conn, $_POST['gender']);
  $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
  $id_proof_type = mysqli_real_escape_string($conn, $_POST['id_proof_type']);
  $id_proof_no = mysqli_real_escape_string($conn, $_POST['id_proof_no']);
  $hostel_facility = mysqli_real_escape_string($conn, $_POST['hostel_facility']);
  $center_id = mysqli_real_escape_string($conn, $_POST['center_id']);
  $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $fathers_contact_number = mysqli_real_escape_string($conn, $_POST['fathers_contact_number']);
  $mothers_contact_number = mysqli_real_escape_string($conn, $_POST['mothers_contact_number']);
  $country = mysqli_real_escape_string($conn, $_POST['country']);
  $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
  $state = mysqli_real_escape_string($conn, $_POST['state']);
  $city = mysqli_real_escape_string($conn, $_POST['city']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

  // Qualification details
  $secondary_year_passing = mysqli_real_escape_string($conn, $_POST['secondary_year_passing']);
  $secondary_board_university = mysqli_real_escape_string($conn, $_POST['secondary_board_university']);
  $secondary_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['secondary_percentage_cgpa']);
  $senior_secondary_year_passing = mysqli_real_escape_string($conn, $_POST['senior_secondary_year_passing']);
  $senior_secondary_board_university = mysqli_real_escape_string($conn, $_POST['senior_secondary_board_university']);
  $senior_secondary_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['senior_secondary_percentage_cgpa']);
  $graduation_year_passing = mysqli_real_escape_string($conn, $_POST['graduation_year_passing']);
  $graduation_board_university = mysqli_real_escape_string($conn, $_POST['graduation_board_university']);
  $graduation_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['graduation_percentage_cgpa']);
  $post_graduation_year_passing = mysqli_real_escape_string($conn, $_POST['post_graduation_year_passing']);
  $post_graduation_board_university = mysqli_real_escape_string($conn, $_POST['post_graduation_board_university']);
  $post_graduation_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['post_graduation_percentage_cgpa']);
  $other_year_passing = mysqli_real_escape_string($conn, $_POST['other_year_passing']);
  $other_board_university = mysqli_real_escape_string($conn, $_POST['other_board_university']);
  $other_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['other_percentage_cgpa']);

  // Program details
  $course_type = mysqli_real_escape_string($conn, $_POST['course_type']);
  $faculty = mysqli_real_escape_string($conn, $_POST['faculty']);
  $course = mysqli_real_escape_string($conn, $_POST['course']);
  $stream = mysqli_real_escape_string($conn, $_POST['stream']);
  $year = mysqli_real_escape_string($conn, $_POST['year']);
  $month = mysqli_real_escape_string($conn, $_POST['month']);
  $mode_of_study = mysqli_real_escape_string($conn, $_POST['mode_of_study']);
  $employeed = mysqli_real_escape_string($conn, $_POST['employeed']);
  $application_fees = mysqli_real_escape_string($conn, $_POST['application_fees']);
  $duration = mysqli_real_escape_string($conn, $_POST['duration']);
  $total_fees = mysqli_real_escape_string($conn, $_POST['total_fees']);
  $paying_fees = mysqli_real_escape_string($conn, $_POST['paying_fees']);

 // Function to handle image upload
// Function to handle image upload
function handle_image_upload($file_input_name, $target_dir, $root_dir, $current_image) {
  // Check if a new file is uploaded
  if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
      // Remove old image if exists
      if (!empty($current_image)) {
          $old_file_path = $root_dir . '/' . $current_image;
          if (file_exists($old_file_path)) {
              unlink($old_file_path);
          }
      }

      // Upload new image
      $temp_name = $_FILES[$file_input_name]["tmp_name"];
      $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
      $filename = uniqid() . '.' . strtolower($extension);
      $target_path = $root_dir . '/' . $target_dir . $filename;
      if (move_uploaded_file($temp_name, $target_path)) {
          return $target_dir . $filename;
      }
  }
  // Return current image path if no new file is uploaded
  return $current_image;
}

// Function to handle document upload
function handle_document_upload($file_input_name, $target_dir, $root_dir, $current_document) {
  // Check if a new file is uploaded
  if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
      // Remove old document if exists
      if (!empty($current_document)) {
          $old_file_path = $root_dir . '/' . $current_document;
          if (file_exists($old_file_path)) {
              unlink($old_file_path);
          }
      }

      // Upload new document
      $temp_name = $_FILES[$file_input_name]["tmp_name"];
      $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
      $filename = uniqid() . '.' . strtolower($extension);
      $target_path = $root_dir . '/' . $target_dir . $filename;
      if (move_uploaded_file($temp_name, $target_path)) {
          return $target_dir . $filename;
      }
  }
  // Return current document path if no new file is uploaded
  return $current_document;
}

// Assuming this script is included after session start and database connection

// Determine the document root dynamically
$root_dir = $_SERVER['DOCUMENT_ROOT'] . '/admin/';

// Upload image
$image_target_dir = 'upload/images/';
$current_image = $admission['image']; // Assuming 'image' is the field in admission table
$image = handle_image_upload('image', $image_target_dir, $root_dir, $current_image);

// Upload documents
$document_target_dir = 'upload/documents/';
$secondary_document = handle_document_upload('secondary_document', $document_target_dir, $root_dir, $admission['secondary_document']);
$senior_secondary_document = handle_document_upload('senior_secondary_document', $document_target_dir, $root_dir, $admission['senior_secondary_document']);
$graduation_document = handle_document_upload('graduation_document', $document_target_dir, $root_dir, $admission['graduation_document']);
$post_graduation_document = handle_document_upload('post_graduation_document', $document_target_dir, $root_dir, $admission['post_graduation_document']);
$other_document = handle_document_upload('other_document', $document_target_dir, $root_dir, $admission['other_document']);

  // Update SQL query for admission update
  $sql = "UPDATE admission SET 
          candidate_name = '$candidate_name',
          fathers_name = '$fathers_name',
          mothers_name = '$mothers_name',
          dob = '$dob',
          gender = '$gender',
          category_id = '$category_id',
          id_proof_type = '$id_proof_type',
          id_proof_no = '$id_proof_no',
          employeed = '$employeed',
          center_id = '$center_id',
          contact_number = '$contact_number',
          email = '$email',
          fathers_contact_number = '$fathers_contact_number',
          mothers_contact_number = '$mothers_contact_number',
          country = '$country',
          nationality = '$nationality',
          state = '$state',
          city = '$city',
          address = '$address',
          pincode = '$pincode',
          secondary_year_passing = '$secondary_year_passing',
          secondary_board_university = '$secondary_board_university',
          secondary_percentage_cgpa = '$secondary_percentage_cgpa',
          secondary_document = '$secondary_document',
          senior_secondary_year_passing = '$senior_secondary_year_passing',
          senior_secondary_board_university = '$senior_secondary_board_university',
          senior_secondary_percentage_cgpa = '$senior_secondary_percentage_cgpa',
          senior_secondary_document = '$senior_secondary_document',
          graduation_year_passing = '$graduation_year_passing',
          graduation_board_university = '$graduation_board_university',
          graduation_percentage_cgpa = '$graduation_percentage_cgpa',
          graduation_document = '$graduation_document',
          post_graduation_year_passing = '$post_graduation_year_passing',
          post_graduation_board_university = '$post_graduation_board_university',
          post_graduation_percentage_cgpa = '$post_graduation_percentage_cgpa',
          post_graduation_document = '$post_graduation_document',
          other_year_passing = '$other_year_passing',
          other_board_university = '$other_board_university',
          other_percentage_cgpa = '$other_percentage_cgpa',
            other_document = '$other_document',
          course_type = '$course_type',
          faculty = '$faculty',
          course = '$course',
          stream = '$stream',
          year = '$year',
          month = '$month',
          mode_of_study = '$mode_of_study',
          hostel_facility = '$hostel_facility',
          application_fees = '$application_fees',
          duration = '$duration',
          total_fees = '$total_fees',
          paying_fees = '$paying_fees',
          image = '$image'
          WHERE id = '$admission_id'";
          

  // Execute SQL query and handle success or failure
  if ($conn->query($sql) === TRUE) {
    $_SESSION['action_message'] = 'Admission record Update successfully!';
    $_SESSION['admission_updated'] = true;
    header("Location:admission.php?admission_id=" . $admission['id'] . "&center_code=" . $_SESSION['center_code']);
    exit();
} else {
    error_log('Error updating record: ' . $conn->error);
    echo '<p class="alert alert-danger">Error updating record: ' . $conn->error . '</p>';
}
}

// Fetch success message if available
$success_message = isset($_SESSION['action_message']) ? $_SESSION['action_message'] : '';
unset($_SESSION['action_message']); // Clear the session variable after displaying

// Fetch admission details based on admission_id
$admission_id = isset($_GET['admission_id']) ? $_GET['admission_id'] : '';
$admission_id = mysqli_real_escape_string($conn, $admission_id);

$sql = "SELECT * FROM admission WHERE id = '$admission_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
$admission = $result->fetch_assoc();
} else {
// Handle if admission record not found
$_SESSION['admission_not_found'] = true;
header("Location: admission.php?center_code=" . $_SESSION['center_code']);
exit();
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Admission Details</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <!-- Font Awesome CSS for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <!-- Google Fonts - Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Custom CSS for the student details page */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      margin: 0;
    }
    .container {
      padding: 20px;
      max-width: 100%;
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
    .table-container {
      margin-top: 20px;
    }
    .btn-admission {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100%;
     
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

  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          Admission Details
        </div>
      </div>
    </div>
    <div class="card-body">
    <form action="edit_admission.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="admission_id" value="<?php echo $admission['id']; ?>">
        <input type="hidden" name="old_image" value="<?php echo isset($admission['image']) ? $admission['image'] : ''; ?>">
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
            <label for="candidate_name">Candidate Name:</label>
              <input type="text" name="candidate_name" class="form-control" placeholder="Candidate Name" value="<?php echo $admission['candidate_name']; ?>" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
            <label for="fathers_name">Father's Name:</label>
              <input type="text" name="fathers_name" class="form-control" placeholder="Father's Name" value="<?php echo $admission['fathers_name']; ?>" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
            <label for="mothers_name">Mother's Name:</label>
              <input type="text" name="mothers_name" class="form-control" placeholder="Mother's Name" value="<?php echo $admission['mothers_name']; ?>" required>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
            <label for="dob">Date Of Birth:</label>
              <input type="date" name="dob" class="form-control" value="<?php echo $admission['dob']; ?>" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
            <label for="gender">Gender:</label>
                <select name="gender" class="form-control" required>
                <option value="male" <?php echo ($admission['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($admission['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($admission['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            </div>
            <div class="col-md-4">
            <div class="form-group">
            <label for="category_id">Category:</label>
                <select name="category_id" class="form-control" required>
                <option value="1" <?php echo ($admission['category_id'] == '1') ? 'selected' : ''; ?>>General</option>
                <option value="2" <?php echo ($admission['category_id'] == '2') ? 'selected' : ''; ?>>SC</option>
                <option value="3" <?php echo ($admission['category_id'] == '3') ? 'selected' : ''; ?>>ST</option>
                <option value="4" <?php echo ($admission['category_id'] == '4') ? 'selected' : ''; ?>>BC</option>
                <option value="5" <?php echo ($admission['category_id'] == '5') ? 'selected' : ''; ?>>SBC</option>
                <option value="6" <?php echo ($admission['category_id'] == '6') ? 'selected' : ''; ?>>OBC</option>
                <option value="7" <?php echo ($admission['category_id'] == '7') ? 'selected' : ''; ?>>EBC</option>
                <option value="8" <?php echo ($admission['category_id'] == '8') ? 'selected' : ''; ?>>PH</option>
                <option value="9" <?php echo ($admission['category_id'] == '9') ? 'selected' : ''; ?>>Ex-Servicemen</option> 
                <option value="10" <?php echo ($admission['category_id'] == '10') ? 'selected' : ''; ?>>Other</option> 
              </select>
            </div>
            </div>
        </div>
        <div class="form-row">
        <div class="col-md-4">
            <div class="form-group">
            <label for="id_proof_type">ID Proof Type:</label>
                <select name="id_proof_type" class="form-control" required onchange="updateIdProofNumberLength()">
                <option value="">Select ID Type</option>
                <option value="aadhaarcard" <?php echo ($admission['id_proof_type'] == 'aadhaarcard') ? 'selected' : ''; ?>>Aadhaar Card</option>
                <option value="hsc" <?php echo ($admission['id_proof_type'] == 'hsc') ? 'selected' : ''; ?>>HSC</option>
                <option value="sslc" <?php echo ($admission['id_proof_type'] == 'sslc') ? 'selected' : ''; ?>>SSLC</option>
                </select>
            </div>
            </div>

          <div class="col-md-4">
            <div class="form-group">
            <label for="id_proof_no">ID Proof No:</label>
              <input type="text" name="id_proof_no" class="form-control" placeholder="ID Proof No" value="<?php echo $admission['id_proof_no']; ?>" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
            <label for="employeed">Employeed:</label>
              <select name="employeed" class="form-control" required>
                <option value="" >Employeed?</option>
                <option value="1" <?php echo ($admission['employeed'] == '1') ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo ($admission['employeed'] == '0') ? 'selected' : ''; ?>>No</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
            <label for="center_name">Center Name:</label>
              <input type="text" class="form-control" value="<?php echo $center_name; ?>" disabled>
              <input type="hidden" name="center_id" value="<?php echo $center_id; ?>">
            </div>
          </div>
          <div class="col-md-4">
    <label for="image">Photo:</label><?php echo isset($error['image']) ? $error['image'] : ''; ?>
    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
    <?php
    $image_path = 'https://tnscpe.graymatterworks.com/admin/' . $admission['image'];
    ?>
    <img id="blah" src="<?php echo $image_path; ?>" alt="Uploaded Image" style="display:block; width:150px; height:200px;" />
</div>

        </div>
    </div>
  </div>
  <div class="card mt-3">
    <div class="card-header">
      <div class="row">
        <div class="col">
          Contact Details
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
          <label for="candidate_name">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" value="<?php echo $admission['contact_number']; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $admission['email']; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="fathers_contact_number">Father's Contact Number:</label>
            <input type="text" name="fathers_contact_number" class="form-control" placeholder="Father's Contact Number" value="<?php echo $admission['fathers_contact_number']; ?>" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
          <label for="mothers_contact_number">Mother's Contact Number:</label>
            <input type="text" name="mothers_contact_number" class="form-control" placeholder="Mother's Contact Number" value="<?php echo $admission['mothers_contact_number']; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="country">Country:</label>
            <input type="text" name="country" class="form-control" placeholder="Country" value="<?php echo $admission['country']; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="nationality">Nationality:</label>
            <input type="text" name="nationality" class="form-control" placeholder="Nationality" value="<?php echo $admission['nationality']; ?>" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
          <label for="state">State:</label>
          <input type="text" name="state" class="form-control" placeholder="State" value="<?php echo isset($admission['state']) ? $admission['state'] : ''; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="city">City:</label>
            <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo $admission['city']; ?>" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
          <label for="address">Address:</label>
            <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo $admission['address']; ?>" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
          <label for="pincode">Pincode:</label>
            <input type="text" name="pincode" class="form-control" placeholder="Pincode" value="<?php echo $admission['pincode']; ?>" required>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="card mt-3">
    <div class="card-header">
      <div class="row">
        <div class="col">
          Qualification Details
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Examination</th>
              <th>Year & Passing</th>
              <th>Board/University</th>
              <th>Percentage/CGPA</th>
              <th>Upload Document</th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>Secondary</td>
            <td><input type="text" name="secondary_year_passing" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_year_passing']); ?>" required></td>
            <td><input type="text" name="secondary_board_university" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_board_university']); ?>" required></td>
            <td><input type="text" name="secondary_percentage_cgpa" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_percentage_cgpa']); ?>" required></td>
            <td>
            <div class="form-group">
              <input type="file" name="secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['secondary_document'])) echo ''; ?>>
              <?php if (!empty($admission['secondary_document'])): ?>
              <br>
              <?php
              // Assuming $domain_url is the base URL you want to prepend to the file path
              $file_url = $DOMAIN_URL . htmlspecialchars($admission['secondary_document']);
              ?>
              <a href="<?php echo $file_url; ?>" target="_blank"><?php echo basename($admission['secondary_document']); ?></a>
              <?php endif; ?>
          </div>
            </td>

            </tr>
            <tr>
              <td>Senior Secondary</td>
              <td><input type="text" name="senior_secondary_year_passing" class="form-control" value="<?php echo $admission['senior_secondary_year_passing']; ?>" required></td>
              <td><input type="text" name="senior_secondary_board_university" class="form-control" value="<?php echo $admission['senior_secondary_board_university']; ?>" required></td>
              <td><input type="text" name="senior_secondary_percentage_cgpa" class="form-control" value="<?php echo $admission['senior_secondary_percentage_cgpa']; ?>" required></td>
              <td>
              <div class="form-group">
              <input type="file" name="senior_secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['senior_secondary_document'])) echo ''; ?>>
              <?php if (!empty($admission['senior_secondary_document'])): ?>
              <br>
              <?php
              // Assuming $domain_url is the base URL you want to prepend to the file path
              $file_url = $DOMAIN_URL . htmlspecialchars($admission['senior_secondary_document']);
              ?>
              <a href="<?php echo $file_url; ?>" target="_blank"><?php echo basename($admission['senior_secondary_document']); ?></a>
              <?php endif; ?>
          </div>
            </td>
            </tr>
            <tr>
              <td>Graduation</td>
              <td><input type="text" name="graduation_year_passing" class="form-control" value="<?php echo $admission['graduation_year_passing']; ?>" ></td>
              <td><input type="text" name="graduation_board_university" class="form-control" value="<?php echo $admission['graduation_board_university']; ?>" ></td>
              <td><input type="text" name="graduation_percentage_cgpa" class="form-control" value="<?php echo $admission['graduation_percentage_cgpa']; ?>" ></td>
              <td>
              <div class="form-group">
              <input type="file" name="graduation_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['graduation_document'])) echo ''; ?>>
              <?php if (!empty($admission['graduation_document'])): ?>
              <br>
              <?php
              // Assuming $domain_url is the base URL you want to prepend to the file path
              $file_url = $DOMAIN_URL . htmlspecialchars($admission['graduation_document']);
              ?>
              <a href="<?php echo $file_url; ?>" target="_blank"><?php echo basename($admission['graduation_document']); ?></a>
              <?php endif; ?>
          </div>
                </td>
            </tr>
            <tr>
              <td>Post Graduation</td>
              <td><input type="text" name="post_graduation_year_passing" class="form-control" value="<?php echo $admission['post_graduation_year_passing']; ?>" ></td>
              <td><input type="text" name="post_graduation_board_university" class="form-control" value="<?php echo $admission['post_graduation_board_university']; ?>" ></td>
              <td><input type="text" name="post_graduation_percentage_cgpa" class="form-control" value="<?php echo $admission['post_graduation_percentage_cgpa']; ?>" ></td>
              <td>
              <div class="form-group">
              <input type="file" name="post_graduation_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['post_graduation_document'])) echo ''; ?>>
              <?php if (!empty($admission['post_graduation_document'])): ?>
              <br>
              <?php
              // Assuming $domain_url is the base URL you want to prepend to the file path
              $file_url = $DOMAIN_URL . htmlspecialchars($admission['post_graduation_document']);
              ?>
              <a href="<?php echo $file_url; ?>" target="_blank"><?php echo basename($admission['post_graduation_document']); ?></a>
              <?php endif; ?>
          </div>
            </td>
            </tr>
            <tr>
                    <td>Other</td>
                    <td><input type="text" name="other_year_passing" class="form-control" value="<?php echo $admission['other_year_passing']; ?>" ></td>
                    <td><input type="text" name="other_board_university" class="form-control" value="<?php echo $admission['other_board_university']; ?>" ></td>
                    <td><input type="text" name="other_percentage_cgpa" class="form-control" value="<?php echo $admission['other_percentage_cgpa']; ?>" ></td>
                    <td>
                    <div class="form-group">
              <input type="file" name="other_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['other_document'])) echo ''; ?>>
              <?php if (!empty($admission['other_document'])): ?>
              <br>
              <?php
              // Assuming $domain_url is the base URL you want to prepend to the file path
              $file_url = $DOMAIN_URL . htmlspecialchars($admission['other_document']);
              ?>
              <a href="<?php echo $file_url; ?>" target="_blank"><?php echo basename($admission['other_document']); ?></a>
              <?php endif; ?>
          </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <!-- Programme Details Card -->
        <div class="card mt-3">
          <div class="card-header">
            <div class="row">
              <div class="col">
                Programme Details
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                <label for="course_type">Course Type:</label>
                  <input type="text" name="course_type" class="form-control" placeholder="course_type" value="<?php echo $admission['course_type']; ?>" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="faculty">Faculty:</label>
                  <input type="text" name="faculty" class="form-control" placeholder="Faculty" value="<?php echo $admission['faculty']; ?>" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="course">Course:</label>
                  <input type="text" name="course" class="form-control" placeholder="Course" value="<?php echo $admission['course']; ?>" required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-group">
                <label for="stream">Stream:</label>
                  <input type="text" name="stream" class="form-control" placeholder="Stream" value="<?php echo $admission['stream']; ?>" required>
                </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                  <label for="year">Year:</label>
                      <select name="year" class="form-control">
                          <option value="">Select Year</option>
                          <?php
                          $currentYear = date("Y");
                          for ($year = $currentYear; $year >= 1900; $year--) {
                              $selected = ($admission['year'] == $year) ? 'selected' : '';
                              echo "<option value='$year' $selected>$year</option>";
                          }
                          ?>
                      </select>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="form-group">
                  <label for="month">Month:</label>
                      <select name="month" class="form-control" required>
                          <option value="">Select Month</option>
                          <?php
                          $months = [
                              "01" => "January",
                              "02" => "February",
                              "03" => "March",
                              "04" => "April",
                              "05" => "May",
                              "06" => "June",
                              "07" => "July",
                              "08" => "August",
                              "09" => "September",
                              "10" => "October",
                              "11" => "November",
                              "12" => "December"
                          ];
                          foreach ($months as $num => $name) {
                              $selected = ($admission['month'] == $num) ? 'selected' : '';
                              echo "<option value='$num' $selected>$name</option>";
                          }
                          ?>
                      </select>
                  </div>
              </div>

            </div>
            <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                <label for="mode_of_study">Mode Of Study:</label>
                  <input type="text" name="mode_of_study" class="form-control" placeholder="Mode of Study" value="<?php echo $admission['mode_of_study']; ?>" required>
                </div>
              </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="hostel_facility">Hostel Facility?</label>
                    <select name="hostel_facility" id="hostel_facility" class="form-control" required>
                        <option value="">Select Hostel Facility</option>
                        <option value="1" <?php echo ($admission['hostel_facility'] == '1') ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($admission['hostel_facility'] == '0') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="application_fees">Application Fees:</label>
                  <input type="text" name="application_fees" class="form-control" placeholder="Application Fees" value="<?php echo $admission['application_fees']; ?>" required>
                </div>
              </div>
            </div>
            <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                <label for="duration">Duration:</label>
                  <input type="text" name="duration" class="form-control" placeholder="Duration" value="<?php echo $admission['duration']; ?>" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="total_fees">Total Fees:</label>
                  <input type="text" name="total_fees" class="form-control" placeholder="Total Fees" value="<?php echo $admission['total_fees']; ?>" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                <label for="paying_fees">Paying Fees:</label>
                  <input type="text" name="paying_fees" class="form-control" placeholder="Paying Fees" value="<?php echo $admission['paying_fees']; ?>" required>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <!-- Submit Button -->
        <button type="submit" name="btnEdit" class="btn btn-primary">Update Admission</button>
      </form>
    </div>
  </div>
</div>
  



  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script>
    function updateIdProofNumberLength() {
      var idProofType = document.getElementsByName('id_proof_type')[0].value;
      var idProofNoInput = document.getElementsByName('id_proof_no')[0];

      if (idProofType === 'aadhaarcard') {
        idProofNoInput.setAttribute('maxlength', '12');
        idProofNoInput.setAttribute('minlength', '12');
      } else if (idProofType === 'hsc') {
        idProofNoInput.setAttribute('maxlength', '6');
        idProofNoInput.setAttribute('minlength', '6');
      } else if (idProofType === 'sslc') {
        idProofNoInput.setAttribute('maxlength', '7');
        idProofNoInput.setAttribute('minlength', '7');
      } else {
        idProofNoInput.removeAttribute('maxlength');
        idProofNoInput.removeAttribute('minlength');
      }
    }
  </script>

  <script>
    // Disable selected center option after selection
    document.getElementsByName('center_id')[0].addEventListener('change', function() {
      var selectedOption = this.options[this.selectedIndex];
      selectedOption.disabled = true;

      // Optional: Reset the select to the default option after selection
      this.value = '';
    });
  </script>
<script>
  function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
  }
</script>
<script>
    $('#edit_slide_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
