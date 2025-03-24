<?php
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $role = $_SESSION['role'];
    $shop = $_SESSION['shop'];
} else {
    header("Location:index.php");
    echo "<script>location='index.php'</script>";
}
include_once "../action/config.php";
$obj = new database();
$worker = $obj->get_rows("`worker`", "`id`,`name`", "`shop`='$shop' AND `payment_type`='day_wise'", 'name asc');
?>
<div class="row">
    <div class="col-md-12" id="addsupplier" style="display:none;">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1" class="save_attendance">Add Supplier</font>
                <font size="+1" class="update_attendance" style="display:none;">Update Supplier</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('supplierlist','addsupplier');">View Supplier</button>
            </div>
        </div><br>
        <form class="row" method="post" action="../action/insertData.php" id="supplierForm">
            <div class="col-md-12">
                <table class="table" style="width:90%; margin:0 auto;">
                    <tr>
                        <th>Date</th>
                        <td colspan="3"><input type="date" name="date" id="att_date" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <th width="20%">Name</th>
                        <td>
                            <input type="text" name="name" id="supplier_name" class="form-control">
                        </td>
                        <th width="20%">Mobile</th>
                        <td>
                            <input type="number" name="mobile" id="supplier_mobile" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">Email</th>
                        <td>
                            <input type="email" name="email" id="supplier_email" class="form-control">
                        </td>
                        <th width="20%">Shop Name</th>
                        <td>
                            <input type="text" name="shop_name" id="supplier_shop" class="form-control">
                        </td>
                    </tr>


                    <tr>
                        <th width="20%">Gst</th>
                        <td>
                            <input type="text" name="gst" id="supplier_gst" class="form-control">
                        </td>
                        <th width="20%">Pan No.</th>
                        <td>
                            <input type="text" name="pan_no" id="supplier_pan" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">Bank</th>
                        <td>
                            <input type="text" name="bank" id="supplier_bank" class="form-control">
                        </td>
                        <th width="20%">Account No.</th>
                        <td>
                            <input type="text" name="acc_no" id="supplier_acc" class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">IFSC</th>
                        <td>
                            <input type="text" name="ifsc" id="supplier_ifsc" class="form-control">
                        </td>
                        <th width="20%">State</th>
                        <td>
                            <select class="form-control" id='state_id' name="state">
                                <option value="" selected>Select State</option>
                                <?php
                                $table = "`sm_area`";
                                $columns = "*";
                                $where = "`type`='state' and `status`=1";
                                $order = "`id`";
                                $statelist = $obj->get_rows($table, $columns, $where, $order);
                                if ($statelist) {
                                    foreach ($statelist as $state) {
                                        echo "<option value='$state[id]'>$state[name]</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">District</th>
                        <td>
                            <select class="form-control" id="district_id" name="district">
                                <option value="">Select District</option>
                            </select>
                        </td>
                        <th width="20%">Pin code</th>
                        <td>
                            <input type="text" class="form-control" id="pin_code" name="pin_code">
                        </td>
                    </tr>

                    <tr>
                        <th width="20%">Address</th>
                        <td colspan="3"><textarea name="address" id="supplier_address" placeholder="Enter Address" class="form-control"></textarea>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <input type="hidden" name="shop" value="<?php echo $shop; ?>">
                            <input type="hidden" name="id" id="supplierid">
                            <input type="submit" class="btn btn-success btn-sm save_supplier" name="save_supplier" id="save_supplier" value="Save">
                            <input type="submit" class="btn btn-success btn-sm update_supplier" name="update_supplier" value="Update" style="display:none;">
                            <button type="button" class="btn btn-danger btn-sm update_supplier" onClick="window.location.reload();" style="display:none;">Cancel</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="col-md-12" id="supplierlist">
        <div class="row">
            <div class="col-md-12" style="padding:0 25px">
                <font size="+1">Supplier List</font>
                <button type="button" class="btn btn-primary btn-sm pull-right" onClick="showThis('addsupplier','supplierlist');">Add Supplier</button>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped table-condensed datatable" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;" width="10%">Sl. No.</th>
                            <th style="text-align:center;">Date</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;">mobile</th>
                            <th style="text-align:center;">Shop Name</th>
                            <th style="text-align:center;">gst</th>
                            <th style="text-align:center;">pan</th>

                            <th style="text-align:center;" width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $table = "`supplier`";
                        $columns = "*";
                        $where = "`shop`='$shop'";
                        $order = "`id`";
                        $supplier = $obj->get_rows($table, $columns, $where, $order);
                        if (is_array($supplier)) {
                            $i = 0;
                            foreach ($supplier as $spl) {
                                $i++;
                                // $worker = $obj->get_details("`supplier`", "*", "`id`='$attendance[worker]'");
                        ?>
                                <tr>
                                    <td align="center"><?php echo $i; ?></td>
                                    <td align="center"><?php echo $spl['date']; ?></td>
                                    <td align="center"><?php echo $spl['name']; ?></td>
                                    <td align="center"><?php echo $spl['mobile']; ?></td>
                                    <td align="center"><?php echo $spl['shop_name']; ?></td>
                                    <td align="center"><?php echo $spl['gst']; ?></td>
                                    <td align="center"><?php echo $spl['pan']; ?></td>
                                    <td align="center">
                            <?php //if ($attendance['paid'] == '0') { ?>
                                <button type="button" class="btn btn-primary btn-xs" onClick="editsupplier('<?php echo $spl['id']; ?>');"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-xs" onClick="deletesupplier('<?php echo $spl['id']; ?>');">
                                <i class="fa fa-trash"></i>
                            </button>
                            <?php //} ?>
                        </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>