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

// Fetch center details including center_id
$sql_center = "SELECT id, center_name FROM center WHERE center_name = '$center_name'";
$result_center = $conn->query($sql_center);

if ($result_center && $result_center->num_rows > 0) {
    $row = $result_center->fetch_assoc();
    $center_id = $row['id'];
} else {
    $center_id = ''; // Handle case where center_id is not found
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
    $employeed = mysqli_real_escape_string($conn, $_POST['employeed']);
    $center_id = mysqli_real_escape_string($conn, $_POST['center_id']); // Capture center_id from POST
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
    $hostel_facility = mysqli_real_escape_string($conn, $_POST['hostel_facility']);
    $application_fees = mysqli_real_escape_string($conn, $_POST['application_fees']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $total_fees = mysqli_real_escape_string($conn, $_POST['total_fees']);
    $paying_fees = mysqli_real_escape_string($conn, $_POST['paying_fees']);

    function handle_image_upload($file_input_name, $target_dir, $root_dir) {
      if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
          $temp_name = $_FILES[$file_input_name]["tmp_name"];
          $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
          $filename = uniqid() . '.' . strtolower($extension);
          $target_path = $root_dir . '/' . $target_dir . $filename;
          if (move_uploaded_file($temp_name, $target_path)) {
              return $target_dir . $filename;
          }
      }
      return '';
  }
  function handle_document_upload($file_input_name, $target_dir, $root_dir) {
    if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
        $temp_name = $_FILES[$file_input_name]["tmp_name"];
        $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . strtolower($extension);
        $target_path = $root_dir . '/' . $target_dir . $filename;
        if (move_uploaded_file($temp_name, $target_path)) {
            return $target_dir . $filename;
        }
    }
    return '';
}
   // Define the root directory
   $root_dir = $_SERVER['DOCUMENT_ROOT'] . '/admin/';

   // Upload image
   $image_target_dir = 'upload/images/';
   $upload_image = handle_image_upload('image', $image_target_dir, $root_dir);

   // Upload documents
   $document_target_dir = 'upload/documents/';
   $secondary_document = handle_document_upload('secondary_document', $document_target_dir, $root_dir);
   $senior_secondary_document = handle_document_upload('senior_secondary_document', $document_target_dir, $root_dir);
   $graduation_document = handle_document_upload('graduation_document', $document_target_dir, $root_dir);
   $post_graduation_document = handle_document_upload('post_graduation_document', $document_target_dir, $root_dir);
   $other_document = handle_document_upload('other_document', $document_target_dir, $root_dir);



  
$sql = "INSERT INTO admission (candidate_name, image, fathers_name, mothers_name, dob, gender, category_id, id_proof_type, id_proof_no, employeed, center_id, contact_number, email, fathers_contact_number, mothers_contact_number, country, nationality, state, city, address, pincode, secondary_year_passing, secondary_board_university, secondary_percentage_cgpa, secondary_document, senior_secondary_year_passing, senior_secondary_board_university, senior_secondary_percentage_cgpa, senior_secondary_document, graduation_year_passing, graduation_board_university, graduation_percentage_cgpa, graduation_document, post_graduation_year_passing, post_graduation_board_university, post_graduation_percentage_cgpa, post_graduation_document, other_year_passing, other_board_university, other_percentage_cgpa, other_document, course_type, faculty, course, stream, year, month, mode_of_study, hostel_facility, application_fees, duration, total_fees, paying_fees) 
      VALUES ('$candidate_name', '$upload_image', '$fathers_name', '$mothers_name', '$dob', '$gender', '$category_id', '$id_proof_type', '$id_proof_no', '$employeed', '$center_id', '$contact_number', '$email', '$fathers_contact_number', '$mothers_contact_number', '$country', '$nationality', '$state', '$city', '$address', '$pincode', '$secondary_year_passing', '$secondary_board_university', '$secondary_percentage_cgpa', '$secondary_document', '$senior_secondary_year_passing', '$senior_secondary_board_university', '$senior_secondary_percentage_cgpa', '$senior_secondary_document', '$graduation_year_passing', '$graduation_board_university', '$graduation_percentage_cgpa', '$graduation_document', '$post_graduation_year_passing', '$post_graduation_board_university', '$post_graduation_percentage_cgpa', '$post_graduation_document', '$other_year_passing', '$other_board_university', '$other_percentage_cgpa', '$other_document', '$course_type', '$faculty', '$course', '$stream', '$year', '$month', '$mode_of_study', '$hostel_facility', '$application_fees', '$duration', '$total_fees', '$paying_fees')";

// Execute SQL query and handle success or failure
if ($conn->query($sql) === TRUE) {
  // Redirect with success message
  $_SESSION['action_message'] = 'New Admission record added successfully!';
  $_SESSION['admission_added'] = true;
  header("Location: admission.php?center_code=" . $_SESSION['center_code']);
  exit();
} else {
  // Display error message
  echo '<p class="alert alert-danger">Error: ' . $sql . '<br>' . $conn->error . '</p>';
}
}
// Fetch success message if available
$success_message = isset($_SESSION['action_message']) ? $_SESSION['action_message'] : '';
unset($_SESSION['action_message']); // Clear the session variable after displaying

// Close database connection
$conn->close();

// Function to handle file uploads (you should define this function if not already defined)
function handle_upload($file_field, $upload_dir, $root_dir) {
  // Implementation of file handling logic
  // You can implement this function according to your specific needs
  // This is just a placeholder for demonstration purposes
  return ""; // Placeholder return
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
            <select name="gender" class="form-control" required>
            <option value="0">Select Category</option>
                <option value="1">General</option>
                <option value="2">SC</option>
                <option value="3">ST</option>
                <option value="4">BC</option>
                <option value="5">SBC</option>
                <option value="6">OBC</option>
                <option value="7">EBC</option>
                <option value="8">PH</option>
                <option value="9">Ex-Servicemen</option>
                <option value="10">Other</option>
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
            <label for="center_id">Center Name:</label>
              <input type="text" class="form-control" name = "center_id" id="center_id" value="<?php echo $center_name; ?>" disabled>
              <input type="hidden" name="center_id" id="center_id" value="<?php echo $center_id; ?>">
            </div>
          </div>
          <div class="col-md-4">
              <label for="image">Photo:</label><?php echo isset($error['image']) ? $error['image'] : ''; ?>
              <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
              <img id="blah" src="#" alt="" style="display:none"/>
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
            </div>
            <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="mode_of_study" class="form-control" placeholder="Mode of Study" required>
                </div>
              </div>
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
            </div>
            <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                  <input type="text" name="duration" class="form-control" placeholder="Duration" required>
                </div>
              </div>
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
<script>
      function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
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
