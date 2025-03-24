<?php
require '../vendor/autoload.php';

session_start();
// if (isset($_SESSION['user'])) {
// 	$role = $_SESSION['role'];
// 	$user = $_SESSION['user'];
// 	$user_id = $_SESSION['user_id'];
// 	$shop = $_SESSION['shop'];
// } else {
// 	header("Location:../index.php");
// 	echo "<script>location='../index.php'</script>";
// }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml">

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
	<!-- <script src="../bootstrap/js/jquery-3.1.1.min.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
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
	$raw_material = $obj->get_rows("`raw_material`", "`id`,`name`", "`shop`='$shop' and `type`='fabric'", 'name asc');
	$width = $obj->get_rows("`width`", "`id`,`width`", "`shop`='$shop'", 'width asc');
	
	$supplier = $obj->get_rows("`supplier`", "*", "`status`=1");
	// print_r($obj); die;
	// var_dump($_GET['pagename']);
	?>
	

	<div class="container">
		<div class="row">
            <div class="col-sm-12"  style="margin:10px">
                <a href="inward_list.php?pagename=packable&type=entry" class="btn btn-success">Inward Entry List</a>
				<a href="inward_entry.php?pagename=packable&type=list" class="btn btn-danger">Inward Entry</a>
            </div>
			<hr style="border: 1px solid grey !important;">
        </div>
	</div>

	<?php 
		//include "./inward_list.php";

		if(!isset($_GET['type'])){
	?>

<div class="container">
    <table class="table datatable">
        <thead>
            <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
            <th class="bg-default" style="text-align:center; vertical-align:middle;">Image</th>
            <th class="bg-primary" style="text-align:center; vertical-align:middle;">Bale no. / Remark</th>
            <th class="bg-info" style="text-align:center; vertical-align:middle;">Item</th>
            <!-- <th class="bg-info" style="text-align:center; vertical-align:middle;">Quantity</th> -->
            <th class="bg-danger" style="text-align:center; vertical-align:middle;">Width</th>
            <th class="bg-warning" style="text-align:center; vertical-align:middle;">Design No.</th>
            <!-- <th class="bg-info" style="text-align:center; vertical-align:middle;">Meter</th> -->
            <!-- <th class="bg-info" style="text-align:center; vertical-align:middle;">Barcode</th> -->
            <th class="bg-success" style="text-align:center; vertical-align:middle;">Quantity</th>
            <th class="bg-success" style="text-align:center; vertical-align:middle;">Unit</th>
            <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Action</th>
        </thead>

        <tbody id="printable-row2">
            <?php
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
            $offset = $page * $count;
            $table = "`stock`";
            $columns = "*";
            if (isset($_GET['query']) && trim($_GET['query']) != "") {
                $query = $_GET['query'];
                $where = "shop='$shop' `material_type` IS NULL";
            } else {
                $where = "`shop`='$shop' and `material_type` IS NULL";
            }
            $order = "id";
            $limit = "$offset,$count";
            $array = $obj->get_rows($table, $columns, $where, $order);
            $rowcount = $obj->get_count($table, $where);
            $pages = ceil($rowcount / $count);
            $i = $offset;
            if (is_array($array)) {
                foreach ($array as $result) {
                    $id = $result['id'];
                    $i++;
            ?>
                    <tr class="printable-row">

                        <td align="center"><?php echo $i; ?></td>
                        <td align="center">
                            <?php if ($result['image'] != '') { ?>
                                <img src="../uploads/<?php echo $result['image']; ?>" class="img-responsive" style="max-width:100px;">
                            <?php } else { ?>
                                <!-- <button class="btn btn-primary" style="text-transform: capitalize;"><?php echo $result['material_type'] . ' Material' ?></button> -->
                                <!-- <img src="../uploads/no-image.png" class="img-responsive" style="max-width:100px;"> -->
                            <?php } ?>
                        </td>
                        </td>
                        <td align="center"><?php if ($result['bale_id'] != '') {
                                                $bale = $obj->get_details("`packable`", "`bale_no`", "`id`='" . $result['bale_id'] . "'");
                                                echo $result['bale_id'];
                                            } else {
                                                echo $result['remark'];
                                            } ?></td>
                        <td align="center"><?php $raw = $obj->get_details("`raw_material`", "`name`", "`id`='" . $result['raw_id'] . "'");
                                            echo $raw['name']; ?></td>
                        <!-- <td align="center"><?php //echo $result['quantity']; 
                                                ?></td> -->
                        <td align="center"><?php $width = $obj->get_details("`width`", "`width`", "`id`='" . $result['width_id'] . "'");
                                            if ($result['width_id'] != 0) {
                                                echo $width['width'];
                                            } else {
                                                echo 0;
                                            } ?></td>
                        <td align="center"><?php echo $result['d_no']; ?></td>
                        <!-- <td align="center"><?php // echo $result['meter']; 
                                                ?></td> -->
                        <!-- <td align="center">
					<?php if (!empty($result['barcode_id'])) { ?>
					<img src='../action/barcodes/barcode_<?php echo $result['barcode_id']; ?>.png'><br>
                    <span><?php echo $result['barcode_id'] ?></span><br>
					<?php } else { ?>
                    <button class="btn btn-info" style="text-transform: capitalize;">Not required</button>
                    <?php } ?>
				</td> -->
                        <td align="center"><?php echo $result['quantity']; ?></td>
                        <td align="center"><?php echo $result['unit']; ?></td>
                        <td align="center">
                            <a href="editstock.php?pagename=stock&id=<?php echo $id; ?>" title="Edit"><i class="btn btn-info btn-xs fa fa-edit"></i></a>
                            <a href="#" title="print" class="print_code">
                                <i class="btn btn-warning btn-xs fa-solid fa-print"></i>
                            </a>
                            <!-- <a href="../action/deleteData.php?deleteStock=deleteStock&id=<?php //echo $id; 
                                                                                                ?>"  onclick="return confirmDel()"><i class="btn btn-danger btn-xs fa fa-trash"></i></a> -->
                        </td>
                    </tr>

            <?php
                }
            } ?>
        </tbody>
    </table>
</div>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script>
    document.querySelectorAll('.print_code').forEach(link => {
        link.addEventListener('click', function(e) {
    e.preventDefault(); // Prevent the default link behavior

    // Get the parent row
    const row = this.closest('.printable-row');

    // Clone the row content so we don't modify the original row in the DOM
    const rowCopy = row.cloneNode(true);

    // Remove the last <td> in the cloned row
    const lastTd = rowCopy.querySelector('td:last-child');
    if (lastTd) {
        lastTd.remove();
    }

    // Create a new window
    const printWindow = window.open('', '', 'width=600,height=400');

    // Build the content to print
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
        <tr>
            <th class="bg-danger" style="vertical-align:middle;">Sl.No.</th>
            <th class="bg-default" style="vertical-align:middle;">Image</th>
            <th class="bg-primary" style="vertical-align:middle;">Bale no. / Remark</th>
            <th class="bg-info" style="vertical-align:middle;">Item</th>
            <th class="bg-danger" style="vertical-align:middle;">Width</th>
            <th class="bg-warning" style="vertical-align:middle;">Design No.</th>
            <th class="bg-info" style="vertical-align:middle;">Quantity</th>
            <th class="bg-info" style="vertical-align:middle;">Unit</th>
        </tr>
    </thead>
    <tbody>
        <tr>${rowCopy.innerHTML}</tr>
    </tbody>
</table>`); // Print only the content of the row without the last td
    printWindow.document.write('</body></html>');

    // printWindow.document.close(); // Close the document
    printWindow.print(); // Trigger print
    // printWindow.close(); // Close the print window after printing
});

    });
</script>
<?php } ?>