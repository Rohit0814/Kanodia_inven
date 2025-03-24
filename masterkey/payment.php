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
	$worker=$obj->get_rows("`worker`","`id`,`name`","`shop`='$shop'" ,'name asc');
    $product=$obj->get_rows("`product`","`id`,`product_name`","`shop`='$shop'");
?>

<div style="margin: 10px 0px;">
    <button class="btn btn-success" onClick="paymentmode('worker_wise','product_wise');">Worker Wise</button>
    <button class="btn btn-warning" onClick="paymentmode('product_wise','worker_wise');">Product Wise</button>
</div>
<div class="row">
    <div class="col-md-12" id="addpayment" style="display:none;">
        <div class="row">
            <div class="col-md-12 payment-head" style="padding:0 25px" style="display: none;">
                <font size="+1" id="add_payment" class="save_payment">Add Payment</font>
                <font size="+1" class="update_payment" style="display:none;">Update Payment</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('paymentlist','addpayment');">View Payment List</button>
            </div>
        </div><br>
        <form class="row worker_wise" method="post" action="../action/insertData.php" id="PaymentFormWorker" style="display: none;">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Select Worker</th>
                        <td>
                            <select name="worker" id="worker" class="form-control" required>
                            	<option value="" selected disabled>Select Worker</option>
                                <?php foreach($worker as $w){ ?>
                            	    <option value="<?= $w['id']; ?>"><?= $w['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th>Work type</th>
                        <td>
                        	<select name="work_type" id="work_type" class="form-control" required>
                            	<option value="">Select Work</option>
                            	<option value="Cutting">Cutting</option>
                            	<option value="Stitching">Stitching</option>
                            	<option value="Zigzag">Zigzag</option>
                            </select>
                        </td>
                    </tr> -->
                    <tr>
                        <th width="20%">Rate</th>
                        <td><input type="number" step="any" name="rate" id="worker_amt" placeholder="Enter Rate" class="form-control" required></td>
                    </tr>
                    <!-- <tr>
                        <th width="20%">Pillow Rate (per pcs.)</th>
                        <td><input type="number" step="any" name="pillow_rate" id="pay_pillowrate" placeholder="Enter Pillow Rate/pcs" class="form-control" required></td>
                    </tr> -->
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="payment_Work_id" id="paymentidworker">
                            <input type="hidden" name="payment_type" id="paymentType" value="worker_wise">
                            <input type="submit" class="btn btn-success btn-sm save_payment_worker" name="save_payment_worker" id="save_width" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_payment_worker" name="update_payment_worker" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_payment_worker" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>


        <form class="row product_wise" method="post" action="../action/insertData.php" id="PaymentFormproduct" style="display: none;">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <!-- <tr>
                        <th width="20%">Select Worker</th>
                        <td>
                            <select name="worker" id="worker" class="form-control" required>
                            	<option value="" selected disabled>Select Worker</option>
                                <?php //foreach($worker as $w){ ?>
                            	    <option value="<?= $w['id']; ?>"><?= $w['name']; ?></option>
                                <?php //} ?>
                            </select>
                        </td>
                    </tr> -->

                    <tr>
                        <th width="20%">Select Product</th>
                        <td>
                            <select name="product" id="payment_product" class="form-control" required>
                            	<option value="" selected disabled>Select Product</option>
                                <?php foreach($product as $p){ ?>
                            	    <option value="<?= $p['id']; ?>"><?= $p['product_name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">Select Size</th>
                        <td>
                            <select name="size_id" id="payment_size" class="form-control" required>
                            	<option value="" selected disabled>Select Size</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="20%">Select Pattern</th>
                        <td>
                            <select name="pattern_id" id="payment_pattern" class="form-control" required>
                            	<option value="" selected disabled>Select Pattern</option>
                            </select>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th>Work type</th>
                        <td>
                        	<select name="work_type" id="work_type" class="form-control" required>
                            	<option value="">Select Work</option>
                            	<option value="Cutting">Cutting</option>
                            	<option value="Stitching">Stitching</option>
                            	<option value="Zigzag">Zigzag</option>
                            </select>
                        </td>
                    </tr> -->
                    <!-- <tr>
                        <th width="20%">Bedsheet Rate (per pcs.)</th>
                        <td><input type="number" step="any" name="bedsheet_rate" id="pay_bedrate" placeholder="Enter Bedsheet Rate/pcs" class="form-control" required></td>
                    </tr> -->
                    <tr>
                        <th width="20%">Rate</th>
                        <td><input type="number" step="any" name="rate" id="parment_product_rate" placeholder="Enter Rate/pcs" class="form-control" required></td>
                    </tr>
                    <!-- <tr>
                        <th width="20%">Pillow Rate (per pcs.)</th>
                        <td><input type="number" step="any" name="pillow_rate" id="pay_pillowrate" placeholder="Enter Pillow Rate/pcs" class="form-control" required></td>
                    </tr> -->
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="payment_product_id" id="paymentidproduct">
                            <input type="hidden" name="payment_type" id="paymentType" value="product_wise">
                            <input type="submit" class="btn btn-success btn-sm save_payment_product" name="save_payment_product" id="save_payment_product" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_payment_product" name="update_payment_product" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_payment_product" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="paymentlist">
        <div class="row payment-head" style="display: none;">
            <div class="col-md-12"  style="padding:0 25px"><font size="+1" id="payment_list">Payment List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addpayment','paymentlist');">Add Payment</button>
            </div>
        </div><br>
        <div class="row worker_wise" style="display:none">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Worker</th>
                        <!-- <th style="text-align:center;">Work Type</th> -->
                        <th style="text-align:center;">Rate</th>
                        <!-- <th style="text-align:center;">Pillow Rate (per pcs.)</th> -->
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['paypage']) && trim($_GET['paypage'])!=''){
                            $page=$_GET['paypage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`payment`";
                        $columns="*";
                        $where="`shop`='$shop' and `work_type`='worker_wise'";
                        $order="`id`";
                        $limit="$offset,$count";
                        $paymentlist=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($paymentlist)){$i=$offset;
                            foreach($paymentlist as $payment){$i++;
	                            $worker=$obj->get_details("`worker`","*","`id`='$payment[worker]'");
                            ?>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $worker['name']; ?></td>
                        <!-- <td align="center"><?php echo $payment['work_type']; ?></td> -->
                        <td align="center"><?php echo $payment['rate']; ?></td>
                        <!-- <td align="center"><?php echo $payment['pillow_rate']; ?></td> -->
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editPaymentWorker('<?php echo $payment['id']; ?>');"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deletepayment('<?php echo $payment['id']; ?>');">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                            }
                        }else{
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
                            <li><a href="../masterkey/?pagename=masterkey&paypage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&paypage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&paypage=<?php echo $page+1; ?>">Next</a></li>
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



        <div class="row product_wise" style="display:none">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Product</th>
                        <th style="text-align:center;">Size</th>
                        <th style="text-align:center;">Pattern</th>
                        <th style="text-align:center;">Rate</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['paypage']) && trim($_GET['paypage'])!=''){
                            $page=$_GET['paypage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`payment`";
                        $columns="*";
                        $where="`shop`='$shop' and `work_type`='product_wise'";
                        $order="`id`";
                        $limit="$offset,$count";
                        $paymentlist=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($paymentlist)){$i=$offset;
                            foreach($paymentlist as $payment){$i++;
	                            $product=$obj->get_details("`product`","*","`id`='$payment[product_id]'");
                                $size=$obj->get_details("`size`","*","`id`='$payment[size_id]'");
                                $pattern=$obj->get_details("`pattern`","*","`id`='$payment[pattern_id]'");
                            ?>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $product['product_name']; ?></td>
                        <td align="center"><?php echo $size['size']; ?></td>
                        <td align="center"><?php echo $pattern['pattern_name']; ?></td>
                        <td align="center"><?php echo $payment['rate']; ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editPaymentWorker('<?php echo $payment['id']; ?>');"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deletepayment('<?php echo $payment['id']; ?>');">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                            }
                        }else{
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
                            <li><a href="../masterkey/?pagename=masterkey&paypage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&paypage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&paypage=<?php echo $page+1; ?>">Next</a></li>
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
</div>