<div class="container-fluid">
      <table class="table datatable">
            <thead>
            <th class="bg-primary" style="text-align:center; vertical-align:middle;">Date</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Cutting ID</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Barcode</th>
                <!-- <th class="bg-warning" style="text-align:center; vertical-align:middle;">Quantity</th> -->
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Cutter Name</th>
                <!-- <th class="bg-success" style="text-align:center; vertical-align:middle;">Total Order</th> -->
                <th class="bg-warning" style="text-align:center; vertical-align:middle;">Total Consumption</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Action</th>
            </thead>
           
            <tbody>
    <?php 
        // Fetching order products with status = 1
        $order_product = $obj->get_rows("`order_product`", "*", "`status`=1", "`created_at` desc");

        // Check if the result is an array and not empty
        if (is_array($order_product) && !empty($order_product)) {
            $prod = array();
            $process = $_GET['pagename'];
            $job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
            
            // Ensure $job_process is valid and not empty
            if (is_array($job_process) && !empty($job_process)) {
                foreach ($order_product as $product) {
                    $job_sqn = json_decode($product['job_squence']);
                    foreach ($job_sqn as $key => $job) {
                        if ($job == $job_process[0]['id']) {
                            $product['sqn_id'] = $key;
                            $prod[$product['id']] = $product;
                            break;
                        }
                    }
                }
                $order_id_array=array();
                // Loop through the filtered products
                foreach ($prod as $product) {
                    $order = $obj->get_rows("`sm_order`", "*", "`id`=" . $product['order_id'],"`created_at` desc");
                    // print_r($order); 
                    $order_no=0;
                    // Check if $order is valid and not empty
                    if (is_array($order) && !empty($order)) {
                        foreach ($order as $ord) {
                            $order_id = json_decode($ord['sqn_id']);
                            $order_id = $order_id[$product['sqn_id']];
                            $order_id_array[] = $order_id;
                            $assin = json_decode($ord['assign_id']);
                            $assin = $assin[$product['sqn_id']];
                            // print_r($assin);
                            $barcode = $obj->get_rows("`bale_meter_tally`", "*", "`stock_id`=" . $ord['stock_id']);
                            $assign_name = $obj->get_rows("`worker`", "*", "`id`=" . $assin);
                            foreach($order_id_array as $ord_array){
                                // print_r($order_id);
                                if($ord_array == $order_id){
                                    $order_no++;
                                   
                                }
                            }
                            // print_r($order_no);
                            if($order_no>1){
                                break;
                            }
                            echo "<tr>";
                            echo "<td>".$ord['date']."</td>";
                                echo "<td>$order_id</td>";
                                echo "<td><img src='../action/barcodes/barcode_" . $barcode[0]['barcode'] . ".png'></td>";
                                // echo "<td>" . $ord['quantity'] . "</td>";
                                echo "<td>" . $assign_name[0]['name'] . "</td>";
                                // echo "<td>" . $ord['order_quantity'] . "</td>";
                                echo "<td>" . number_format($ord['total_consumption'], 2) . "</td>";
                                echo "<td align='center'>
                                    <a class='btn btn-success btn-xs' href='../cutting/printcutting.php?id=" . $ord['id'] . "&pagename=".$process."' >
                                        <i class='fa fa-print'></i>
                                    </a>
                                </td>";
                            echo "</tr>";
                            
                            // break;
                        }
                        // foreach($order_id_array as $ord_array){
                        //     if($ord_array == $order_id){
                        //         $order_no=1;
                        //         break;
                        //     }
                        // }
                        // if($order_no>1){
                        //     con;
                        // }
                    }
                }
            } 
        } else {
            echo "<tr><td colspan='7'>No order products found </td></tr>";
        }
    ?>
</tbody>

</table>
</div>