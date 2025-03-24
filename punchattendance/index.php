<?php
session_start();
if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
	$role = $_SESSION['role'];
	$shop = $_SESSION['shop'];
} else {
	header("Location:index.php");
	echo "<script>location='index.php'</script>";
}
include_once "../action/config.php";
$obj = new database();
?>
<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">


	<!-- Bootstrap Core CSS -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- Custom CSS -->
	<link href="../css/style.css" rel="stylesheet">
	<!-- Bootstrap Core JavaScript -->
	<script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.css" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.2.1/js/dataTables.searchBuilder.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
	<style>
		.selwid,
		.consume,
		.selraw,
		.used,
		.selworker,
		.charge,
		.pattern {
			position: relative;
			float: left;
			width: 32%;
			margin: 2px;
		}
	</style>
	<title>Master Key</title>
</head>

<body>
<?php include("../header.php"); ?>
<div class="container">
<div class="row">
    <div class="col-md-12" id="addattendance" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_attendance">Add Attendance</font>
                <font size="+1" class="update_attendance" style="display:none;">Update Attendance</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('attendancelist','addattendance');">View Attendance List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php?pagename=punch" id="AttendanceForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                <tr>
                    <th>Date</th>
                        <td><input type="date" name="date" id="att_date" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" class="form-control"></td>
                    </tr>
                    <tr>
                        <th width="20%">Select Worker</th>
                        <td>
                            <select name="worker[]" id="att_worker" class="form-control" multiple required>
                            	<option value="" disabled>Select Worker</option>
                                <?php $worker=$obj->get_rows("`worker`","`id`,`name`","`active`=1" ,'name asc'); foreach($worker as $w){ ?>
                            	    <option value="<?= $w['id']; ?>"><?= $w['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Remark (Optional)</th>
                        <td><textarea name="remark" id="remark" placeholder="Enter Remark" class="form-control"></textarea>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="attendanceid">
                            <input type="submit" class="btn btn-success btn-sm save_attendance" name="save_attendance" id="save_width" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_attendance" name="update_attendance" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_attendance" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="attendancelist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px"><font size="+1">Attendance List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addattendance','attendancelist');">Add Attendance</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed datatable" width="100%">
                    <thead>
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Date</th>
                        <th style="text-align:center;">Worker</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center;">Remark</th>
                        <!-- <th style="text-align:center;" width="15%">Action</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $table="`attendance`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`date` DESC";
                        $attendancelist=$obj->get_rows($table,$columns,$where,$order);
                        if(is_array($attendancelist)){$i=0;
                            foreach($attendancelist as $attendance){$i++;
	                            $worker=$obj->get_details("`worker`","*","`id`='$attendance[worker]'");
                            ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $attendance['date']; ?></td>
                        <td align="center"><?php echo $worker['name']; ?></td>
                        <td align="center"><?php 
                        if($attendance['status']==1 && $attendance['date'] == date('Y-m-d')){
                            echo "<a class='btn btn-success' href='../action/insertData.php?pagename=checkout&id=".$attendance['id']."'>CheckIn</a>";
                        }
                        else{
                            echo "<a class='btn btn-danger'>"."CheckOUT"."</a>";
                        }
                        ?></td>
                        <td align="center"><?php echo $attendance['remark']; ?></td>
                        <!-- <td align="center">
                            <?php if($attendance['paid']=='0'){ ?>
                                <button type="button" class="btn btn-primary btn-xs" onClick="editAttendance('<?php echo $attendance['id']; ?>');"><i class="fa fa-edit"></i></button>
                            <?php } ?>
                        </td> -->
                    </tr>
                    <?php } }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script>
        function showThis(str1, str2) {
			var div1 = "#" + str1;
			var div2 = "#" + str2;
			$(div1).show();
			$(div2).hide();
		}
        $('#att_worker').select2({
				placeholder: {
					id: '',
					text: '-- Select Multiple Worker --'
				},
				width: '100%'
			});
    </script>
</body>

</html>