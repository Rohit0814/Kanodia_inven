<div class="row">
    <div class="col-md-12" id="addraw" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_raw">Add Raw Material</font>
                <font size="+1" class="update_raw" style="display:none;">Update Raw Material</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('rawlist','addraw');">View Raw Materials</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="rawForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Enter Name</th>
                        <td><input type="text" name="name" id="rname" class="form-control r-enter" required></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>
                        	<select name="type" id="type" class="form-control r-enter">
                            	<option value="">Select Type</option>
                            	<option value="fabric">Fabric</option>
                            	<option value="subsidiary">Subsidiary</option>
                                <option value="stitching">stitching</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Unit</th>
                        <td>
                        	<select name="unit" id="unit" class="form-control r-enter">
                            	<option value="">Select Unit</option>
                            	<option value="Meter">Meter</option>
                            	<option value="Piece">Piece</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Rate</th>
                        <td><input type="text" name="rate" id="rate" value="0" class="form-control r-enter"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="rid">
                            <input type="submit" class="btn btn-success btn-sm save_raw" name="save_raw" id="save_raw" value="Save" >
                            <input type="submit" class="btn btn-success btn-sm update_raw" name="update_raw" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_raw" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="rawlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px"><font size="+1">Raw Material List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addraw','rawlist');">Add Raw Material</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Name</th>
                        <th style="text-align:center;">Unit</th>
                        <th style="text-align: center;">Type</th>
                        <th style="text-align:center;">Rate</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['rpage']) && trim($_GET['rpage'])!=''){
                            $page=$_GET['rpage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`raw_material`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`name`";
                        $limit="$offset,$count";
                        $array=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($array)){$i=$offset;
                            foreach($array as $raw){$i++;
                    ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $raw['name']; ?></td>
                        <td align="center"><?php echo $raw['unit']; ?></td>
                        <td align="center" style="text-transform: capitalize;"><?php echo $raw['type']; ?></td>
                        <td align="center"><?php echo $raw['rate']; ?></td>
                        
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editRaw('<?php echo $raw['id']; ?>');">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deleteRaw('<?php echo $raw['id']; ?>');">
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
                            <li><a href="../masterkey/?pagename=masterkey&rpage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&rpage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&rpage=<?php echo $page+1; 
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
</div>