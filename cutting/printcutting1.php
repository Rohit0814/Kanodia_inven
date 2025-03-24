<?php 
include('../action/config.php');
$obj=new database();
$id=$_GET['id'];
$table="`sm_order`";
$columns="*";
$where="`id`=$id";
$cutting=$obj->get_details($table,$columns,$where);
// $worker=$obj->get_details('`worker`','*',"`id`=$cutting[worker]");

$process = $_GET['pagename'];
$job_process = $obj->get_rows("`job_process`", "*", "`slug`='$process'");
// print_r($job_process); die;

// $cuttingitem=$obj->get_rows('`cuttingitem`','*',"`cutting_id`=$cutting[id]");
// $cuttingitem=$obj->get_rows('`cuttingitem` t1,`stock` t2,`raw_material` t3,`width` t4','t1.*,t2.raw_id,t2.d_no,t2.meter,t3.name as raw_material,t4.width',"t1.`cutting_id`=$cutting[id] and t1.stock_id=t2.id and t2.raw_id=t3.id and t2.width_id=t4.id");

// echo "<pre>";print_r($cuttingitem);
// echo "<pre>";print_r($worker);die;
?>
<html>
	<head>
		<title>Print Cutting Sheet</title>
		<style type="text/css" media="print">
			body{
				margin:0px;
				padding:0px;
			}
			@page {
					margin:0;
					/*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
					/*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
			}
			@media print{
				table {page-break-inside: avoid;}
				#buttons{
						display:none;
				}
				#page{
					margin:10px 20px;
  				}
			}
		</style>
	</head>
	<body>
		<section id="page">
			<h3 style="text-align:center;"><?php echo $job_process[0]['process'].' Details'; ?></h3>
			<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<th>Date</th>
					<td><?= $cutting['date']; ?></td>
					<th>Order ID</th>
					<td><?= $cutting['order_id']; ?></td>
				</tr>
			</table>
			<?php if(!empty($cuttingitem)){
				$i=0;foreach($cuttingitem as $item){ $i++; ?>
				<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<th>#</th>
					<th>Raw Material</th>
					<td>Design No.</td>
					<th>Width</th>
					<td>Meter</td>
				</tr>
				<tr>
					<td><?= $i; ?></td>
					<td><?= $item['raw_material']; ?></td>
					<td><?= $item['d_no']; ?></td>
					<td><?= $item['width']; ?></td>
					<td><?= $item['meter']; ?></td>
				</tr>
				<tr>
					<td colspan="5">
				<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<th>Meter Breakup</th>
					<th>Remark</th>
					<th>Bedsheet Size</th>
					<th>Qty</th>
					<th>Pillow Size</th>
					<th>Qty</th>
					<th>Total Consume</th>
				</tr>
				<?php $cuttingdetail=$obj->get_rows('`cuttingdetail`','*',"`cuttingitem_id`=$item[id]"); 
				if(!empty($cuttingdetail)){
					foreach($cuttingdetail as $detail){ 
						?>
					<tr>
						<td><?= $detail['meterbreakup']; ?></td>
						<td>
							<?php $remark=json_decode($detail['remark']); 
							if(!empty($remark)){
								foreach($remark as $r){
							?>
							<p><?= $r; ?></p>
							<?php } } ?>
						</td>
						<td>
						<?php $bedsheetsize_id=json_decode($detail['bedsheetsizeid']);
							if(!empty($bedsheetsize_id)){
								foreach($bedsheetsize_id as $bsid){
						$size=$obj->get_details('`size`','*',"`id`=$bsid");
							?>
							<p><?= $size['size']; ?></p>
							<?php } } ?>
						</td>
						<td>
						<?php $bedsheetpcs=json_decode($detail['bedsheetpcs']);
							if(!empty($bedsheetpcs)){
								foreach($bedsheetpcs as $bkey=>$bsp){
									if(!empty($bedsheetsize_id[$bkey])){
							?>
							<p><?= $bsp; ?></p>
							<?php } } } ?>
						</td>
						<td>
						<?php $pillowsize_id=json_decode($detail['pillowsizeid']);
							if(!empty($pillowsize_id)){
								foreach($pillowsize_id as $psid){
						$size=$obj->get_details('`size`','*',"`id`=$psid");

							?>
							<p><?= $size['size']; ?></p>
							<?php  } } ?>
						</td>
						<td>
						<?php $pillowpcs=json_decode($detail['pillowpcs']);
							if(!empty($pillowpcs)){
								foreach($pillowpcs as $pkey=>$psp){
									if(!empty($pillowsize_id[$pkey])){
							?>
							<p><?= $psp; ?></p>
							<?php } } } ?>
						</td>
						<td>
						<?php $consume=json_decode($detail['consume']);
							if(!empty($consume)){
								foreach($consume as $c){
							?>
							<p><?= $c; ?></p>
							<?php } } ?>
						</td>
					</tr>
				<?php } }
				?>

				</table>
				</td>
				</tr>
				<tr>
					<th></th>
					<th>Total Meter</th>
					<th>Total Bedsheet</th>
					<th>Total Pillow</th>
					<th>Total Consumption</th>
				</tr>
				<tr>
					<th></th>
					<td><?= $item['totalmeter']; ?></td>
					<td><?= $item['totalbedsheet']; ?></td>
					<td><?= $item['totalpillow']; ?></td>
					<td><?= $item['totalconsume']; ?></td>
				</tr>
			</table>

				<?php } } ?>
<br>
			<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<!-- <th>Total consumption</th>
					<td><?= $cutting['total_consumption']; ?></td>
					<th>Wastage</th>
					<td><?= $cutting['wastage']; ?></td>
					<th>Excess</th>
					<td><?= $cutting['excess']; ?></td> -->
					<th>Total consumption</th>
					<td><?= $cutting['total_consumption']; ?></td>
					<th>Cutting Id</th>
					<?php $cutting_id = json_decode($cutting['sqn_id']); ?>
					<td><?= $cutting_id[0]; ?></td>
					<!-- <th></th>
					<td></td> -->
				</tr>
				<!-- <tr>
					<th>Total Bedsheet</th>
					<td><?= $cutting['finalbedsheet']; ?></td>
					<th>Total Pilow</th>
					<td><?= $cutting['finalpillow']; ?></td>
					<th>Cutter Name</th>
					<td><?= $worker['name']; ?></td>

				</tr> -->
			</table>
<br>
			<table border="1" cellpadding="5" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Consumption</th>
            <th>Pattern</th>
            <th>Width</th>
            <th>Worker Name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            
        </tr>
        <!-- Add more rows as needed, or loop through products to display multiple rows -->
    </tbody>
</table>
		</section>
		<div id="buttons" style="width:100%; margin-top:10px;">
             	<center>
                  	<button type="button" class="btn btn-danger" onclick="window.print();" 
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
                 	<button type="button" onclick="closeThis();" class="btn btn-default"
                    	style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button>
             	</center>
         	</div>
	</body>
	<script language="javascript">
        	function closeThis(){
		
			
					window.location="../cutting/";	
				
			}
        </script>
</html>