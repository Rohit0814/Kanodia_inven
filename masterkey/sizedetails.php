<?php
  	include_once "../action/config.php";
  	$obj=new database();   
	$id=$_POST['id'];
    // echo $id;
	$details=$obj->get_details("`size`","*","`id`='$id'");
	$array=$obj->get_rows("`consumption` t1, `width` t2","t1.*,t2.`width`","t1.`size_id`='$id' and t1.`width_id`=t2.`id`");
?>
<div class="row">
    <div class="col-md-12" style="padding:0 25px">
        <font size="+1" class="save_size">Size Consumption Details</font>
    </div>
</div><br>
<div class="row" >
    <div class="col-md-12">
        <table class="table" style="width:90%; margin:0 auto;">
            <tr>
                <th width="20%">Item</th>
            <td><?php echo $details['item']; ?></td>
                
            </tr>
            <tr>
            <th width="20%">Size</th>
            <td><?php echo $details['size']; ?></td>
            </tr>
            <tr>
                <th>Consumptions</th>
                <td>
					<?php
                    	foreach($array as $consumption){
							echo "width :".$consumption['width']." - Consume : ".$consumption['consume']."<br>";
						}
					?>
                </td>
            </tr>
            <tr>
            	<td colspan="2"><button type="button" class="btn btn-danger btn-sm" onClick="showThis('sizelist','sizedetails');">Close</button></td>
            </tr>
        </table>
    </div>
</div>