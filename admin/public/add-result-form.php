<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $year_semester = $db->escapeString($_POST['year_semester']);
    $exam_month_year = $db->escapeString($_POST['exam_month_year']);
    $total_marks = $db->escapeString($_POST['total_marks']);
    $obtained_marks = $db->escapeString($_POST['obtained_marks']);
    $sgpa = $db->escapeString($_POST['sgpa']);
    $registration_no_id = $db->escapeString($_POST['registration_no_id']);
    $status = isset($_POST['status']) ? $db->escapeString($_POST['status']) : '';

    $error = array();

    if (empty($year_semester)) {
        $error['year_semester'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($exam_month_year)) {
        $error['exam_month_year'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($total_marks)) {
        $error['total_marks'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($obtained_marks)) {
        $error['obtained_marks'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($sgpa)) {
        $error['sgpa'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($registration_no_id)) {
        $error['registration_no_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if ($status === '') {
        $error['status'] = " <span class='label label-danger'>Required!</span>";
    }

    if (!empty($year_semester) && !empty($exam_month_year) && !empty($total_marks) && !empty($obtained_marks) && !empty($sgpa) && !empty($registration_no_id) && $status !== '') {
        $sql_query = "INSERT INTO result (year_semester, exam_month_year, total_marks, obtained_marks, sgpa, status,registration_no_id) VALUES ('$year_semester', '$exam_month_year', '$total_marks', '$obtained_marks', '$sgpa', '$status','$registration_no_id')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $error['add_languages'] = "<section class='content-header'><span class='label label-success'>Result Added Successfully</span></section>";
        } else {
            $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>

<section class="content-header">
    <h1>Add New Results <small><a href='result.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Results</a></small></h1>
    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <form method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-4'>
                                       <label for="registration_no_id">Registration No</label> <i class="text-danger asterik">*</i>
                                       <select id='registration_no_id' name="registration_no_id" class='form-control' required>
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, registration_no FROM `student`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['registration_no'] ?></option>
                                            <?php } ?>
                                       </select>
                                </div>
                                <div class='col-md-4'>
                                    <label for="year_semester">Year/Semester</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="year_semester" required>
                                   
                                </div>
                                <div class='col-md-4'>
                                    <label for="exam_month_year">Exam Month and Year</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="exam_month_year" required>
                                     
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-4'>
                                    <label for="total_marks">Total Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="total_marks" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="obtained_marks">Obtained Marks</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="obtained_marks" required>
                                   
                                </div>
                                <div class='col-md-4'>
                                    <label for="sgpa">SGPA</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="sgpa" required>
                                    
                                </div>
                            </div>
                        </div>
                        <br>
                           <div class="row">
                                <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="status">Status</label> <i class="text-danger asterik">*</i><br>
                                    <div class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1"> Pass
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0"> Fail
                                        </label>
                                    </div>
                                    <?php echo isset($error['status']) ? $error['status'] : ''; ?>
                                </div>
                                </div>
                            </div> 
                      </div>
                    <br>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn btn-warning" value="Clear" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
            width: 'element',
            placeholder: 'Type in name to search',
        });
    });

    function refreshPage() {
        window.location.reload();
    }

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<?php $db->disconnect(); ?>
