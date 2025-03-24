<?php
//  include "../header.php"; 
    include "./bale_meter_stock.php";
?>
<center>
<h4 class="save_msg" style="font-weight: bolder;">
<?php 
    if(isset($_SESSION['add_msg'])){
        echo "<span style='color:green'>Barcode Generated</span>";
        unset($_SESSION['add_msg']);
    }
    else if(isset($_SESSION['err'])){
        echo "<span style='color:green'>". $_SESSION['err'] . "</span>";
        unset($_SESSION['err']);
    }
    else{

    }
?>
</h4>
</center>

<script>
    $(document).ready(function() {
    setTimeout(function() {
        $('.save_msg').hide(); 
    }, 2000);  
});

</script>
<div class="container">
            <table class="table datatable">
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
                    $array = $obj->get_rows($table, $columns, "`status`=1", $order, '', 'stock_id');
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
                            <tr class="printable-row">
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
                                        <?php $meter_breakup = $obj->get_rows("bale_meter_tally",'*',"`stock_id`=$id and `status`=1");  ?>
                                <td align="center"><?php 
                                    foreach($meter_breakup as $breakup){
                                        echo "<button class='btn-sm btn-secondary' style='margin: 0px 5px; !important'>".$breakup['meter_breakup']."</button>";
                                    }
                                ?></td>
                                <!-- <td align="center"><?php echo $stock['quantity']; ?></td> -->
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
                                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
                                <th class="bg-default" style="text-align:center; vertical-align:middle;">Image</th>
                                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Bale no. / Remark</th>
                                <th class="bg-info" style="text-align:center; vertical-align:middle;">Item</th>
                                <th class="bg-info" style="text-align:center; vertical-align:middle;">Quantity</th>
                                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Width</th>
                                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Design No.</th>
                                <th class="bg-info" style="text-align:center; vertical-align:middle;">Barcode</th>
                                <th class="bg-info" style="text-align:center; vertical-align:middle;">Meter Breakup</th>
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