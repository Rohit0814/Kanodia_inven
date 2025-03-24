<?php
if(isset($_SESSION['user'])){
  $user=$_SESSION['user'];
  $role=$_SESSION['role'];
  $shop=$_SESSION['shop'];
}
else{
	 header("Location:index.php");
	 echo "<script>location='index.php'</script>"; 
}
include_once "../action/config.php";
  $obj=new database();
  $worker=$obj->get_rows("`worker`","`id`,`name`","`shop`='$shop' AND `payment_type`='day_wise'" ,'name asc');
?>
<div class="row">
    <div class="col-md-12" id="addattendance" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_attendance">Add Attendance</font>
                <font size="+1" class="update_attendance" style="display:none;">Update Attendance</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('attendancelist','addattendance');">View Attendance List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="AttendanceForm">
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
                                <?php foreach($worker as $w){ ?>
                            	    <option value="<?= $w['id']; ?>"><?= $w['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Remark</th>
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
                        <th style="text-align:center;">Amount</th>
                        <th style="text-align:center;">Remark</th>
                        <!-- <th style="text-align:center;" width="15%">Action</th> -->
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $table="`attendance`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`id`";
                        $attendancelist=$obj->get_rows($table,$columns,$where,$order);
                        if(is_array($attendancelist)){$i=0;
                            foreach($attendancelist as $attendance){$i++;
	                            $worker=$obj->get_details("`worker`","*","`id`='$attendance[worker]'");
                            ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $attendance['date']; ?></td>
                        <td align="center"><?php echo $worker['name']; ?></td>
                        <td align="center"><?php echo $attendance['amount']; ?></td>
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