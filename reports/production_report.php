<?php
session_start();
if (isset($_SESSION['user'])) {
	$role = $_SESSION['role'];
	$user = $_SESSION['user'];
	$shop = $_SESSION['shop'];
} else {
	header("Location:../index.php");
	echo "<script>location='../index.php'</script>";
}
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
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>

</head>

<body>
	<?php include "../header.php"; ?>
	<div class="container">
		<center>
			<font size="+2">Production Report</font>
		</center>
		<hr />
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
		<hr />
				<div class="row">
					<div class="col-md-3"><input type="date" id="from" class="form-control" value="<?php if (isset($_GET['from'])) {
																										echo $_GET['from'];
																									} ?>" /></div>
					<div class="col-md-3"><input type="date" id="to" class="form-control" value="<?php if (isset($_GET['to'])) {
																										echo $_GET['to'];
																									} ?>" /></div>
					<div class="col-md-3"><button type="button" class="btn btn-success" onclick="getDates();">Search <i class="fa fa-search"></i></button></div>

					<div class="col-md-12">
						<br>
						<div id="query_result" class="table-responsive col-md-12">
							<?php include "productlist.php"; ?>
						</div>
					</div>
				</div>
			</div>
			</div><!-- end of row -->
		</div><!-- end of container -->
		<script language="javascript">
			function getDates() {
				var from = $('#from').val();
				var to = $('#to').val();
				var shop = '<?php echo $shop; ?>';
				//alert(query);
				$.ajax({
					type: "GET",
					url: "productlist.php",
					data: {
						from: from,
						to: to,
						shop: shop
					},
					success: function(data) {
						//alert(data);
						$('#query_result').html(data);
					}
				});
			}
			$(document).ready( function () {
	$('.datatable').DataTable( { } );
} ); 
		</script>

		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		</br> </br>
</body>

</html>