<div class="container-fluid">
      <table class="table datatable">
            <thead>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Cutting ID</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Cutter Name</th>
                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Total Meter</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Wastage</th>
                <th class="bg-success" style="text-align:center; vertical-align:middle;">Excess</th>
                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Total Bedsheet</th>
                <th class="" style="text-align:center; vertical-align:middle;">Total Pillow</th>
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
				$table="`cutting`";
				$columns="*";
				if(isset($_GET['query']) && trim($_GET['query'])!=""){
					$query=$_GET['query'];
					$where="`status`='1' AND `shop`='$shop'";
				}
				else{
					$where="`status`='1' AND `shop`='$shop'";
				}
				$order="id";
				$limit="$offset,$count";
				$array=$obj->get_rows($table,$columns,$where,$order);
				$rowcount=$obj->get_count($table,$where);
				$pages=ceil($rowcount/$count);
    			$i=$offset;
				if(is_array($array)){
                	foreach($array as $result){
						$id=$result['id'];$i++;
            ?>
            <tr>
               	<td align="center"><?php echo $result['id'];?></td>
                <td align="center"><?php $raw=$obj->get_details("`worker`","`name`","`id`='".$result['worker']."'"); echo $raw['name'];?></td>
                <td align="center"><?php echo $result['finalmeter'];?></td>
                <td align="center"><?php echo $result['wastage'];?></td>
				<td align="center"><?php echo $result['excess'];?></td>
				<td align="center"><?php echo $result['finalbedsheet'];?></td>
                <td align="center"><?php echo $result['finalpillow'];?></td>

                <td align="center">
					<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal" onClick="selectCutting('<?php echo $result['id']; ?>');">
						<i class="fa fa-check"></i>
					</button>
				</td>
            </tr> 
            <?php } } ?>
            </tbody>
</table>
</div>