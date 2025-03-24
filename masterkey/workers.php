<?php
if(isset($_SESSION['user'])){
  $user=$_SESSION['user'];
  $role=$_SESSION['role'];
  $shop=$_SESSION['shop'];
}
else{
	 header("Location:index.php");
	 echo "<script>location='index.php'</script>"; 
}
include_once "../action/config.php";
  $obj=new database();
  $designation=$obj->get_rows("`designation`","`id`,`designation`","`shop`='$shop' AND `status`=1");
?>

<div class="row">
    <div class="col-md-12" id="addworker" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_worker">Add Job Worker</font>
                <font size="+1" class="update_worker" style="display:none;">Update Job Worker</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('workerlist','addworker');">View Job Workers</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="workerForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                <tr>
                        <th width="20%">Designation</th>
                        <td><select name="designation" id="designation" class="form-control w-enter">
                                <option value="">-- Select Option --</option>
                                <!-- <option value="worker">Worker</option>
                                <option value="cutter">Cutter</option> -->
                                <?php foreach($designation as $d){ ?>
                            	    <option value="<?= $d['id']; ?>"><?= $d['designation']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Enter Name</th>
                        <td><input type="text" name="name" id="name" class="form-control w-enter" required></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>
                        	<textarea name="address" id="address"  class="form-control w-enter"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td><input type="text" name="mobile" id="mobile" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>Aadhar</th>
                        <td><input type="text" name="aadhar" id="aadhar" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>PAN Card</th>
                        <td><input type="text" name="pan" id="pan" class="form-control w-enter"></td>
                    </tr>
                    <tr style="display: none;">
                        <th>GSTIN</th>
                        <td><input type="text" name="gst" id="gst" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>Bank Name</th>
                        <td><input type="text" name="bank" id="bank" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>Account No</th>
                        <td><input type="text" name="account" id="account" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>IFSC</th>
                        <td><input type="text" name="ifsc" id="ifsc" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>Reference</th>
                        <td><input type="text" name="reference" id="reference" class="form-control w-enter"></td>
                    </tr>
                    <tr>
                        <th>Payment Type</th>
                        <td>
                            <select name="payment_type" id="payment_type" class="form-control w-enter">
                                <option value="">-- Select Option --</option>
                                <option value="Pcs_Wise">Pcs Wise</option>
                                <option value="Day_Wise">Day Wise</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="hidden">
                        <th>Payment <br>(Per Day)</th>
                        <td><input type="number" name="payment" id="payment" class="form-control w-enter" value="0"></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><input type="text" name="username" id="username" class="form-control w-enter" required></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input type="text" name="password" id="password" class="form-control w-enter" required></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="wid">
                            <input type="submit" class="btn btn-success btn-sm save_worker" name="save_worker" id="save_worker" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_worker" name="update_worker" id="update_worker" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_worker" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="workerlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px"><font size="+1">Job Worker List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addworker','workerlist');">Add Job Worker</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Name</th>
                        <th style="text-align:center;">Designation</th>
                        <th style="text-align:center;">Mobile</th>
                        <th style="text-align:center;">Address</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['wpage'])){
                            $page=$_GET['wpage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`worker`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`name`";
                        $limit="$offset,$count";
                        $array=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($array)){ $i=$offset;
                            foreach($array as $worker){ $i++;
                                $designation = $obj->get_rows("`designation`","*",'`id`='.$worker['designation']);
                    ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php $worker_name = isset($worker['name'])? $worker['name']:''; echo $worker_name; ?></td>
                        <td align="center"><?php $designation = isset($designation[0]['designation'])?$designation[0]['designation']:''; echo $designation; ?></td>
                        <td align="center"><?php echo $worker['mobile']; ?></td>
                        <td align="center"><?php if(strlen($worker['address'])>25){echo substr($worker['address'],0,25)."...";  }else{echo $worker['address'];} ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-info btn-xs" onClick="viewWorker('<?php echo $worker['id']; ?>');">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-xs" onClick="editWorker('<?php echo $worker['id']; ?>');">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php } }else{
                            echo "<tr><td colspan='5' class='text-center text-danger'>No Records Found!</td></tr>";
                        }
                    ?>
                </table>
                <?php
                    if($pages>1){
                ?>
                <div class="text-center">
                <?php
                        if($page!=1){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li><a href="../masterkey/?pagename=masterkey&wpage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&wpage=<?php echo $i;?>"><?php echo $i; ?></a>
                            </li>
                        </ul>
                <?php		
                            }
                            elseif($pages>5 && ($i==4 || $i==$pages-3)){
                ?>
                        <ul class="pagination pagination-sm">
                            <li>
                                <a>...</a>
                            </li>
                        </ul>
                <?php
                            }			
                        }
                        if($page!=$pages){
                ?>
                        <ul class="pagination pagination-sm">
                            <li><a href="../masterkey/?pagename=masterkey&wpage=<?php echo $page+1; 
                            ?>">Next</a></li>
                        </ul>
                <?php
                        }
                ?>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="workerdetails" style="display:none;">
  	</div>
</div>