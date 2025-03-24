<?php
  	include_once "../action/config.php";
  	$obj=new database();   
	$id=$_POST['id'];
	$details=$obj->get_details("`finished`","*","`id`='$id'");
	$array=$obj->get_rows("`materials_used` t1, `raw_material` t2","t1.*,t2.`name`","t1.`finished_id`='$id' and t1.`rawmaterial`=t2.`id`");
	$workers=$obj->get_rows("`job_worker` t1, `worker` t2","t1.`job_charge`,t2.`name` as `worker`","t1.`finished`='$id' and t1.`worker`=t2.`id`");
?>
<div class="row">
    <div class="col-md-12" style="padding:0 25px">
        <font size="+1" class="save_worker">Finished Product Details</font>
    </div>
</div><br>
<div class="row" >
    <div class="col-md-12">
        <table class="table" style="width:90%; margin:0 auto;">
            <tr>
                <th width="20%">Name</th>
                <td><?php echo $details['name'] ?></td>
            </tr>
            <tr>
                <th>Combination</th>
                <td>
					<?php
                    	foreach($array as $rawmaterial){
							echo $rawmaterial['name']." - ".$rawmaterial['used']."<br>";
						}
					?>
                </td>
            </tr>
            <tr>
                <th>Job Charges</th>
                <td>
					<?php
                    	foreach($workers as $worker){
							echo $worker['worker']." - ".$worker['job_charge']."<br>";
						}
					?>
                </td>
            </tr>
            <tr>
                <th>Rate</th>
                <td><?php echo $details['rate'] ?></td>
            </tr>
            <tr>
                <th>Packaging Cost</th>
                <td><?php echo $details['pcost'] ?></td>
            </tr>
            <tr>
            	<td colspan="2"><button type="button" class="btn btn-danger btn-sm" onClick="showThis('finishedlist','finisheddetails');">Close</button></td>
            </tr>
        </table>
    </div>
</div>