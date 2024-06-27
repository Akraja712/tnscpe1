<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $candidate_name = $db->escapeString($_POST['candidate_name']);
    $fathers_name = $db->escapeString($_POST['fathers_name']);
    $mothers_name = $db->escapeString($_POST['mothers_name']);
    $dob = $db->escapeString($_POST['dob']);
    $gender = $db->escapeString($_POST['gender']);
    $category_id = $db->escapeString($_POST['category_id']);
    $id_proof_type = $db->escapeString($_POST['id_proof_type']);
    $id_proof_no = $db->escapeString($_POST['id_proof_no']);
    $employeed = $db->escapeString($_POST['employeed']);
    $center_id = $db->escapeString($_POST['center_id']);

    if (empty($candidate_name)) {
        $error['candidate_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($fathers_name)) {
        $error['fathers_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($mothers_name)) {
        $error['mothers_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($dob)) {
        $error['dob'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($gender)) {
        $error['gender'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($category_id)) {
        $error['category_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($id_proof_type)) {
        $error['id_proof_type'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($id_proof_no)) {
        $error['id_proof_no'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($employeed)) {
        $error['employeed'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($center_id)) {
        $error['center_id'] = " <span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image upload
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $extension = pathinfo($_FILES["image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';

        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO admission (candidate_name, image, fathers_name, mothers_name, dob, gender, category_id, id_proof_type, id_proof_no, employeed, center_id) VALUES ('$candidate_name', '$upload_image','$fathers_name','$mothers_name','$dob','$gender','$category_id','$id_proof_type','$id_proof_no','$employeed','$center_id')";
        $db->sql($sql);
    } else {
        // Image is not uploaded or empty, insert only the name
        $sql = "INSERT INTO admission (candidate_name, fathers_name, mothers_name, dob, gender, category_id, id_proof_type, id_proof_no, employeed, center_id) VALUES ('$candidate_name','$fathers_name','$mothers_name','$dob','$gender','$category_id','$id_proof_type','$id_proof_no','$employeed','$center_id')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'>
                                            <span class='label label-success'>Admission Added Successfully</span> </section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add Admission <small><a href='admission.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Admission</a></small></h1>

    <?php echo isset($error['add_slide']) ? $error['add_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_slide_form" method="post" enctype="multipart/form-data" onsubmit="return validateIdProofNumber();">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="candidate_name">Candidate Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['candidate_name']) ? $error['candidate_name'] : ''; ?>
                                        <input type="text" class="form-control" name="candidate_name" id="candidate_name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_name']) ? $error['fathers_name'] : ''; ?>
                                        <input type="text" class="form-control" name="fathers_name" id="fathers_name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="mothers_name">Mother's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['mothers_name']) ? $error['mothers_name'] : ''; ?>
                                        <input type="text" class="form-control" name="mothers_name" id="mothers_name" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i><?php echo isset($error['dob']) ? $error['dob'] : ''; ?>
                                        <input type="date" class="form-control" name="dob" id="dob" required>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                                       <select id='gender' name="gender" class='form-control' required>
                                          <option value='male'>male</option>
                                          <option value='female'>female</option>
                                          <option value='others'>others</option>
                                       </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" style="display:none"/>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="category_id">Category</label> <i class="text-danger asterik">*</i>
                                       <select id='category_id' name="category_id" class='form-control' required>
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, name FROM `category`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                            <?php } ?>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="id_proof_type">Id Proof Type</label> <i class="text-danger asterik">*</i>
                                       <select id='id_proof_type' name="id_proof_type" class='form-control' onchange="updateIdProofNumberLength()" required>
                                          <option value=''>Select Id Type</option>
                                          <option value='aadhaarcard'>Aadhaar Card</option>
                                          <option value='hsc'>HSC</option>
                                          <option value='sslc'>SSLC</option>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="id_proof_no">Id Proof No</label> <i class="text-danger asterik">*</i><?php echo isset($error['id_proof_no']) ? $error['id_proof_no'] : ''; ?>
                                        <input type="text" class="form-control" name="id_proof_no" id="id_proof_no" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="center_id">Center</label> <i class="text-danger asterik">*</i>
                                       <select id='center_id' name="center_id" class='form-control' required>
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, center_name FROM `center`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['center_name'] ?></option>
                                            <?php } ?>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="employeed">Are You Employeed</label> <i class="text-danger asterik">*</i>
                                       <select id='employeed' name="employeed" class='form-control' required>
                                          <option value='1'>Yes</option>
                                          <option value='0'>No</option>
                                       </select>
                                    </div>
                                </div>
                            </div> 
                        </div>
        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn btn-warning" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_mahabharatham_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });

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

    function updateIdProofNumberLength() {
        var idProofType = document.getElementById('id_proof_type').value;
        var idProofNo = document.getElementById('id_proof_no');

        if (idProofType == 'aadhaarcard') {
            idProofNo.setAttribute('maxlength', '12');
            idProofNo.setAttribute('minlength', '12');
        } else if (idProofType == 'hsc') {
            idProofNo.setAttribute('maxlength', '6');
            idProofNo.setAttribute('minlength', '6');
        } else if (idProofType == 'sslc') {
            idProofNo.setAttribute('maxlength', '7');
            idProofNo.setAttribute('minlength', '7');
        } else {
            idProofNo.removeAttribute('maxlength');
            idProofNo.removeAttribute('minlength');
        }
    }

    function validateIdProofNumber() {
        var idProofType = document.getElementById('id_proof_type').value;
        var idProofNo = document.getElementById('id_proof_no').value;

        if (idProofType == 'aadhaarcard' && idProofNo.length != 12) {
            alert('Aadhaar card number must be 12 digits long.');
            return false;
        } else if ((idProofType == 'hsc' || idProofType == 'sslc') && idProofNo.length != 10) {
            alert('HSC/SSLC number must be 10 digits long.');
            return false;
        }

        return true;
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
        window.location.reload();
    } 
</script>
<?php $db->disconnect(); ?>
