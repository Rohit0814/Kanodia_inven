<?php
session_start();
  	if(isset($_SESSION['user'])){
		$user=$_SESSION['user'];
		$role=$_SESSION['role'];
		$shop=$_SESSION['shop'];
	}
	else{
   		header("Location:index.php");
   		echo "<script>location='index.php'</script>"; 
	}
  	include_once "../action/config.php";
  	$obj=new database();   
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
		<style>
        	.form-control{
				height:30px;
			}
			.start_time{
				position:relative;
				float:left;
				width:100%;
				height:25px !important;
				margin:2px 0;
			}
			.finish_time{
				position:relative;
				float:left;
				width:100%;
				height:25px !important;
				margin:2px 0;
			}
			</style>
    	<title>Zigzag Recoard</title>
    </head>
    <body>
    	<?php include("../header.php"); ?>
    	<div class="container">
        	<div class="row">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']); }
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']); }
				?>
                <div class="col-md-12">
                    <div id="formPanel" class="panel panel-info">
                        <div class="panel-heading">
                            <font size="+2">Add Zigzag Recoard</font>
                        </div>
                        <div class="panel-body">
                            <form action="../action/insertData.php" method="post" style="font-size:16px;">
                            <div class="table-responsive">
							<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cuttinglist">Select Stitching Sheet <i class="fa fa-search"></i></button>
							<table class="table table-bordered table-condensed">
                                	<tr>
										<th width="20%">Date</th>
                                        <td width="30%"><input type="date" name="date" id="date" class="form-control to-enter" required value="<?php echo date('Y-m-d'); ?>"></td>
                                    	<th>Stitching ID</th>
                                        <td><input type="text" name="stitching_id"  id="stitching_id" class="form-control to-enter" placeholder="Enter Stitching ID" readonly></td>
                                    </tr>
                                	<tr>
										<th width="20%">Pillow Qty.</th>
                                        <td><input type="text"  id="cutting_pillow_qty" class="form-control to-enter"  value="" readonly></td>
                                    </tr>
                                </table>
								</div>
                                <div class="table-responsive">
								<table class="table table-bordered table-condensed hidden" border="1" id="table2" width="100%">
									<tr>
                                        <th style="text-align:center;" width="15%">#</th>
                                        <th style="text-align:center;" width="15%">Worker</th>
                                        <th style="text-align:center;" width="15%">Job</th>
                                        <th style="text-align:center;" width="15%">Quantity</th>
                                        <th style="text-align:center;" width="20%">Start Time</th>
                                        <th style="text-align:center;" width="20%">Finish Time</th>
                                    </tr>
                                        <?php for($i=1;$i<2;$i++){ ?>

                                        <tr class="rows" id="row<?php echo $i; ?>">
                                            <td align="center"><?php echo $i; ?></td>
                                            <td align="center"><select name="worker<?php echo $i; ?>" id="worker<?php echo $i; ?>" class="form-control selworker  to-enter"></select></td>
                                            <td align="center">
												<select name="job<?php echo $i; ?>" id="job<?php echo $i; ?>" class="form-control job  to-enter">
													<option value="" disabled selected>-- Select Job --</option>
													<option value="Zigzag">Zigzag</option>
												</select>
											</td>
                                            <td align="center"><input type="text" class="form-control qty to-enter" name="qty<?php echo $i ?>" id="qty<?php echo $i; ?>" placeholder="Qty."></td>
                                            <td align="center"><input type="datetime-local" class="form-control start_time to-enter" id="start_time<?php echo $i; ?>" name="start_time<?php echo $i; ?>"></td>
                                            <td align="center"><input type="datetime-local" class="form-control finish_time to-enter" id="finish_time<?php echo $i; ?>" name="finish_time<?php echo $i; ?>"></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="6"><button type="button" class="btn btn-primary btn-sm" onClick="addWorker();">Add Worker</button></td>
                                        </tr>
                                </table>
								</div>
								<div class="row field hidden" style="margin-bottom: 10px;">
									<div class="col-md-4">
										<label>Short</label>
										<input type="number" class="form-control to-enter" value="0" name="alteration" id="alteration">
									</div>
									<div class="col-md-4">
									<label>Excess</label>
										<input type="number"class="form-control to-enter" value="0" name="damage" id="damage">
									</div>
									<div class="col-md-4">
										<label>Total Working Hrs.</label>
										<input type="text" class="form-control to-enter" name="working_hrs" id="working_hrs" value="0" readonly>
									</div>
								</div>
								<div class="row field hidden" style="margin-bottom: 10px;">
									<div class="col-md-4">
									<label>Total Zigzag</label>
										<input type="number" step="any" class="form-control to-enter" name="totalzigzag" value="0" id="totalzigzag" readonly>
									</div>
								</div>
                                </div>
								<div class="row" style="margin-bottom:10px;">
									<div class="col-md-12 text-center">
									<input type="hidden" id="count" name="count" value="<?php echo $i-1; ?>">
								<input type="hidden" name="shop" value="<?php echo $shop; ?>">
								<input type="hidden" name="user" value="<?php echo $user; ?>">
                                <input type="submit" class="btn btn-sm btn-success mb-2 hidden" name="save_zigzag" id="save_zigzag" value="Save">
									</div>
								</div>
							</form>
                        </div>
                    </div><!-- form panel closed-->
                </div><!-- end of col-md-12 -->
            </div>
        </div>
		<div class="modal fade" id="cuttinglist" role="dialog">
    		<div class="modal-dialog modal-lg">
            	<!-- Modal content-->
      			<div class="modal-content">
        			<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                    <h4 class="modal-title">Stitching List</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="row">
                        	<div class="col-md-12 table-responsive">
                    			<?php include('stitchinglist.php'); ?>
                        	</div>
						</div>
                    <div class="modal-footer">
                    	<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	getWorker();
	function selectStitching(id){
		$('#stitching_id').val(id).trigger('blur');
	}
	$('#stitching_id').blur(function(){
		var stitching_id=$(this).val();
		$.ajax({
			type:"POST",
			url:"../ajax_returns.php",
			data:{stitching_id:stitching_id,page:'zigzag',getStitching:'getStitching'},
			dataType:"json",
			success: function(data){
				console.log(data);
				if(data!=false){
					$('#cutting_bedsheet_qty').val(data['totalbedsheet']);
					$('#cutting_pillow_qty').val(data['totalpillow']);
					$('#table2').removeClass('hidden');
					$('.field').removeClass('hidden');
					$('#save_zigzag').removeClass('hidden');
				}
			},
		});
	});
	function getWorker(){
		var shop="<?php echo $shop; ?>";
				$.ajax({
					type:"POST",
					url:"../ajax_returns.php",
					data:{shop:shop,getWorker:'getWorker',page:'stitching'},
					success: function(data){
						$.each($(".selworker"), function(){            
							if($(this).html()==''){
								$(this).html(data);
							}	
							if($(this).val()==''){
								$(this).html(data);
							}		
						});
					}
				});
			}
	function addWorker(){
				var count=$('#count').val();
				var prev='#row'+count;
				count++;
				var row='<tr class="rows" id="row'+count+'">';
				row+='<td align="center">'+count+'</td>';
                row+='<td align="center"><select name="worker'+count+'" id="worker'+count+'" class="form-control selworker  to-enter" required></select></td>';
				row+='<td align="center"><select name="job'+count+'" id="job'+count+'" class="form-control job  to-enter"><option value="" disabled selected>-- Select Job --</option><option value="Bedsheet">Bedsheet</option><option value="Pillow">Pillow</option><!--<option value="Zigzag">Zigzag</option>--></select></td>';
				row+='<td align="center"><input type="text" class="form-control qty to-enter" name="qty'+count+'" id="qty'+count+'" placeholder="Qty." required></td>';
                row+='<td align="center"><input type="datetime-local" class="form-control start_time to-enter" id="start_time'+count+'" name="start_time'+count+'" required></td>';
                row+='<td align="center"><input type="datetime-local" class="form-control finish_time to-enter" id="finish_time'+count+'" name="finish_time'+count+'" required></td>';
				$(row).insertAfter(prev);
				$('#count').val(count);
				getWorker();
			}
			$('body').on('keyup','.qty',function(){
				var count=Number($('#count').val());
				var totalzigzag=0;
				for(j=1;j<=count;j++){
					qty=parseFloat($('#qty'+j).val());
					job=$('#job'+j).val();
					if(job=='Zigzag'){
						if(isNaN(qty)){qty=0;}
						totalzigzag+=qty;
					}
				}
				$('#totalzigzag').val(totalzigzag);
				var result=parseFloat($('#cutting_pillow_qty').val()-totalzigzag);
				if(result < 0){
					$('#alteration').val('0');
					$('#damage').val(result);

				}else if(result > 0){
					$('#alteration').val(result);
					$('#damage').val('0');
				}else{
					$('#alteration').val('0');
					$('#damage').val('0');
				}
			});
			$('body').on('change','.finish_time',function(){
				var finish_time=$(this).val();
				var start_time=$(this).closest('tr').find('.start_time').val();
				var working_hrs=Number($('#working_hrs').val());
				var diff =  Math.abs(new Date(finish_time) - new Date(start_time));
				var seconds = Math.floor(diff/1000); //ignore any left over units smaller than a second
				var minutes = Math.floor(seconds/60); 
				seconds = seconds % 60;
				var hours = Math.floor(minutes/60);
				$('#working_hrs').val(working_hrs+hours);
			});
		</script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
    </body>
</html