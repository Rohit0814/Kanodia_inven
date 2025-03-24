<?php
include "./addpackable.php";
?>
<div class="container">
    <div class="row">
        <?php
        if (isset($_SESSION['msg'])) {
            echo "<h4 class='text-success text-center'>" . $_SESSION['msg'] . "</h4>";
            unset($_SESSION['msg']);
        }
        if (isset($_SESSION['err'])) {
            echo "<h4 class='text-danger text-center'>" . $_SESSION['err'] . "</h4>";
            unset($_SESSION['err']);
        }
        ?>
        <div class="col-md-12">
            <div id="formPanel" class="panel panel-primary">
                <div class="panel-heading" style="display: flex; justify-content: space-between;">
                    <font size="+2">Add Packable</font>
                    <div style="display: flex;">
                        <form action="../action/insertData.php" method="post" enctype="multipart/form-data">
                            <input type="file" id="fileUpload" name="import_file" style="display: none;" accept=".xls,.xlsx,.csv">
                            <a href="#" class="btn btn-default pull-right" style="line-height: initial; margin: 3px;" id="uploadBtn">Upload Excel</a>

                            <button type="submit" name="save_excel_data" style="display: none;" id="submitBtn"></button>
                        </form>

                        <form action="../action/insertData.php" method="post" enctype="multipart/form-data">
                            <!-- <input type="file" id="fileUpload" name="import_file" style="display: none;" accept=".xls,.xlsx,.csv"> -->
                            <a href="#" class="btn btn-default pull-right" style="line-height: initial; margin: 3px;" id="formateBtn">Excel Formate</a>
                            <button type="submit" name="formate_excel_data" style="display: none;" id="submitBtn"></button>
                        </form>
                    </div>

                    <script>
                        document.getElementById('uploadBtn').onclick = function() {
                            document.getElementById('fileUpload').click();
                        };

                        document.getElementById('fileUpload').onchange = function() {
                            if (this.files.length > 0) {
                                document.getElementById('submitBtn').click();
                            }
                        };

                        document.getElementById('formateBtn').onclick = function() {
                            window.location.href = "../action/excelFormate.php";
                        };
                    </script>
                </div>
                <div class="panel-body">
                    <form action="../action/insertData.php" method="post" enctype="multipart/form-data" id="packableForm">

                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="12%"><b>Job Id <span class="text-danger">*</span> :</b></td>
                                        <td width="38%"><input type="text" name="jobId" id="jobId" class="form-control" readonly /></td>
                                        <td width="12%"><b>Date <span class="text-danger">*</span> :</b></td>
                                        <td width="38%"><input type="text" name="bill_date" id="bill_date" class="form-control" value="<?php echo date('Y-m-d') ?>" readonly /></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <script>
                            // Function to generate a unique job ID
                            function generateJobId() {
                                const timestamp = Date.now(); // Get the current timestamp
                                const randomNum = Math.floor(Math.random() * 1000); // Generate a random number
                                return `JOB-${timestamp}`; // Format the job ID
                            }

                            // Set the job ID when the page loads
                            document.addEventListener("DOMContentLoaded", function() {
                                document.getElementById("jobId").value = generateJobId();
                            });
                        </script>
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <td width="12%"><b>Supplier <span class="text-danger">*</span> :</b></td>
                                        <td width="22%"><select name="supplier_id" id="supplier_id" class="form-control">
                                                <option value="">Select</option>
                                                <?php foreach ($supplier as $spl) {
                                                ?>
                                                    <option value="<?php echo $spl['id']; ?>"><?php echo $spl['name'] . ' - ' . $spl['shop_name']; ?></option>
                                                <?php
                                                } ?>
                                        </td>
                                        <td width="12%"><b>Bill No <span class="text-danger">*</span> :</b></td>
                                        <td width="22%"><input type="text" name="bill_no" id="bill_no" class="form-control" /></td>
                                        <td width="12%"><b>Date <span class="text-danger">*</span> :</b></td>
                                        <td width="22%"><input type="date" name="pur_date" id="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- end of form row 1 -->

                        <!-- <div class="row">
								<div class="table-responsive col-md-12">
									<table class="table table-bordered">
										<tr>
											<td width="12%"><b>Lot No. <span class="text-danger">*</span> :</b></td>
											<td width="38%"><input type="text" name="lot_no" id="jobId" class="form-control"/></td>
											<td width="12%"><b>Bale No. <span class="text-danger">*</span> :</b></td>
											<td width="38%"><input type="text" name="bale_no" id="bale_no" class="form-control" /></td>
										</tr>
									</table>
								</div>
							</div> -->
                        <div class="row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-condensed table-prod" style="margin-bottom:0;">
                                    <tbody class="tbody-prod">
                                        <tr>
                                            <td width="19%"><b>Bale No. <span class="text-danger">*</span> :</b>
                                                <input type="text" name="bale_no[]" id="bale_no" class="form-control bale_no" />
                                            </td>
                                            <td width="19%"><b>Lot No. <span class="text-danger">*</span> :</b>
                                                <input type="text" name="lot_no[]" id="jobId" class="form-control lot_no" />
                                            </td>




                                            <td width="19%">
                                                <b>Item:</b>
                                                <select name="raw_id[]" id="raw_id" class="form-control raw_id">
                                                    <option value="">Select</option>
                                                    <?php foreach ($raw_material as $raw) {
                                                        // if($raw['type']=='fabric'){
                                                    ?>
                                                        <option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
                                                    <?php }  ?>
                                                </select>
                                            </td>
                                            <td width="15%">
                                                <b>Width:</b>
                                                <select name="width_id[]" id="width_id" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php foreach ($width as $value) { ?>
                                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['width'] . " Inch"; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td width="19%">
                                                <b>D No.:</b>
                                                <input type="text" name="d_no[]" id="d_no" class="form-control">
                                            </td>
                                            <!-- <td width="19%">
												<b>Meter:</b>
												<input type="number" name="meter" id="meter" class="form-control">
											</td> -->
                                            <td width="19%">
                                                <b>Quantity:</b>
                                                <input type="text" name="quantity[]" value="1" id="quantity" class="form-control">
                                            </td>
                                            <td width="19%">
                                                <b>Unit: </b>
                                                <input type="text" name="Unit[]" class="form-control unit" readonly style="width: 80px;">
                                            </td>
                                            <td width="15%">
                                                <b>Image :</b>
                                                <input type="file" name="image[]" id="filePhoto" class="form-control-file" style="width:200px">
                                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" class="form-control" />
                                                <input type="hidden" name="tempbutton" id="tempbutton" /><input type="hidden" name="shop" id="shop" value="<?php echo $shop; ?>" />
                                            </td>
                                            <td align="center" style="vertical-align:middle; display: flex !important;" rowspan="6">
                                                <button type="button" id="addbutton" class="btn btn-primary btn-sm addnewBale" style="margin: 5px; padding: 10px 10px;" onclick="addnewBale()"><i class="fas fa-plus-circle"></i></button>
                                                <button type="button" id="addbale" class="btn btn-primary btn-sm addsameBale" style="margin: 5px; padding: 10px 10px;" onclick="addsameBale()"><i class="fas fa-share-square"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table><!-- end of product table -->
                            </div><!-- end of table div  -->
                        </div><!-- end of form row 2 -->
                        <div class="row">
                            <div class="text-center" id="response" style="display:none;"></div>
                            <div class="col-md-12 table-responsive" id="purchase_temp" style="-color:#eeeeee; padding:0;">
                                <?php //include('packabletemp.php'); 
                                ?>
                            </div><!-- end of table div -->
                        </div><!-- end of form row 3 --><br />
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <tr>
                                        <td align="center" style="vertical-align:middle;" colspan="2">
                                            <input type="hidden" name="add_packable" value="save" id="savebutton1" />
                                            <button type="submit" id="savebutton" class="btn btn-success btn-sm">save</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div><!-- end of form row 4 -->
                    </form>
                </div>
            </div><!-- form panel closed-->
        </div><!-- end of col-md-12 -->
    </div><!-- end of row -->
</div><!-- end of container -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script>
    // This function adds a new row when the "addnewBale" button is clicked
    function addnewBale() {
        // Define the HTML for the new row
        let stock_tbl = `
        <tbody>
            <tr>
                <td width="19%"><b>Bale No. <span class="text-danger">*</span> :</b>
                    <input type="text" name="bale_no[]" class="form-control bale_no" />
                </td>
                <td width="19%"><b>Lot No. <span class="text-danger">*</span> :</b>
                    <input type="text" name="lot_no[]" class="form-control lot_no" />
                </td>
                <td width="19%">
                    <b>Item:</b>
                    <select name="raw_id[]" class="form-control raw_id">
                        <option value="">Select</option>
                        <?php foreach ($raw_material as $raw) { ?>
                            <option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td width="15%">
                    <b>Width:</b>
                    <select name="width_id[]" class="form-control">
                        <option value="">Select</option>
                        <?php foreach ($width as $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['width'] . " Inch"; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td width="19%">
                    <b>D No.:</b>
                    <input type="text" name="d_no[]" class="form-control">
                </td>
                <td width="19%">
                    <b>Quantity:</b>
                    <input type="text" name="quantity[]" value="1" class="form-control">
                </td>
                <td width="19%">
                                                <b>Unit: </b>
                                                <input type="text" name="Unit[]" class="form-control unit" readonly style="width: 80px;">
                                            </td>
                <td width="15%">
                    <b>Image :</b>
                    <input type="file" name="image[]" class="form-control-file" style="width:200px">
                </td>
                <td align="center" style="vertical-align:middle; display: flex !important;" rowspan="6">
                    <button type="button" class="btn btn-primary btn-sm addsameBale" style="margin: 5px; padding: 10px 10px;"><i class="fas fa-share-square"></i></button>
                </td>
            </tr>
            </tbody>
            `;

        // Append the new row to the closest <tbody> using event delegation
        $(this).closest('table').append(stock_tbl);

        $('.raw_id').change(function(){
        let raw_id = $(this).val();
        // console.log(raw_id);
        let raw = $(this);
        $.ajax({
            url: '../ajax_returns.php',
            type: 'post',
            data: { raw_id: raw_id,
                get_unit_stock: 'get_unit_stock'
             },
            success: function(response) {
                response = JSON.parse(response);
                // console.log(response['process']['unit']);
                raw.closest('tbody').find('.unit').val(response['process']['unit']);
            }
        });
    })
    }

    // This function adds the same row when the "addsameBale" button is clicked
    function addsameBale() {
        // Define the HTML for the new row
        let bale_no = $(this).closest('tr').find('.bale_no').val();
        let lot_no = $(this).closest('tr').find('.lot_no').val();
        if(bale_no==""){
            alert("Please enter Bale No.");
            return false;
        }
        let stock_tbl = `
            <tr>
                <td width="19%"><b>Bale No. <span class="text-danger">*</span> :</b>
                    <input type="text" name="bale_no[]" class="form-control bale_no" value="${bale_no}" readonly/>
                </td>
                <td width="19%"><b>Lot No. <span class="text-danger">*</span> :</b>
                    <input type="text" name="lot_no[]" class="form-control lot_no" value="${lot_no}" />
                </td>
                <td width="19%">
                    <b>Item:</b>
                    <select name="raw_id[]" class="form-control raw_id">
                        <option value="">Select</option>
                        <?php foreach ($raw_material as $raw) { ?>
                            <option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td width="15%">
                    <b>Width:</b>
                    <select name="width_id[]" class="form-control">
                        <option value="">Select</option>
                        <?php foreach ($width as $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['width'] . " Inch"; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td width="19%">
                    <b>D No.:</b>
                    <input type="text" name="d_no[]" class="form-control">
                </td>
                <td width="19%">
                    <b>Quantity:</b>
                    <input type="text" name="quantity[]" value="1" class="form-control">
                </td>
                <td width="19%">
                                                <b>Unit: </b>
                                                <input type="text" name="Unit[]" class="form-control unit" readonly style="width: 80px;">
                                            </td>
                <td width="15%">
                    <b>Image :</b>
                    <input type="file" name="image[]" class="form-control-file" style="width:200px">
                </td>
                <td align="center" style="vertical-align:middle; display: flex !important;" rowspan="6">
                    
                </td>
            </tr>`;

        // Append the new row to the closest <tbody> using event delegation
        $(this).closest('tbody').append(stock_tbl);
        $('.raw_id').change(function(){
        let raw_id = $(this).val();
        // console.log(raw_id);
        let raw = $(this);
        $.ajax({
            url: '../ajax_returns.php',
            type: 'post',
            data: { raw_id: raw_id,
                get_unit_stock: 'get_unit_stock'
             },
            success: function(response) {
                response = JSON.parse(response);
                // console.log(response['process']['unit']);
                raw.closest('tr').find('.unit').val(response['process']['unit']);
            }
        });
    })
    }

    // Event listener for dynamically added buttons using event delegation
    $(document).on('click', '.addnewBale', addnewBale);
    $(document).on('click', '.addsameBale', addsameBale);
</script>

<script>
    $('.raw_id').change(function(){
        let raw_id = $(this).val();
        // console.log(raw_id);
        let raw = $(this);
        $.ajax({
            url: '../ajax_returns.php',
            type: 'post',
            data: { raw_id: raw_id,
                get_unit_stock: 'get_unit_stock'
             },
            success: function(response) {
                response = JSON.parse(response);
                // console.log(response['process']['unit']);
                raw.closest('tbody').find('.unit').val(response['process']['unit']);
            }
        });
    })
</script>

<script language="javascript">
        

    $(document).ready(function(e) {
        $('#packableForm').on('submit', function(e) {

            if ($('#bale_no').val() == undefined && $('#tempbutton').val() != 'add') {
                alert('Please Enter Bale No.');
                return false;
            }
            if ($('#date').val() == undefined && $('#tempbutton').val() != 'add') {
                alert('Please Enter Date');
                return false;
            }
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "../action/insertData.php",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#response').html("<h4 class='text-success'>" + data + "</h4>");
                    $('#response').show();
                    $('#response').hide(5000);
                    setTimeout((function() {
                        location.reload();
                    }), 3000);
                },
            });
        });


        $('#savebutton').on('click', function(e) {
            // if ($('#bale_no').val() == '') {
            // 	alert('Please Enter Bale No.');
            // 	return false;
            // }
            // if ($('#date').val() == '') {
            // 	alert('Please Enter Date');
            // 	return false;
            // }
            $('#savebutton1').val('save');
            $('#tempbutton').val('');
            $('#packableForm').submit()
        });



        $('#check_advance').click(function() {
            var total_amount = $('#total_amount').val();
            var advance = $('#advance').val();
            $('#paid').val('');
            if ($(this).is(":checked")) {
                var dues = total_amount - advance;
                if (dues < 0) {
                    dues = 0;
                }
            } else {
                dues = total_amount;
            }
            $('#dues').val(dues);
            if (dues == 0) {
                $('#next_payment').attr('readonly', true);
            } else {
                $('#next_payment').attr('readonly', false);
            }
        });
    });
    $('#purchase').keyup(function() {
        var category = $('#category').val();
        var purchase = Number($(this).val());
        var json = '<?php if (!empty($gst)) {
                        echo json_encode($gst);
                    } ?>';
        var gst = JSON.parse(json);
        var price_limit = Number(gst[category]['price_limit']);
        var cgst = gst[category]['cgst'];
        var sgst = gst[category]['sgst'];
        var igst = gst[category]['igst'];
        if (price_limit != '' && purchase > price_limit) {
            cgst = gst[category]['pcgst'];
            sgst = gst[category]['psgst'];
            igst = gst[category]['pigst'];
        }
        if (category != '') {
            cgst = "@ " + cgst + "%";
            sgst = "@ " + sgst + "%";
            igst = "@ " + igst + "%";

            $('#cvalue').html(cgst);
            $('#svalue').html(sgst);
            $('#ivalue').html(igst);

        }
    });

    function getMobile(str) {
        var id = str;
        var code = "<?php if (!empty($state_code)) {
                        echo $state_code;
                    } ?>";
        $('#check_advance').attr("checked", false);
        $('#dues').val('');
        $('#paid').val('');
        if (id != '') {
            var numbers = '<?php if (!empty($array)) {
                                echo json_encode($array);
                            } ?>';
            var mobiles = JSON.parse(numbers);
            $('#mobile').val(mobiles[id]['phone']);
            var state = mobiles[id]['state'];
            $('#advance').val(mobiles[id]['advance']);
            if (state == code) {
                $("input[name=gst][value='cgst']").prop("checked", true);
            } else {
                $("input[name=gst][value='igst']").prop("checked", true);
            }
        } else {
            $('#mobile').val('');
            $('#advance').val(0);
            $('input[name=gst]').prop("checked", false);
        }
    }

    function getCheque(str) {
        var mode = str;
        if (mode == 'cheque') {
            $('.cheque').show();
        } else {
            $('.cheque').hide();
        }
    }

    function resetFields(str) {
        if (str == 'category') {
            var company = "<option value=''>Select Company</option>";
            $('#company_id').html(company);
            $('#cvalue').html("");
            $('#svalue').html("");
            $('#ivalue').html("");
        }
        if (str == 'category' || str == 'company') {
            var models = "<option value=''>Select Model</option>";
            $('#model').html(models);
        }
        $('#hsn').val('');
        $('#mrp').val('');
        $('#uom').val('');
        $('#purchase').val('');
        $('#quantity').val('');
        $('#charity').val('');
        $('#discount').val('');
        $('#cust_discount').val('');
        $('#special_discount').val('');
        $('#cash_discount').val('');
        $('#tempbutton').val('');
    }

    function resetAmount() {
        $('#gross_amount').val($('#temp_amount').val());
        $('#round').val($('#temp_round').val());
        $('#total_amount').val($('#temp_total').val());
        $('#check_advance').attr("checked", false);
        $('#transport').val('');
        $('#paid').val('');
        $('#dues').val('');
    }

    function getCompany(str) {
        var category = str;
        resetFields('category');
        resetAmount();
        var shop = '<?php echo $shop; ?>';
        if (category != '') {
            $.ajax({
                type: 'POST',
                url: "../ajax_returns.php",
                data: {
                    category: category,
                    shop: shop,
                    get_company: 'get_company',
                    page: 'purchase'
                },
                success: function(data) {
                    $("#company_id").html(data);
                    var array = '<?php echo json_encode($gst); ?>';
                    var gst = JSON.parse(array);
                    var cgst = gst[category]['cgst'];
                    var sgst = gst[category]['sgst'];
                    var igst = gst[category]['igst'];
                    $('#cvalue').html("@ " + cgst + "%");
                    $('#svalue').html("@ " + sgst + "%");
                    $('#ivalue').html("@ " + igst + "%");

                }
            });
        }
    }

    function getModel(str) {
        var company_id = str;
        var category = $('#category').val();
        var shop = '<?php echo $shop; ?>';
        resetFields('company');
        resetAmount();
        $.ajax({
            type: 'POST',
            url: "../ajax_returns.php",
            data: {
                company_id: company_id,
                category: category,
                get_model: 'get_model',
                page: 'purchase',
                shop: shop
            },
            success: function(data) {
                $('#model').html(data);
            }
        });
    }

    function selectModel(str) {
        var model = str;
        var shop = '<?php echo $shop; ?>';
        $.ajax({
            type: 'POST',
            url: "../ajax_returns.php",
            data: {
                model: model,
                get_hsn: 'get_hsn',
                page: 'purchase',
                shop: shop
            },
            success: function(data) {
                //  console.log(data);
                // alert(data);
                $('#hsn').val(data);
            }
        });
        resetFields('');
        resetAmount();
    }


    function validateAdd() { //add product
        // var reader = new FileReader();
        // var img = reader.readAsDataURL(document.getElementById("image").files[0]);
        // alert(img);
        var item = document.getElementById("raw_id");
        var width = document.getElementById("width_id");
        var d_no = document.getElementById("d_no");
        var quantity = document.getElementById("quantity");
        var shop = '<?php echo $shop; ?>';
        var user_id = '<?php echo $user_id; ?>';
        //alert(item.value + "," + width.value + "," + d_no.value);
        $('#addbutton').addClass("disabled");
        if (item.value == '') {
            $('#addbutton').removeClass("disabled");
            alert("Select an item!!");
            return false;
        }
        if (quantity.value == '') {
            $('#addbutton').removeClass("disabled");
            alert("Enter Quantity!!");
            $('#quantity').focus();
            return false;
        }
        if (d_no.value == '' || d_no.value == 0) {
            $('#addbutton').removeClass("disabled");
            alert("Enter Rate!!");
            $('#purchase').focus();
            return false;
        } else {
            $('#tempbutton').val('add');
            $.ajax({
                type: "POST",
                url: "../action/insertData.php",
                data: $("#packableForm").serialize(), //serializes the form's elements.
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    //  console.log(data);
                    $('#response').html("<h4 class='text-success'>" + data + "</h4>");
                    $('#response').show();
                    $('#response').hide(500);
                    $('#addbutton').removeClass("disabled");
                    $.ajax({
                        type: "GET",
                        url: "packabletemp.php",
                        data: {
                            shop: shop,
                            user_id: user_id
                        },
                        success: function(data) {
                            // console.log(data);
                            $('#packabletemp').html(data);
                            location.reload();
                        }
                    });
                }
            });
            //e.preventDefault();// avoid to execute the actual submit of the form.
        }
    } //add product

    function deleteTemp(str) {
        var id = str;
        var shop = '<?php echo $shop; ?>';
        if (confirm("Are you sure you want to Delete this?")) {
            $.ajax({
                url: '../action/deleteData.php',
                type: 'GET',
                data: {
                    id: id,
                    shop: shop,
                    del_packable_temp: 'del_packable_temp'
                },
                success: function(data) //on recieve of reply
                {
                    //alert(data); // show response from the php script.
                    $('#response').html("<h4 class='text-success'>" + data + "</h4>");
                    $('#response').show();
                    $('#response').hide(5000);
                    $.ajax({
                        type: "GET",
                        url: "packabletemp.php",
                        data: {
                            shop: shop
                        },
                        success: function(data) {
                            $('#packabletemp').html(data);
                            location.reload();

                        }
                    });
                }
            });
        }
    }


    function addSameBale(str) {
        var id = str;
        var shop = '<?php echo $shop; ?>';
        $.ajax({
            url: '../ajax_returns.php',
            type: 'GET',
            data: {
                id: id,
                shop: shop,
                same_packable_temp: 'same_packable_temp'
            },
            success: function(data) //on recieve of reply
            {
                let json_data = JSON.parse(data);
                $('#supplier_id').val(json_data.supplier_id);
                $('#bale_no').val(json_data.bale_no);
            }
        });
    }

    function calcTransport(str) {
        var transport = parseFloat(str);
        if (isNaN(transport)) {
            transport = 0;
        }
        var amount = parseFloat($('#temp_total').val());
        var total = amount + transport;
        $('#next_payment').attr('readonly', false);
        $('#dues').val('');
        $('#paid').val('')
        $('#total_amount').val(total);
        $('#check_advance').attr("checked", false);
        $('#paid').val('');
        $('#dues').val('');

    }

    function calcDues(str) {
        var paid = parseFloat(str);
        if (isNaN(paid)) {
            paid = 0;
        }
        var total = parseFloat($('#total_amount').val());
        if ($('#check_advance').is(":checked")) {
            var advance = parseFloat($('#advance').val());
            if (isNaN(advance)) {
                advance = 0;
            }
        } else {
            var advance = 0;
        }
        var dues = total - advance - paid;
        $('#dues').val(dues)
        if (dues < 0) {
            d = 0 - dues;
            alert("Return Rs." + d);
            $('#next_payment').attr('readonly', true);
        } else if (dues > 0) {
            $('#next_payment').attr('readonly', false);
        } else {
            $('#next_payment').attr('readonly', true);
        }
    }

    



    // function validate(){
    // 	var type=$('#type').val();
    // 	var paid=$('#paid').val();
    // 	var final=$('#total_amount').val();
    // 	if(final==0){
    // 		alert("Add Product");
    // 		return false;	
    // 	}
    // 	if(type=='credit'){
    // 		$('#payment_mode').val('');
    // 		$('#paid').val('0');
    // 		$('#dues').val(final);
    // 		$('#check_advance').attr("checked",false);
    // 	}	
    // 	else{
    // 		var mode=$('#payment_mode').val();
    // 		var paid=$('#paid').val();
    // 		if(mode==''){
    // 			if(paid!='' && paid!=0){
    // 				alert("Select Payment Mode");
    // 				$('#payment_mode').focus();
    // 				return false;
    // 			}
    // 			else if($('#check_advance').is(":checked")){
    // 			}
    // 			else{
    // 				$('#type').val('credit');
    // 				$('#paid').val('0');
    // 				$('#dues').val(final);
    // 			}
    // 		}
    // 		else if(paid==''){
    // 			alert("Enter Paid Amount!");
    // 			$('#paid').focus();
    // 			return false;	
    // 		}
    // 	}
    // 	if(confirm("Click Ok to Submit. \nClick Cancel to Edit.")){
    // 		return true;
    // 	}
    // 	else{
    // 		return false;
    // 	}

    // }
</script>

<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>