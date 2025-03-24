<div class="container-fluid">
    <table class="table datatable">
        <thead>
            <th class="bg-primary" style="text-align:center; vertical-align:middle;">Cutting ID</th>
            <th class="bg-info" style="text-align:center; vertical-align:middle;">Barcode</th>
            <th class="bg-warning" style="text-align:center; vertical-align:middle;">Quantity</th>
            <!-- <th class="bg-danger" style="text-align:center; vertical-align:middle;">Assined Name</th> -->
            <th class="bg-success" style="text-align:center; vertical-align:middle;">Total Order</th>
            <th class="bg-warning" style="text-align:center; vertical-align:middle;">Total Consumption</th>
            <th class="bg-secondary" style="text-align:center; vertical-align:middle;">Status</th>
            <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Action</th>
        </thead>

        <tbody>
            <?php
            $process = $_GET['pagename'];
            $job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
            $curr_page = $job_process[0]['id'];
            $order_product = $obj->get_rows("`order_product`", "*", "`status`=1 and `curr_job`=$curr_page", "`updated_at` desc");
            // print_r($order_product);
            if (is_array($order_product) && !empty($order_product)) {
                $prod = array();
                $process = $_GET['pagename'];
                if (is_array($job_process) && !empty($job_process)) {
                    $order_id_array = array();
                    foreach ($order_product as $product) {
                        $order = $obj->get_rows("`sm_order`", "*", "`id`=" . $product['order_id']);
                        $job_sqn = json_decode($product['job_squence']);
                        // print_r($job_sqn);
                        $current_index = 0;
                        foreach ($job_sqn as $k1 => $js) {
                            if ($js == $curr_page) {
                                $current_index = $k1;
                                break;
                            }
                        }
                        $order_no = 0;
                        // print_r($order);
                        foreach ($order as $ord) {
                            $order_id = json_decode($ord['sqn_id']);
                            $order_id = $order_id[0];
                            $order_id_array[] = $order_id;
                            $assin = json_decode($ord['assign_id']);
                            $barcode = $obj->get_rows("`bale_meter_tally`", "*", "`stock_id`=" . $ord['stock_id']);
                            foreach ($order_id_array as $ord_array) {
                                if ($ord_array == $order_id) {
                                    $order_no++;
                                }
                            }
                            if ($order_no > 1) {
                                break;
                            }
                            if ($product['order_status'] == 1) {
                                echo "<tr>";
                                echo "<td>$order_id <input type='hidden' value=$order_id class='order_id_row'</td>";
                                echo "<td><img src='../action/barcodes/barcode_" . $barcode[0]['barcode'] . ".png'></td>";
                                echo "<td>" . $ord['quantity'] . "</td>";
                                echo "<td>" . $ord['order_quantity'] . "</td>";
                                echo "<td>" . number_format($ord['total_consumption'], 2) . "</td>";
                                echo "<td>";
                                echo "<a class='btn btn-danger'>InCompleted</a></td>";
                                echo "<td align='center'>
                                    <a class='btn btn-warning btn-xs select_jobprocess' order_id=" . $ord['id'] . " sqn_id=" . $current_index . " curr_job_id=" . $product['curr_job'] . ">
                                        <i class='fa fa-check'></i>
                                    </a>
                                    
                                </td>";
                                echo "</tr>";
                            }
                        }
                    }
                }
            } else {
                echo "<tr><td colspan='8'>No order products found </td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('.select_jobprocess').off('click').on('click', function() { // Prevent multiple bindings
            let order_id = $(this).attr("order_id");
            let curr_job_id = $(this).attr("curr_job_id");
            let sqn_id = $(this).attr("sqn_id");
            let order_id_row = $(this).closest('tr').find('.order_id_row').val();

            $.ajax({
                url: '../ajax_returns.php',
                type: 'POST',
                data: {
                    order_id: order_id,
                    curr_job_id: curr_job_id,
                    sqn_id: sqn_id,
                    pagename: "<?php echo $process; ?>",
                    job_process_flow: "job_process_flow"
                },
                success: function(response) {
                    let json_res = JSON.parse(response);
                    // console.log(json_res);
                    $('#worker_assign').html('');
                    $('#product_table').html('');
                    $("#cutting_id").val(order_id_row);
                    if (json_res['status']) {
                        $('#worker_assign').append(json_res.data_table);
                        $('#product_table').html(json_res.product_table);
                    }

                    // Initialize DataTable once, outside the click function
                    if (!$.fn.DataTable.isDataTable('.my_product_table')) {
                        $('.my_product_table').DataTable({
                            "paging": false,
                            "searching": false,
                            "info": false,
                            "lengthChange": false,
                            "ordering": false,
                            "responsive": true
                        });
                    }

                    if (!$.fn.DataTable.isDataTable('.product_table')) {
                        $('.product_table').DataTable({
                            "paging": false,
                            "searching": false,
                            "info": false,
                            "lengthChange": false,
                            "ordering": false,
                            "responsive": true
                        });
                    }

                    $(document).on('click', '.add_worker_job', function() {
                        var clickedRow = $(this).closest('tr');

                        clickedRow.addClass('added-child-row');

                        var parentRow = clickedRow.prev('tr');


                        var newParentRow = parentRow.clone();
                        // newParentRow.find('input').val('');
                        // newParentRow.find('select').val('');

                        var newChildRow = clickedRow.clone();
                        // newChildRow.find('input').val('');

                        newChildRow.find('.add_worker_job').on('click', function() {
                            console.log('Add Worker job clicked in new row');
                        });
                        newParentRow.removeClass('parent');
                        clickedRow.after(newParentRow);
                        // newParentRow.after(newChildRow);
                        var table = $('.my_product_table').DataTable();

                        table.rows.add(newParentRow);
                        table.rows.add(newChildRow);
                        table.draw();
                        newChildRow.removeClass('hidden-child-row');
                    });



                }
            });
        });
    });
</script>