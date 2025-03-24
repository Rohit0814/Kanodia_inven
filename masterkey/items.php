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
  $job=$obj->get_rows("`job_process`","`id`,`process`","`shop`='$shop' AND `status`=1");
?>
<div class="row">
    <div class="col-md-12" id="addproduct" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_size">Add Product</font>
                <font size="+1" class="update_size" style="display:none;">Update Width</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('productlist','addproduct');">View Product List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="productForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Enter Product Name</th>
                        <td><input type="text" name="product" id="product" class="form-control" required></td>
                    </tr>
                    <tbody id="job-seq-table">
                    <tr class="job-sq">
                        <th width="20%">Job Sequences</th>
                        <td><select id="job_process" class="form-control" multiple required>
                            	<option value="" disabled>Select Job Process</option>
                                <?php foreach($job as $j){ ?>
                            	    <option value="<?= $j['id']; ?>"><?= $j['process']; ?></option>
                                <?php } ?>
                            </select></td>
                            <input type="hidden" id="input_select" name="job_process">
                    </tr>
                    </tbody>

                    

                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="productid">
                            <input type="submit" class="btn btn-success btn-sm save_product" name="save_product" id="save_product" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_product" name="update_product" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_product" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>                                                                                  
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <div class="col-md-12" id="productlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1">Product List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addproduct','productlist');">Add Product</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Item</th>
                        <th style="text-align:center; width:300px">Job Sequence</th>
                        <th style="text-align:center;">Created_at</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>


                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['productpage']) && trim($_GET['productpage'])!=''){
                            $page=$_GET['productpage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`product`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`created_at`";
                        $limit="$offset,$count";
                        $array=$obj->get_rows($table,$columns,$where,$order,$limit);
                        // print_r($array);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($array)){$i=$offset;
                            foreach($array as $size){$i++;
                    ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $size['product_name']; ?></td>
                        <td>
                        <?php 
                            $job_sqn = json_decode($size['job_squence']);
                            foreach($job_sqn as $job){
                                $job_name = $obj->get_rows("`job_process`","`process`","`id`='$job'");
                                echo "<span style='background: #337ab7;margin: 5px;padding: 5px;border-radius: 10px;font-weight: 600;color: white;line-height: 2; white-space: nowrap;'>".$job_name[0]['process']."</span>";
                            }
                        ?>
                        </td>
                        <td align="center"><?php $created_at = is_numeric($size['created_at']) ? $size['created_at'] : strtotime($size['created_at']); echo date('Y-m-d', $created_at);  ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editProduct('<?php echo $size['id']; ?>');">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deleteProduct('<?php echo $size['id']; ?>');">
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
                            <li><a href="../masterkey/?pagename=masterkey&patternpage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&patternpage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&patternpage=<?php echo $page+1; 
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
    <div class="col-md-12" id="sizedetails" style="display:none;">

    </div>
</div>