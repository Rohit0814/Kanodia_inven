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
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
          <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.1/b-2.0.0/b-html5-2.0.0/b-print-2.0.0/date-1.1.1/r-2.2.9/sb-1.2.1/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>
          <script type="text/javascript" src="https://cdn.datatables.net/searchbuilder/1.2.1/js/dataTables.searchBuilder.min.js"></script>
          <script type="text/javascript" src="https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js"></script>
</head>

<body>
	<?php include "../header.php"; ?>
	<div class="container-fluid">
		<center>
			<font size="+2">Payment Report</font>
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
			<div class="col-md-6">
			<center>
			<font size="+1">Pcs Wise Payment Report</font>
		</center>
		<hr />
				<div class="row">
				<div class="col-md-3"><input type="text" id="pcsworker" placeholder="Enter Worker" class="form-control" value="<?php if (isset($_GET['pcsworker'])) {
																										echo $_GET['pcsworker'];
																									} ?>" /></div>
					<div class="col-md-3">
						<select id="pcsstatus" class="form-control">
							<option value="" disabled>Select Status</option>
							<option value="" selected>All</option>
							<option value="1" <?php if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='1'){ echo "selected";} ?>>Paid</option>
							<option value="0" <?php if(isset($_GET['pcsstatus']) && $_GET['pcsstatus']=='0'){ echo "selected";} ?>>Unpaid</option>
						</select>
					</div>
					<div class="col-md-3"><input type="date" id="from" class="form-control" value="<?php if (isset($_GET['from'])) {
																										echo $_GET['from'];
																									} ?>" /></div>
					<div class="col-md-3"><input type="date" id="to" class="form-control" value="<?php if (isset($_GET['to'])) {
																										echo $_GET['to'];
																									} ?>" /></div>
					<div class="col-md-3"><button type="button" class="btn btn-success" id="getpcsDates">Search <i class="fa fa-search"></i></button></div>

					<div class="col-md-12">
						<br>
						<div id="query_result" class="table-responsive col-md-12">
							<?php include "pcspaymentlist.php"; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
			<center>
			<font size="+1">Day Wise Payment Report</font>
		</center>
		<hr />

			<div class="row">
			<div class="col-md-3"><input type="text" id="dayworker" placeholder="Enter Worker" class="form-control" value="<?php if (isset($_GET['dayworker'])) {
																										echo $_GET['dayworker'];
																									} ?>" /></div>
										<div class="col-md-3">
						<select id="daystatus" class="form-control">
							<option value="" disabled>Select Status</option>
							<option value="" selected>All</option>
							<option value="1" <?php if(isset($_GET['daystatus']) && $_GET['daystatus']=='1'){ echo "selected";} ?>>Paid</option>
							<option value="0" <?php if(isset($_GET['daystatus']) && $_GET['daystatus']=='0'){ echo "selected";} ?>>Unpaid</option>
						</select>
					</div>																				
					<div class="col-md-3"><input type="date" id="from1" class="form-control" value="<?php if (isset($_GET['from1'])) {
																										echo $_GET['from1'];
																									} ?>" /></div>
					<div class="col-md-3"><input type="date" id="to1" class="form-control" value="<?php if (isset($_GET['to1'])) {
																										echo $_GET['to1'];
																									} ?>" /></div>
					<div class="col-md-3"><button type="button" class="btn btn-success" id="getdayDates">Search <i class="fa fa-search"></i></button></div>

					<div class="col-md-12">
						<br>
						<div id="query_result1" class="table-responsive col-md-12">
							<?php include "daypaymentlist.php"; ?>
						</div>
					</div>
				</div>
				</div>
			</div><!-- end of row -->
		</div><!-- end of container -->
		<script language="javascript">
			$(document).ready( function () {
				$('body').on('click','#getpcsDates',function(){
				var from = $('#from').val();
				var to = $('#to').val();
				var pcsworker = $('#pcsworker').val();
				var pcsstatus = $('#pcsstatus').val();
				var shop = '<?php echo $shop; ?>';
				//alert(query);
				$.ajax({
					type: "GET",
					url: "pcspaymentlist.php",
					data: {
						from: from,
						to: to,
						pcsworker:pcsworker,
						pcsstatus:pcsstatus,
						shop: shop
					},
					success: function(data) {
						//alert(data);
						$('#query_result').html(data);
						var table = $('.datatable').DataTable({
						searchBuilder: true,
						responsive:true,
						dom: 'lBfrtip',
						buttons: [
							{ extend: 'pdf', footer: true },
            { extend: 'print', footer: true }
						]
					});
					}
				});
			});
			$('body').on('click','#getdayDates',function(){
				var from = $('#from1').val();
				var to = $('#to1').val();
				var dayworker = $('#dayworker').val();
				var daystatus = $('#daystatus').val();
				var shop = '<?php echo $shop; ?>';
				//alert(query);
				$.ajax({
					type: "GET",
					url: "daypaymentlist.php",
					data: {
						from: from,
						to: to,
						dayworker: dayworker,
						daystatus:daystatus,
						shop: shop
					},
					success: function(data) {
						//alert(data);
						$('#query_result1').html(data);
						var table = $('.datatable1').DataTable({
                    searchBuilder: true,
                    responsive:true,
                    dom: 'lBfrtip',
                    buttons: [
						{ extend: 'pdf', footer: true },
            { extend: 'print', footer: true }
                    ]
                });

					}
				});
			});
                var table = $('.datatable').DataTable({
                    searchBuilder: true,
                    responsive:true,
                    dom: 'lBfrtip',
                    buttons: [
            { extend: 'pdf', footer: true },
            { extend: 'print', footer: true }

			
                    ]
                });
				var table = $('.datatable1').DataTable({
                    searchBuilder: true,
                    responsive:true,
                    dom: 'lBfrtip',
                    buttons: [
						{ extend: 'pdf', footer: true },
            	{ extend: 'print', footer: true }
                    ]
                });

				$('body').on('click','.cuttingcheckbox',function(){
					 var amount=0;
					 var cuttingtotal=0;
					$(".cuttingcheckbox:checked").each(function(){
   						  amount=parseFloat($(this).data('total'));
						 cuttingtotal+=amount;
					});
					$('#paycutting').show();
					$('#cutting_total').html('Total Amount : '+cuttingtotal);

				}); 
				$('body').on('click','.stitchingcheckbox',function(){
					 var amount=0;
					 var stitchingtotal=0;
					$(".stitchingcheckbox:checked").each(function(){
   						  amount=parseFloat($(this).data('total'));
						  stitchingtotal+=amount;
					});
					$('#paystitching').show();
					$('#stitching_total').html('Total Amount : '+stitchingtotal);

				}); 
				$('body').on('click','.zigzagcheckbox',function(){
					 var amount=0;
					 var zigzagtotal=0;
					$(".zigzagcheckbox:checked").each(function(){
   						  amount=parseFloat($(this).data('total'));
						  zigzagtotal+=amount;
					});
					$('#payzigzag').show();
					$('#zigzag_total').html('Total Amount : '+zigzagtotal);

				}); 
				$('body').on('click','.attendancecheckbox',function(){
					 var amount=0;
					 var attendancetotal=0;
					$(".attendancecheckbox:checked").each(function(){
   						  amount=parseFloat($(this).data('total'));
						  attendancetotal+=amount;
					});
					$('#payattendance').show();
					$('#attendance_total').html('Total Amount : '+attendancetotal);

				}); 
			}); 
		</script>

		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		</br> </br>
</body>

</html>