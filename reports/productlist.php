<?php
include_once "../action/config.php";
$obj=new database();
if(isset($_GET['shop'])){
	$shop=$_GET['shop'];	
}

	$table="`finished_stock`";
	$columns="*";
	if(isset($_GET['from']) && isset($_GET['to'])){
		$from=$_GET['from']; $to=$_GET['to'];
		$where="(`date` between '$from' and '$to') and shop='$shop'";
	}
	else{
		$where="`shop`='$shop'";
	}
	$order="`date` DESC";
	$array=$obj->get_rows($table,$columns,$where,$order);  
?>

      <table class="table datatable">
            <thead>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Image</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Item</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Raw Material</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Design No.</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Bedsheet Size</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Bedsheet Qty.</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Pillow Size</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Pillow Qty.</th>
                <th class="bg-secondary" style="text-align:center; vertical-align:middle;">Created ON</th>
            </thead>
            <tbody>
             <?php
				$i=$total_bed=$total_pillow=0;
				if(is_array($array)){
                	$i=0;foreach($array as $result){
						$i++;
            ?>
            <tr>
               	<td align="center"><?php echo $i;?></td>
               	<td align="center"><img src="../uploads/<?php echo $result['image'];?>" class="img-responsive" style="width:70px;"></td>
                   <td align="center"><?php echo $result['item'];?></td>
				<td align="center"><?php echo $result['raw_material'];?></td>
                <td align="center"><?php echo $result['d_no'];?></td>
				<td align="center"><?php echo $result['bedsheet_size'];?></td>
                <td align="center"><?php echo $result['bedsheet_qty'];?></td>
                <td align="center"><?php echo $result['pillow_size'];?></td>
                <td align="center"><?php echo $result['pillow_qty'];?></td>
                <td align="center"><?php echo $result['date'];?></td>
            </tr>
            <?php 
                	$total_bed+= $result['bedsheet_qty'];
					$total_pillow+= $result['pillow_qty'];
					} } ?>
            </tbody>
			<h4><span class="text-success">Total Item: <?= $i; ?></span> <span class="text-primary">Total Bedsheet: <?= $total_bed; ?></span> <span class="text-warning">Total Pillow: <?= $total_pillow; ?></span>  </h4>
</table>

