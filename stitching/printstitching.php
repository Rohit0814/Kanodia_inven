<?php 
print_r("Hii"); die;
include('../action/config.php');
$obj=new database();
$id=$_GET['id'];
$table="`stitching`";
$columns="*";
$where="`id`=$id";
$stitching=$obj->get_details($table,$columns,$where);
$stitchingdetail=$obj->get_rows('stitching_detail','*',"`stitching_id`='$stitching[id]'");
// echo "<pre>";print_r($stitching);
// echo "<pre>";print_r($stitchingdetail);die;
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
			<h3 style="text-align:center;">Stitching Detail</h3>
			<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<th>Date</th>
					<td><?= $stitching['date']; ?></td>
					<th>Stitching ID</th>
					<td><?= $stitching['id']; ?></td>
				</tr>
			</table>

				<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
				<tr>
					<th>#</th>
					<th>Worker</th>
					<td>Job</td>
					<th>Qty.</th>
					<td>Start time</td>
					<td>End time</td>
				</tr>
				<?php if(!empty($stitchingdetail)){
				$i=0;foreach($stitchingdetail as $detail){ $i++; 
					$worker=$obj->get_details('`worker`','*',"`id`=$detail[worker]");
				?>
				<tr>
					<td><?= $i; ?></td>
					<td><?= $worker['name']; ?></td>
					<td><?= $detail['job']; ?></td>
					<td><?= $detail['qty']; ?></td>
					<td><?= $detail['start_time']; ?></td>
					<td><?= $detail['finish_time']; ?></td>
				</tr>
				<?php } } ?>
			</table>

			

			<table width="100%" border="1" cellpadding="5px" cellspacing="0px" style="text-align:center;">
			<tr>
					<th>Total Zigzag</th>
					<td><?= $stitching['totalzigzag']; ?></td>
					<th></th>
					<td></td>
					</tr>
				<tr>
					<tr>
					<th>Total Bedsheet</th>
					<td><?= $stitching['totalbedsheet']; ?></td>
					<th>Total Pillow</th>
					<td><?= $stitching['totalpillow']; ?></td>
					</tr>
				<tr>
					<th>Bedsheet Alteration</th>
					<td><?= $stitching['alteration']; ?></td>
					<th>Bedsheet Damage</th>
					<td><?= $stitching['damage']; ?></td>
				</tr>
					<tr>
					<th>Pillow Alteration</th>
					<td><?= $stitching['palteration']; ?></td>
					<th>Pillow Damage</th>
					<td><?= $stitching['pdamage']; ?></td>
					</tr>
					<tr>
					<th>Zigzag Alteration</th>
					<td><?= $stitching['zigzag_alteration']; ?></td>
					<th>Zigzag Damage</th>
					<td><?= $stitching['zigzag_damage']; ?></td>
					</tr>
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