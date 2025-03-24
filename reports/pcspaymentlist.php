<?php 
include_once "../action/config.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}
	$table="`payment` t1, `cutting` t2 ,`worker` t3";
	$columns="t1.*,t2.id as cutting_id,t2.`finalbedsheet` as bedsheet_qty,t2.`finalpillow` as pillow_qty,t2.`date`,t2.`paid`,t3.name as worker_name";
	$where="t1.`worker`=t2.`worker` and t1.`work_type`='Cutting' and t2.shop='$shop' and t1.`worker`=t3.`id`";
	if((isset($_GET['from']) && isset($_GET['to'])) && (!empty($_GET['from']) && !empty($_GET['to']))){
		$from=$_GET['from']; $to=$_GET['to'];
		$where.=" and (t2.`date` between '$from' and '$to')";
	}else if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='1'){
		$where.="and t2.`paid`=1";
	}else if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='0'){
		$where.="and t2.`paid`=0";
	}else if(isset($_GET['pcsworker']) && !empty($_GET['pcsworker'])){
		$where.=" and t3.`name` like '$_GET[pcsworker]%'";
	}
	$order="t2.`id`";
	$array1=$obj->get_rows($table,$columns,$where,$order);

	$table2="`payment` t1, `stitching_detail` t2,`worker` t3";
	$columns2="t1.*,t2.id as stitching_id,t2.job,t2.`qty`,t2.`date`,t2.`paid`,t3.name as worker_name";
	$where2="t1.`worker`=t2.`worker` and t1.`work_type`='Stitching' and t2.job!='Zigzag' and t1.`shop`='$shop' and t1.`worker`=t3.`id`";
	if((isset($_GET['from']) && isset($_GET['to'])) && (!empty($_GET['from']) && !empty($_GET['to']))){
		$from=$_GET['from']; $to=$_GET['to'];
		$where2.=" and (t2.`date` between '$from' and '$to')";
	}if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='1'){
		$where2.="and t2.`paid`=1";
	}
	if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='0'){
		$where2.="and t2.`paid`=0";
	}
	if(isset($_GET['pcsworker']) && !empty($_GET['pcsworker'])){
		$where2.=" and t3.`name` like '$_GET[pcsworker]%'";
	}
	$order2="t2.`id`";
	$array2=$obj->get_rows($table2,$columns2,$where2,$order2);

	$table3="`payment` t1, `stitching_detail` t2,`worker` t3";
	$columns3="t1.*,t2.id as zigzag_id,t2.job,t2.`qty`,t2.`date`,t2.`paid`,t3.name as worker_name";
	$where3="t1.`worker`=t2.`worker` and t1.`work_type`='Zigzag' and t2.job='Zigzag' and t1.shop='$shop' and t1.`worker`=t3.`id`";
	if((isset($_GET['from']) && isset($_GET['to'])) && (!empty($_GET['from']) && !empty($_GET['to']))){
		$from=$_GET['from']; $to=$_GET['to'];
		$where3.=" and (t2.`date` between '$from' and '$to')";
	}else if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='1'){
		$where3.="and t2.`paid`=1";
	}
	else if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='0'){
		$where3.="and t2.`paid`=0";
	}else if(isset($_GET['pcsworker']) && !empty($_GET['pcsworker'])){
		$where3.=" and t3.`name` like '$_GET[pcsworker]%'";
	}
	$order3="t2.`id`";
	$array3=$obj->get_rows($table3,$columns3,$where3,$order3);
?>
<p>Cutting Recoard</p><hr>
<form action="../action/updateData.php" method="post">
<table class="table-striped table-bordered table-hover table-condensed datatable" id="daypay_list" style="width:100%">
    <thead>
    	<tr>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Worker</th>
            <th style="text-align:center">Work</th>
            <th style="text-align:center">Bedsheet Qty.</th>
            <th style="text-align:center">Pillow Qty.</th>
            <th style="text-align:center">Bedsheet Rate(/pcs.)</th>
            <th style="text-align:center">Pilow Rate(/pcs.)</th>
            <th style="text-align:center">Total</th>
            <th style="text-align:center">Status</th>
    	</tr>
    </thead>
	<tbody>
    <?php
	$grand_total=0;
    	if(is_array($array1)){
			foreach($array1 as $list){			
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($list['date'])); ?></td>
    	<td align="center"><?php echo $list['worker_name']; ?></td>
    	<td align="center"><?php echo $list['work_type']; ?></td>
    	<td align="center"><?php echo $list['bedsheet_qty']; ?></td>
    	<td align="center"><?php echo $list['pillow_qty']; ?></td>
    	<td align="center"><?php echo $list['bedsheet_rate']; ?></td>
    	<td align="center"><?php echo $list['pillow_rate']; ?></td>
    	<td align="center">
		<?php 
		$total= ($list['bedsheet_qty']*$list['bedsheet_rate'])+($list['pillow_qty']*$list['pillow_rate']);
		$grand_total+=$total;
		echo $total;

		?>
		</td>
        <td>
			<?php 
				if($list['paid']==0){ ?>
					<span class='text-danger'>Unpaid</span> 
					<input type="checkbox" name="cutting_id[]" class="form-control-checkbox cuttingcheckbox" value="<?= $list['cutting_id']; ?>" data-total="<?= $total; ?>">
				<?php }else{
					echo "<span class='text-success'>Paid</span>"; 
				}
			?>
      	</td>		
    </tr>
    <?php
			}	
		}
	?>
	</tbody>
	<tfoot>
	<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th align="center"><?= 'Grand Total: '.$grand_total; ?></th>
	<th></th>
	</tr>
	</tfoot>
	<b id="cutting_total" class="text-danger" style="font-size:15px;"></b>&nbsp;&nbsp;&nbsp;
	<button type="submit" name="paycutting" id="paycutting" class="btn btn-xs btn-success" style="display:none;">Pay Amount</button>
</table>
</form>
<p>Stitching Recoard</p><hr>
<form action="../action/updateData.php" method="post">
<table class="table-striped table-bordered table-hover table-condensed datatable" id="daypay_list" style="width:100%">
    <thead>
    	<tr>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Worker</th>
            <th style="text-align:center">Work</th>
            <th style="text-align:center">Item</th>
            <th style="text-align:center">Qty.</th>
            <th style="text-align:center">Rate</th>
            <th style="text-align:center">Total</th>
            <th style="text-align:center">Status</th>
    	</tr>
    </thead>
	<tbody>

    <?php
	$grand_total1=0;
    	if(is_array($array2)){
			foreach($array2 as $list){			
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($list['date'])); ?></td>
    	<td align="center"><?php echo $list['worker_name']; ?></td>
    	<td align="center"><?php echo $list['work_type']; ?></td>
    	<td align="center"><?php echo $list['job']; ?></td>
    	<td align="center"><?php echo $list['qty']; ?></td>
    	<td align="center"><?php 			
		if($list['job']=='Bedsheet'){
				echo $list['bedsheet_rate'];
			}elseif($list['job']=='Pillow'){
				echo $list['pillow_rate'];
			}
 ?></td>
    	<td align="center">
			<?php
			if($list['job']=='Bedsheet'){
			$total= ($list['qty']*$list['bedsheet_rate']);
			}elseif($list['job']=='Pillow'){
				$total= ($list['qty']*$list['pillow_rate']);
			}
		$grand_total1+=$total;

			echo $total;

		
		?></td>
        <td>
			<?php 
			if($list['paid']==0){ ?>
				<span class='text-danger'>Unpaid</span>
				<input type="checkbox" name="stitching_id[]" class="form-control-checkbox stitchingcheckbox" value="<?= $list['stitching_id']; ?>" data-total="<?= $total; ?>">
			<?php }else{
					echo "<span class='text-success'>Paid</span>";  
				}
			?>
      	</td>		
    </tr>
    <?php
			}	
		}
	?>
	</tbody>
	<tfoot>
	<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th align="center"><?= 'Grand Total: '.$grand_total1; ?></th>
	<th></th>
	</tr>
	</tfoot>
<b id="stitching_total" class="text-danger" style="font-size:15px;"></b>&nbsp;&nbsp;&nbsp;
	<button type="submit" name="paystitching" id="paystitching" class="btn btn-xs btn-success" style="display:none;">Pay Amount</button>
</table>
</form>
<p>Zigzag Recoard</p><hr>
<form action="../action/updateData.php" method="post">
<table class="table-striped table-bordered table-hover table-condensed datatable" id="daypay_list" style="width:100%">
    <thead>
    	<tr>
            <th style="text-align:center">Date</th>
            <th style="text-align:center">Worker</th>
            <th style="text-align:center">Work</th>
            <th style="text-align:center">Item</th>
            <th style="text-align:center">Qty.</th>
            <th style="text-align:center">Rate</th>
            <th style="text-align:center">Total</th>
            <th style="text-align:center">Status</th>
    	</tr>
    </thead>
	<tbody>

    <?php
	$grand_total2=0;

    	if(is_array($array3)){
			foreach($array3 as $list){			
	?>
    <tr>
    	<td align="center"><?php echo date('d-m-Y',strtotime($list['date'])); ?></td>
    	<td align="center"><?php echo $list['worker_name']; ?></td>
    	<td align="center"><?php echo $list['work_type']; ?></td>
    	<td align="center"><?php echo ($list['job']=='Zigzag')?'Pillow':$list['job']; ?></td>
    	<td align="center"><?php echo $list['qty']; ?></td>
    	<td align="center"><?php echo $list['pillow_rate']; ?></td>
    	<td align="center">
			<?php 
				$total= ($list['qty']*$list['pillow_rate']);
		$grand_total2+=$total;

				echo $total;
			?>
		</td>
        <td>
			<?php 
				if($list['paid']==0){ ?>
					<span class='text-danger'>Unpaid</span> 
					<input type="checkbox" name="zigzag_id[]" class="form-control-checkbox zigzagcheckbox" value="<?= $list['zigzag_id']; ?>" data-total="<?= $total; ?>">
				<?php }else{
					echo "<span class='text-success'>Paid</span>";  
				}
			?>
      	</td>		
    </tr>
    <?php } } ?>
	</tbody>
	<tfoot>
	<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th align="center"><?= 'Grand Total: '.$grand_total2; ?></th>
	<th></th>
	</tr>
	</tfoot>
	<b id="zigzag_total" class="text-danger" style="font-size:15px;"></b>&nbsp;&nbsp;&nbsp;
	<button type="submit" name="payzigzag" id="payzigzag" class="btn btn-xs btn-success" style="display:none;">Pay Amount</button>
</table>
</form>