<?php
include "./bale_meter_stock.php";
?>

<style>
    details {
        margin: 1rem auto;
        padding: 0 1rem;
        /* width: 35em; */
        max-width: calc(100% - 2rem);
        position: relative;
        border: 1px solid #78909C;
        border-radius: 6px;
        background-color: white;
        color: black;
        transition: background-color 0.15s;
    }

    details> :last-child {
        margin-bottom: 1rem;
    }

    details::before {
        width: 100%;
        height: 100%;
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        border-radius: inherit;
        opacity: 0.15;
        box-shadow: 0 0.25em 0.5em #263238;
        pointer-events: none;
        transition: opacity 0.2s;
        z-index: -1;
    }

    details[open] {
        background-color: #FFF;
        color: black;
    }

    details[open]::before {
        opacity: 0.6;
    }

    summary {
        padding: 1rem 2em 1rem 0;
        display: block;
        position: relative;
        font-size: 1.33em;
        font-weight: bold;
        cursor: pointer;
    }

    summary::before,
    summary::after {
        width: 0.75em;
        height: 2px;
        position: absolute;
        top: 50%;
        right: 0;
        content: "";
        background-color: currentColor;
        text-align: right;
        transform: translateY(-50%);
        transition: transform 0.2s ease-in-out;
    }

    summary::after {
        transform: translateY(-50%) rotate(90deg);
    }

    [open] summary::after {
        transform: translateY(-50%) rotate(180deg);
    }

    summary::-webkit-details-marker {
        display: none;
    }

    p {
        margin: 0 0 1em;
        line-height: 1.5;
    }

    ul {
        margin: 0 0 1em;
        padding: 0 0 0 1em;
    }

    li:not(:last-child) {
        margin-bottom: 0.5em;
    }

    code {
        padding: 0.2em;
        border-radius: 3px;
        background-color: #E0E0E0;
    }

    pre>code {
        display: block;
        padding: 1em;
        margin: 0;
    }
</style>
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
                    <font size="+2">Bale Meter Tally</font>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="10%"><b>Bale No. <span class="text-danger">*</span> :</b></td>
                                    <?php $bale_no = $obj->get_rows("stock","DISTINCT bale_id "); 
                                    // print_r($bale_no);
                                    ?>
                                    <!-- <td width="16%"><input type="text" name="bill_no" id="bale_no" class="form-control" > </td> -->
                                    <td width="16%">
                                        <select class="form-control" id="bale_no">
                                            <option value=''>Select Bale No</option>
                                            <?php foreach($bale_no as $bale){
                                                ?> 
                                                    <option value=<?php echo $bale['bale_id'] ?>><?php echo $bale['bale_id'] ?></option>
                                                <?php
                                            } ?>
                                        </select>    
                                    </td>
                                    <td width="10%"><b>Item Name <span class="text-danger">*</span> :</b></td>
                                    <td width="16%"><select name="supplier_id" id="raw_id" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($raw_material as $raw) {
                                                // if($raw['type']=='fabric'){
                                            ?>
                                                <option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
                                            <?php }  ?>
                                    <td width="10%"><b>Width <span class="text-danger">*</span> :</b></td>
                                    <td width="16%"><select name="supplier_id" id="width_id" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach ($width as $value) { ?>
                                                <option value="<?php echo $value['id']; ?>"><?php echo $value['width'] . " Inch"; ?></option>
                                            <?php } ?></td>
                                            <?php $lot_no = $obj->get_rows("stock","DISTINCT lot_no "); ?>
                                    <td width="10%"><b>Lot No.<span class="text-danger">*</span> :</b></td>
                                    <!-- <td width="16%"><input type="text" name="bill_no" id="lot_no" class="form-control" /></td> -->
                                    <td width="16%">
                                        <select class="form-control" id="lot_no">
                                            <option value=''>Select Lot No</option>
                                            <?php foreach($lot_no as $lot){
                                                ?> 
                                                    <option value=<?php echo $lot['lot_no'] ?>><?php echo $lot['lot_no'] ?></option>
                                                <?php
                                            } ?>
                                        </select>    
                                    </td>
                                </tr>

                                <tr>
                                    <td width="100%" colspan="8">
                                        <center><button class="btn btn-success search_stock">Search</button></center>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="../action/insertData.php" method="post">
                        <div>
                            <div>
                                <div class="row meter_breakup" id="print_balemeter" style="padding:10px">
                                </div>
                            </div>
                        </div>

                        <div class="row colum_rotal" style="display: none;">
                            <div class="col-md-4">
                                <label for="bale_meter_tally">Total</label>
                                <input type="text" class="form-control bale_meter_tally" id="total_breakup" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="bale_meter_tally">Difference</label>
                                <input type="text" class="form-control bale_meter_tally" id="breakup_diff" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="bale_meter_tally">Remark</label>
                                <input type="text" name="remark" class="form-control bale_meter_tally">
                            </div>
                        </div>
                        <br>

                        <div>
                            <center>
                                <div class="generate_barcode" style="display: none;">
                                    <input type="hidden" name="save_balemeter_tally">
                                    <button type="submit" class="btn btn-info" id="print_bale_meter"><i class="fas fa-barcode"></i> Generate Barcode</button>
                                </div>
                            </center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

</script>

<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script>
    var total = 0;
    let difference = 0;
    let total_quant = 0;
    let quant = 0;
    // Function to search stock based on the selected supplier and item
    $(".search_stock").click(function() {
        total = 0;
        difference = 0;
        total_quant = 0;
        quant = 0;
        const raw_id = $("#raw_id").val();
        const width_id = $("#width_id").val();
        const bale_no = $("#bale_no").val();
        const lot_no = $("#lot_no").val();
        $(".meter_breakup").html(''); // Clear previous results

        $.ajax({
            url: "../ajax_returns.php",
            type: "POST",
            data: {
                raw_id: raw_id,
                width_id: width_id,
                bale_no: bale_no,
                lot_no: lot_no,
                bale_meter_tally: "bale_meter_tally"
            },
            success: function(response) {
                let json_data = JSON.parse(response);
                // console.log(json_data);

                if (json_data.length == 0 || json_data == false) {
                    alert("No stock found for the given criteria.");
                    return false;
                }



                // Loop through each item in the response (json_data)
                for (let i = 0; i < json_data.length; i++) {
                    // Create a column for each bale's stock_id
                    let newColumn = document.createElement('div');
                    newColumn.classList.add('col-md-12'); // Add a column for the bale
                    // newColumn.style.border = '1px solid black';
                    // newColumn.style.padding = '10px';
                    newColumn.style.marginBottom = '10px';
                    quant = parseFloat(quant) + parseFloat(json_data[i].quantity);

                    // Add the stock data to the new column (item details)
                    //     newColumn.innerHTML = `
                    //     <div class="col-md-12">
                    //         <label for="bale_meter_tally">Item</label>
                    //         <input type="text" value="${json_data[i].name}" class="form-control bale_meter_tally" readonly>
                    //         <label for="bale_meter_tally">Bale No.</label>
                    //         <input type="text" value="${json_data[i].bale_id}" class="form-control bale_meter_tally" readonly>
                    //         <label for="bale_meter_tally">Quantity</label>
                    //         <input type="text" value="${json_data[i].quantity}" class="form-control bale_meter_tally total_quant" readonly>
                    //     </div>
                    //     <div class="col-md-9" id="rowstock_${json_data[i].stock_id}">
                    //         <label for="bale_meter_tally">Meter Breakup</label>
                    //         <input type="text" name="bale_meter_tally[${json_data[i].stock_id}][]" class="form-control bale_meter_input" id="meter_break_${json_data[i].stock_id}" style="border-radius: unset;">
                    //     </div>
                    //     <div class="col-md-2" >
                    //         <input type="hidden" name="stock_id[]" class="form-control" value="${json_data[i].stock_id}" readonly>
                    //         <button type="button" class="btn btn-success add_breakup" style="margin: 24px 0px;" data-stockid="${json_data[i].stock_id}">Add</button>
                    //     </div>
                    // `;
                    // console.log(json_data[i]);
                    newColumn.innerHTML = `
                    <details class="bale-column">
                                                        <summary>
                                                            <table class="table" style="margin-bottom: 0px !important; font-size: small;">
                                                                <tr>
                                                                    <th style="border-top: none !important; width:4%; white-space: nowrap;">${i+1}</th>
                                                                    <th style="border-top: none !important; width:10%;white-space: nowrap;">Date - ${json_data[i].date}</th>
                                                                    <th style="border-top: none !important; width:20%;white-space: nowrap;">Item - ${json_data[i].name}</th>
                                                                    <th style="border-top: none !important; width:16%; white-space: nowrap;">Supplier - ${json_data[i].supplier_name}</th>
                                                                    <th style="border-top: none !important; width:8%; white-space: nowrap;">Width - ${json_data[i].width_name} ${json_data[i].width_unit}</th>
                                                                    <th style="border-top: none !important; width:8%; white-space: nowrap;">Quantity - ${json_data[i].quantity} ${json_data[i].unit}</th>
                                                                </tr>
                                                            </table>
                                                        </summary>
                              
                        <div class="col-md-9" id="rowstock_${json_data[i].stock_id}">
                            <label for="bale_meter_tally">Meter Breakup</label>
                            <input type="text" name="bale_meter_tally[${json_data[i].stock_id}][]" class="form-control bale_meter_input" id="meter_break_${json_data[i].stock_id}" style="border-radius: unset;">
                        </div>
                        <div class="col-md-2" >
                            <input type="hidden" name="stock_id[]" class="form-control" value="${json_data[i].stock_id}" readonly>
                            <button type="button" class="btn btn-success add_breakup" style="margin: 24px 0px;" data-stockid="${json_data[i].stock_id}">Add</button>
                        </div>
                                                       
                                                    </details>
                `;
                    // Append the new column to the parent container
                    $(".meter_breakup").append(newColumn);

                    // AJAX request to get the meter breakup data for each stock_id
                    $.ajax({
                        url: "../ajax_returns.php",
                        type: "POST",
                        data: {
                            stock_id: json_data[i].stock_id,
                            get_meterbreakup: "meter_breakup"
                        },
                        success: function(response2) {
                            let balemeter = JSON.parse(response2);
                            if (balemeter.length == 0 || balemeter == false) {
                                // console.log("hii");
                                // total += parseInt(balemeter[0]['meter_breakup']);
                                // difference += parseInt(balemeter[0]['meter_breakup']) - parseInt(balemeter[1]['meter_breakup']);
                                return false;
                            }
                            // console.log(balemeter);
                            $('#meter_break_' + json_data[i].stock_id).val(balemeter[0]['meter_breakup']);
                            $('#meter_break_' + json_data[i].stock_id).removeAttr('name');


                            $('#meter_break_' + json_data[i].stock_id).attr('readonly', true);
                            total += parseInt(balemeter[0]['meter_breakup']);
                            // console.log(balemeter);
                            // if(balemeter==null){
                            //     console.log("hii");
                            // }
                            // console.log(total);
                            // difference += parseInt(balemeter[0]['meter_breakup']) - parseInt(balemeter[1]['meter_breakup']);
                            // Loop through each meter breakup entry and add a new row in the same column
                            for (let j = 1; j < balemeter.length; j++) {
                                // Find the rowstock column based on the stock_id
                                let column = $('#rowstock_' + json_data[i].stock_id);
                                // console.log(balemeter[i]);

                                // Create a new row with a textbox for the meter breakup
                                let newRow = document.createElement('div');
                                newRow.classList.add('row', 'bale-row');
                                newRow.innerHTML = `
                                <div class="col-md-12" style="width: 100%;">
                                    <input type="text" value="${balemeter[j]['meter_breakup']}" class="form-control bale_meter_input" style="border-radius: unset;" readonly>
                                </div>
                            `;
                                // Append the new row inside the column
                                column.append(newRow);
                                total += parseFloat(balemeter[j]['meter_breakup']);
                                total_quant = parseFloat(quant) - parseFloat(total);
                                // console.log(total_quant);
                                // console.log(total);

                                // difference += parseInt(balemeter[j]['meter_breakup']) - parseInt(balemeter[j-1]['meter_breakup']);
                            }
                            $('#breakup_diff').val(total_quant);
                            $('#total_breakup').val(total);
                        }
                    });
                    // total = $('#total_breakup').val();


                    $('.generate_barcode').show();
                    $('.colum_rotal').show();
                }
            }
        });
    });


    function add_breakup(button) {
        // Find the closest parent column of the button and add a new row to that column
        let column = $(button).closest('.bale-column'); // Ensure .bale-row is the parent container of the button
        let stock_id = $(button).data('stockid'); // Use .data('stockid') instead of .attr('data-stockid')
        // console.log(total);
        // Create a new row dynamically
        let newRow = document.createElement('div');
        newRow.classList.add('row', 'bale-row');

        // Add content to the new row (new input for Meter Breakup)
        newRow.innerHTML = `
        <div class="col-md-11" style="width: 76.5%;  margin: 0px 0px 0px 15px;">
            <input type="text" name="bale_meter_tally[${stock_id}][]" class="form-control bale_meter_input" style="border-radius:unset;width: 95.2%;">
        </div>
    `;

        // Append the new row inside the same column
        column.append(newRow);
    }



    // Use event delegation to handle click events for dynamically added buttons
    $(document).on('click', '.add_breakup', function() {
        add_breakup(this); // Pass the button element to add_breakup
    });

    $(document).on('input', '.bale_meter_input', function() {
        let input = $('.bale_meter_input');
        total = 0;
        // console.log(input);
        for (let i = 0; i < input.length; i++) {
            // console.log(input[i].value);
            if (input[i].value != '') {
                total = parseFloat(total) + parseFloat(input[i].value);
            }
        }
        // input = $(this).val();
        // let tmp=total;
        // if(input!=''){
        //     total= parseInt(total) + parseInt(input);
        // }
        // else{
        //     total=tmp;
        // }
        // console.log(quant);
        total_quant = parseFloat(quant) - parseFloat(total);
        // console.log(total_quant);
        $('#breakup_diff').val(total_quant);
        $('#total_breakup').val(total);
        // difference += parseInt($(this).val()) - parseInt(inputs[inputs.length-2].value);
        // $('#breakup_diff').val(difference);
        // console.log(difference);
    })
</script>
</body>

</html>