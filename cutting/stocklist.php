<div class="container-fluid">
      <table class="table datatable">
            <thead>
			<th class="bg-danger" style="text-align:center; vertical-align:middle;">Barcode</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Stock ID</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Image</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Item</th>
                <th class="" style="text-align:center; vertical-align:middle;">Width</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Design No.</th>
                <!-- <th class="bg-info" style="text-align:center; vertical-align:middle;">Meter</th> -->
                <th class="bg-success" style="text-align:center; vertical-align:middle;">Quantity</th>
				<th class="bg-warning" style="text-align:center; vertical-align:middle;">Current Stock</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Action</th>
            </thead>
           
            <tbody>
             <?php
                $count=20;
                $offset =0;
				if(isset($_GET['page'])){
                	$page=$_GET['page'];
				}
				else{$page=0;}
				
				if(isset($_GET['shop'])){
					$shop=$_GET['shop'];	
					include('../action/class.php');
					$obj=new database();
				}
                $offset=$page*$count;
				$table="`bale_meter_tally`";
				$columns="*";
				if(isset($_GET['query']) && trim($_GET['query'])!=""){
					$query=$_GET['query'];
					$where="`current_stock`!='0' AND `shop`='$shop'";
				}
				else{
					$where="`current_stock`!='0' AND `shop`='$shop'";
				}
				$order="id";
				$group="stock_id";
				$limit="$offset,$count";
				$array=$obj->get_rows($table,$columns,"",$order,'',$group);
				// print_r($array);
				$rowcount=$obj->get_count($table,'');
				$pages=ceil($rowcount/$count);
    			$i=$offset;
				if(is_array($array)){
                	foreach($array as $result){
						$id=$result['id'];$i++;
						// print_r($result);

						$array2=$obj->get_rows('stock',$columns,'`id`=' .$result["stock_id"] .'',$order,'');
						// $job_process = $obj
						if(is_array($array2)){
							foreach($array2 as $result2){
								// if(empty($result['material_type'])){
								if($result2['current_stock']>0){
					?>
					<tr>
					<td align="center">
							<?php if(!empty($result['barcode'])){ ?>
							<img src='../action/barcodes/barcode_<?php echo $result['barcode'];?>.png'>
		
							<?php } else {?>
							<button class="btn btn-info" style="text-transform: capitalize;">Not required</button>
							<?php }?>
						</td>
						   <td align="center"><?php echo $result2['id'];?></td>
						   <td align="center"><img src="../uploads/<?php echo $result2['image'];?>" class="img-responsive" style="width:70px;"></td>
						<td align="center"><?php $raw=$obj->get_details("`raw_material`","`name`","`id`='".$result2['raw_id']."'"); echo $raw['name'];?></td>
						<td align="center"><?php $width=$obj->get_details("`width`","`width`","`id`='".$result2['width_id']."'"); echo $width['width'];?></td>
						<td align="center"><?php echo $result2['d_no'];?></td>
						<!-- <td align="center"><?php echo $result2['meter'];?></td> -->
						<td align="center"><?php echo $result2['quantity'];?></td>
						<td align="center"><?php echo $result2['current_stock'];?></td>
						<td align="center">   
					<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" onClick="selectStock('<?php echo $result2['id']; ?>');">
						<i class="fa fa-check"></i>
					</button>
				</td>
					</tr>
					
					<?php 
							} } } 
						} ?>
						<?php }?>
            </tbody>
</table>
</div>