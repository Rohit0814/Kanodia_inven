<?php 
include_once "../action/config.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}

	$table="`attendance` t1,`worker` t2";
	$columns="t1.*,t2.name as worker_name";
	$where="t1.`shop`='$shop' and t1.`worker`=t2.`id`";
	if((isset($_GET['from1']) && isset($_GET['to1'])) && (!empty($_GET['from1']) && !empty($_GET['to1']))){
		$from=$_GET['from1']; $to=$_GET['to1'];
		$where.=" and (t1.`date` between '$from' and '$to')";
	}
	if(isset($_GET['daystatus']) && $_GET['daystatus']=='1'){
		$where.="and t1.`paid`='1'";
	}
	if(isset($_GET['daystatus']) && $_GET['daystatus']=='0'){
		$where.="and t1.`paid`='0'";
	}
	if(isset($_GET['dayworker']) && (!empty($_GET['dayworker']))){
		$where.="and t2.`name` like '$_GET[dayworker]%'";
	}
	//echo $where;
	$order="t1.`date` DESC , t1.`paid` ASC";
	$array1=$obj->get_rows($table,$columns,$where,$order);
?>
<form action="../action/updateData.php" method="post">
<table class="table-striped table-bordered table-hover table-condensed datatable1" id="daypay_list" style="width:100%">
    <thead>
    	<tr>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Worker</th>
            <th style="text-align:center">Amount</th>
            <th style="text-align:center">Remark</th>
            <th style="text-align:center">Status</th>
    	</tr>
    </thead>
	<tbody>
    <?php
    	if(is_array($array1)){
			foreach($array1 as $list){	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($list['date'])); ?></td>
    	<td align="center"><?php echo $list['worker_name']; ?></td>
    	<td align="center"><?php echo $list['amount']; ?></td>
    	<td align="center"><?php echo $list['remark']; ?></td>
        <td>
			<?php 
				if($list['paid']==0){ ?>
					<span class='text-danger'>Unpaid</span> 
					<input type="checkbox" name="attendance_id[]" class="form-control-checkbox attendancecheckbox" value="<?= $list['id']; ?>" data-total="<?= $list['amount']; ?>">
			<?php }else{
					echo "<span class='text-success'>Paid</span>";  
				} ?>
      	</td>		
    </tr>
    <?php }	} ?>
	</tbody>
    <b id="attendance_total" class="text-danger" style="font-size:15px;"></b>&nbsp;&nbsp;&nbsp;
	<button type="submit" name="payattendance" id="payattendance" class="btn btn-xs btn-success" style="display:none;">Pay Amount</button>
</table>
</form>