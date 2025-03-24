<div class="container-fluid">
      <table class="table datatable">
            <thead>
			<th class="bg-primary" style="text-align:center; vertical-align:middle;">Stitching ID</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Cutting ID</th>
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
				$table="`stitching`";
				$columns="*";
				if(isset($_GET['query']) && trim($_GET['query'])!=""){
					$query=$_GET['query'];
					$where="``shop`='$shop'";
				}
				else{
					$where="`shop`='$shop'";
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
               	<td align="center"><?php echo $result['cutting_id'];?></td>
				<td align="center"><?php echo $result['totalbedsheet'];?></td>
                <td align="center"><?php echo $result['totalpillow'];?></td>

                <td align="center">
				<a class="btn btn-success btn-xs" href="printstitching.php?id=<?php echo $result['id']; ?>">
						<i class="fa fa-print"></i>
					</a>
				</td>
            </tr> 
            <?php } } ?>
            </tbody>
</table>
</div>