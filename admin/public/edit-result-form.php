<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
	$ID = $db->escapeString($_GET['id']);
} else {
	// $ID = "";
	return false;
	exit(0);
}
if (isset($_POST['btnEdit'])) {

	$year_semester = $db->escapeString(($_POST['year_semester']));
    $exam_month_year = $db->escapeString(($_POST['exam_month_year']));
    $total_marks = $db->escapeString(($_POST['total_marks']));
    $obtained_marks = $db->escapeString(($_POST['obtained_marks']));
    $sgpa = $db->escapeString(($_POST['sgpa']));
	$registration_no_id = $db->escapeString($_POST['registration_no_id']);
    $status = $db->escapeString(($_POST['status']));
	$error = array();

		$sql_query = "UPDATE result SET year_semester='$year_semester',exam_month_year='$exam_month_year',total_marks='$total_marks',obtained_marks='$obtained_marks',sgpa='$sgpa',status='$status',registration_no_id='$registration_no_id'  WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Result updated Successfully</span></section>";
		} else {
			$error['update_languages'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}



// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM result WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "result.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Results<small><a href='result.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Results</a></small></h1>
	<small><?php echo isset($error['update_languages']) ? $error['update_languages'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <form id="edit_languages_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
							<div class='col-md-4'>
                                   <label for="registration_no_id">Category</label> <i class="text-danger asterik">*</i>
                                    <select id='registration_no_id' name="registration_no_id" class='form-control' required>
                                      <option value="">--Select--</option>
                                      <?php
                                      $sql = "SELECT id, registration_no FROM `student`";
                                      $db->sql($sql);
                                       $result = $db->getResult();
                                      foreach ($result as $value) {
                                          ?>
                                      <option value='<?php echo $value['id'] ?>' <?php echo ($res[0]['registration_no_id'] == $value['id']) ? 'selected' : ''; ?>><?php echo $value['registration_no'] ?></option>
                                     <?php } ?>
                                  </select>
                                </div>

                                <div class='col-md-4'>
                                    <label for="year_semester">Year/Semester</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="year_semester" value="<?php echo $res[0]['year_semester']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="exam_month_year">Exam Month and Year</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="exam_month_year" value="<?php echo $res[0]['exam_month_year']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
							<div class='col-md-4'>
                                    <label for="total_marks">Total Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_marks" value="<?php echo $res[0]['total_marks']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="obtained_marks">Obtained Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="obtained_marks" value="<?php echo $res[0]['obtained_marks']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="sgpa">SGPA</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="sgpa" value="<?php echo $res[0]['sgpa']; ?>" required>
                                </div>
                            </div>
                        </div>
						<br>
						<div class="row">
                             <div class="form-group">
							 <div class='col-md-4'>
                                    <label for="status">Status</label> <i class="text-danger asterik">*</i><br>
									<label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Pass 
                                    </label>
                                    <label class="btn btn-danger" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Fail
                                    </label>
                                </div>
						     </div>
					    </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#edit_languages_form').validate({
        ignore: [],
        debug: false,
        rules: {
            year_semester: "required",
            exam_month_year: "required",
            total_marks: "required",
            obtained_marks: "required",
            sgpa: "required",
            status: "required"
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
            width: 'element',
            placeholder: 'Type in name to search',
        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>