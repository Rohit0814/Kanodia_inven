<div class="row">
    <div class="col-md-12" id="adddesignation" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_designation">Add Job Designation</font>
                <font size="+1" class="update_designation" style="display:none;">Update Job Designation</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('designationlist','adddesignation');">View Designation List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="designationForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Enter Designation</th>
                        <td><input type="text" name="designation" id="designation1" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="designationid">
                            <input type="submit" class="btn btn-success btn-sm save_designation" name="save_desination" id="save_designation" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_designation" name="update_designation" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_designation" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <div class="col-md-12" id="designationlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1">Designation List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('adddesignation','designationlist');">Add Designation</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Designation</th>
                        <th style="text-align:center;">Created_at</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>


                    <?php
                        $count=25;
                        $offset =0;
                        if(isset($_GET['dpage']) && trim($_GET['dpage'])!=''){
                            $page=$_GET['dpage'];
                        }
                        else{
                            $page=1;	
                        }
                        $offset=($page-1)*$count;
                        $table="`designation`";
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
                        <td align="center"><?php echo $size['designation']; ?></td>
                        <td align="center"><?php $created_at = is_numeric($size['created_at']) ? $size['created_at'] : strtotime($size['created_at']); echo date('Y-m-d', $created_at);  ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-xs" onClick="editdesignation('<?php echo $size['id']; ?>');">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onClick="deletedesignation('<?php echo $size['id']; ?>');">
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
                            <li><a href="../masterkey/?pagename=masterkey&dpage=<?php echo $page-1; ?>">Prev</a></li>
                        </ul>
                <?php
                        }
                        for($i=1;$i<=$pages;$i++){
                            if($i<4 || $i>$pages-3 || $i==$page || $i==$page-1 || $i==$page+1 || $i==$page-2 || $i==$page+2){
                ?>	
                        <ul class="pagination pagination-sm">
                            <li <?php if($i==$page){echo "class='active'";} ?>>
                                <a href="../masterkey/?pagename=masterkey&dpage=<?php echo $i;?>"><?php echo $i; ?></a>
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
                            <li><a href="../masterkey/?pagename=masterkey&dpage=<?php echo $page+1; 
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