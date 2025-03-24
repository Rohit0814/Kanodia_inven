<?php
session_start();
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
	<script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.css" />
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.2.1/js/dataTables.searchBuilder.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
	<style>
		.selwid,
		.consume,
		.selraw,
		.used,
		.selworker,
		.charge,
		.pattern {
			position: relative;
			float: left;
			width: 23.5%;
			margin: 2px;
		}

		@media(min-width:1220px) {
			.selwid,
			.consume,
			.selraw,
			.used,
			.selworker,
			.charge,
			.pattern {
				position: relative;
				float: left;
				width: 24%;
				margin: 2px;
			}
		}
	</style>
	<title>Master Key</title>
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
				<div class="panel panel-success">
					<div class="panel-heading">
						<font size="+2">Master Key</font>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2"><br>
								<ul class="nav nav-pills nav-stacked">
									<li <?php if (!isset($_GET['wipage']) && !isset($_GET['jprocess']) && !isset($_GET['dpage']) && !isset($_GET['productpage']) && !isset($_GET['patternpage']) && !isset($_GET['rpage']) && !isset($_GET['fpage'])  && !isset($_GET['spage']) && !isset($_GET['ppage']) && !isset($_GET['apage']) && !isset($_GET['supplier'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#jobDiv">Job Worker</a></li>
									<li <?php if (isset($_GET['dpage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#DesignationDiv">Designation</a></li>
									<li <?php if (isset($_GET['productpage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#productDiv">Product</a></li>
									<li <?php if (isset($_GET['rpage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#rawDiv">Raw Material</a></li>
									<li <?php if (isset($_GET['patternpage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#paymentDiv">Pattern</a></li>
									<li <?php if (isset($_GET['wipage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#widthDiv">Width</a></li>
									<li <?php if (isset($_GET['spage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#sizeDiv">Size</a></li>
									<!-- <li <?php if (isset($_GET['fpage'])) {
													echo "class='active'";
												} ?>><a data-toggle="pill" href="#finishDiv">Finish Product</a></li> -->
									<li <?php if (isset($_GET['ppage'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#payDiv">Payment</a></li>
									<li <?php if (isset($_GET['apage'])) {
											echo "class='active'";
										} ?> style="display:none"><a data-toggle="pill" href="#attendanceDiv">Attendance</a></li>

									<li <?php if (isset($_GET['supplier'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#supplierDiv">Supplier</a></li>

									<li <?php if (isset($_GET['jprocess'])) {
											echo "class='active'";
										} ?>><a data-toggle="pill" href="#jobprocessDiv">Job Process</a></li>
								</ul>
							</div>
							<div class="col-md-10">
								<div class="tab-content">
									<div id="jobDiv" class="tab-pane fade<?php if (!isset($_GET['wipage']) && !isset($_GET['jprocess']) && !isset($_GET['dpage']) && !isset($_GET['patternpage']) && !isset($_GET['productpage']) && !isset($_GET['rpage']) && !isset($_GET['fpage']) && !isset($_GET['spage']) && !isset($_GET['ppage']) && !isset($_GET['apage']) && !isset($_GET['supplier'])) {
																				echo " in active";
																			} ?> ">
										<h3 style="border-bottom:1px solid #cdcdcd;">Job Workers</h3>
										<?php include("workers.php"); ?>
									</div>

									<div id="DesignationDiv" class="tab-pane fade<?php if (isset($_GET['dpage'])) {
																						echo " in active";
																					} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Designation</h3>
										<?php include("designation.php"); ?>
									</div>

									<div id="productDiv" class="tab-pane fade<?php if (isset($_GET['productpage'])) {
																					echo " in active";
																				} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Products</h3>
										<?php include("items.php"); ?>
									</div>

									<div id="rawDiv" class="tab-pane fade<?php if (isset($_GET['rpage'])) {
																				echo " in active";
																			} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Raw Material</h3>
										<?php include("rawmaterials.php"); ?>
									</div>
									<div id="paymentDiv" class="tab-pane fade<?php if (isset($_GET['patternpage'])) {
																					echo " in active";
																				} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Pattern</h3>
										<?php include("pattern.php"); ?>
									</div>

									<div id="widthDiv" class="tab-pane fade<?php if (isset($_GET['wipage'])) {
																				echo " in active";
																			} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Width</h3>
										<?php include("width.php"); ?>
									</div>
									<div id="sizeDiv" class="tab-pane fade<?php if (isset($_GET['spage'])) {
																				echo " in active";
																			} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Size</h3>
										<?php include("size.php"); ?>
									</div>
									<!-- <div id="finishDiv" class="tab-pane fade<?php if (isset($_GET['fpage'])) {
																						echo " in active";
																					} ?>">
                                            <h3 style="border-bottom:1px solid #cdcdcd;">Finish Product</h3>
                                            <?php include("finished.php"); ?>
                                        </div> -->
									<div id="payDiv" class="tab-pane fade<?php if (isset($_GET['ppage'])) {
																				echo " in active";
																			} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Payment</h3>
										<?php include("payment.php"); ?>
									</div>
									<div id="attendanceDiv" class="tab-pane fade<?php if (isset($_GET['apage'])) {
																					echo " in active";
																				} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Attendance</h3>
										<?php include("attendance.php"); ?>
									</div>

									<div id="supplierDiv" class="tab-pane fade<?php if (isset($_GET['supplier'])) {
																					echo " in active";
																				} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Supplier</h3>
										<?php include("supplier.php"); ?>
									</div>

									<div id="jobprocessDiv" class="tab-pane fade<?php if (isset($_GET['jprocess'])) {
																					echo " in active";
																				} ?>">
										<h3 style="border-bottom:1px solid #cdcdcd;">Job Process</h3>
										<?php include("jobprocess.php"); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end of col-md-12 -->
		</div>
	</div>
	<script language="javascript">
		document.onkeydown = function(e) {
			if (e.which == 113) {
				$('#savebtn').click();
			}
		}

		$(document).ready(function(e) {



			$('#att_worker').select2({
				placeholder: {
					id: '',
					text: '-- Select Multiple Worker --'
				},
				width: '100%'
			});

			$('#att_job').select2({
				placeholder: {
					id: '',
					text: '-- Select Job In Sequence--'
				},
				width: '100%'
			});

			// $('#job_process').select2({
			// 	placeholder: {
			// 		id: '',
			// 		text: '-- Select Job In Sequence--'
			// 	},
			// 	width: '100%'
			// });
			let selectedOrder = [];

			// Initialize Select2
			let $select = $("#job_process").select2({
				placeholder: "-- Select Job In Sequence --",
				width: '100%'
			});
			// When an option is selected
			$("#job_process").on("select2:select", function(e) {
				let value = e.params.data.id;
				if (!selectedOrder.includes(value)) {
					selectedOrder.push(value);
				}
				reorderSelect2();
			});

			// When an option is unselected
			$("#job_process").on("select2:unselect", function(e) {
				let value = e.params.data.id;
				selectedOrder = selectedOrder.filter(id => id !== value);
				reorderSelect2();
			});

			function reorderSelect2() {
				$select.val(selectedOrder);
				$('#input_select').val(selectedOrder);
				console.log($select.val());
				// console.log("Updated Order:", selectedOrder);
			}

			$('#att_permission').select2({
				placeholder: {
					id: '',
					text: '-- Select Job Designation--'
				},
				width: '100%'
			});

			$('body').on('change', '#payment_type', function(e) {
				if ($(this).val() == 'Day_Wise') {
					$('#payment').closest('tr').removeClass('hidden');
				} else {
					$('#payment').closest('tr').addClass('hidden');

				}

			});

			$('body').on('keypress', '.w-enter', function(e) {
				if (e.which == 13) {
					var index = $(this).index('.w-enter');
					index++;
					if (index < $('.w-enter').length) {
						$('.w-enter').eq(index).focus();
						e.preventDefault();
					}
				}
			});
			$('body').on('keypress', '.r-enter', function(e) {
				if (e.which == 13) {
					var index = $(this).index('.r-enter');
					index++;
					if (index < $('.r-enter').length) {
						$('.r-enter').eq(index).focus();
						e.preventDefault();
					}
				}
			});
			$('body').on('keypress', '.f-enter', function(e) {
				if (e.which == 13) {
					var index = $(this).index('.f-enter');
					index++;
					if (index < $('.f-enter').length) {
						$('.f-enter').eq(index).focus();
						e.preventDefault();
					}
				}
			});
			$('body').on('keypress', '.s-enter', function(e) {
				if (e.which == 13) {
					var index = $(this).index('.s-enter');
					index++;
					if (index < $('.s-enter').length) {
						$('.s-enter').eq(index).focus();
						e.preventDefault();
					}
				}
			});
			getRaw('', '');
			getWorker('', '');
			getWidth('', '');
			var table = $('.datatable').DataTable({
				searchBuilder: true,
				responsive: true,
				dom: 'Bfrtip',
				buttons: [
					'pdf', 'print'
				]
			});
		});

		function getWorker(wsize, workers) {
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					getWorker: 'getWorker'
				},
				success: function(data) {
					$.each($(".selworker"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				},
				complete: function() {
					setWorkers(wsize, workers);
				}
			});
		}

		function paymentmode(pay1, pay2) {
			// Select the first elements matching the classes
			if (pay1 == "worker_wise") {
				$('#add_payment').html("Add Payment Worker Wise");
				$('#payment_list').html("Payment List Worker Wise");
			}
			if (pay1 == "product_wise") {
				$('#add_payment').html("Add Payment Product Wise")
				$('#payment_list').html("Payment List Product Wise");
			}
			let payment1 = $('.' + pay1);
			let payment2 = $('.' + pay2);

			// Check if payment1 element exists, then display it
			if (payment1.length > 0) {
				payment1.css('display', 'block');
			}

			// Check if payment2 element exists, then hide it
			if (payment2.length > 0) {
				payment2.css('display', 'none');
			}
			$('.payment-head').css('display', 'block');
		}

		$('#payment_product').change(function() {
			var val = $(this).val();
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					product: val,
					getProduct: 'getProduct'
				},
				success: function(data) {
					//    console.log(data);
					let json_data = JSON.parse(data);
					$('#payment_size').html(json_data.size);
					$('#payment_pattern').html(json_data.pattern);
				}
			})
		})


		function getRaw(size, materials) {
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					getRaw: 'getRaw'
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
				},
				complete: function() {
					setValues(size, materials);
				}
			});
		}

		function getWidth(csize, width) {
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					getWidth: 'getWidth'
				},
				success: function(data) {
					$.each($(".selwid"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				},
				complete: function() {
					setWidths(csize, width);
				}
			});
		}

		function getpattern(csize, pattern) {
			var shop = "<?php echo $shop; ?>";
			let product_id = $('#item1').val();
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: product_id,
					shop: shop,
					getPattern: 'getPattern'
				},
				success: function(data) {
					$.each($(".pattern"), function() {
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				},
				complete: function() {
					setpatterns(csize, pattern);
				}
			});
		}

		function getsubsidaryproduct(csize, pattern) {
			var shop = "<?php echo $shop; ?>";
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					shop: shop,
					getsubsidaryproduct: 'getsubsidaryproduct'
				},
				success: function(data) {
					console.log(data);
					// $('.subsidary_prod');
					$.each($(".subsidary_prod"), function() {
						// console.log($(this));
						if ($(this).html() == '') {
							$(this).html(data);
						}
						if ($(this).val() == '') {
							$(this).html(data);
						}
					});
				},
				complete: function() {
					setsubsidary(csize, pattern);
				}
			});
		}

		function showThis(str1, str2) {
			var div1 = "#" + str1;
			var div2 = "#" + str2;
			$(div1).show();
			$(div2).hide();
		}

		function viewWorker(id) {
			$.ajax({
				type: "POST",
				url: "workerdetails.php",
				data: {
					id: id
				},
				success: function(data) {
					$('#workerdetails').html(data);
					$('#workerlist').hide();
					$('#workerdetails').show();
				}
			});
		}

		function viewSize(id) {
			$.ajax({
				type: "POST",
				url: "sizedetails.php",
				data: {
					id: id
				},
				success: function(data) {
					;
					$('#sizedetails').html(data);
					$('#sizelist').hide();
					$('#sizedetails').show();
				}
			});
		}

		function editWorker(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editWorker: 'editWorker'
				},
				dataType: "json",
				success: function(data) {
					// console.log(data);
					$('#wid').val(data['id']);
					$('#designation').val(data['designation']);
					$('#name').val(data['name']);
					$('#address').val(data['address']);
					$('#mobile').val(data['mobile']);
					$('#aadhar').val(data['aadhar']);
					$('#pan').val(data['pan']);
					$('#gst').val(data['gst']);
					$('#bank').val(data['bank']);
					$('#account').val(data['account']);
					$('#ifsc').val(data['ifsc']);
					$('#reference').val(data['reference']);
					$('#payment_type').val(data['payment_type']);
					if (data['payment_type'] == 'Day_Wise') {
						$('#payment').closest('tr').removeClass('hidden');
					}
					$('#payment').val(data['payment']);
					$('#username').val(data['username']);
					$('#password').val(data['password']);
					$('.save_worker').hide();
					$('.update_worker').show();
					$('#workerlist').hide();
					$('#addworker').show();
					$('#save_worker').attr('type', 'hidden');
					$('#workerForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editdesignation(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editdesignation: 'editdesignation'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					$('#designation1').val(json_data['designation']['designation']);
					$('#designationid').val(json_data['designation']['id']);
					$('.save_designation').hide();
					$('.update_designation').show();
					$('#designationlist').hide();
					$('#adddesignation').show();
					$('#save_designation').attr('type', 'hidden');
					$('#designationForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editProduct(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editProduct: 'editProduct'
				},
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data);
					$('#product').val(json_data['product']['product_name']);
					var selectedJobProcesses = json_data['job_seq'].map(function(job) {
						return job['id'];
					});
					$('#job_process').val(selectedJobProcesses);
					$('#productid').val(json_data['product']['id']);
					$('#job_process').prop('multiple', true);
					$('#job_process').trigger('change');
					$('#job_process').prop('multiple', true);
					$('.save_product').hide();
					$('.update_product').show();
					$('#productlist').hide();
					$('#addproduct').show();
					$('#save_product').attr('type', 'hidden');
					$('#productForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editPattern(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editPattern: 'editPattern'
				},
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data);
					$('#item').val(json_data['pattern']['prod_id']);
					$('#pattern').val(json_data['pattern']['pattern_name']);
					$('#patternid').val(json_data['pattern']['id']);
					$('.save_pattern').hide();
					$('.update_pattern').show();
					$('#patternlist').hide();
					$('#addpattern').show();
					$('#save_pattern').attr('type', 'hidden');
					$('#patternForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function deletedesignation(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deletedesignation: 'deletedesignation'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&dpage';
					}

				}
			});
		}

		function deleteProduct(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deleteProduct: 'deleteProduct'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&productpage';
					}

				}
			});
		}

		function deletePattern(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deletePattern: 'deletePattern'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&patternpage';
					}

				}
			});
		}

		function deleteWidth(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deleteWidth: 'deleteWidth'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&wipage';
					}

				}
			});
		}

		function deleteRaw(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deleteRaw: 'deleteRaw'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&rpage';
					}

				}
			});
		}

		function deleteprocess(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deleteprocess: 'deleteprocess'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&jprocess';
					}

				}
			});
		}

		function deletepayment(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deletepayment: 'deletepayment'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&ppage';
					}

				}
			});
		}

		function deletesupplier(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					deletesupplier: 'deletesupplier'
				},
				// dataType: "json",
				success: function(data) {
					json_data = JSON.parse(data);
					// console.log(json_data['designation']['designation']);
					if (json_data['status']) {
						window.location.href = '?pagename=masterkey&supplier';
					}

				}
			});
		}

		function editRaw(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editRaw: 'editRaw'
				},
				dataType: "json",
				success: function(data) {
					$('#rid').val(data['id']);
					$('#rname').val(data['name']);
					$('#unit').val(data['unit']);
					$('#type').val(data['type']);
					$('#rate').val(data['rate']);
					$('.save_raw').hide();
					$('.update_raw').show();
					$('#rawlist').hide();
					$('#addraw').show();
					$('#save_raw').attr('type', 'hidden');
					$('#rawForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editWidth(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editWidth: 'editWidth'
				},
				dataType: "json",
				success: function(data) {
					$('#widthid').val(data['id']);
					$('#width').val(data['width']);
					$('#w_unit').val(data['unit']);
					$('.save_width').hide();
					$('.update_width').show();
					$('#widthlist').hide();
					$('#addwidth').show();
					$('#save_width').attr('type', 'hidden');
					$('#widthForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editPayment(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editPayment: 'editPayment'
				},
				dataType: "json",
				success: function(data) {
					$('#paymentid').val(data.id);
					$('#worker').val(data.worker);
					$('#work_type').val(data.work_type);
					$('#pay_bedrate').val(data.bedsheet_rate);
					$('#pay_pillowrate').val(data.pillow_rate);
					$('.save_payment').hide();
					$('.update_payment').show();
					$('#paymentlist').hide();
					$('#addpayment').show();
					$('#save_payment').attr('type', 'hidden');
					$('#PaymentForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editPaymentWorker(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editPaymentWorker: 'editPaymentWorker'
				},
				dataType: "json",
				success: function(data) {
					console.log(data);
					if (data.work_type == 'worker_wise') {
						$('#payment_product').val('');
						$('#payment_product').trigger('change');
						$('#worker').val(data.worker.id);
						$('#worker_amt').val(data.rate);
						$('#paymentidworker').val(data.id);
						$('#parment_product_rate').val('');
						$('#paymentidproduct').val('');
						$('.save_payment_worker').hide();
						$('.update_payment_worker').show();
						$('.save_payment_product').show();
						$('.update_payment_product').hide();
						$('#paymentlist').hide();
						$('#addpayment').show();
						$('#save_payment_worker').attr('type', 'hidden');
						$('#PaymentFormWorker').attr('action', '../action/updateData.php');
					} else {
						$('#payment_product').val(data.product_id);
						$('#payment_product').trigger('change');
						setTimeout(function() {
							$('#payment_size').val(data.size_id);
							$('#payment_size').trigger('change');
							$('#payment_pattern').val(data.pattern_id);
							$('#payment_pattern').trigger('change');
						}, 50);
						$('#parment_product_rate').val(data.rate);
						$('#paymentidworker').val('');
						$('#worker').val('');
						$('#worker_amt').val('');
						$('#paymentidproduct').val(data.id);
						$('.save_payment_worker').show();
						$('.update_payment_worker').hide();
						$('.save_payment_product').hide();
						$('.update_payment_product').show();
						$('#paymentlist').hide();
						$('#addpayment').show();
						$('#save_payment_worker').attr('type', 'hidden');
						$('#PaymentFormproduct').attr('action', '../action/updateData.php');
					}
				}
			});
		}



		function editAttendance(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editAttendance: 'editAttendance'
				},
				dataType: "json",
				success: function(data) {
					$('#attendanceid').val(data.id);
					$('#att_worker').val(data.worker);
					$('#att_date').val(data.date);
					$('#amount').val(data.amount);
					$('#remark').val(data.remark);
					$('.save_attendance').hide();
					$('.update_attendance').show();
					$('#attendancelist').hide();
					$('#addattendance').show();
					$('#save_attendance').attr('type', 'hidden');
					$('#AttendanceForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editprocess(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editprocess: 'editprocess'
				},
				dataType: "json",
				success: function(data) {
					// console.log(data);
					$('#process_name').val(data['process']['process']);
					json_data = JSON.parse(data['process']['permission']);
					// console.log(json_data);
					var selectedJobProcesses = json_data.map(function(job) {
						// console.log(job);
						return job;
					});
					// console.log(selectedJobProcesses);
					$('#att_permission').val(json_data);
					$('#att_permission').prop('multiple', true);
					$('#att_permission').trigger('change');
					$('#att_permission').prop('multiple', true);
					$('#job_process_id').val(data['process']['id']);
					$('.save_jobprocess').hide();
					$('.update_jobprocess').show();
					$('#processlist').hide();
					$('#addprocess').show();
					$('#save_jobprocess').attr('type', 'hidden');
					$('#processForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editsupplier(id) {
			// console.log($id);
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editsupplier: 'editsupplier'
				},
				dataType: "json",
				success: function(data) {
					let supplier = data['supplier'];

					if (supplier['name'] != '' && supplier['name'] != null) {
						$('#supplier_name').val(supplier['name']);
					}
					$('#supplier_mobile').val(supplier['mobile']);
					$('#supplier_email').val(supplier['email']);
					$('#supplier_shop').val(supplier['shop_name']);
					$('#supplier_gst').val(supplier['gst']);
					$('#supplier_pan').val(supplier['pan']);
					$('#supplier_bank').val(supplier['bank_name']);
					$('#supplier_acc').val(supplier['account_no']);
					$('#supplier_ifsc').val(supplier['ifsc']);
					$('#state_id').val(supplier['state']);
					$('#state_id').trigger('change');
					setTimeout(function() {
						console.log(supplier['district']);
						$('#district_id').val(supplier['district']);
						$('#district_id').trigger('change');
					}, 700);
					$('#pin_code').val(supplier['pin_code']);
					$('#supplier_address').val(supplier['address']);
					$('#supplierid').val(supplier['id']);

					$('.save_supplier').hide();
					$('.update_supplier').show();
					$('#supplierlist').hide();
					$('#addsupplier').show();
					$('#save_supplier').attr('type', 'hidden');
					$('#supplierForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function editSize(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editSize: 'editSize'
				},
				dataType: "json",
				success: function(data) {
					let consume = data['consume']; // This is the object containing the consumption data
					// This will keep track of the count for dynamic element IDs

					// Set initial values in form
					var itemSelect = $('#item1');
					itemSelect.val(data['item']);
					itemSelect.trigger('change');
					$('#size').val(data['size']);
					$('#sid').val(data['id']);
					let idx = 0;
					$('#consumption').html(`<div id="consumption" style="display: block;">
					<input class="form-control selwid" name="rate[]" placeholder="Enter Rate">
                                <select name="width[]" id="width1" class="form-control selwid"><option value="">Select Width</option><option value="11">44</option><option value="12">58</option><option value="13">70</option><option value="14">90</option><option value="15">108</option></select>
                                <select name="pattern[]" id="pattern1" class="form-control pattern">
                                    <option value="">Select Pattern</option>
                                                                                <option value="9">xyz</option>
                                                                    </select>

                                <input type="text" name="consume[]" id="consume1" class="form-control consume" placeholder="Consumed">
                                
                            </div>`);
					// Loop through each consumption entry in 'consume'
					Object.values(consume).forEach(function(key1, value) {
						// console.log(key1);
						Object.keys(key1).forEach(function(key) {
							// console.log("Hi");
							var count = 1;
							let consumptionData = key1[key];
							// console.log(count);
							if (idx == 0) {
								count = 1;
								// console.log(key1[key]);

								var newRow = '';
								newRow = $('<tr id="subsidary2' + count + '"></tr>');

								let consume = $('.consume');
								let pattern = $('.pattern');
								let selwid = $('.selwid');

								consume.first().val(key1[key]['consume']);
								pattern.first().val(key1[key]['pattern']);
								selwid.first().val(key1[key]['width_id']);
								var subsidaryDiv = '';
								subsidaryDiv = $('<div></div>').attr('id', 'subsidary' + count).css({
									'margin': '0px 10px',
									'border-radius': '10px',
									'display': 'unset'
								});
								subsidaryDiv.empty();
								$('#subsidary' + count).html('');
								$('#subsidary2' + count).remove();

								key1['subsidary'].forEach(function(subsidaryItem) {
									// console.log();
									// console.log();
									var subsidaryRow = `<div id="subsidary" style="margin:0px 10px; border-radius: 10px; display:unset">
                                    <!-- <input type="text" class="form-control" style="margin:5px">
                                    <input type="text" class="form-control" style="margin:5px"> -->
                                
										<div style="display:flex; margin:0px 20px">
											<select class="form-control subsidary_prod" name="sub_prod[][]" id="sub_prod" style="margin:2px 10px">${subsidaryItem['sub_option']}</select>
											<input type="text" class="col-4 form-control" name="sub_consume[][]" value="${subsidaryItem['subsidary_consume']}" placeholder="Subsidary consumed" style="margin:2px 10px">
										</div>
									</div>`
									// var subsidaryRow = $('<div></div>').text(subsidaryItem.product_name + ' (' + subsidaryItem.subsidary_consume + ')');
									subsidaryDiv.append(subsidaryRow);
								});

								newRow.append($('<td></td>').html(subsidaryDiv));

								$('#consumption').append(newRow);
								count++;
							} else {
								var newRow = $('<div id="consumption" style="display: block;"></div>');
								// newRow = $('<tr style="display:flex"></tr>');
								// console.log(consumptionData);
								// console.log(data['consume']);
								let consume = $('.consume');
								let pattern = $('.pattern');
								let selwid = $('.selwid');

								if (consumptionData['consume'] != null)

									newRow = $(`
									<input class="form-control selwid" name="rate[]" placeholder="Enter Rate">
                                <select name="width[]" id="width1" class="form-control selwid"><option value="">Select Width</option><option value="11">44</option><option value="12">58</option><option value="13">70</option><option value="14">90</option><option value="15">108</option></select>
                                <select name="pattern[]" id="pattern1" class="form-control pattern">
                                    <option value="">Select Pattern</option>
                                                                                <option value="9">xyz</option>
                                                                    </select>

                                <input type="text" name="consume[]" id="consume1" class="form-control consume" placeholder="Consumed">
								
                                
                            `)

								// consume.first().val(key1[key]['consume']);
								// pattern.first().val(key1[key]['pattern']);
								// selwid.first().val(key1[key]['width_id']);
								var subsidaryDiv = '';
								subsidaryDiv = $('<div></div>').attr('id', 'subsidary' + count).css({
									'margin': '0px 10px',
									'border-radius': '10px',
									'display': 'unset'
								});
								// subsidaryDiv.empty();
								// $('#subsidary'+count).html('');
								// $('#subsidary2'+count).remove();

								key1['subsidary'].forEach(function(subsidaryItem) {
									// console.log();
									console.log(subsidaryItem);
									var subsidaryRow = `<div id="subsidary" style="margin:0px 10px; border-radius: 10px; display:unset">
								            <!-- <input type="text" class="form-control" style="margin:5px">
								            <input type="text" class="form-control" style="margin:5px"> -->

												<div style="display:flex; margin:0px 20px">
													<select class="form-control subsidary_prod" name="sub_prod[][]" id="sub_prod" style="margin:2px 10px">${subsidaryItem['sub_option']}</select>
													<input type="text" class="col-4 form-control" name="sub_consume[][]" value="${subsidaryItem['subsidary_consume']}" placeholder="Subsidary consumed" style="margin:2px 10px">
												</div>
											</div>`
									// var subsidaryRow = $('<div></div>').text(subsidaryItem.product_name + ' (' + subsidaryItem.subsidary_consume + ')');
									subsidaryDiv.append(subsidaryRow);
								});
								// console.log(subsidaryDiv.html());

								newRow.append($('<tr></tr>').append(subsidaryDiv));

								$('#consumption').append(newRow);
								$('.main_tr').append(`<td style="display: flex;"><button type="button" class="btn btn-primary btn-sm" onclick="addsubsidary(this);"><i class="fa fa-sitemap" aria-hidden="true"></i></button></td>`);
								count++;
							}

						});
						idx++;
					});

					$('.save_size').hide();
					$('.update_size').show();
					$('#sizelist').hide();
					$('#addsize').show();
					$('#save_size').attr('type', 'hidden');
					$('#sizeForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function addWorker() {
			var count = $('#jcount').val();
			var prev = "#job_charge" + count;
			count++;
			$('#jcount').val(count);
			var input = "<select name='worker[]' id='worker" + count + "' class='form-control f-enter selworker'></select>";
			input += "<input type='text' name='job_charge[]' id='job_charge" + count + "' class='form-control f-enter charge' placeholder='Job Charge'>";
			$(input).insertAfter(prev);
			getWorker('', '');
		}

		function addWidth(button) {
			var count = $('#count').val();
			// var prev = "#consume" + count;
			var prev = $(button).closest('tr');
			// console.log(prev.html());
			count++;

			// console.log(sub_count);
			// $(button).closest('tr').find('#sub_count').val(sub_count);
			var input = `<tr>
                        <th></th>
                        <td>
                            <div id="consumption">
							<input class="form-control selwid" name="rate[]" placeholder="Enter Rate">
                                <select name="width[]" id="width1" class="form-control selwid"></select>
                                <select name="pattern[]" id="pattern1" class="form-control pattern">
                                    <option value="">Select Pattern</option>
                                </select>

                                <input type="text" name="consume[]" id="consume1" class="form-control consume" placeholder="Consumed">
                            </div>

							<div id="subsidary" style="margin:0px 10px; border-radius: 10px; display:unset">
                                    
                            </div>


                            <input type="hidden" name="count" id="count" value="${count}" />
                        </td>
                        <td style="display: flex;"><button type="button" class="btn btn-primary btn-sm" onclick="addsubsidary(this);"><i class="fa fa-sitemap" aria-hidden="true"></i></button></td>
                    </tr>`;
			// var input = "<select name='width[]' id='width" + count + "' class='form-control f-enter selwid'></select>";
			// input += "<select name='pattern[] id='pattern" + count + "' class='form-control f-enter pattern'></select>";
			// input += "<input type='text' name='consume[]' id='consume" + count + "' class='form-control f-enter consume' placeholder='Consume'>";
			$(input).insertAfter(prev);
			// $(input).push(prev);
			getWidth('', '');
			getpattern('', '');
		}

		function addsubsidary(button) {
			// Find the closest <tr> to the button clicked
			var tr = $(button).closest('tr').find('#subsidary');


			var comsumtion = $(button).closest('tr').find('#width1').val();
			var pattern = $(button).closest('tr').find('#pattern1').val();

			// Create the new row content with the input field
			var input = `
			<div  style="display:flex; margin:0px 20px">
			<select class="form-control subsidary_prod" name="sub_prod[${comsumtion}][]" id="sub_prod" style="margin:2px 10px"></select>
			<input type='text' class="col-4 form-control" name="sub_consume[${pattern}][]" placeholder='Subsidary consumed' style="margin:2px 10px">
			</div>
			`;

			// Insert the new row right after the <div id='consumption'> in the same row
			$(tr).append(input);
			getsubsidaryproduct('', '')
		}


		function addMaterial() {
			var count = $('#count').val();
			var prev = "#used" + count;
			count++;
			$('#count').val(count);
			var input = "<select name='rawmaterial[]' id='rawmaterial" + count + "' class='form-control f-enter selraw'></select>";
			input += "<input type='text' name='used[]' id='used" + count + "' class='form-control f-enter used' placeholder='Units Consumed'>";
			$(input).insertAfter(prev);
			getRaw('', '');
		}

		function removeAll() {
			var input = "";
			for (count = 1; count <= 5; count++) {
				input += "<select name='rawmaterial[]' id='rawmaterial" + count + "' class='form-control f-enter selraw'></select>";
				input += "<input type='text' name='used[]' id='used" + count + "' class='form-control f-enter used' placeholder='Units Consumed'>";
			}
			$('#combination').html(input);
			$('#count').val('5');
			getRaw('', '');

			var jinput = "<select name='worker[]' id='worker1' class='form-control f-enter selworker'></select>";
			jinput += "<input type='text' name='job_charge[]' id='job_charge1' class='form-control f-enter charge' placeholder='Job Charge'>";
			$('#chargelist').html(jinput)
			$('#jcount').val('1');
			getWorker('', '');
		}


		function viewFinished(id) {
			$.ajax({
				type: "POST",
				url: "finisheddetails.php",
				data: {
					id: id
				},
				success: function(data) {
					$('#finisheddetails').html(data);
					$('#finishedlist').hide();
					$('#finisheddetails').show();
				}
			});
		}

		function editFinished(id) {
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					id: id,
					editFinished: 'editFinished'
				},
				dataType: "json",
				success: function(data) {
					//$('#json').html(JSON.stringify(data));
					$('#finished_product').val(data['finished_product']);
					$('#bedsheet_size').val(data['bedsheet_size']);
					$('#bedsheet_qty').val(data['bedsheet_qty']);
					$('#pillow_size').val(data['pillow_size']);
					$('#pillow_qty').val(data['pillow_qty']);
					$('#finishedid').val(data['id']);
					$('.save_finished').hide();
					$('.update_finished').show();
					$('#finishedlist').hide();
					$('#addfinished').show();
					$('#save_finished').attr('type', 'hidden');
					$('#finishedForm').attr('action', '../action/updateData.php');
				}
			});
		}

		function setValues(size, materials) {
			if (size != 0) {
				for (var i = 0; i < size; i++) {
					var pos = i + 1;
					var rawid = "#rawmaterial" + pos;
					if (materials[i]['rawmaterial'] != 0)
						$(rawid).val(materials[i]['rawmaterial']);
					var usedid = "#used" + pos;
					$(usedid).val(materials[i]['used']);
				}
			}
		}

		function setWidths(csize, width) {
			if (csize != 0) {
				for (var i = 0; i < csize; i++) {
					var pos = i + 1;
					var wid = "#width" + pos;
					if (width[i]['width_id'] != 0)
						$(wid).val(width[i]['width_id']);
					var consume = "#consume" + pos;
					$(consume).val(width[i]['consume']);
				}
			}
		}

		function setPatterns(csize, pattern) {
			// console.log(pattern);
			if (csize != 0) {
				for (var i = 0; i < csize; i++) {
					var pos = i + 1;
					var wid = "#pattern" + pos;
					if (width[i]['width_id'] != 0)
						$(wid).val(width[i]['width_id']);
					var consume = "#consume" + pos;
					$(consume).val(width[i]['consume']);
				}
			}
		}

		function setsubsidary(csize, pattern) {
			// console.log(pattern);
			if (csize != 0) {
				for (var i = 0; i < csize; i++) {
					var pos = i + 1;
					var wid = "#sub_prod" + pos;
					console.log(wid);
					if (width[i]['width_id'] != 0)
						$(wid).val(width[i]['width_id']);
					var consume = "#consume" + pos;
					$(consume).val(width[i]['consume']);
				}
			}
		}

		function setWorkers(size, workers) {
			if (size != 0) {
				for (var i = 0; i < size; i++) {
					var pos = i + 1;
					var wid = "#worker" + pos;
					if (workers[i]['worker'] != 0)
						$(wid).val(workers[i]['worker']);
					var jcid = "#job_charge" + pos;
					$(jcid).val(workers[i]['job_charge']);
				}
			}
		}

		$('body').on('change', '#state_id', function(e) {
			let state_id = $(this).val();
			// console.log(state_id);
			$.ajax({
				type: "POST",
				url: "../ajax_returns.php",
				data: {
					state_id: state_id,
					getCity: "getCity"
				},
				success: function(data) {
					// console.log($('#district_id'));
					$('#district_id').html(data);
				},
				error: function(xhr, status, error) {
					console.log(xhr.status);
					console.log(xhr.responseText);
				}
			});
		});
	</script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>