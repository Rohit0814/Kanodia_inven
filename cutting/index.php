<?php
session_start();
// print_r($_SESSION);DIE;
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
?>
<!doctype html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- Bootstrap Core CSS -->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="../bootstrap/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- Custom CSS -->
	<link href="../css/style.css" rel="stylesheet">
	<!-- Bootstrap Core JavaScript -->
	<!-- <script src="../bootstrap/js/jquery-3.1.1.min.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script> -->

	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

	<!-- DataTables & Responsive JS -->
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
	<style>
		.breakup_table th{
			width: 150px !important;
		}
		.form-control {
			height: 30px;
		}

		.remark {
			position: relative;
			height: 25px;
			margin: 3px 0;
		}

		.total {
			position: relative;
			height: 25px;
			margin: 3px 0;
		}

		.bedsheetsize {
			position: relative;
			float: left;
			width: 20%;
			height: 25px !important;
			margin: 2px 0;
		}

		.pillowsize {
			position: relative;
			float: left;
			width: 20%;
			height: 25px !important;
			margin: 2px 0;
		}

		.pieces {
			position: relative;
			float: left;
			width: 16%;
			height: 25px;
			margin: 2px 0;
		}

		.left {
			position: relative;
			float: left;
			width: 45%;
		}

		.into,
		.no {
			position: relative;
			float: left;
			width: 5%;
			margin: 2px 4px;
			vertical-align: bottom;
			text-align: center;
		}

		.rows td {
			border-bottom: 2px solid #000000 !important;
		}
	</style>
	<title>Cutting Sheet</title>
</head>

<body>
	<?php include("../header.php"); ?>
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
				<div id="formPanel" class="panel panel-info">
					<div class="panel-heading">
						<font size="+2">Create Cutting Sheet</font>
					</div>
					<div class="panel-body">
						<form action="../action/insertData.php" method="post" style="font-size:16px;" onsubmit="return validateform()">
							<div class="table-responsive">
								<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#pending">Select From Stock <i class="fa fa-search"></i></button>
								<!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addstock">Add Stock <i class="fa fa-plus"></i></button> -->
								<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cuttinglist">Cutting List <i class="fa fa-list"></i></button>
								<table class="table table-bordered table-condensed">
									<tr>
										<td width="20%"><label>Barcode</label></td>
										<td><input type="text" class="form-control" id="barcode_id" style="max-width:200px; margin:5px 0px" autofocus onmouseover="this.focus();"></td>
									</tr>
								</table>
								<table class="table table-bordered table-condensed">
									<tr>
										<th width="10%">Cutting Id</th>
										<td width="20%"><input type="text" name="cutting_id" id="cutting_id" class="form-control cutting_id" readonly required></td>
										<th width="15%">Date</th>

										<script>
											let cutting_id = $('#cutting_id').val("cutting-" + Date.now());
										</script>
										<?php $process = $_GET['pagename'];
										$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
										$job_permission = json_decode($job_process[0]['permission']);
										// print_r($job_permission);
										$where = '';
										if (!empty($job_permission)) {
											$lastKey = array_key_last($job_permission); // Get the last key
											foreach ($job_permission as $key => $job) {
												if ($key == $lastKey) {
													$where .= "`designation`='$job'"; // Do not add 'or' to the last element
												} else {
													$where .= "`designation`='$job' or "; // Add 'or' for all other elements
												}
											}
										}
										// print_r($where); die;
										?>
										<td width="25%"><input type="date" name="date" class="form-control to-enter" required value="<?php echo date('Y-m-d'); ?>"></td>
										<!-- <th>Stock ID</th> -->
										<td><input type="hidden" id="stock_id" class="form-control to-enter" placeholder="Enter Stock ID" readonly></td>
										<th><label>Cutter Name</label></th>
										<td>
											<select name="worker_id" id="worker_id" class="form-control to-enter" required>
												<option value='' selected disabled>Select Cutter</option>
												<?php

												$workers = $obj->get_rows("`worker`", "*", "$where");

												if (!empty($workers)) {
													foreach ($workers as $workerData) {
														$attendance = $obj->get_rows("`attendance`", "*", "`worker`=" . $workerData['id'] . " AND `date`='" . date('Y-m-d') . "' AND `status`=1");

														// Print attendance for debugging
														// print_r($attendance);

														if (!empty($attendance)) {
												?>
															<option value="<?php echo $workerData['id']; ?>"><?php echo $workerData['name']; ?></option>
												<?php
														}
													}
												}
												?>
											</select>
										</td>
									</tr>
								</table>
							</div>
							<div id="table" class="table-responsive">
								<table class="table table-bordered table-condensed" border="1" id="table2" width="100%">

								</table>
							</div>
							<div class="row" style="margin-bottom: 10px;">
								<div class="col-md-3">
									<label>Total Quantity</label>
									<input type="text" step="any" class="form-control to-enter" name="total_quant" id="total_qty">
								</div>
								<div class="col-md-3">
									<label>Consumption</label>
									<input type="number" step="any" class="form-control to-enter" name="consumption" id="consumed" oninput="calc_excess()">
								</div>
								<div class="col-md-3">
									<label>Excess</label>
									<input type="number" step="any" class="form-control to-enter" name="excess" id="excess">
								</div>
								<div class="col-md-3">
									<label>Balance</label>
									<input type="number" step="any" class="form-control to-enter" name="balanace" id="final_balance">
								</div>
							</div>
							<!-- <div class="row" style="margin-bottom: 10px;"> -->

							<!-- <div class="col-md-4">
									<label>Total Pillow</label>
									<input type="number" step="any" class="form-control to-enter" name="finalpillow" id="finalpillow">
								</div> -->
							<!-- <div class="col-md-4">
									<label>Cutter Name</label>
									<select name="worker_id" id="worker_id" class="form-control to-enter">
										<option value="" selected disabled>Select Cutter</option>
										<?php
										$worker = $obj->get_rows("`worker`", "*", "`designation`='cutter'");
										if (!empty($worker)) {
											foreach ($worker as $worker) {
										?>
												<option value="<?php echo $worker['id']; ?>"><?php echo $worker['name']; ?></option>
										<?php }
										} ?>
									</select>
								</div> -->
							<!-- </div> -->
							<!-- </div> -->

							<div class="row subsidary_item" style="display: none;">
								<div class="col-md-12">
									<h4 style="text-align: center;"><b>Subsidary Items Requirement List</b></h4>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered" style="margin: 10px; width: 98%;">
										<tr>
											<th>#</th>
											<th>Subsidary Item</th>
											<th>Quantity Per unit</th>
											<th>Consumption</th>
											<th>Unit</th>
											<th>Product</th>
											<th>Size</th>
											<th>Width</th>
											<th>rate</th>
										</tr>
										<tbody class="subsidary_list">

										</tbody>
									</table>
								</div>
							</div>
							<div class="row" style="margin-bottom:10px;">
								<div class="col-md-12 text-center">
									<input type="hidden" id="main_row_count" value="0">
									<input type="hidden" name="shop" value="<?php echo $shop; ?>">
									<input type="hidden" name="user" value="<?php echo $user; ?>">
									<input type="submit" class="btn btn-sm btn-warning mb-2" name="save_cuttingsheet_withstock" id="savebtn" value="Keep Balance in stock">
									<input type="submit" class="btn btn-sm btn-success mb-2" name="save_cuttingsheet_check_settled" id="savebtn" value="Check & Settle">
								</div>
							</div>
						</form>
					</div>
				</div><!-- form panel closed-->
			</div><!-- end of col-md-12 -->
		</div>
	</div>
	<div class="modal fade" id="pending" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Stock List</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="row">
							<div class="col-md-12 table-responsive" id="stocklist">
								<?php include('stocklist.php'); ?>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="cuttinglist" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Cutting List</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="row">
							<div class="col-md-12 table-responsive" id="cuttinglist">
								<?php include('cuttinglist.php'); ?>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addstock" role="dialog">
		<div class="modal-dialog modal-lg">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Stock</h4>
				</div>
				<form action="#" method="POST" id="addstockform" enctype="multipart/form-data">
					<div class="modal-body">
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
						<div class="row">
							<div class="col-md-12">
								<label>Date</label>
								<input type="date" name="date" id="date" class="form-control to-enter" value="<?php echo date('Y-m-d'); ?>">
								<input type="hidden" name="shop" id="shop" class="form-control to-enter" value="<?php echo $shop; ?>">

							</div>
							<div class="col-md-6">
								<label>Item</label>
								<select name="raw_id" id="raw_id" class="form-control to-enter">
									<option value="" selected disabled>Select Item</option>
									<?php
									$raw_material = $obj->get_rows("`bale_meter_tally`", "", "", "", "", "`stock_id`");
									if (!empty($raw_material)) {
										foreach ($raw_material as $raw) {
									?>
											<option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Width</label>
								<select name="width_id" id="width_id" class="form-control to-enter">
									<option value="" selected disabled>Select Width</option>
									<?php
									$width = $obj->get_rows("`width`");
									if (!empty($width)) {
										foreach ($width as $width) { ?>
											<option value="<?php echo $width['id']; ?>"><?php echo $width['width']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-md-6">
								<label>Meter</label>
								<input type="text" name="meter" id="meter" class="form-control to-enter" value="">
							</div>
							<div class="col-md-6 hidden">
								<label>Quantity</label>
								<input type="text" name="quantity" id="quantity" value="1" class="form-control to-enter" value="">
							</div>
							<div class="col-md-6">
								<label>Design No</label>
								<input type="text" name="d_no" id="d_no" class="form-control to-enter" value="">
							</div>
							<div class="col-md-6" style="display: none;">
								<label>Image</label>
								<input type="file" name="image" id="image" class="form-control to-enter" value="">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-success pull-left" onclick="addcuttingstock();" value="save">
						<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->

	<script language="javascript">
		var shop = "<?php echo $shop; ?>";
		document.onkeydown = function(e) {
			if (e.which == 113) {
				$('#savebtn').click();
			}
		}
		$(document).ready(function(e) {
			getRaw();
			$('body').on('keypress', '.to-enter', function(e) {
				if (e.which == 13) {
					var index = $(this).index('.to-enter');
					index++;
					if (index < $('.to-enter').length) {
						$('.to-enter').eq(index).focus();
						e.preventDefault();
					}
				}
			});

		});

		function addcuttingstock() {
			var date = $('#date').val();
			var shop = $('#shop').val();
			var raw_id = $('#raw_id').val();
			var width_id = $('#width_id').val();
			var meter = $('#meter').val();
			var quantity = $('#quantity').val();
			var d_no = $('#d_no').val();
			var image = $("#image").prop("files")[0];
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					date: date,
					shop: shop,
					raw_id: raw_id,
					width_id: width_id,
					meter: meter,
					quantity: quantity,
					d_no: d_no,
					image: image,
					addcuttingstock: 'add'
				},
				success: function(data) {
					if (data != '0') {
						selectStock(data);
						$('#addstock').modal('hide');
					}
				}
			});
		}

		function selectStock(id) {
			$('#stock_id').val(id).trigger('blur');
			// $('#stock_id').removeAttr("readonly");
		}

		function getSubsidaryElement() {

			// if (quant === undefined || quant === '' || isNaN(quant)) {
			// 	quant = 1;
			// }

			// Initialize empty arrays to hold the values
			let pattern_ids = [];
			let prod_ids = [];
			let size_ids = [];
			let width_ids = [];
			let consume_inputs = [];

			// Iterate over all elements with the class '.pattern_option' and collect their values
			$('.pattern_option').each(function() {
				pattern_ids.push($(this).val());
			});

			// Iterate over all elements with the class '.product_option' and collect their values
			$('.product_option').each(function() {
				prod_ids.push($(this).val());
			});

			// Iterate over all elements with the class '.size_option' and collect their values
			$('.size_option').each(function() {
				size_ids.push($(this).val());

			});

			$('.consume_input').each(function() {
				if ($(this).val() === undefined || $(this).val() === '' || isNaN($(this).val())) {
					consume_inputs.push(1);
				} else {
					consume_inputs.push($(this).val());
				}
			})

			// Collect the width_id based on the value of breakup_row (assuming breakup_row exists in the context)
			// let breakup_row = $('.row_count');
			$('.row_count').each(function() {
				let breakup_row = $(this).val();

				let width_id = $('#width_id' + breakup_row).val();
				// Push the width_id to the array
				width_ids.push(width_id);
			})


			// Log or return the collected values
			pattern_ids.forEach(function(pattern, index) {
				let pattern_id = pattern;
				if (pattern_id == '') {
					return;
				}
				let prod_id = prod_ids[index];
				let size_id = size_ids[index];
				let width_id = width_ids[index];
				let quant = consume_inputs[index];
				$('.subsidary_list').empty();
				$.ajax({
					method: 'post',
					url: '../ajax_returns.php',
					data: {
						pattern_id: pattern_id,
						size_id: size_id,
						prod_id: prod_id,
						width_id: width_id,
						pagename: 'cutting-sheet',
						page: 'get_subsidary',
					},
					success: function(data) {

						data1 = JSON.parse(data);
						// console.log();
						json_data = data1['subsidary'];
						// console.log(data1);
						// console.log(json_data)
						subsidary_table = '';
						for (let i = 0; i < json_data.length; i++) {
							subsidary_table += `<tr>
							<td>${parseInt(i)+1}</td>
							<td><input type="text" value="${json_data[i]['subsidary_name']}" name="subsidary_name[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['consumption']}" name="subsidary_consumption[]" style="width:100%" readonly></td>
							<td><input type="text" value="${parseFloat(quant)*parseFloat(json_data[i]['consumption'])}" name="subsidary_quantity[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['subsidary_unit']}" name="subsidary_unit[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['product_name']}" name="subsidary_product[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['size']}" name="subsidary_size[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['width']}" name="subsidary_width[]" style="width:100%" readonly></td>
							<td><input type="text" value="${json_data[i]['rate'] }" style="width:100%" name="subsidary_rate[]" readonly>
							<input type="hidden" value="${json_data[i]['pattern_id'] }" style="width:100%" name="subsidary_pattern[]" readonly>
							<input type="hidden" value="${json_data[i]['prod_id'] }" style="width:100%" name="subsidary_prodid[]" readonly>
							<input type="hidden" value="${json_data[i]['size_id'] }" style="width:100%" name="subsidary_sizeid[]" readonly>
							</td>
						</tr>`;
						}

						$('.subsidary_list').append(subsidary_table);

						// $('#subsidary_option').html(json_data.subsidary_option);
					}
				})
			});
		}


		$(document).on('change', '.pattern_option', function() {
			let pattern_id = $(this).val();
			let prod_id = $(this).closest('tr').find('.product_option').val();
			let size_id = $(this).closest('tr').find('.size_option').val();
			let consumption = $(this).closest('tr').find('.consumption').val('');
			let cal_consumption = $(this).closest('tr').find('.cal_consumption');
			let consumption2 = $('.cal_consumption');
			// console.log(consumption);
			let breakup = $(this).closest('tr').find('.row_count');
			let breakup_row = breakup.val();
			// console.log(breakup_row);
			let width_id = $('#width_id' + breakup_row).val();

			let raw_id = $('#raw_id' + breakup_row);
			let raw_id_value = raw_id.val();
			// console.log(width_id);
			// console.log(size_id);
			$.ajax({
				method: 'post',
				url: '../ajax_returns.php',
				data: {
					pattern_id: pattern_id,
					size_id: size_id,
					prod_id: prod_id,
					width_id: width_id,
					pagename: 'cutting-sheet',
					page: 'get_subsidary',
				},
				success: function(data) {

					data1 = JSON.parse(data);
					// console.log();
					json_data = data1['subsidary'];
					// console.log(data1);
					// console.log(json_data)
					subsidary_table = '';
					getSubsidaryElement();
					// for (let i = 0; i < json_data.length; i++) {
					// 	subsidary_table += `<tr>
					// 		<td>${parseInt(i)+1}</td>
					// 		<td><input type="text" value="${json_data[i]['subsidary_name']}" name="subsidary_name[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['consumption']}" name="subsidary_consumption[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['subsidary_unit']}" name="subsidary_unit[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['product_name']}" name="subsidary_product[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['size']}" name="subsidary_size[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['width']}" name="subsidary_width[${raw_id_value}][]" style="width:100%" readonly></td>
					// 		<td><input type="text" value="${json_data[i]['rate'] }" style="width:100%" name="subsidary_rate[${raw_id_value}][]" readonly>
					// 		<input type="hidden" value="${json_data[i]['pattern_id'] }" style="width:100%" name="subsidary_pattern[${raw_id_value}][]" readonly>
					// 		<input type="hidden" value="${json_data[i]['prod_id'] }" style="width:100%" name="subsidary_prodid[${raw_id_value}][]" readonly>
					// 		<input type="hidden" value="${json_data[i]['size_id'] }" style="width:100%" name="subsidary_sizeid[${raw_id_value}][]" readonly>
					// 		</td>
					// 	</tr>`;
					// }
					if (json_data.length > 0) {
						$('.subsidary_item').show();
					}

					// $('.subsidary_list').append(subsidary_table);
					consume = $('.consume_input');
					// console.log(data1['consumption']);
					consumption.val(data1['consumption']);
					let changedInput = $('.consumption');
					let sum = 0;

					// console.log(cal_consumption);
					cal_consumption.val(data1['consumption']);
					// for (let x = 0; x < changedInput.length; x++) {
					// 	con_val = changedInput[x].value;
					// 	// if(consume[x]!=''){
					// 	// 	consume[x].value = 1;
					// 	// }
					// 	if (con_val != '') {
					// 		sum = parseFloat(sum) + parseFloat(con_val);

					// 	}
					// }

					$('#consumed').attr('readonly', true);
					sum = parseFloat(sum);


					for (let f = 0; f < consumption2.length; f++) {
						if (consumption2[f].value != '') {
							// console.log(consumption2[f].value);
							sum = parseFloat(sum) + parseFloat(consumption2[f].value);
						}
					}
					// console.log(sum);
					// console.log($('#total_qty').val());
					let total_qty = parseFloat($('#total_qty').val());
					if (!isNaN(total_qty) && !isNaN(sum)) {
						let fin_calc = (total_qty - sum);
						$('#consumed').val(sum);
						if (fin_calc < 0) {
							$('#excess').val(fin_calc);
							$('#excess').attr('readonly', true);
							$('#final_balance').val(0);
							$('#final_balance').attr('readonly', true);
						} else {
							$('#excess').val('0');
							$('#excess').attr('readonly', true);
							$('#final_balance').val(fin_calc);
							$('#final_balance').attr('readonly', true);
						}
						//console.log(fin_calc);
					} else {
						console.log("Invalid input: One or both values are not numbers");
					}

					// $('#subsidary_option').html(json_data.subsidary_option);
				}
			})
		});

		// function calc_excess(){
		// 	console.log("hii");
		// }

		// $('#consumed').input(function(){
		// 	console.log("hii");
		// })

		// $(document).on('input','.cal_consumption',function(){
		// 	console.log("hii");
		// });

		// $(document).on('change','.cal_consumption',function(){
		// 	console.log("hii");
		// })

		$(document).on('input', '.consume_input', function() {
			let consumed_val = parseFloat($(this).val());
			getSubsidaryElement();
			var cons = $(this).val();
			if (cons == '') {
				consumed_val = 1;
			}

			sum = 0;
			// console.log(consumed_val);
			let total_qty = parseFloat($('#total_qty').val());
			let excess_val = parseFloat($('#excess').val());
			let final_balance_val = parseFloat($('#final_balance').val());
			// if (isNaN(consumed_val) || isNaN(total_qty) || isNaN(excess_val) || isNaN(final_balance_val)) {
			// 	console.log("Invalid input: One or both values are not numbers");
			// 	return;
			// }

			let consumption = $(this).closest('tr').find('.consumption');
			let consumption2 = $('.cal_consumption');
			let cal_consumption = $(this).closest('tr').find('.cal_consumption');
			// let sum = consumed_val + excess_val + final_balance_val;
			// let sum = parseFloat($('#consumed').val());
			// console.log(consumption[0].value);

			// console.log(consumption[0].value);
			if (consumption != '') {
				let sum2 = parseFloat(consumption[0].value) * parseFloat(consumed_val);
				let fin_calc = (sum2);
				// console.log(cons);
				if (consumed_val == '' || isNaN(consumed_val)) {
					consumed_val = 1;
					cal_consumption[0].value = consumption[0].value;
					return;
				}
				// console.log(cons);
				if (consumption[0].value != '') {

					cal_consumption[0].value = fin_calc;
				}


				$('#consumed').attr('readonly', true);
				sum = parseFloat(sum);
				// console.log(cons)

				for (let f = 0; f < consumption2.length; f++) {
					// console.log(cons);
					// if($(this).val()==''){
					// 		// consumed_val = 1;
					//         console.log("hii");
					// 	}
					if (consumption2[f].value != '') {
						// console.log(consumption[f].value);

						sum = parseFloat(sum) + parseFloat(consumption2[f].value);
					}
				}
				// console.log("sum = "+sum);
				// let prev_val = $('#consumed').val();

				let total_qty = parseFloat($('#total_qty').val());
				if (!isNaN(total_qty) && !isNaN(sum)) {
					let fin_calc = (total_qty - sum).toFixed(2);
					$('#consumed').val(sum);
					if (fin_calc < 0) {
						$('#excess').val(fin_calc);
						$('#excess').attr('readonly', true);
						$('#final_balance').val(0);
						$('#final_balance').attr('readonly', true);
					} else {
						$('#excess').val('0');
						$('#excess').attr('readonly', true);
						$('#final_balance').val(fin_calc);
						$('#final_balance').attr('readonly', true);
					}
					//console.log(fin_calc);
				}

				// else if(consumed_val==''){

				// }
			}
		})

		$(document).on('change', '.product_option', function() {
			let current_tr = $(this).closest('tr');
			// console.log(current_tr.HTML);
			let prod_id = $(this).val();
			let breakup = $(this).closest('tr').find('.row_count');
			let breakup_row = breakup.val();
			// console.log(breakup_row);
			let width_id = $('#width_id' + breakup_row).val();
			// console.log(width_id);
			$.ajax({
				method: 'post',
				url: '../ajax_returns.php',
				data: {
					prod_id: prod_id,
					width_id : width_id,
					pagename: 'cutting-sheet',
					page: 'get_product_details',
				},
				success: function(data) {
					// console.log(data);
					json_data = JSON.parse(data);
					current_tr.find('.size_option').html(json_data.size_option);
					current_tr.find('.pattern_option').html(json_data.pattern_option);
					// $('#consumed').val(0);
					// $('#excess').val(0);
					// $('#final_balance').val(0);
				}
			})
		});

		// $(document).on('input', '.consumption', function() {
		// 	let changedInput = $(this);  
		// 	console.log(changedInput);  // You can also add further logic here to perform actions like recalculating totals or updating related fields.
		// });

		// function change_cal_consumption(){
		// 	console.log("hii");
		// }
		// Attach the event handler for the "Add Cutting Breakupwise" button outside of the blur function
		$(document).on('click', '.add_cutting_breakupwise', function() {
			let current_row = $(this).closest('tr');
			let row_count = $(this).attr('data_row_count');
			// let stock_id = $('#stock_id').val();
			let stock_id = $(this).attr('date_stock_id');
			// console.log(stock_id);
			let raw_id = $('#raw_id' + row_count);
			let raw_id_value = raw_id.val();
			// console.log(raw_id_value);
			$(this).closest('table').find('.breakup_table').remove(); // Removes .breakup_table
			$(this).closest('table').find('tbody tr:nth-child(n+2)').remove(); // Removes the second <tr> inside <tbody>


			let breakup_table = "";
			if ($(this).closest('table').find('.breakup_table').length > 0) {
				alert('This breakup table has already been added for the selected stock!');
				return;
			}

			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					stock_id: stock_id,
					pagename: 'cutting-sheet',
					page: 'cutting_breakupwise',
				},
				success: function(data) {
					// Parse the response data
					let json_data = JSON.parse(data);
					// console.log(json_data);
					let meter_tally = json_data.bale_meter;
					let cutting_prod = json_data.cutting_prod;

					// Start building the breakup table HTML
					let breakup_table = `<table class="table table-bordered breakup_table" data-stock-id="${stock_id}">
                            <tr>
                                <th style="text-align:center; width:300px !important">Breakup</th>
                                <th style="text-align:center;">Product</th>
                                <th style="text-align:center;">Size</th>
                                <th style="text-align:center;">Pattern</th>
                                <th style="text-align:center;">Quantity</th>
								<th style="text-align:center;">Consumption</th>
                                <th style="text-align:center;">Action</th>
                            </tr>`;

					// Loop through the meter_tally data and build rows dynamically
					for (let k = 0; k < meter_tally.length; k++) {
						breakup_table += `<tr>
                            <td style="text-align:center;display: flex; justify-content: center;">
                                <input type="text" value="${meter_tally[k]['meter_breakup']}" name="meter_breakup[${raw_id_value}][]" readonly style="background: #ebebeb;border: none;padding: 5px 10px;font-weight: bolder; width:40% !important">
                                &nbsp;<input type="text" class="form-control" name="remark[${raw_id_value}][][]" placeholder="color" style="width:40% !important">
                            </td>
                            <td style="text-align:center;">
                                <select class="form-control product_option" name="product[${raw_id_value}][${meter_tally[k]['meter_breakup']}][]" style="width:100% !important">
                                    <option value="">Select Product</option>
                                    ${cutting_prod.map(function(product) {
                                        return `<option value="${product.id}">${product.product_name}</option>`;
                                    }).join('')}
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <input type="hidden" class="row_count" value="${row_count}">
                                <select class="form-control size_option" name="size[${raw_id_value}][${meter_tally[k]['meter_breakup']}][]" style="width:100% !important">
                                    <option value="">Select Size</option>
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <select class="form-control pattern_option" name="pattern[${raw_id_value}][${meter_tally[k]['meter_breakup']}][]" style="width:100% !important">
                                    <option value="">Select Pattern</option>
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <input type="hidden" class="form-control consumption" style="width:100% !important">
                                <input type="hidden" class="form-control" value="${json_data.job}" name="job[${raw_id_value}]" style="width:100% !important">
                                <input type="hidden" class="form-control" value="${JSON.parse(json_data.job_sqn)}" name="job_sqn[${raw_id_value}]" style="width:100% !important">
                                <input type="text" class="form-control consume_input" name="consumption2[${raw_id_value}][${meter_tally[k]['meter_breakup']}][]" min="1" style="width:100% !important" >
                            </td>

							<td style="text-align:center;">
                                <input type="text" class="form-control cal_consumption" style="width:100% !important">
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn-sm btn-primary add_more_item" >Add</button>
                            </td>
                        </tr>`;

						// Update the total breakout quantity
						$('#total_breakup_quantity').val(parseInt($('#total_breakup_quantity').val()) + parseInt(meter_tally[k]['quantity']));
					}

					// Close the table tag
					breakup_table += `</table>`;

					// Append the breakup table as a new row directly after the current row
					current_row.after(`<tr><td colspan="9">${breakup_table}</td></tr>`);

					// $(document).ready(function () {
						console.log(jQuery.fn.jquery);

    if (!$.fn.DataTable.isDataTable('.breakup_table')) {
        $('.breakup_table').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "lengthChange": false,
            "ordering": false,
            "responsive": true
        });
    }
// });
                


					// $('.subsidary_list').html('');
					// $('.subsidary_item').hide();
				}

			});
		});




		$(document).on('click', '.add_total', function() {
			let current_row = $(this).closest('tr');
			let row_count = $(this).attr('data_row_count');
			let stock_id = $('#stock_id').val();
			let raw_id = $('#raw_id' + row_count);
			let raw_id_value = raw_id.val();
			// console.log(raw_id_value);

			let breakup_table = "";
			$(this).closest('table').find('.breakup_table').remove(); // Removes .breakup_table
			$(this).closest('table').find('tbody tr:nth-child(n+2)').remove();


			// $(this).closest('table').find('.breakup_table').length=0;
			if ($(this).closest('table').find('.breakup_table').length > 0) {
				alert('This breakup table has already been added for the selected stock!');
				return;
			}

			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					stock_id: stock_id,
					pagename: 'cutting-sheet',
					page: 'cutting_breakupwise',
				},
				success: function(data) {
					// Parse the response data
					let json_data = JSON.parse(data);
					// console.log(json_data);
					let meter_tally = json_data.bale_meter;
					let cutting_prod = json_data.cutting_prod;

					// Start building the breakup table HTML
					let breakup_table = `<table class="table table-bordered breakup_table" data-stock-id="${stock_id}">
                            <tr>
                                <th style="text-align:center;">Product</th>
                                <th style="text-align:center;">Size</th>
                                <th style="text-align:center;">Pattern</th>
                                <th style="text-align:center;">Quantity</th>
                                <th style="text-align:center;">Action</th>
                            </tr>`;

					// Loop through the meter_tally data and build rows dynamically
					for (let k = 0; k < meter_tally.length; k++) {
						if (k == 0) {
							breakup_table += `<tr>
                            <td style="text-align:center;">
                                <select class="form-control product_option" name="product[${raw_id_value}][][]" style="width:100% !important">
                                    <option value="">Select Product</option>
                                    ${cutting_prod.map(function(product) {
                                        return `<option value="${product.id}">${product.product_name}</option>`;
                                    }).join('')}
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <input type="hidden" class="row_count" value="${row_count}">
                                <select class="form-control size_option" name="size[${raw_id_value}][][]" style="width:100% !important">
                                    <option value="">Select Size</option>
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <select class="form-control pattern_option" name="pattern[${raw_id_value}][][]" style="width:100% !important">
                                    <option value="">Select Pattern</option>
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <input type="hidden" class="form-control consumption" style="width:100% !important">
                                <input type="hidden" class="form-control cal_consumption" style="width:100% !important">
                                <input type="hidden" class="form-control" value="${json_data.job}" name="job[${raw_id_value}]" style="width:100% !important">
								<input type="hidden" class="form-control" value="add_total" name="button_type" style="width:100% !important">
                                <input type="hidden" class="form-control" value="${JSON.parse(json_data.job_sqn)}" name="job_sqn[${raw_id_value}]" style="width:100% !important">
                                <input type="text" class="form-control consume_input" name="consumption2[${raw_id_value}][][]" min="1" style="width:100% !important" >
                            </td>
                            <td style="text-align:center;">
                                <button type="button" class="btn-sm btn-primary add_more_item" data-type="add_total">Add</button>
                            </td>
                        </tr>`;


							// Update the total breakout quantity
							$('#total_breakup_quantity').val(parseInt($('#total_breakup_quantity').val()) + parseInt(meter_tally[k]['quantity']));
						} else {
							break;
						}
					}

					// Close the table tag
					breakup_table += `</table>`;

					// Append the breakup table as a new row directly after the current row
					current_row.after(`<tr><td colspan="9">${breakup_table}</td></tr>`);

					// $('.subsidary_list').html('');
					// $('.subsidary_item').hide();
					// Add event listener for the "Add" button
				}

			});
		});

		$(document).on('click', '.add_more_item', function() {
			// Find the current row of the clicked button
			var currentRow = $(this).closest('tr');

			// console.log(currentRow.html())
			// Clone the current row
			var clonedRow = currentRow.clone();
			// console.log(clonedRow);
			// Optionally clear input values in the cloned row (except for static values)
			clonedRow.find('input[type="text"], select').val('');

			// Make the first column empty
			if ($(this).attr('data-type') != "add_total") {
				clonedRow.find('td:first').html('');
			}

			clonedRow.find('td:first').css('display', 'table-cell');
			clonedRow.find('td:last').html('<div style="display:flex"><button type="button" class="btn-sm btn-primary add_more_item" data-type="add_total">Add</button> &nbsp;&nbsp; <button type="button" class="btn-sm btn-danger remove_more_item">Remove</button></div>'); // Add the "Remove" button

			clonedRow.css('border-top', 'none');

			//currentRow.after(clonedRow);
			currentRow.after(clonedRow);
		});



		$(document).on('click', '.remove_more_item', function() {
			$(this).closest('tr').remove();
		});

		// document.addEventListener('contextmenu', function (e) {
		//     e.preventDefault();
		// });
		let qty = 0;
		$('#stock_id').blur(function() {
			var stock_id = $(this).val();

			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					stock_id: stock_id,
					getStock: 'getStock'
				},
				dataType: "json",
				success: function(data) {
					// console.log(data);
					if (data != false) {
						$('#raw_id').val(data['raw_id']);
						$('#width_id').val(data['width_id']);
						$('#meter').val(data['meter']);
						$('#qty').val(data['quantity']);
						$('#d_no').val(data['d_no']);
						var main_row_count = $('#main_row_count').val();

						if (main_row_count < 3) {
							var prev_tb = '.main_row' + main_row_count;
							main_row_count++;
							var i = 1;
							var table_view = '';
							table_view += '<tr><th style="text-align:center;">Breakup</th><th style="text-align:center;" width="10%">Raw Material</th><th style="text-align:center;" width="5%">Design No.</th><th style="text-align:center;" width="35%">Width</th><th style="text-align:center; display:none;" width="35%">Meter</th><th style="text-align:center;" width="35%">Total Qty</th><th style="text-align:center;" width="35%">Available Qty</th></tr>';
							table_view += '<tr><td style="text-align:center;">' + main_row_count + '</td><td><img src="../uploads/' + data['image'] + '" class="img-responsive" style="width:70px;">' + data['raw_name'] + '<input type="hidden" name="raw_id[]" id="raw_id' + main_row_count + '" value="' + data['raw_id'] + '"><input type="hidden" name="stock_id' + main_row_count + '" id="stock_id' + main_row_count + '" value="' + stock_id + '"></td><td style="text-align:center;">' + data['d_no'] + '<input type="hidden" name="d_no' + main_row_count + '" id="d_no' + main_row_count + '" value="' + data['d_no'] + '"></td><td style="text-align:center;">' + data['width_name'] + '<input type="hidden" name="width_id' + main_row_count + '" id="width_id' + main_row_count + '" value="' + data['width_id'] + '"></td><td style="text-align:center;display:none;">' + data['meter'] + '<input type="hidden" name="meter' + main_row_count + '" id="meter' + main_row_count + '" value="' + data['meter'] + '"></td><td style="text-align:center;">' + data['quantity'] + '<input type="hidden" name="quantity1' + main_row_count + '" id="quantity1' + main_row_count + '" value="' + data['quantity'] + '"></td><td style="text-align:center;">' + data['current_stock'] + '<input type="hidden" name="quantity' + main_row_count + '" id="quantity' + main_row_count + '" value="' + data['current_stock'] + '"></td></tr>';
							// qty = parseFloat(qty) + parseFloat(data['current_stock']);
							qty = parseFloat(qty) + parseFloat(data['current_stock']);

							$('#total_qty').val(qty);
							$('#total_qty').attr('readonly', 'true');
							table_view += '<tr><td colspan="9"><table class="table table-bordered table-condensed"><tr><th colspan="9"><button type="button" class="btn btn-success add_cutting_breakupwise" date_stock_id = ' + stock_id + ' data_row_count = ' + main_row_count + '>Add Cutting Breakupwise</button>&nbsp;&nbsp;<button type="button" class="btn btn-danger add_total" date_stock_id = ' + stock_id + ' data_row_count = ' + main_row_count + '>Add Total</button></th></tr></table>';

							// Append table if it's the first row
							if (main_row_count == 1) {
								$('#table2').html(table_view);
							} else {
								// $('#table2 tr:last').after(table_view);
								$('#table2').append(table_view);
							}
							$('#main_row_count').val(main_row_count);
							getBedsheetsize(data['width_id']);
							getPillowsize(data['width_id']);
							getFinalCal(main_row_count);
							getTotalBed(main_row_count);
						}
					} else {
						if (stock_id != '') alert('Stock not available!');
						stock_id = '';
						$('#stock_id').val('');
						$('#raw_id').val('');
					}
				}
			});
		});



		// $('#barcode_id').keyup(function(e) {
		// 	var barcode = $(this).val();

		// 	// Prevent form submission on Enter key
		// 	if (e.key === 'Enter') {
		// 		e.preventDefault(); // Prevent form submission
		// 		return; // Exit the function early
		// 	}

		// 	if (barcode.length === 6) {
		// 		$.ajax({
		// 			type: "POST",
		// 			url: "../ajax_returns.php",
		// 			data: {
		// 				barcode: barcode,
		// 				page: 'barcode'
		// 			},
		// 			success: function(data) {
		// 				var data = JSON.parse(data);
		// 				selectStock(data[0]['id']);
		// 				// Clear the barcode input if desired
		// 				// $('#barcode_id').val(''); 
		// 			}
		// 		});
		// 	}
		// });
		$('#query').keyup(function() {
			var query = $(this).val();
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "GET",
				url: "joblist.php",
				data: {
					query: query,
					shop: shop
				},
				success: function(data) {
					$('#joblist').html(data);
				}
			});
		});

		function addOld() {
			$('#job_id').removeAttr("readonly");
		}

		function getRaw() {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					getRaw: 'getRaw',
					page: 'cutting'
				},
				success: function(data) {
					$.each($(".selraw"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				}
			});
		}

		function getTotalMeter(main_row) {
			var mid = "#metrebreak" + main_row;
			var tid = "#totalmeter" + main_row;
			var total = 0;
			for (j = 1; j <= 12; j++) {
				metre = parseFloat($(mid + j).val());
				if (isNaN(metre)) {
					metre = 0;
				}
				total += metre;
			}
			$(tid).val(total);
			getFinalCal(main_row);
		}

		function getTotalBed(main_row) {
			var bsize = "#bedsheetsize" + main_row;
			var bpieces = "#bpieces" + main_row;
			var totalbed = "#totalbed" + main_row;
			var total = 1;
			var ftotal = 0;

			for (j = 1; j <= 12; j++) {
				for (k = 1; k <= 6; k++) {
					bed = $(bsize + j + k).val();
					if (isNaN(bed) || bed == null || bed == '') {} else {
						ftotal += (total) * $(bpieces + j + k).val();
					}
				}
			}
			$(totalbed).val(ftotal);
			getTotalConsume(main_row);
		}

		function getTotalPillow(main_row) {
			var psize = "#pillowsize" + main_row;
			var ppieces = "#ppieces" + main_row;
			var totalpillow = "#totalpillow" + main_row;
			var total = 1;
			var ftotal = 0;
			for (j = 1; j <= 12; j++) {
				for (k = 1; k <= 6; k++) {
					pillow = $(psize + j + k).val();
					if (isNaN(pillow) || pillow == null || pillow == '') {} else {
						ftotal += (total) * $(ppieces + j + k).val();
					}
				}
			}
			$(totalpillow).val(ftotal);
			getTotalConsume(main_row);
		}

		function getTotalConsume(main_row) {
			var consumeid = "#consume" + main_row;
			var totalconsume = "#totalconsume" + main_row;
			var total = 0;
			for (j = 1; j <= 12; j++) {
				for (k = 1; k <= 6; k++) {
					consume = parseFloat($(consumeid + j + k).val());
					if (isNaN(consume) || consume == null || consume == '') {} else {
						total += consume;
					}
				}
			}
			$(totalconsume).val(total);
			getFinalCal(main_row);
		}

		function getConsume(i) {
			var bsize = "#bedsheetsize" + i;
			var bpieces = "#bpieces" + i;
			var psize = "#pillowsize" + i;
			var ppieces = "#ppieces" + i;
			var cid = "#consume" + i;
			var btotal = 0;
			var ptotal = 0;
			var consume = 0;
			bsize = parseFloat($(bsize).val());
			if (isNaN(bsize)) {
				bsize = 0;
			}
			bpieces = parseFloat($(bpieces).val());
			if (isNaN(bpieces)) {
				bpieces = 0;
			}
			btotal = bsize * bpieces;
			psize = parseFloat($(psize).val());
			if (isNaN(psize)) {
				psize = 0;
			}
			ppieces = parseFloat($(ppieces).val());
			if (isNaN(ppieces)) {
				ppieces = 0;
			}
			ptotal = psize * ppieces;
			consume = btotal + ptotal;
			//console.log(consume);
			$(cid).val(consume);
		}

		function getFinalCal(main_row) {
			var meter = "#totalmeter";
			var qty = "#quantity";
			var fmeter = "#finalmeter";
			var mtotal = 0;
			var tbedsheet = "#totalbed";
			var tpillow = "#totalpillow";
			var tconsume = "#totalconsume";
			var btotal = 0;
			var ptotal = 0;
			var ctotal = 0;
			var excess = 0;
			var wastage = 0;
			var diff = 0;
			for (j = 1; j <= 3; j++) {
				m = parseFloat($(meter + j).val());
				if (isNaN(m)) {
					m = 0;
				} else {
					mtotal += m;
				}
			}
			for (j = 1; j <= 3; j++) {
				tb = parseFloat($(tbedsheet + j).val());
				if (isNaN(tb)) {
					tb = 0;
				} else {
					btotal += tb;
				}
			}
			for (j = 1; j <= 3; j++) {
				tp = parseFloat($(tpillow + j).val());
				if (isNaN(tp)) {
					tp = 0;
				} else {
					ptotal += tp;
				}
			}
			for (j = 1; j <= 3; j++) {
				tc = parseFloat($(tconsume + j).val());
				if (isNaN(tc)) {
					tc = 0;
				} else {
					ctotal += tc;
				}
			}
			$('#finalmeter').val(mtotal);
			$('#finalbed').val(btotal);
			$('#finalpillow').val(ptotal);
			var res = parseFloat(mtotal - ctotal);
			if (ctotal > mtotal) {
				$('#excess').val(res);
				$('#wastage').val('0');
			} else if (ctotal < mtotal) {
				$('#excess').val('0');
				$('#wastage').val(res);
			} else {
				$('#excess').val('0');
				$('#wastage').val('0');
			}

		}
		// function addRaw(){
		// $('.add_mb').click(function(){
		$('body').on('click', '.add_mb', function() {
			var that = $(this);
			var rcount = that.closest('tr').find('.rcount').val();
			var count = that.closest('tr').find('.count' + rcount).val();
			var width_id = $('#width_id' + rcount).val();
			var prev = ".row" + rcount + count;
			//alert(prev);
			count++;
			if (count != 12) {
				var row = '<tr class="rows row' + rcount + count + '">';
				row += '<td align="center">' + count + '</td>';
				row += '<td align="center"><input type="text" class="form-control metre to-enter" name="metrebreak' + rcount + count + '" id="metrebreak' + rcount + count + '" onKeyUp="getTotalMeter(' + rcount + ')" placeholder="Enter Meter"></td><td>';
				var no = 0;
				for (j = 0; j < 3; j++) {
					for ($k = 0; $k < 2; $k++) {
						no++;
						row += '<input type="text" class="form-control remark" name="remark' + rcount + count + '[]" id="remark' + rcount + count + '" placeholder="Enter Color">';
					}
				}
				row += '</td><td><div>';
				var no = 0;
				for (j = 0; j < 3; j++) {
					for ($k = 0; $k < 2; $k++) {
						no++;
						row += ' <span class="no" style="text-align:right;">' + no + '.</span>';
						row += '<select name="bedsheetsize' + rcount + count + '[]" id="bedsheetsize' + rcount + count + no + '" class="bedsheetsize  to-enter" onchange="getConsume(' + rcount + count + no + '); getTotalBed(' + rcount + ');"></select>';
						row += '<div class="into">X</div>';
						row += '<input type="text" class="form-control bpieces to-enter" name="bpieces' + rcount + count + '[]" id="bpieces' + rcount + count + no + '" value="1" onKeyUp="getConsume(' + rcount + count + no + '); getTotalBed(' + rcount + ');">';
					}
				}
				row += '</div></td>';
				row += '<td><div>';
				var no = 0;
				for (j = 0; j < 3; j++) {
					for ($k = 0; $k < 2; $k++) {
						no++;
						row += ' <span class="no" style="text-align:right;">' + no + '.</span>';
						row += '<select name="pillowsize' + rcount + count + '[]" id="pillowsize' + rcount + count + no + '" class="pillowsize  to-enter" onchange="getConsume(' + rcount + count + no + '); getTotalPillow(' + rcount + ');"></select>';
						row += '<div class="into">X</div>';
						row += '<input type="text" class="form-control ppieces to-enter" name="ppieces' + rcount + count + '[]" id="ppieces' + rcount + count + no + '" value="1" onKeyUp="getConsume(' + rcount + count + no + '); getTotalPillow(' + rcount + ')">';
					}
				}
				row += '</div></td><td>';
				var no = 0;
				for (j = 0; j < 3; j++) {
					for ($k = 0; $k < 2; $k++) {
						no++;
						row += '<input type="text" class="form-control consume" id="consume' + rcount + count + no + '" name="consume' + rcount + count + '[]" readonly >';
					}
				}
				row += '</td></tr>';

				$(row).insertAfter(prev);
				that.closest('tr').find('.count' + rcount).val(count);
				getBedsheetsize(width_id);
				getPillowsize(width_id);
			}
		});

		function getBedsheetsize(width_id) {
			var shop = "<?php echo $shop; ?>";
			var width_id = width_id;

			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					width_id: width_id,
					getBedsheetsize: 'getBedsheetsize'
				},
				success: function(data) {
					// console.log(data);
					$.each($(".bedsheetsize"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				}
			});
		}

		// 		document.addEventListener('focus', function() {
		//     document.body.style.filter = 'blur(5px)';
		// }, true);

		function getPillowsize(width_id) {
			var shop = "<?php echo $shop; ?>";
			var width_id = width_id;
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					width_id: width_id,
					getPillowsize: 'getPillowsize'
				},
				success: function(data) {
					// console.log(data);
					$.each($(".pillowsize"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				}
			});
		}

		function validate() {
			var count = $('#count').val();
			var total = 0;
			$('.total').each(function() {
				t = $(this).val();
				if (isNaN(t)) {
					t = 0;
				}
				total += t;
			});
			if (count == 0) {
				alert("Please add a product!");
				return false;
			} else if (total == 0 && $('.total').length > 1) {
				alert("Please add a product!");
				return false;
			} else {
				if (confirm("Click Ok to Submit. \nClick Cancel to Edit.")) {
					return true;
				} else {
					return false;
				}
			}
		}

		function navigate(page, query) {
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "GET",
				url: "joblist.php",
				data: {
					query: query,
					shop: shop,
					page: page
				},
				success: function(data) {
					$('#joblist').html(data);
				}
			});
		}

		function printThis() {
			var job_id = $('#job_id').val();
			window.location = "print_jobslip.php?job_id=" + job_id;
		}



		$(document).ready(function() {
			let debounceTimer;

			$('#barcode_id').on('keydown', function(e) {
				// Prevent form submission on Enter key
				if (e.key === 'Enter') {
					e.preventDefault(); // Prevent the default action of Enter key
				}
			});

			$('#barcode_id').on('keyup', function() {
				clearTimeout(debounceTimer); // Clear the previous timer
				var barcode = $(this).val();

				// Set a new timer for debouncing
				debounceTimer = setTimeout(function() {
					if (barcode.length === 6) {
						$.ajax({
							type: "POST",
							url: "../ajax_returns.php",
							data: {
								barcode: barcode,
								page: 'barcode'
							},
							success: function(data) {
								var data = JSON.parse(data);
								selectStock(data[0]['id']);
								// Optionally clear the input
								$('#barcode_id').val('');
							}
						});
					}
				}, 300); // Wait for 300 ms before sending the request
			});


		});

		function validateform() {
			let worker_id = $('#worker_id').val();
			if (worker_id == '') {
				alert('Please select a worker.');
				return false;
			}
			return true;
		}
	</script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>