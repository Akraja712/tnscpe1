<?php
session_start();
require 'db.php'; // Ensure db.php includes your database connection

$DOMAIN_URL = "https://tnscpe.graymatterworks.com/";

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

// Fetch categories from the database
$sql_categories = "SELECT id, name FROM category";
$result_categories = $conn->query($sql_categories);

// Generate options for the category dropdown
$category_options = '';
if ($result_categories && $result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $id = $row['id'];
        $name = htmlspecialchars($row['name']);
        $category_options .= "<option value='$id'>$name</option>";
    }
}

// Fetch centers from the database
$sql_centers = "SELECT id, center_name FROM center"; // Adjust table name as per your database structure
$result_centers = $conn->query($sql_centers);

// Generate options for the center dropdown
$center_options = '';
if ($result_centers && $result_centers->num_rows > 0) {
    while ($row = $result_centers->fetch_assoc()) {
        $center_id = $row['id'];
        $center_name_db = htmlspecialchars($row['center_name']);
        // Disable the option if it matches the center_name fetched from URL center_code
        $disabled = ($center_name_db === $center_name) ? 'disabled' : '';
        $center_options .= "<option value='$center_id' $disabled>$center_name_db</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $candidate_name = mysqli_real_escape_string($conn, $_POST['candidate_name']);
    $fathers_name = mysqli_real_escape_string($conn, $_POST['fathers_name']);
    $mothers_name = mysqli_real_escape_string($conn, $_POST['mothers_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $id_proof_type = mysqli_real_escape_string($conn, $_POST['id_proof_type']);
    $id_proof_no = mysqli_real_escape_string($conn, $_POST['id_proof_no']);
    $employeed = ($_POST['employeed'] == 'Yes') ? 1 : 0;
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
    $secondary_document = mysqli_real_escape_string($conn, $_POST['secondary_document']);
    $senior_secondary_year_passing = mysqli_real_escape_string($conn, $_POST['senior_secondary_year_passing']);
    $senior_secondary_board_university = mysqli_real_escape_string($conn, $_POST['senior_secondary_board_university']);
    $senior_secondary_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['senior_secondary_percentage_cgpa']);
    $senior_secondary_document = mysqli_real_escape_string($conn, $_POST['senior_secondary_document']);
    $graduation_year_passing = mysqli_real_escape_string($conn, $_POST['graduation_year_passing']);
    $graduation_board_university = mysqli_real_escape_string($conn, $_POST['graduation_board_university']);
    $graduation_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['graduation_percentage_cgpa']);
    $graduation_document = mysqli_real_escape_string($conn, $_POST['graduation_document']);
    $post_graduation_year_passing = mysqli_real_escape_string($conn, $_POST['post_graduation_year_passing']);
    $post_graduation_board_university = mysqli_real_escape_string($conn, $_POST['post_graduation_board_university']);
    $post_graduation_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['post_graduation_percentage_cgpa']);
    $post_graduation_document = mysqli_real_escape_string($conn, $_POST['post_graduation_document']);
    $other_year_passing = mysqli_real_escape_string($conn, $_POST['other_year_passing']);
    $other_board_university = mysqli_real_escape_string($conn, $_POST['other_board_university']);
    $other_percentage_cgpa = mysqli_real_escape_string($conn, $_POST['other_percentage_cgpa']);
    $other_document = mysqli_real_escape_string($conn, $_POST['other_document']);

    // Program details
    $course_type = mysqli_real_escape_string($conn, $_POST['course_type']);
    $faculty = mysqli_real_escape_string($conn, $_POST['faculty']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $stream = mysqli_real_escape_string($conn, $_POST['stream']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);
    $mode_of_study = mysqli_real_escape_string($conn, $_POST['mode_of_study']);
    $hostel_facility = ($_POST['hostel_facility'] == 'Yes') ? 1 : 0;
    $application_fees = mysqli_real_escape_string($conn, $_POST['application_fees']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $total_fees = mysqli_real_escape_string($conn, $_POST['total_fees']);
    $paying_fees = mysqli_real_escape_string($conn, $_POST['paying_fees']);

    function handle_upload($file_input_name, $target_dir, $root_dir, $file_type) {
      if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
          $temp_name = $_FILES[$file_input_name]["tmp_name"];
          $file_info = pathinfo($_FILES[$file_input_name]["name"]);
          $extension = strtolower($file_info['extension']);
          
          // Check if it's an image or document based on file type
          if ($file_type == 'image') {
              $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
              $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
              $upload_dir = $root_dir . 'images/';
          } elseif ($file_type == 'document') {
              $allowed_extensions = array('pdf', 'doc', 'docx', 'txt');
              $allowed_mime_types = array('application/pdf', 'application/msword', 'text/plain');
              $upload_dir = $root_dir . 'documents/';
          } else {
              return ''; // Invalid file type
          }
  
          // Validate extension and MIME type
          if (!in_array($extension, $allowed_extensions) || !in_array($_FILES[$file_input_name]['type'], $allowed_mime_types)) {
              return ''; // Invalid file extension or MIME type
          }
  
          $filename = uniqid() . '.' . $extension;
          $target_path = $upload_dir . $filename;
  
          if (move_uploaded_file($temp_name, $target_path)) {
              return $target_path; // Return the full path to the uploaded file
          } else {
              // Handle move_uploaded_file failure (e.g., check directory permissions)
              error_log("Failed to move uploaded file: $temp_name to $target_path");
          }
      } else {
          // Handle upload conditions not met (e.g., file size is 0)
          return '';
      }
      return '';
  }
  $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/admin/';

  // Handle image upload into admin/images/
  $image = handle_upload('image', 'images/', $root_dir, 'image');
  
  // Handle document uploads into admin/documents/
  $secondary_document = handle_upload('secondary_document', 'documents/', $root_dir, 'document');
  $senior_secondary_document = handle_upload('senior_secondary_document', 'documents/', $root_dir, 'document');
  $graduation_document = handle_upload('graduation_document', 'documents/', $root_dir, 'document');
  $post_graduation_document = handle_upload('post_graduation_document', 'documents/', $root_dir, 'document');
  $other_document = handle_upload('other_document', 'documents/', $root_dir, 'document');
    
            $sql = "INSERT INTO admission (candidate_name, image, fathers_name, mothers_name, dob, gender, category_id, id_proof_type, id_proof_no, employeed, center_id, contact_number, email, fathers_contact_number, mothers_contact_number, country, nationality, state, city, address, pincode, secondary_year_passing, secondary_board_university, secondary_percentage_cgpa, secondary_document, senior_secondary_year_passing, senior_secondary_board_university, senior_secondary_percentage_cgpa, senior_secondary_document, graduation_year_passing, graduation_board_university, graduation_percentage_cgpa, graduation_document, post_graduation_year_passing, post_graduation_board_university, post_graduation_percentage_cgpa, post_graduation_document, other_year_passing, other_board_university, other_percentage_cgpa, other_document, course_type, faculty, course, stream, year,month, mode_of_study, hostel_facility, application_fees, duration, total_fees, paying_fees) 
            VALUES ('$candidate_name', '$upload_image', '$fathers_name', '$mothers_name', '$dob', '$gender', '$category_id', '$id_proof_type', '$id_proof_no', '$employeed', '$center_id', '$contact_number', '$email', '$fathers_contact_number', '$mothers_contact_number', '$country', '$nationality', '$state', '$city', '$address', '$pincode', '$secondary_year_passing', '$secondary_board_university', '$secondary_percentage_cgpa', '$secondary_document', '$senior_secondary_year_passing', '$senior_secondary_board_university', '$senior_secondary_percentage_cgpa', '$senior_secondary_document', '$graduation_year_passing', '$graduation_board_university', '$graduation_percentage_cgpa', '$graduation_document', '$post_graduation_year_passing', '$post_graduation_board_university', '$post_graduation_percentage_cgpa', '$post_graduation_document', '$other_year_passing', '$other_board_university', '$other_percentage_cgpa', '$other_document', '$course_type', '$faculty', '$course', '$stream', '$year','$month', '$mode_of_study', '$hostel_facility', '$application_fees', '$duration', '$total_fees', '$paying_fees')";

if ($conn->query($sql) === TRUE) {
  // Redirect with success message
  $_SESSION['admission_added'] = true;
  header("Location: admission.php?center_code=" . $_SESSION['center_code']);
  exit();
} else {
  echo '<p class="alert alert-danger">Error: ' . $sql . '<br>' . $conn->error . '</p>';
}
}

// Close database connection
$conn->close();
?>>close();
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

      <div class="container">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          Admission Details
        </div>
      </div>
    </div>
    <div class="card-body">
      <form action="add_admission.php" method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="candidate_name" class="form-control" placeholder="Candidate Name" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="fathers_name" class="form-control" placeholder="Father's Name" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="mothers_name" class="form-control" placeholder="Mother's Name" required>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="date" name="dob" class="form-control" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select name="gender" class="form-control" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php echo $category_options; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
              <select name="id_proof_type" class="form-control" required onchange="updateIdProofNumberLength()">
                <option value="">Select ID Type</option>
                <option value="aadhaarcard">Aadhaar Card</option>
                <option value="hsc">HSC</option>
                <option value="sslc">SSLC</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" name="id_proof_no" class="form-control" placeholder="ID Proof No" required>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <select name="employeed" class="form-control" required>
                <option value="">Employeed?</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-4">
            <div class="form-group">
              <input type="text" class="form-control" value="<?php echo $center_name; ?>" disabled>
              <input type="hidden" name="center_id" value="<?php echo $center_id; ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <input type="file" name="image" class="form-control-file" accept="image/*" required>
            </div>
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
            <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="fathers_contact_number" class="form-control" placeholder="Father's Contact Number" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="mothers_contact_number" class="form-control" placeholder="Mother's Contact Number" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="country" class="form-control" placeholder="Country" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="nationality" class="form-control" placeholder="Nationality" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="state" class="form-control" placeholder="State" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="city" class="form-control" placeholder="City" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="address" class="form-control" placeholder="Address" required>
          </div>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-4">
          <div class="form-group">
            <input type="text" name="pincode" class="form-control" placeholder="Pincode" required>
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
              <td><input type="text" name="secondary_year_passing" class="form-control" required></td>
              <td><input type="text" name="secondary_board_university" class="form-control" required></td>
              <td><input type="text" name="secondary_percentage_cgpa" class="form-control" required></td>
              <td><input type="file" name="secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" required></td>
            </tr>
            <tr>
              <td>Senior Secondary</td>
              <td><input type="text" name="senior_secondary_year_passing" class="form-control" required></td>
              <td><input type="text" name="senior_secondary_board_university" class="form-control" required></td>
              <td><input type="text" name="senior_secondary_percentage_cgpa" class="form-control" required></td>
              <td><input type="file" name="senior_secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" required></td>
            </tr>
            <tr>
              <td>Graduation</td>
              <td><input type="text" name="graduation_year_passing" class="form-control" ></td>
              <td><input type="text" name="graduation_board_university" class="form-control" ></td>
              <td><input type="text" name="graduation_percentage_cgpa" class="form-control" ></td>
              <td><input type="file" name="graduation_document" class="form-control-file" accept=".pdf,.doc,.docx" ></td>
            </tr>
            <tr>
              <td>Post Graduation</td>
              <td><input type="text" name="post_graduation_year_passing" class="form-control"></td>
              <td><input type="text" name="post_graduation_board_university" class="form-control"></td>
              <td><input type="text" name="post_graduation_percentage_cgpa" class="form-control"></td>
              <td><input type="file" name="post_graduation_document" class="form-control-file" accept=".pdf,.doc,.docx"></td>
            </tr>
            <tr>
                    <td>Other</td>
                    <td><input type="text" name="other_year_passing" class="form-control" ></td>
                    <td><input type="text" name="other_board_university" class="form-control" ></td>
                    <td><input type="text" name="other_percentage_cgpa" class="form-control" ></td>
                    <td><input type="file" name="other_document" class="form-control-file" accept=".pdf,.doc,.docx" ></td>
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
                  <input type="text" name="course_type" class="form-control" placeholder="course_type" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="faculty" class="form-control" placeholder="Faculty" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="course" class="form-control" placeholder="Course" required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="stream" class="form-control" placeholder="Stream" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    <select name="year" class="form-control" required>
                        <option value="">Select Year</option>
                        <?php
                        $currentYear = date("Y");
                        for ($year = $currentYear; $year >= 1900; $year--) {
                            echo "<option value='$year'>$year</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
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
                            echo "<option value='$num'>$name</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="mode_of_study" class="form-control" placeholder="Mode of Study" required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-group">
                  <select name="hostel_facility" class="form-control" required>
                    <option value="">Hostel Facility?</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="application_fees" class="form-control" placeholder="Application Fees" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="duration" class="form-control" placeholder="Duration" required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="total_fees" class="form-control" placeholder="Total Fees" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="paying_fees" class="form-control" placeholder="Paying Fees" required>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
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
</body>
</html>
