<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])) {
	$center_name = $db->escapeString($_POST['center_name']);
    $center_code = $db->escapeString($_POST['center_code']);
    $director_name = $db->escapeString($_POST['director_name']);
    $mobile_number = $db->escapeString($_POST['mobile_number']);
    $whatsapp_number = $db->escapeString($_POST['whatsapp_number']);
    $email_id = $db->escapeString($_POST['email_id']);
    $institute_address = $db->escapeString($_POST['institute_address']);
    $city = $db->escapeString($_POST['city']);
    $state = $db->escapeString($_POST['state']);
    $country = $db->escapeString($_POST['country']);
    $password = $db->escapeString($_POST['password']);

    $sql = "UPDATE center SET center_name='$center_name', center_code='$center_code', director_name='$director_name', mobile_number='$mobile_number', whatsapp_number='$whatsapp_number', email_id='$email_id', institute_address='$institute_address', city='$city', state='$state', country='$country', password='$password' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Center Updated Successfully</span>";
    }

    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Cannot upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = $full_path;
        $sql = "UPDATE center SET image='$upload_image' WHERE id='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Center updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$sql_query = "SELECT * FROM `center` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
if (!isset($res[0])) {
    echo '<p class="alert alert-danger">Center not found.</p>';
    exit();
}
?>

<section class="content-header">
    <h1>Edit Center <small><a href='center.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Center</a></small></h1>
    <?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <form id='edit_slide_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>">
					<div class="row">
                            <div class="form-group">
                                <div class='col-md-3'>
                                    <label for="center_name">Center Name</label> <?php echo isset($error['center_name']) ? $error['center_name'] : ''; ?>
                                    <input type="text" class="form-control" name="center_name" id="center_name" value="<?php echo isset($res[0]['center_name']) ? $res[0]['center_name'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="center_code">Center Code</label> <?php echo isset($error['center_code']) ? $error['center_code'] : ''; ?>
                                    <input type="text" class="form-control" name="center_code" id="center_code" value="<?php echo isset($res[0]['center_code']) ? $res[0]['center_code'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="director_name">Director Name</label> <?php echo isset($error['director_name']) ? $error['director_name'] : ''; ?>
                                    <input type="text" class="form-control" name="director_name" id="director_name" value="<?php echo isset($res[0]['director_name']) ? $res[0]['director_name'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="mobile_number">Mobile Number</label> <?php echo isset($error['mobile_number']) ? $error['mobile_number'] : ''; ?>
                                    <input type="number" class="form-control" name="mobile_number" id="mobile_number" value="<?php echo isset($res[0]['mobile_number']) ? $res[0]['mobile_number'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-3'>
                                    <label for="whatsapp_number">Whatsapp Number</label> <?php echo isset($error['whatsapp_number']) ? $error['whatsapp_number'] : ''; ?>
                                    <input type="number" class="form-control" name="whatsapp_number" id="whatsapp_number" value="<?php echo isset($res[0]['whatsapp_number']) ? $res[0]['whatsapp_number'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="email_id">Email ID</label> <?php echo isset($error['email_id']) ? $error['email_id'] : ''; ?>
                                    <input type="email" class="form-control" name="email_id" id="email_id" value="<?php echo isset($res[0]['email_id']) ? $res[0]['email_id'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="institute_address">Institute Address</label> <?php echo isset($error['institute_address']) ? $error['institute_address'] : ''; ?>
                                    <input type="text" class="form-control" name="institute_address" id="institute_address" value="<?php echo isset($res[0]['institute_address']) ? $res[0]['institute_address'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="city">City</label> <?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                    <input type="text" class="form-control" name="city" id="city" value="<?php echo isset($res[0]['city']) ? $res[0]['city'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-3'>
                                    <label for="state">State</label> <?php echo isset($error['state']) ? $error['state'] : ''; ?>
                                    <input type="text" class="form-control" name="state" id="state" value="<?php echo isset($res[0]['state']) ? $res[0]['state'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="country">Country</label> <?php echo isset($error['country']) ? $error['country'] : ''; ?>
                                    <input type="text" class="form-control" name="country" id="country" value="<?php echo isset($res[0]['country']) ? $res[0]['country'] : ''; ?>" required>
                                </div>
                                <div class='col-md-3'>
                                    <label for="password">Password</label> <?php echo isset($error['password']) ? $error['password'] : ''; ?>
                                    <input type="text" class="form-control" name="password" id="password" value="<?php echo isset($res[0]['password']) ? $res[0]['password'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="image">Photo</label>
                                    <?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" required/><br>
                                    <img id="blah" src="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>" alt="Current Image" style="display:block; max-width:150px; max-height:200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
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