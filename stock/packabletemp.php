<?php
 error_reporting(0);
	if(isset($_GET['shop'])){
		$shop=$_GET['shop'];
		$user_id=$_GET['user_id'];
		//print_r($user_id); die;
		include_once "../action/config.php";
		$obj=new database();
	}
?>
<table class="table table-bordered table-condensed">
	<tr class="bg-primary">	
    	<th style="text-align:center" width="5%">Sl no</th>
    	<th style="text-align:center">Image</th>
    	<th style="text-align:center">Item</th>
		<th style="text-align:center">bill No</th>
		<th style="text-align:center">Supplier</th>
		<th style="text-align:center">Bale No.</th>
    	<th style="text-align:center">Width</th>
    	<th style="text-align:center">Design No.</th>
    	<th style="text-align:center">Meter</th>
    	<!-- <th style="text-align:center">Quantity</th> -->
    	<th style="text-align:center" width="5%">Action</th>
    </tr>
    <?php
		$select_temp=$obj->get_rows("`packabletemp`","*","`shop`='$shop' AND `user_id`='$user_id'");
		$i=0;
		if(is_array($select_temp)){
			foreach($select_temp as $temp){$i++;
	?>
    <tr>
    	<td align="center"><?php echo $i;  ?></td>
    	<td align="left"><img src="../uploads/<?php echo $temp['image'];?>" class="img-responsive" style="width:70px;"></td>
    	<td align="center"><?php $raw=$obj->get_details("`raw_material`","`name`","`id`='".$temp['raw_id']."'"); echo $raw['name'];  ?></td>
		<td align="center"><?php echo $temp['bill_no'];  ?></td>
		<td align="center"><?php $raw=$obj->get_details("`supplier`","`name`","`id`='".$temp['supplier_id']."'"); echo $raw['name'];  ?></td>
		<td align="center"><?php echo $temp['bale_no'];  ?></td>
    	<td align="center"><?php $width=$obj->get_details("`width`","`width`","`id`='".$temp['width_id']."'"); echo $width['width'];  ?></td>
    	<td align="center"><?php echo $temp['d_no']; ?></td>
    	<td align="center"><?php echo $temp['meter']; ?></td>
    	<!-- <td align="center"><?php //echo $temp['qty']; ?></td> -->
        <td align="center">
        	<button type="button" class="btn btn-danger btn-xs fa fa-trash" title="Delete" onClick="deleteTemp('<?php echo $temp['id']; ?>')"></button> <br><br>
			<button type="button" class="btn btn-success btn-sm fa fa-share-square" title="Same Bale" onClick="addSameBale('<?php echo $temp['id']; ?>')"></button>
        </td>
    </tr>
    <?php } } ?>
</table>