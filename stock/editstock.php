<?php
session_start();
  if(isset($_SESSION['user'])){
	   	$role=$_SESSION['role'];
	   	$user=$_SESSION['user'];
	   	$shop=$_SESSION['shop'];
  }
  else{
	   header("Location:../index.php");
	   echo "<script>location='../index.php'</script>";
	 
  }
  	if(!isset($_GET['id'])){
	   header("Location:../stock?pagename=stock");
	}
	$id=$_GET['id'];
	include_once "../action/config.php";
	$obj=new database();
  $array=$obj->get_details("`stock`","*","`id`='$id' and `shop`='$shop'");
  $raw_material=$obj->get_rows("`raw_material`","`id`,`name`","`shop`='$shop'" ,'name asc');
  $width=$obj->get_rows("`width`","`id`,`width`","`shop`='$shop'" ,'width asc');  
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
		<style>
			.sexy_line{
				margin-right:100px;
				height:1px;
				background:black;
				background:-webkit-gradient(linear, 0 0, 100% 0, from(white), to(white), color-stop(50%, black));
			}
        </style>
	</head>

    <body>
<?php include "../header.php";?>
<div class="container">
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
         <div class="row">
           <div class="col-md-12">
				<form action="../action/updateData.php" enctype="multipart/form-data" method="post" onsubmit="return validate()">
                        	<fieldset style="padding-left:6px; border-radius:5px;">
                            	<h4 class="text-center text-info" style="padding-right:50px;">Update Item</h4>
                                <div class="sexy_line"></div><br />
                                <table class="table table-bordered">
									<tr>
										<td><b>Date:</b></td>
										<td><input type="date" name="date" class="form-control" required="required" value="<?php echo date('Y-m-d'); ?>"	/></td>
									</tr>
									<tr>	
										<td><b>Item:</b></td>
										<td>
                                        	<select name="raw_id" id="raw_id" class="form-control" required="required">
                                            	<option value="">Select</option>
                                                <?php foreach($raw_material as $raw){?>
                                            	<option value="<?php echo $raw['id']; ?>" <?php if($raw['id']==$array['raw_id']){ echo "selected"; } ?>><?php echo $raw['name']; ?></option>
                                                <?php } ?>
                                            </select>
											<input type="hidden" name="shop" value="<?php echo $shop; ?>" />
                                            <input type="hidden" name="id" value="<?php echo $id; ?>"  />
                                        </td>
									</tr>
									<tr>	
										<td><b>Width:</b></td>
										<td>
                                        	<select name="width_id" id="width_id" class="form-control" required="required">
                                            	<option value="">Select</option>
                                                <?php foreach($width as $value){?>
                                            	<option value="<?php echo $value['id']; ?>" <?php if($value['id']==$array['width_id']){ echo "selected"; } ?>><?php echo $value['width']." Inch"; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
									</tr>
									<tr>	
										<td><b>D No.:</b></td>
										<td>
                                        	<input type="text" name="d_no" id="d_no" value="<?php echo $array['d_no']; ?>" class="form-control" required="required">
                                        </td>
									</tr>
									<tr>	
										<td><b>Meter :</b></td>
										<td>
                                        	<input type="number" name="meter" id="meter" value="<?php echo $array['meter']; ?>" class="form-control" required="required">
                                        </td>
									</tr>
									<tr class="hidden">	
										<td><b>Quantity:</b></td>
										<td><input type="number" name="quantity" id="quantity" value="<?php echo $array['quantity']; ?>" class="form-control" required="required"></td>
									</tr>
									<tr>	
										<td><b>Image :</b></td>
										<td>
										<input type="file" name="image" id="image" class="form-control-file">
                                        </td>
									</tr>
									<tr align="center">
										<td colspan="2">
                                        	<input type="submit" name="update_stock" value="Update"  class="btn btn-success btn-sm" style="text-align:center; font-size:15px; width:200px;">
                                        </td>
									</tr>
								</table>
                            </fieldset>
                        </form>
           </div><!-- end of column for form -->
         </div><!-- end of row for form --> 
    </div><!-- end of row for main forms -->
  </div><!-- end of row -->
</div><!-- end of container -->
<script>
	function validate(){	
		if(confirm("Click Ok to Submit. \nClick Cancel to Edit.")){
			return true;
		}
		else{
			return false;
		}
	}
	
</script>
        
  		<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>            
</body>
</html>
