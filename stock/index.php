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
$raw_material = $obj->get_rows("`raw_material`", "`id`,`name`,`type`", "`shop`='$shop'", 'name asc');
$width = $obj->get_rows("`width`", "`id`,`width`", "`shop`='$shop'", 'width asc');

?>
<!doctype html>
<html>

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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- Custom CSS -->
	<link href="../css/style.css" rel="stylesheet">
	<!-- Bootstrap Core JavaScript -->
	<script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
	<style>
		.sexy_line {
			margin-right: 100px;
			height: 1px;
			background: black;
			background: -webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
		}
	</style>
</head>

<body>
	<?php include "../header.php"; ?>
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
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">

						<form action="../action/insertData.php" method="post" enctype="multipart/form-data" onsubmit="return validate()">

							<fieldset style="padding-left:6px; border-radius:5px;">
								<h4 class="text-center text-info" style="padding-right:50px;">Add Stock</h4>
								<div class="sexy_line"></div><br />
								<table class="table table-bordered">
									<td>Material Type</td>
									<td><select name="material_type" id="material_type" class="form-control" required="required">
											<option value="">Select</option>
											<option value="subsidiary">Subsidary Material</option>
											<option value="stitching">Stitching Material</option>
											<option value="loose">Loose Material</option>
										</select></td>
								</table>
								<table class="table table-bordered subsidiary" style="display: none;">
									<tr>
										<td><b>Date:</b></td>
										<td><input type="date" name="sub_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></td>
									</tr>
									<tr>
										<td><b>Item:</b></td>
										<td>
											<select name="sub_raw_id" id="raw_id" class="form-control">
												<option value="">Select</option>
												<?php foreach ($raw_material as $raw) {
													if ($raw['type'] == 'subsidiary') {
												?>
														<option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
												<?php }
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<td><b>Quantity:</b></td>
										<td>
											<input type="text" name="sub_quantity" id="quantity" value="1" class="form-control">
										</td>
									</tr>
									<tr>
										<td><b>Remark:</b></td>
										<td>
											<!-- <input type="text" name="quantity" id="quantity" value="1" class="form-control" required="required"> -->
											<textarea name="sub_remark" id="remark" class="form-control"></textarea>
										</td>
										</td>
									</tr>
									<tr align="center">
										<td colspan="2">
											<input type="hidden" name="shop" value="<?php echo $shop; ?>">
											<input type="submit" name="save_loose_stock" value="Add" class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
										</td>
									</tr>
								</table>

								<table class="table table-bordered stitching" style="display: none;">
									<tr>
										<td><b>Date:</b></td>
										<td><input type="date" name="sti_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></td>
									</tr>
									<tr>
										<td><b>Item:</b></td>
										<td>
											<select name="sti_raw_id" id="raw_id" class="form-control">
												<option value="">Select</option>
												<?php foreach ($raw_material as $raw) {
													if ($raw['type'] == 'stitching') {
												?>
														<option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
												<?php }
												} ?>
											</select>
										</td>
									</tr>
									<tr>
										<td><b>Quantity:</b></td>
										<td>
											<input type="text" name="sti_quantity" id="quantity" value="1" class="form-control">
										</td>
									</tr>
									<tr>
										<td><b>Remark:</b></td>
										<td>
											<!-- <input type="text" name="quantity" id="quantity" value="1" class="form-control" required="required"> -->
											<textarea name="sti_remark" id="remark" class="form-control"></textarea>
										</td>
										</td>
									</tr>
									<tr align="center">
										<td colspan="2">
											<input type="hidden" name="shop" value="<?php echo $shop; ?>">
											<input type="submit" name="save_loose_stock" value="Add" class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
										</td>
									</tr>
								</table>
								<table class="table table-bordered loose" style="display:none">
									<tr>
										<td><b>Date:</b></td>
										<td><input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /></td>
									</tr>
									<tr>
										<td><b>Item:</b></td>
										<td>
											<select name="raw_id" id="raw_id" class="form-control">
												<option value="">Select</option>
												<?php foreach ($raw_material as $raw) { 
													if($raw['type']=='fabric'){
													?>
													<option value="<?php echo $raw['id']; ?>"><?php echo $raw['name']; ?></option>
												<?php } } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td><b>Width:</b></td>
										<td>
											<select name="width_id" id="width_id" class="form-control">
												<option value="">Select</option>
												<?php foreach ($width as $value) { ?>
													<option value="<?php echo $value['id']; ?>"><?php echo $value['width'] . " Inch"; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td><b>D No.:</b></td>
										<td>
											<input type="text" name="d_no" id="d_no" class="form-control">
										</td>
									</tr>
									<!-- <tr>
										<td><b>Meter</b></td>
										<td>
											<input type="number" name="meter" id="meter" class="form-control">
										</td>
									</tr> -->
									<tr>
										<td><b>Quantity:</b></td>
										<td>
											<input type="text" name="quantity" id="quantity" value="1" class="form-control">
										</td>
									</tr>
									<tr>
										<td><b>Image :</b></td>
										<td>
											<input type="file" name="image" id="image" class="form-control-file">

										</td>
									</tr>
									<tr align="center">
										<td colspan="2">
											<input type="hidden" name="shop" value="<?php echo $shop; ?>">
											<input type="submit" name="save_loose_stock" value="Add" class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
										</td>
									</tr>
								</table>
							</fieldset>
						</form>
					</div><!-- end of column for form -->
				</div><!-- end of row for form -->
			</div><!-- end of row for main forms -->
			<div class="col-md-8">
				<span style="display: flex; justify-content:center; align-items:center">
					<h4 class="text-center text-primary">Stock Details</h4> &nbsp;&nbsp; <a href="" title="print" class="print_stock"><i class="btn btn-warning btn-xs fa-solid fa-print"></i></a>
				</span>
				<div class="sexy_line"></div><br />
				<div class="table-responsive" id="stock_table">
					<?php include('stock_table.php'); ?>
				</div>
			</div><!-- end of stock details -->
		</div><!-- end of row -->
	</div><!-- end of container -->
	<script>
		function confirmDel() {
			if (confirm("Are you sure you want to Delete this?")) {
				return true;
			} else {
				return false;
			}
		}

		$(function() {
			$('#query').keyup(function() {
				var shop = '<?php echo $shop; ?>';
				$.ajax({
					type: 'GET',
					url: 'stock_table.php',
					data: {
						query: $(this).val(),
						shop: shop
					},
					success: function(data) //on recieve of reply
					{
						$('#stock_table').html(data)
					}
				});
			});
		});

		function getCompany(str) {
			var category = str;
			var shop = '<?php echo $shop; ?>';
			var models = "<option value=''>Select Model</option>";
			var comp = "<option value=''>Select Company</option>";
			$('#slno').val('');
			$('#hsn').val('');
			$('#quantity').val('');
			$('#purchase').val('');
			$('#base_price').val('');
			$('#model').html(models);
			if (category != '') {
				$.ajax({
					type: 'POST',
					url: "../ajax_returns.php",
					data: {
						shop: shop,
						category: category,
						get_company: 'get_company',
						page: 'stock'
					},
					success: function(data) {
						$('#company_id').html(data);
					}
				});
			} else {
				$('#company_id').html(comp);
			}
		}

		function getModel(str) {
			var company_id = str;
			var shop = '<?php echo $shop; ?>';
			var category = $('#category').val();
			$('#slno').val('');
			$('#hsn').val('');
			$('#quantity').val('');
			$('#purchase').val('');
			$('#base_price').val('');
			if (category != '') {
				$.ajax({
					type: 'POST',
					url: "../ajax_returns.php",
					data: {
						company_id: company_id,
						shop: shop,
						category: category,
						get_model: 'get_model',
						page: 'stock'
					},
					success: function(data) {
						$('#model').html(data);
					}
				});
			}
		}

		function selectModel(str) {
			$('#slno').val('');
			$('#hsn').val('');
			$('#quantity').val('');
			$('#purchase').val('');
			$('#base_price').val('');
		}

		function validate() {
			if (confirm("Click Ok to Submit. \n Click Cancel to Edit.")) {
				return true;
			} else {
				return false;
			}
		}
		$(document).ready(function() {
			$('.datatable').DataTable({});


			$('#material_type').change(function(){
				$('.subsidiary').css('display','none');
				$('.stitching').css('display','none');
				$('.loose').css('display','none');
				let material_type = $(this).val();
				if(material_type=='subsidiary'){
					$('.subsidiary').css('display','table');
				}
				if(material_type=='stitching'){
                    $('.stitching').css('display','table');
                }
				if(material_type=='loose'){
                    $('.loose').css('display','table');
                }
			});

			document.querySelectorAll('.print_stock').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior

        // Get the parent row
        const row = document.getElementById('printable-row2');
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
                    <th class="bg-info" style="vertical-align:middle;">Meter</th>
                    <th class="bg-info" style="vertical-align:middle;">Barcode</th>
                    <th style="background-color:#FFFAD3; vertical-align:middle;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>${row.innerHTML}</tr>
            </tbody>
        </table>`); // Print only the content of the row
        printWindow.document.write('</body></html>');
        
        // printWindow.document.close(); // Close the document
        printWindow.print(); // Trigger print
        // printWindow.close(); // Close the print window after printing
    });
});
		});
	</script>

	<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>