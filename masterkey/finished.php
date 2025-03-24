<div class="row">
    <div class="col-md-12" id="addfinished" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_finished">Add Finished</font>
                <font size="+1" class="update_finished" style="display:none;">Update Finished</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('finishedlist','addfinished');">View Finished List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="finishedForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th>Enter Finished Product Name</th>
                        <td><input type="text" name="finished_product" id="finished_product" class="form-control" placeholder="Enter Finished Product" required></td>
                    </tr>
                    <tr>
                        <th>Bedsheet</th>
                        <td>
                            <select name="bedsheet_size" id="bedsheet_size" class="form-control" required>
                                <option value="">Select Bedsheet Size</option>
                                <?php $bsize=$obj->get_rows("`size`","*","`item`='Bedsheet' and `shop`='$shop'");
                                    if(is_array($bsize)){
                                        foreach($bsize as $b){ ?>
                                        <option value="<?php echo $b['size']; ?>"><?php echo $b['size']; ?></option>
                                    <?php } } ?>
                            </select>
                        </td>
                        <td><input type="text" name="bedsheet_qty" id="bedsheet_qty" class="form-control" placeholder="Enter Quantity"></td>
                    </tr>
                    <tr>
                        <th>Pillow</th>
                        <td>
                            <select name="pillow_size" id="pillow_size" class="form-control" required>
                                <option value="">Select Pillow Size</option>
                                <?php $psize=$obj->get_rows("`size`","*","`item`='Pillow' and `shop`=$shop");
                                    if(is_array($psize)){
                                        foreach($psize as $p){ ?>
                                        <option value="<?php echo $p['size']; ?>"><?php echo $p['size']; ?></option>
                                    <?php } } ?>
                            </select>
                        </td>
                        <td><input type="text" name="pillow_qty" id="pillow_qty" class="form-control" placeholder="Enter Quantity"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="finishedid">
                            <input type="submit" class="btn btn-success btn-sm save_finished" name="save_finished" id="save_finished" value="Save" >
                            <input type="submit" class="btn btn-success btn-sm update_finished" name="update_finished" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_finished" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="finishedlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px"><font size="+1">Finished List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addfinished','finishedlist');">Add Finished</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>Sl. No.</th>
                        <th>Finished Product</th>
                        <th>Bedsheet</th>
                        <th>Pillow</th>
                        <th>Action</th>
                    </tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['spage']) && trim($_GET['spage'])!=''){
                            $page=$_GET['spage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`finished`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`id`";
                        $limit="$offset,$count";
                        $array=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($array)){$i=$offset;
                            foreach($array as $finish){$i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $finish['finished_product']; ?></td>
                        <td><?php echo "<b>Size : </b>".$finish['bedsheet_size']."<br><b>Qty : </b>".$finish['bedsheet_qty']; ?></td>
                        <td><?php echo "<b>Size : </b>".$finish['pillow_size']."<br><b>Qty : </b>".$finish['pillow_qty']; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-xs" onClick="editFinished('<?php echo $finish['id']; ?>');">
                                <i class="fa fa-edit"></i>
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
                            <li><a href="../masterkey/?pagename=masterkey&spage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&spage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&spage=<?php echo $page+1; 
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
    <div class="col-md-12" id="finisheddetails" style="display:none;">
    
    </div>
</div>