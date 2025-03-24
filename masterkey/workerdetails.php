<?php
  	include_once "../action/config.php";
  	$obj=new database();   
	$id=$_POST['id'];
	$array=$obj->get_details("`worker`","*","`id`='$id'");
    $array1=$obj->get_details("`users`","*","`user_id`='$id'");
?>
<div class="row">
    <div class="col-md-12" style="padding:0 25px">
        <font size="+1" class="save_worker">Job Worker Details</font>
    </div>
</div><br>
<div class="row" >
    <div class="col-md-12">
        <table class="table" style="width:90%; margin:0 auto;">
            <tr>
                <th width="20%">Enter Name</th>
                <td><?php echo $array['name'] ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $array['address'] ?></td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td><?php echo $array['mobile'] ?></td>
            </tr>
            <tr>
                <th>Aadhar</th>
                <td><?php echo $array['aadhar'] ?></td>
            </tr>
            <tr>
                <th>PAN Card</th>
                <td><?php echo $array['pan'] ?></td>
            </tr>
            <tr>
                <th>GSTIN</th>
                <td><?php echo $array['gst'] ?></td>
            </tr>
            <tr>
                <th>Bank Name</th>
                <td><?php echo $array['bank'] ?></td>
            </tr>
            <tr>
                <th>Account No</th>
                <td><?php echo $array['account'] ?></td>
            </tr>
            <tr>
                <th>IFSC</th>
                <td><?php echo $array['ifsc'] ?></td>
            </tr>
            <tr>
                <th>Reference</th>
                <td><?php echo $array['reference'] ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo $array1['username'] ?></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><?php echo $array1['password'] ?></td>
            </tr>
            <tr>
            	<td colspan="2"><button type="button" class="btn btn-danger btn-sm" onClick="showThis('workerlist','workerdetails');">Close</button></td>
            </tr>
        </table>
    </div>
</div>