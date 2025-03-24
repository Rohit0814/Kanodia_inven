<?php
require '../vendor/autoload.php';

session_start();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Stock</title>
    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <?php include "../header.php";
    include_once "../action/config.php";
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $role = $_SESSION['role'];
        $shop = $_SESSION['shop'];
    } else {
        header("Location:index.php");
        echo "<script>location='index.php'</script>";
    }
    $obj = new database();
    $raw_material = $obj->get_rows("`raw_material`", "`id`,`name`", "`shop`='$shop' and `type`='fabric'");
    $width = $obj->get_rows("`width`", "`id`,`width`", "`shop`='$shop'");
    // print_r($raw_material); die;
    $supplier = $obj->get_rows("`supplier`", "*", "`status`=1");
    // print_r($obj); die;
    // var_dump($_GET['pagename']);
    ?>
    <?php
    // session_start();
    $stock_id = $_SESSION['stock']; ?></h1>


    <div class="container">
        <table class="table datatable printable-row">
            <thead>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
                <th class="bg-default" style="text-align:center; vertical-align:middle;">Image</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Bale no. / Remark</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Item</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Quantity</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Width</th>
                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Design No.</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Barcode</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Meter Breakup</th>
                <!-- <th class="bg-success" style="text-align:center; vertical-align:middle;">Quantity</th> -->
            </thead>

            <tbody id="printable-row2">

                <?php
                foreach ($stock_id as $stock) {
                    // print_r($stock); die;
                    $count = 20;
                    $offset = 0;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 0;
                    }

                    if (isset($_GET['shop'])) {
                        $shop = $_GET['shop'];
                        include('../action/class.php');
                        $obj = new database();
                    }
                    // $obj = new database();
                    $offset = $page * $count;
                    $table = "`bale_meter_tally`";
                    $columns = "*";
                    if (isset($_GET['query']) && trim($_GET['query']) != "") {
                        $query = $_GET['query'];
                        $where = "shop='$shop' `material_type` IS NULL";
                    } else {
                        $where = "`shop`='$shop' and `material_type` IS NULL";
                    }
                    $order = "id";
                    $limit = "$offset,$count";
                    $array = $obj->get_rows($table, $columns, "`status`=1 and stock_id=$stock", $order, '', 'stock_id');
                    $rowcount = $obj->get_count($table);
                    $pages = ceil(intval($rowcount) / intval($count));
                    // var_dump($rowcount);
                    $i = $offset;
                    if (is_array($array)) {
                        foreach ($array as $result) {
                            // print_r($result); die;
                            $id = $result['stock_id'];
                            $i++;
                ?>
                            <tr>
                                <?php
                                $stock =  $obj->get_rows('`stock`', '*', "`id`=$id");
                                // print_r($stock); die;

                                $stock = $stock[0];
                                // print_r($stock['image']); die;

                                ?>
                                <td align="center"><?php echo $i; ?></td>
                                <td align="center">
                                    <?php if ($stock['image'] != '') { ?>
                                        <img src="../uploads/<?php echo $stock['image']; ?>" class="img-responsive" style="max-width:100px;">
                                    <?php } else { ?>
                                        <!-- <button class="btn btn-primary" style="text-transform: capitalize;"><?php echo $stock['material_type'] . ' Material' ?></button> -->
                                        <!-- <img src="../uploads/no-image.png" class="img-responsive" style="max-width:100px;"> -->
                                    <?php } ?>
                                </td>
                                </td>
                                <td align="center"><?php if ($stock['bale_id'] != '') {
                                                        $bale = $obj->get_details("`packable`", "`bale_no`", "`id`='" . $stock['bale_id'] . "'");
                                                        echo $stock['bale_id'];
                                                    } else {
                                                        echo $stock['remark'];
                                                    } ?></td>
                                <td align="center"><?php $raw = $obj->get_details("`raw_material`", "`name`", "`id`='" . $stock['raw_id'] . "'");
                                                    echo $raw['name']; ?></td>
                                <td align="center"><?php echo $stock['quantity']; ?></td>
                                <td align="center"><?php $width = $obj->get_details("`width`", "`width`", "`id`='" . $stock['width_id'] . "'");
                                                    if ($stock['width_id'] != 0) {
                                                        echo $width['width'];
                                                    } else {
                                                        echo 0;
                                                    } ?></td>
                                <td align="center"><?php echo $stock['d_no']; ?></td>
                                <td align="center">
                                    <?php if (!empty($result['barcode'])) { ?>
                                        <img src='../action/barcodes/barcode_<?php echo $result['barcode']; ?>.png'><br>
                                        <span><?php echo $result['barcode'] ?></span><br>
                                    <?php } else { ?>
                                        <button class="btn btn-info" style="text-transform: capitalize;">Not required</button>
                                    <?php } ?>
                                </td>
                                <?php $meter_breakup = $obj->get_rows("bale_meter_tally", '*', "`stock_id`=$id and `status`=1");  ?>
                                <td align="center"><?php
                                                    foreach ($meter_breakup as $breakup) {
                                                        echo "<button class='btn-sm btn-secondary' style='margin: 0px 5px; !important'>" . $breakup['meter_breakup'] . "</button>";
                                                    }
                                                    ?></td>
                                <!-- <td align="center"><?php echo $stock['quantity']; ?></td> -->
                                
                            </tr>

                <?php
                        }
                    }
                } ?>
            </tbody>
        </table>
    </div>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script>
       if (typeof document !== 'undefined' && typeof window !== 'undefined') {
        const row = document.querySelector('.printable-row');
        console.log(row);
        const rowCopy = row.cloneNode(true);
        const printWindow = window.open('', '', 'width=600,height=400');

        printWindow.document.write('<html><head><title>Print Row</title>');
        printWindow.document.write('<link rel="stylesheet" href="path/to/font-awesome.css">'); // Include any required styles
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; } ' +
            'table { width: 100%; border-collapse: collapse; } ' +
            'th, td { border: 1px solid #000; padding: 8px; text-align: center; } ' +
            'th { background-color: #f2f2f2; } ' + // Header background color
            '.bg-danger { background-color: #ffcccc; } ' + // Custom styles for bg-danger
            '.bg-default { background-color: #e0e0e0; } ' + // Custom styles for bg-default
            '.bg-primary { background-color: #cce5ff; } ' + // Custom styles for bg-primary
            '.bg-info { background-color: #d1ecf1; } ' + // Custom styles for bg-info
            '.bg-warning { background-color: #fff3cd; } ' + // Custom styles for bg-warning
            '</style>'); // Add your own styles here
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2 style="text-align:center;">Stock</h2>'); // Heading
        printWindow.document.write(`<table>
            <thead>
                
            </thead>
            <tbody>
                <tr>${rowCopy.innerHTML}</tr>
            </tbody>
        </table>`); // Print only the content of the row without the last td
        printWindow.document.write('</body></html>');

        // Trigger the print dialog
        printWindow.document.close(); 
        printWindow.print();
            window.location.href = 'bale_meter_list.php?pagename=bale_meter_tally&type=list'; // Replace with your redirect URL
    };


    </script>