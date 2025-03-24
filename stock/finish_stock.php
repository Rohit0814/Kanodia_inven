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

  $array=$obj->get_rows("`finished_stock`","*","`shop`='$shop'","`date` DESC");
//   echo "<pre>";print_r($array);die;
   
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
        <!-- Custom CSS -->
        <link href="../css/style.css" rel="stylesheet">
        <!-- Bootstrap Core JavaScript -->
        <script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
		<style>
        .sexy_line { 
           margin-right:100px;
            height: 1px;
            background: black;
            background: -webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
        }
        </style>
	</head>

    <body>
<?php include "../header.php";?>
<div class="container">
  <div class="row">
            	<?php
                	if(isset($_SESSION['msg'])){echo "<h4 class='text-success text-center'>".$_SESSION['msg']."</h4>"; unset($_SESSION['msg']);}
                	if(isset($_SESSION['err'])){echo "<h4 class='text-danger text-center'>".$_SESSION['err']."</h4>"; unset($_SESSION['err']);}
				?>
    <div class="col-md-12">
      <span><h4 class="text-center text-primary">Stock Details</h4></span>
       <div class="sexy_line"></div><br />
       	<div class="table-responsive" id="stock_table">
<div class="container-fluid">
      <table class="table datatable">
            <thead>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Sl.No.</th>
                <th class="bg-danger" style="text-align:center; vertical-align:middle;">Image</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Item</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Raw Material</th>
                <th class="bg-primary" style="text-align:center; vertical-align:middle;">Design No.</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Bedsheet Size</th>
                <th class="bg-info" style="text-align:center; vertical-align:middle;">Bedsheet Qty.</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Pillow Size</th>
                <th style="background-color:#FFFAD3; text-align:center; vertical-align:middle;">Pillow Qty.</th>
                <th class="bg-secondary" style="text-align:center; vertical-align:middle;">Created ON</th>
            </thead>
            <tbody>
             <?php
				if(is_array($array)){
                	$i=0;foreach($array as $result){
						$i++;
            ?>
            <tr>
               	<td align="center"><?php echo $i;?></td>
               	<td align="center"><img src="../uploads/<?php echo $result['image'];?>" class="img-responsive" style="width:70px;"></td>
                   <td align="center"><?php echo $result['item'];?></td>
				<td align="center"><?php echo $result['raw_material'];?></td>
                <td align="center"><?php echo $result['d_no'];?></td>
				<td align="center"><?php echo $result['bedsheet_size'];?></td>
                <td align="center"><?php echo $result['bedsheet_qty'];?></td>
                <td align="center"><?php echo $result['pillow_size'];?></td>
                <td align="center"><?php echo $result['pillow_qty'];?></td>
                <td align="center"><?php echo $result['date'];?></td>
            </tr>
            
            <?php 
                	} } ?>
            </tbody>
</table>
</div>
</div>
    </div><!-- end of stock details -->
    </div><!-- end of row -->
</div><!-- end of container -->
<script>
$(document).ready( function () {
	$('.datatable').DataTable({ });
});   
	
</script>
    
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
</body>
</html>