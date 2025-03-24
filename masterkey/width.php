<div class="row">
    <div class="col-md-12" id="addwidth" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_width">Add Width</font>
                <font size="+1" class="update_width" style="display:none;">Update Width</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('widthlist','addwidth');">View Width List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="widthForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Enter Width</th>
                        <td><input type="number" name="width" id="width" class="form-control" required></td>
                    </tr>
                    <tr>
                        <th>Unit</th>
                        <td>
                        	<select name="unit" id="w_unit" class="form-control">
                            	<option value="">Select Unit</option>
                            	<option value="Inch">Inch</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="widthid">
                            <input type="submit" class="btn btn-success btn-sm save_width" name="save_width" id="save_width" value="Save" >
                            <input type="submit" class="btn btn-success btn-sm update_width" name="update_width" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_width" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="widthlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px"><font size="+1">Width List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addwidth','widthlist');">Add Width</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Width</th>
                        <th style="text-align:center;">Unit</th>
                        <!-- <th style="text-align:center;">Rate</th> -->
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['wipage']) && trim($_GET['wipage'])!=''){
                            $page=$_GET['wipage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`width`";
                        $columns="*";
                        $where="`shop`='$shop'";
                        $order="`width`";
                        $limit="$offset,$count";
                        $array=$obj->get_rows($table,$columns,$where,$order,$limit);
                        $rowcount=$obj->get_count($table,$where);
                        $pages=ceil($rowcount/$count);
                        if(is_array($array)){$i=$offset;
                            foreach($array as $width){$i++;
                    ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $width['width']; ?></td>
                        <td align="center"><?php echo $width['unit']; ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editWidth('<?php echo $width['id']; ?>');">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deleteWidth('<?php echo $width['id']; ?>');">
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
                            <li><a href="../masterkey/?pagename=masterkey&wipage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&wipage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&wipage=<?php echo $page+1; 
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