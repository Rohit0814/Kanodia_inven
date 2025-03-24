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
    	<title>Finished Product</title>
    </head>
    <body>
    	<?php include("../header.php"); ?>
		<div class="container">
        	<div class="row">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
                <div class="col-md-12">
                    <div id="formPanel" class="panel panel-info">
                        <div class="panel-heading">
                            <font size="+2">Finished Product</font>
                        </div>
                        <div class="panel-body">
						
                            <form action="../action/insertData.php" method="post" style="font-size:16px;">
							<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cuttinglist">Select Cutting Sheet<i class="fa fa-search"></i></button>
							<table class="table table-bordered table-condensed">
                                	<tr>
										<th width="20%">Date</th>
                                        <td width="30%"><input type="date" name="date" id="date" class="form-control to-enter" required value="<?php echo date('Y-m-d'); ?>"></td>
                                    	<th>Cutting ID</th>
                                        <td><input type="number" name="cutting_id"  id="cutting_id" class="form-control to-enter" placeholder="Enter Cutting ID" readonly>
											<input type="hidden" name="shop"  id="shop" value="<?php echo $shop; ?>">
										</td>
                                    </tr>

                                </table>
							<div id="html">
									
							</div>
							<div id="itemrow" style="display:none;">
							<div class="row" style="margin-top:5px;">
								<div class="col-md-4 mt-2">
									<input type="text" name="item[]" placeholder="Enter Item Name" class="form-control">
								</div>
								<div class="col-md-4 mt-2">
									<div class="row">
										<div class="col-md-6">
										<select name="bedsheet_size[]" class="form-control">
										<option>-- Bedsheet Size --</option>
										<?php $bsize=$obj->get_rows("`size`","*","`item`='Bedsheet' and `shop`='$shop'");
                                    if(is_array($bsize)){
                                        foreach($bsize as $b){ ?>
                                        <option value="<?php echo $b['size']; ?>"><?php echo $b['size']; ?></option>
                                    <?php } } ?>

									</select>
										</div>
										<div class="col-md-6">
											<input type="number" name="bedsheet_qty[]" placeholder="Bedsheet Qty" class="form-control">
											
										</div>
									</div>
									
								</div>
								<div class="col-md-4 mt-2">
									<div class="row">
										<div class="col-md-6">
										<select name="pillow_size[]" class="form-control">
										<option>-- Pillow Size --</option>
										<?php $psize=$obj->get_rows("`size`","*","`item`='Pillow' and `shop`=$shop");
                                    if(is_array($psize)){
                                        foreach($psize as $p){ ?>
                                        <option value="<?php echo $p['size']; ?>"><?php echo $p['size']; ?></option>
                                    <?php } } ?>

									</select>
										</div>
										<div class="col-md-6">
									<input type="number" name="pillow_qty[]" placeholder="Pillow Qty" class="form-control">
											
										</div>
									</div>
								</div>
							</div>
							</div>
							<div id="addeditemrow">
							</div>
							
							<div class="row" id="itembtn" style="display:none; margin-top:5px;">
								<div class="col-md-4" style="margin-top:5px;">
								<button type="button" id="addrow" class="btn btn-sm btn-primary">Add Row</button>

								<input type="submit" name="finish_stock" value="Add Finish Goods Stock" class="btn btn-sm btn-success">
								</div>
							</div>
							</form>
							</div>
                        </div><!-- form panel closed-->
                    </div><!-- end of col-md-12 -->
                </div><!-- end of Row -->
            </div><!-- end of container -->
    	
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
	function selectCutting(id){
		$('#cutting_id').val(id).trigger('blur');
	}
	$('#cutting_id').blur(function(){
		var cutting_id=$(this).val();
		$.ajax({
			type:"POST",
			url:"../ajax_returns.php",
			data:{cutting_id:cutting_id,getStitching:'getStitching'},
			// dataType:"json",
			success: function(data){
				$('#html').html(data);
				$('#itemrow').show();
				$('#itembtn').show();
			},
		});
	});
	$('#addrow').click(function(){
		var row=$('#itemrow').html();
		$('#addeditemrow').append(row);
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
                row+='<td align="center"><select name="worker'+count+'" id="worker'+count+'" class="form-control selworker  to-enter"></select></td>';
				row+='<td align="center"><select name="job'+count+'" id="job'+count+'" class="form-control job  to-enter"><option value="" disabled selected>-- Select Job --</option><option value="Bedsheet">Bedsheet</option><option value="Pillow">Pillow</option><option value="Zigzag">Zigzag</option></select></td>';
				row+='<td align="center"><input type="text" class="form-control qty to-enter" name="qty'+count+'" id="qty'+count+'" readonly></td>';
                row+='<td align="center"><input type="datetime-local" class="form-control start_time to-enter" id="start_time'+count+'" name="start_time'+count+'"></td>';
                row+='<td align="center"><input type="datetime-local" class="form-control finish_time to-enter" id="finish_time'+count+'" name="finish_time'+count+'"></td>';
				$(row).insertAfter(prev);
				$('#count').val(count);
				getWorker();
			}
			$('body').on('change','.job',function(){
				var job=$(this).val();
				var cutting_bedsheet_qty=Number($('#cutting_bedsheet_qty').val());
				var cutting_pillow_qty=Number($('#cutting_pillow_qty').val());
				// alert(cutting_bedsheet_qty);
				if(job=='Bedsheet'){
					$(this).closest('tr').find('.qty').val(cutting_bedsheet_qty);
					$('#totalbedsheet').val(cutting_bedsheet_qty);
				}else if(job=='Pillow'){
					$(this).closest('tr').find('.qty').val(cutting_pillow_qty);
					$('#totalpillow').val(cutting_pillow_qty);
				}
				else if(job=='Zigzag'){
					$(this).closest('tr').find('.qty').val(cutting_pillow_qty);
					//$('#totalpillow').val(pillowqty);
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