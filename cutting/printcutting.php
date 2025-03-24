<?php
include('../action/config.php');
$obj = new database();
$id = $_GET['id'];
$table = "`sm_order`";
$columns = "*";
$where = "`id`=$id";
$cutting = $obj->get_details($table, $columns, $where);
// $worker=$obj->get_details('`worker`','*',"`id`=$cutting[worker]");

$process = $_GET['pagename'];
$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");


$table = "`order_product`";
$columns = "*";
$where = "`order_id`=$id";
$order_prod = $obj->get_rows($table, $columns, $where);

// print_r($order_prod); die;

// $cuttingitem=$obj->get_rows('`cuttingitem`','*',"`cutting_id`=$cutting[id]");
// $cuttingitem=$obj->get_rows('`cuttingitem` t1,`stock` t2,`raw_material` t3,`width` t4','t1.*,t2.raw_id,t2.d_no,t2.meter,t3.name as raw_material,t4.width',"t1.`cutting_id`=$cutting[id] and t1.stock_id=t2.id and t2.raw_id=t3.id and t2.width_id=t4.id");

// echo "<pre>";print_r($cuttingitem);
// echo "<pre>";print_r($worker);die;
?>
<html>

<head>
    <title>Print Cutting Sheet</title>
    <style type="text/css" media="print">
        body {
            margin: 0px;
            padding: 0px;
        }

        @page {
            margin: 0;
            /*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
            /*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
        }

        @media print {
            table {
                page-break-inside: avoid;
            }

            #buttons {
                display: none;
            }

            #page {
                margin: 10px 20px;
            }
        }
    </style>
</head>

<body>
    <?php
    include('../action/config.php');
    $obj = new database();
    $id = $_GET['id'];
    $table = "`sm_order`";
    $columns = "*";
    $where = "`id`=$id";
    $cutting = $obj->get_details($table, $columns, $where);
    // $worker=$obj->get_details('`worker`','*',"`id`=$cutting[worker]");

    $process = $_GET['pagename'];
    $job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
    // print_r($job_process); die;

    // $cuttingitem=$obj->get_rows('`cuttingitem`','*',"`cutting_id`=$cutting[id]");
    // $cuttingitem=$obj->get_rows('`cuttingitem` t1,`stock` t2,`raw_material` t3,`width` t4','t1.*,t2.raw_id,t2.d_no,t2.meter,t3.name as raw_material,t4.width',"t1.`cutting_id`=$cutting[id] and t1.stock_id=t2.id and t2.raw_id=t3.id and t2.width_id=t4.id");

    // echo "<pre>";print_r($cuttingitem);
    // echo "<pre>";print_r($worker);die;
    ?>
    <html>

    <head>
        <title>Print Cutting Sheet</title>
        <style type="text/css" media="print">
            body {
                margin: 0px;
                padding: 0px;
            }

            @page {
                margin: 0;
                /*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
                /*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
            }

            @media print {
                table {
                    page-break-inside: avoid;
                }

                #buttons {
                    display: none;
                }

                #page {
                    margin: 10px 20px;
                }
            }

            #page {
                font-family: Arial, sans-serif;
                margin: 20px;
            }

            /* Title Styling */
            .page-title {
                text-align: center;
                font-size: 24px;
                margin-bottom: 20px;
                color: #333;
            }

            /* Table Styles */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            td {
                background-color: #fff;
            }

            tr:nth-child(even) td {
                background-color: #f9f9f9;
            }

            /* Final summary table styling */
            .final-summary td {
                font-weight: bold;
                background-color: #f2f2f2;
            }

            /* Specific table for product details */
            .product-table thead {
                background-color: #4CAF50;
                color: white;
            }

            .product-table th,
            .product-table td {
                text-align: left;
                padding: 8px;
            }

            .info-table,
            .item-table,
            .detail-table,
            .final-summary {
                margin-bottom: 20px;
            }

            /* Hover effect on table rows */
            tr:hover {
                background-color: #f1f1f1;
            }
        </style>
    </head>

    <body>
        <section id="page">
            <h3 style="text-align:center;"><?php echo $job_process[0]['process'] . ' Details'; ?></h3>
            <table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
                <tr>
                    <th>Date</th>
                    <td><?= $cutting['date']; ?></td>
                    <th>Order ID</th>
                    <td><?= $cutting['order_id']; ?></td>
                </tr>
            </table>
            <?php if (!empty($cuttingitem)) {
                $i = 0;
                foreach ($cuttingitem as $item) {
                    $i++; ?>
                    <table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
                        <tr>
                            <th>#</th>
                            <th>Raw Material</th>
                            <td>Design No.</td>
                            <th>Width</th>
                            <td>Meter</td>
                        </tr>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $item['raw_material']; ?></td>
                            <td><?= $item['d_no']; ?></td>
                            <td><?= $item['width']; ?></td>
                            <td><?= $item['meter']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
                                    <tr>
                                        <th>Meter Breakup</th>
                                        <th>Remark</th>
                                        <th>Bedsheet Size</th>
                                        <th>Qty</th>
                                        <th>Pillow Size</th>
                                        <th>Qty</th>
                                        <th>Total Consume</th>
                                    </tr>
                                    <?php $cuttingdetail = $obj->get_rows('`cuttingdetail`', '*', "`cuttingitem_id`=$item[id]");
                                    if (!empty($cuttingdetail)) {
                                        foreach ($cuttingdetail as $detail) {
                                    ?>
                                            <tr>
                                                <td><?= $detail['meterbreakup']; ?></td>
                                                <td>
                                                    <?php $remark = json_decode($detail['remark']);
                                                    if (!empty($remark)) {
                                                        foreach ($remark as $r) {
                                                    ?>
                                                            <p><?= $r; ?></p>
                                                    <?php }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php $bedsheetsize_id = json_decode($detail['bedsheetsizeid']);
                                                    if (!empty($bedsheetsize_id)) {
                                                        foreach ($bedsheetsize_id as $bsid) {
                                                            $size = $obj->get_details('`size`', '*', "`id`=$bsid");
                                                    ?>
                                                            <p><?= $size['size']; ?></p>
                                                    <?php }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php $bedsheetpcs = json_decode($detail['bedsheetpcs']);
                                                    if (!empty($bedsheetpcs)) {
                                                        foreach ($bedsheetpcs as $bkey => $bsp) {
                                                            if (!empty($bedsheetsize_id[$bkey])) {
                                                    ?>
                                                                <p><?= $bsp; ?></p>
                                                    <?php }
                                                        }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php $pillowsize_id = json_decode($detail['pillowsizeid']);
                                                    if (!empty($pillowsize_id)) {
                                                        foreach ($pillowsize_id as $psid) {
                                                            $size = $obj->get_details('`size`', '*', "`id`=$psid");

                                                    ?>
                                                            <p><?= $size['size']; ?></p>
                                                    <?php  }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php $pillowpcs = json_decode($detail['pillowpcs']);
                                                    if (!empty($pillowpcs)) {
                                                        foreach ($pillowpcs as $pkey => $psp) {
                                                            if (!empty($pillowsize_id[$pkey])) {
                                                    ?>
                                                                <p><?= $psp; ?></p>
                                                    <?php }
                                                        }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php $consume = json_decode($detail['consume']);
                                                    if (!empty($consume)) {
                                                        foreach ($consume as $c) {
                                                    ?>
                                                            <p><?= $c; ?></p>
                                                    <?php }
                                                    } ?>
                                                </td>
                                            </tr>
                                    <?php }
                                    }
                                    ?>

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Total Meter</th>
                            <th>Total Bedsheet</th>
                            <th>Total Pillow</th>
                            <th>Total Consumption</th>
                        </tr>
                        <tr>
                            <th></th>
                            <td><?= $item['totalmeter']; ?></td>
                            <td><?= $item['totalbedsheet']; ?></td>
                            <td><?= $item['totalpillow']; ?></td>
                            <td><?= $item['totalconsume']; ?></td>
                        </tr>
                    </table>

            <?php }
            } ?>
            <br>
            <table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
                <tr>
                    <!-- <th>Total consumption</th>
					<td><?= $cutting['total_consumption']; ?></td>
					<th>Wastage</th>
					<td><?= $cutting['wastage']; ?></td>
					<th>Excess</th>
					<td><?= $cutting['excess']; ?></td> -->
                    <th>Total consumption</th>
                    <td><?= $cutting['total_consumption']; ?></td>
                    <th>Cutting Id</th>
                    <?php $cutting_id = json_decode($cutting['sqn_id']); ?>
                    <td><?= $cutting_id[0]; ?></td>
                    <!-- <th></th>
					<td></td> -->
                </tr>
                <!-- <tr>
					<th>Total Bedsheet</th>
					<td><?= $cutting['finalbedsheet']; ?></td>
					<th>Total Pilow</th>
					<td><?= $cutting['finalpillow']; ?></td>
					<th>Cutter Name</th>
					<td><?= $worker['name']; ?></td>

				</tr> -->
            </table>
            <br>
            <table border="1" cellpadding="5" cellspacing="0" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Size</th>
                        <th>Pattern</th>
                        <th>Width</th>
                        <th>Quantity</th>
                        <th>Consumption</th>
                        <th>Worker Name</th>
                        <th>Assign Quantity</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($order_prod as $prod) {
        // Get product details
        $prods = $obj->get_details('`product`', '*', '`id`=' . $prod['product_id']);
        $size = $obj->get_details('`size`', '*', '`id`=' . $prod['size_id']);
        $width = $obj->get_details('`width`', '*', '`id`=' . $prod['width_id']);
        $pattern = $obj->get_details('`pattern`', '*', '`id`=' . $prod['pattern_id']);
        $consumption = $obj->get_details('`consumption`', '*', "`prod_id`=" . $prod['product_id'] . " AND `size_id`=" . $prod['size_id'] . " AND `width_id`=" . $prod['width_id'] . " AND `pattern`=" . $prod['pattern_id']);
        $assign_his = $obj->get_rows('`order_assign_history`', '*', "`order_id` = '{$prod['order_id']}' AND `order_prod_id` = '{$prod['id']}' AND `process_id` = '{$job_process[0]['id']}' AND `status`=1");

        // Proceed only if $assign_his has rows, otherwise skip to next iteration
        if (!empty($assign_his)) {
            foreach ($assign_his as $his) {
                // Check if worker details are available
                $worker = !empty($his['assign_id']) ? $obj->get_details('`worker`', '*', '`id`=' . $his['assign_id']) : null;

                // Fallback values if data is missing
                $product_name = !empty($prods['product_name']) ? $prods['product_name'] : 'N/A';
                $size_name = !empty($size['size']) ? $size['size'] : 'N/A';
                $pattern_name = !empty($pattern['pattern_name']) ? $pattern['pattern_name'] : 'N/A';
                $width_value = !empty($width['width']) ? $width['width'] : 'N/A';
                $product_quant = !empty($prod['product_quant']) ? $prod['product_quant'] : 0;
                $consume_value = !empty($consumption['consume']) ? $consumption['consume'] : 0;
                $worker_name = !empty($worker['name']) ? $worker['name'] : 'N/A';
                $assign_quantity = !empty($his['quant']) ? $his['quant'] : 0;

                // Calculate total consumption
                $total_consumption = $consume_value * $product_quant;
    ?>
                <tr>
                    <td><?php echo $product_name; ?></td>
                    <td><?php echo $size_name; ?></td>
                    <td><?php echo $pattern_name; ?></td>
                    <td><?php echo $width_value; ?></td>
                    <td><?php echo $product_quant; ?></td>
                    <td><?php echo $total_consumption; ?></td>
                    <td><?php echo $worker_name; ?></td>
                    <td><?php echo $assign_quantity; ?></td>
                </tr>
    <?php
            }
        }
    } ?>
</tbody>

            </table>
        </section>
    </body>
    <script language="javascript">
        function closeThis() {


            window.location = "../cutting/";

        }
    </script>

    </html>
    <div id="buttons" style="width:100%; margin-top:10px;">
        <center>
            <button type="button" class="btn btn-danger" onclick="window.print();" style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Print</button>
            <button type="button" onclick="closeThis();" class="btn btn-default" style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button>
        </center>
    </div>
</body>
<script language="javascript">
    function closeThis() {
        window.location = "../cutting/?pagename=cutting-sheet";
    }
</script>


</html>