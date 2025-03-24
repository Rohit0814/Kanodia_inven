<div class="row">
    <div class="col-md-12" id="addsize" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_size">Add size</font>
                <font size="+1" class="update_size" style="display:none;">Update Width</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('sizelist','addsize');">View Size List</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="sizeForm">
            <div class="col-md-12">
                <table class="table" id="size_gen_table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th width="20%">Item</th>
                        <td><select name="item" id="item1" class="form-control" required>
                                <?php
                                // $product = $obj->get_rows('product','*','`status`=1');
                                // print_r($product);
                                $products = $obj->get_rows('product', '*', 'status=1');

                                if ($products) {
                                    echo "<option value=''>Select Product</option>";
                                    foreach ($products as $row) {
                                ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['product_name']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                                <!-- <option value="Bedsheet">Bedsheet</option>
                                <option value="Pillow">Pillow</option> -->
                            </select></td>
                    </tr>
                    <tr>
                        <th width="20%">Enter Size Name</th>
                        <td><input type="text" name="size" id="size" class="form-control" required></td>
                    </tr>
                    <tr class="main_tr">
                        <th>Consumption</th>
                        <td>
                            <div id="consumption" style="display: block;">
                                <input class="form-control selwid" name="rate[]" placeholder="Enter Rate">
                                <select name="width[]" id="width1" class="form-control selwid"></select>
                                <select name="pattern[]" id="pattern1" class="form-control pattern">
                                    <option value="">Select Pattern</option>
                                </select>

                                <input type="text" name="consume[]" id="consume1" class="form-control consume" placeholder="Consumed">

                            </div>

                            <div id="subsidary" style="margin:0px 10px; border-radius: 10px; display:unset">
                                <!-- <input type="text" class="form-control" style="margin:5px">
                                    <input type="text" class="form-control" style="margin:5px"> -->
                            </div>


                            <input type="hidden" name="count" id="count" value="1" />
                        </td>
                        <td style="display: flex;"><button type="button" class="btn btn-primary btn-sm" onclick="addWidth(this);">Add Width</button>&nbsp;<button type="button" class="btn btn-primary btn-sm" onclick="addsubsidary(this);"><i class="fa fa-sitemap" aria-hidden="true"></i></button></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="sizeid">
                            <input type="submit" class="btn btn-success btn-sm save_size" name="save_size" id="save_size" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_size" name="update_size" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_size" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="sizelist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1">Size List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addsize','sizelist');">Add Size</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th style="text-align:center;" width="10%">Sl. No.</th>
                        <th style="text-align:center;">Item</th>
                        <th style="text-align:center;">Size</th>
                        <th style="text-align:center;" width="15%">Action</th>
                    </tr>
                    <?php
                    $count = 25;
                    $offset = 0;
                    if (isset($_GET['spage']) && trim($_GET['spage']) != '') {
                        $page = $_GET['spage'];
                    } else {
                        $page = 1;
                    }
                    $offset = ($page - 1) * $count;
                    $table = "`size`";
                    $columns = "*";
                    $where = "`shop`='$shop'";
                    $order = "`item`";
                    $limit = "$offset,$count";
                    $array = $obj->get_rows($table, $columns, $where, $order, $limit);
                    $rowcount = $obj->get_count($table, $where);
                    $pages = ceil($rowcount / $count);
                    if (is_array($array)) {
                        $i = $offset;
                        foreach ($array as $size) {
                            $i++;
                    ?>
                            <tr>
                                <td align="center"><?php echo $i; ?></td>
                                <?php
                                $products2 = $obj->get_rows('product', '*', 'status=1 and id=' . $size['item']);
                                ?>
                                <td align="center"><?php $product_name = isset($products2[0]['product_name'])?$products2[0]['product_name']:''; echo $product_name; ?></td>
                                <td align="center"><?php $size1 = isset($size['size'])?$size['size']:''; echo $size1 ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-info btn-xs" onClick="viewSize('<?php echo $size['id']; ?>');">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-xs" onClick="editSize('<?php echo $size['id']; ?>');">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-danger'>No Records Found!</td></tr>";
                    }
                    ?>
                </table>
                <?php
                if ($pages > 1) {
                ?>
                    <div class="text-center">
                        <?php
                        if ($page != 1) {
                        ?>
                            <ul class="pagination pagination-sm">
                                <li><a href="../masterkey/?pagename=masterkey&spage=<?php echo $page - 1; ?>">Prev</a></li>
                            </ul>
                            <?php
                        }
                        for ($i = 1; $i <= $pages; $i++) {
                            if ($i < 4 || $i > $pages - 3 || $i == $page || $i == $page - 1 || $i == $page + 1 || $i == $page - 2 || $i == $page + 2) {
                            ?>
                                <ul class="pagination pagination-sm">
                                    <li <?php if ($i == $page) {
                                            echo "class='active'";
                                        } ?>>
                                        <a href="../masterkey/?pagename=masterkey&spage=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                </ul>
                            <?php
                            } elseif ($pages > 5 && ($i == 4 || $i == $pages - 3)) {
                            ?>
                                <ul class="pagination pagination-sm">
                                    <li>
                                        <a>...</a>
                                    </li>
                                </ul>
                            <?php
                            }
                        }
                        if ($page != $pages) {
                            ?>
                            <ul class="pagination pagination-sm">
                                <li><a href="../masterkey/?pagename=masterkey&spage=<?php echo $page + 1;
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


<script>
    $('#item1').change(function(){
        let product_id = $(this).val();
        $.ajax({
            type: "post",
            url: "../ajax_returns.php",

            data: {
					id: product_id,
					get_pattern: 'get_pattern'
				},
                success: function(data){
                    let json_data = JSON.parse(data);
                    // console.log(json_data.pattern);
                    $('.pattern').html(json_data['pattern']);
                }
        });
    })
</script>

